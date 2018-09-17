<?php

namespace App\Admin\Controllers;

use App\Admin\Services\UploadService;
use App\Models\Payment;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\ProjectDelivery;
use App\Models\ProjectRemittance;
use App\Models\User;
use App\Services\ProjectsService;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class PaymentController extends Controller
{
    use HasResourceActions;

    protected $statusTexts = [
        Project::STATUS_CANCELED      => '已取消',
        Project::STATUS_REVIEW_FAILED => '审核未通过',
        Project::STATUS_REVIEWING     => '待审核',
        Project::STATUS_TENDERING     => '招标中',
        Project::STATUS_WORKING       => '作标中',
        Project::STATUS_COMPLETED     => '已完成'
    ];

    public function index(Content $content)
    {
        return $content
            ->header('设计费发放登记')
            ->description('选择一个项目，填写设计费发放信息')
            ->body($this->grid());
    }

    public function show($id, Content $content)
    {
        $project = Project::findOrFail($id);

        return $content
            ->header('项目设计费发放列表')
            ->description('只能给提交设计文件的设计师发放设计费')
            ->body($this->paymentList($project))
            ->body($this->designerList($project))
            ->body($this->projectDetail($id));
    }

    protected function grid()
    {
        $grid = new Grid(new Project);

        // 已完成的项目在前面
        $STATUS = Project::STATUS_COMPLETED;
        $grid->model()->orderByRaw("case when status = $STATUS then 1 else 0 end desc, id desc");

        $grid->id('ID')->sortable();
        $grid->user('甲方')->display(function ($user) {
            $route = 'users/' . $user['id'];
            return "<a href='{$route}'>{$user['name']}</a>";
        });

        $texts = $this->statusTexts;
        $grid->status('状态')->display(function ($status) use ($texts) {
            $styles = [
                Project::STATUS_CANCELED      => 'default',
                Project::STATUS_REVIEW_FAILED => 'default',
                Project::STATUS_REVIEWING     => 'warning',
                Project::STATUS_TENDERING     => 'info',
                Project::STATUS_WORKING       => 'primary',
                Project::STATUS_COMPLETED     => 'default'
            ];

            return "<span class='label label-{$styles[$status]}'>$texts[$status]</span>";
        });

        $grid->mode('模式')->display(function ($mode) {
            $modes = [
                'free' => '自由式',
                'invite' => '邀请式',
                'specify' => '指定式'
            ];
            return $modes[$mode];
        });

        $grid->title('标题');
        $grid->column('delivery_count', '提交作品人数')->display(function () {
            return $this->deliveries->count() . ' 人';
        });
        $grid->created_at('发布于')->sortable();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->equal('id', '项目ID');
            $filter->like('user.name', '甲方姓名');
            $filter->like('user.phone', '甲方手机号');
            $filter->scope('reviewing', '待审核')->where('status', Project::STATUS_REVIEWING);
            $filter->scope('canceled', '已取消')->where('status', Project::STATUS_CANCELED);
            $filter->scope('review_failed', '审核未通过')->where('status', Project::STATUS_REVIEW_FAILED);
            $filter->scope('tendering', '招标中')->where('status', Project::STATUS_TENDERING);
            $filter->scope('working', '作标中')->where('status', Project::STATUS_WORKING);
            $filter->scope('completed', '已完成')->where('status', Project::STATUS_COMPLETED);
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            $actions->append("<a href='/admin/project_payments/{$actions->getKey()}'><i class='fa fa-eye'></i>&nbsp;&nbsp;查看</i></a>");
        });

        $grid->disableCreateButton();

        return $grid;
    }

    public function form(Request $request, Content $content)
    {
        $this->validateQueryParams($request);

        return $content
            ->header('编辑项目汇款信息')
            ->description('初次为"报名中"项目设置汇款信息时，项目状态会自动转为"工作中"')
            ->body($this->paymentForm($request))
            ->body($this->projectDetail($request->project_id));
    }

    protected function paymentList(Project $project)
    {
        $payments = $project->payments;
        if (!$payments->count()) {
            $content = '暂无设计费发放信息';
        } else {
            $grid = new Grid(new Payment);
            $grid->model()->where('project_id', $project->id);
            $grid->setResource('/admin/payments');
            $grid->id('ID')->sortable();
            $grid->user('设计师')->display(function ($user) {
                $url = 'users/' . $user['id'];
                return "<a href='{$url}'>{$user['name']}</a>";
            });
            $grid->number('流水号');
            $grid->bank('收款行');
            $grid->name('收款人');
            $grid->amount('金额');
            $grid->remitted_at('汇款日期')->display(function ($time) {
                return (new Carbon($time))->toDateString();
            });
            $grid->created_at('信息录入于');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
                $filter->like('user.name', '设计师姓名');
                $filter->like('user.phone', '设计师手机号');
                $filter->between('remitted_at', '汇款时间')->date();
                $filter->between('created_at', '信息录入时间')->date();
            });

            $grid->disableCreateButton();

            $grid->actions(function ($actions) use ($project) {
                $actions->disableView();
                $actions->disableEdit();

                $user = $actions->row->user; // 是数组形式，不是User对象

                $actions->prepend("<a href='/admin/payments/form?project_id={$project->id}&user_id={$user['id']}'><i class='fa fa-pencil'></i>&nbsp;&nbsp;</a>");
            });

            $content = $grid->render();
        }

        $box = (new Box('设计费发放信息', $content))->style('info');
        return $box;
    }

    protected function designerList(Project $project)
    {
        $deliveries = $project->deliveries;
        if (!$deliveries->count()) {
            $content = '暂无设计师上传设计文件，只能给提交设计文件的设计师发放设计费';
        } else {
            $grid = new Grid(new ProjectDelivery);
            $grid->model()->where('project_id', $project->id);
            $grid->setResource('/admin/deliveries');
            $grid->id('ID')->sortable();
            $grid->user('设计师')->display(function ($user) {
                $url = 'users/' . $user['id'];
                return "<a href='{$url}'>{$user['name']}</a>";
            });
            $grid->remark('附加说明')->limit(20);
            $grid->file_url('附件')->display(function ($url) {
                return "<a href='{$url}' target='_blank'>下载</a>";
            });
            $grid->created_at('上传时间');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
                $filter->like('user.name', '设计师姓名');
                $filter->like('user.phone', '设计师手机号');
                $filter->between('created_at', '上传时间')->date();
            });

            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->actions(function ($actions) use ($project) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();

                $user = $actions->row->user; // 是数组形式，不是User对象

                if (Payment::where([
                    'project_id' => $project->id,
                    'user_id'    => $user['id']
                ])->exists()) {
                    $actions->append("<a class='btn btn-sm btn-default disabled' href='#'><i class='fa fa-send-o'></i>&nbsp;&nbsp;已发放</a>");
                } else {
                    $actions->append("<a class='btn btn-sm btn-primary' href='/admin/payments/form?project_id={$project->id}&user_id={$user['id']}'><i class='fa fa-send-o'></i>&nbsp;&nbsp;发放设计费</a>");
                }
            });

            $content = $grid->render();
        }

        $box = (new Box('已提交设计文件的设计师', $content))->style('info');
        return $box;
    }

    protected function projectDetail($id)
    {
        $project = Project::findOrFail($id);

        $show = new Show($project);

        $show->panel()
            ->tools(function ($tools) use ($id) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
                $tools->append(
                    "<a class='btn btn-sm btn-primary' href='/admin/projects/$id''><i class='fa fa-eye'></i>&nbsp;&nbsp;查看详情</a>"
                );
            });

        $show->panel()->title('项目信息');

        $show->id('项目ID');
        $show->title('项目标题');
        $show->user()->name('甲方');
        $texts = $this->statusTexts;
        $show->status('项目状态')->as(function ($status) use ($texts) {
            $styles = [
                Project::STATUS_CANCELED      => 'default',
                Project::STATUS_REVIEW_FAILED => 'default',
                Project::STATUS_REVIEWING     => 'warning',
                Project::STATUS_TENDERING     => 'info',
                Project::STATUS_WORKING       => 'primary',
                Project::STATUS_COMPLETED     => 'default'
            ];
            return "<span class='label label-{$styles[$status]}'>$texts[$status]</span>";
        });
        $show->payment('希望付给设计师的费用');
        $show->created_at('发布于');

        return $show;
    }

    // 创建或者编辑的表单
    protected function paymentForm(Request $request)
    {
        $project = Project::find($request->project_id);

        $payment = $project->payments()->where(['user_id' => $request->user_id])->first();
        if (!$payment) {
            $payment = new Payment();
        }

        // 必须显式加载一下，否则display('user.name', '设计师')不会获取到数据
        $payment->user = User::find($request->user_id);

        $form = new \Encore\Admin\Widgets\Form($payment);

        $form->display('user.name', '设计师');
        $form->text('number', '流水号');
        $form->text('bank', '收款行');
        $form->text('name', '收款人');
        $form->number('amount', '金额');
        $form->date('remitted_at', '汇款日期');

        $form->action( "/admin/payments/update?project_id={$request->project_id}&user_id={$request->user_id}");

        $box = new Box('设计费发放信息', $form);
        $box->style('info');
        return $box;
    }

    public function update(Request $request)
    {
        $this->validateQueryParams($request);

        $this->validate($request, [
            'number'      => 'required|max:300',
            'bank'        => 'required|max:300',
            'name'        => 'required|max:300',
            'amount'      => 'required|max:300',
            'remitted_at' => 'required|date',
        ], [], [
            'number'      => '流水号',
            'bank'        => '汇款行',
            'name'        => '汇款人',
            'amount'      => '金额',
            'remitted_at' => '汇款日期',
        ]);

        $attributes = $request->only(['number', 'bank', 'name', 'amount', 'remitted_at']);

        $payment = Payment::where([
            'project_id' => $request->project_id,
            'user_id' => $request->user_id
        ])->first();

        if ($payment) {
            $payment->update($attributes);
        } else {
            $attributes['project_id'] = $request->project_id;
            $attributes['user_id'] = $request->user_id;
            Payment::create($attributes);
        }

        return redirect("/admin/project_payments/$request->project_id");
    }

    public function destroy(Request $request, Payment $payment)
    {
        if ($payment->delete()) {
            $data = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }

    // 验证project_id与user_id是否合理合法
    protected function validateQueryParams(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required|exists:projects,id',
            'user_id'    => 'required|exists:users,id,type,designer'
        ], [
            'project_id.exists' => '该项目不存在',
            'user_id.exists'    => '该用户不存在或不是设计师'
        ]);

        if (!ProjectDelivery::where([
            'project_id' => $request->project_id,
            'user_id'    => $request->user_id
        ])->exists()) {
            throw new BadRequestHttpException('只能给成功上传设计文件的设计师发放设计费');
        }
    }
}
