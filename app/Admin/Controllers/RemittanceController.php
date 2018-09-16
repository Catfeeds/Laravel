<?php

namespace App\Admin\Controllers;

use App\Admin\Services\UploadService;
use App\Models\Project;
use App\Http\Controllers\Controller;
use App\Models\ProjectRemittance;
use App\Services\ProjectsService;
use Carbon\Carbon;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Illuminate\Http\Request;

class RemittanceController extends Controller
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

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('汇款入账登记')
            ->description('选择一个项目，填写汇款信息，项目将自动进入"工作中"状态')
            ->body($this->grid());
    }

    /**
     * Show interface.
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        $project = Project::findOrFail($id);
        $remittance = $project->remit;

        if (!$remittance) {
            $remittance = new ProjectRemittance();
            $remittance->number = '暂无汇款信息';
        }

        return $content
            ->header('项目汇款信息')
            ->body($this->remittanceBox($project->remittance))
            ->body($this->remittanceDetail($remittance, $id))
            ->body($this->projectDetail($id));
    }

    public function edit($id, Content $content)
    {
        $project = Project::findOrFail($id);
        return $content
            ->header('编辑项目汇款信息')
            ->description('初次为"报名中"项目设置汇款信息时，项目状态会自动转为"工作中"')
            ->body($this->remittanceBox($project->remittance))
            ->body($this->form($project));
//            ->body($this->projectDetail($id));
    }

    protected function grid()
    {
        $grid = new Grid(new Project);

        // 进行中的项目在前面
        $TENDERING_STATUS = Project::STATUS_TENDERING;
        $grid->model()->orderByRaw("case when status = $TENDERING_STATUS then 1 else 0 end desc, id desc");

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

        $grid->title('标题');
        $grid->find_time('希望用多长时间找设计师');
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
        });

        $grid->disableCreateButton();

        return $grid;
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

    protected function remittanceDetail($remittance, $projectId)
    {
        $show = new Show($remittance);

        $show->panel()->title('汇款信息');

        $show->panel()
            ->tools(function ($tools) use ($projectId) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
                $tools->append(
                    "<a class='btn btn-sm btn-primary' href='/admin/project_remittances/$projectId/edit'><i class='fa fa-pencil'></i>&nbsp;&nbsp;编辑</a>"
                );
            });

        $show->number('流水号');
        $show->bank('汇款行');
        $show->name('汇款人');
        $show->amount('金额');
        $show->remitted_at('汇款日期')->as(function ($time) {
            return (new Carbon($time))->toDateString();
        });
        $show->created_at('信息录入于');
        return $show;
    }

    protected function form($project)
    {
        $form = new \Encore\Admin\Widgets\Form($project->remit);

        $form->text('number', '流水号');
        $form->text('bank', '汇款行');
        $form->text('name', '汇款人');
        $form->number('amount', '金额');
        $form->date('remitted_at', '汇款日期');
        $form->display('created_at', '信息录入于');

        $form->action("/admin/projects/$project->id/remittances");

        $box = new Box('汇款信息', $form);
        $box->style('info');
        return $box;
    }

    protected function remittanceBox($remittance) {
        $box = new Box('甲方填写的汇款信息', $remittance ? $remittance :'暂未填写');
        $box->collapsable();
        $box->style('primary');
        $box->solid();
        return $box;
    }

    public function update(Request $request, Project $project)
    {

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

        if ($project->remit) {
            $project->remit()->update($attributes);
        } else {
            $attributes['project_id'] = $project->id;
            ProjectRemittance::create($attributes);
        }

        return redirect("/admin/project_remittances/$project->id");
    }
}
