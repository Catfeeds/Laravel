<?php

namespace App\Admin\Controllers;

use App\Admin\Services\UploadService;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProjectController extends Controller
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
            ->header('项目列表')
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
        return $content
            ->header('项目详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑项目信息')
            ->body($this->form($id)->edit($id));
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Project);

        $grid->id('ID')->sortable();
        $grid->user('发布者')->display(function ($user) {
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
        $grid->types('类型')->implode('/');
        $grid->features('功能')->implode('/');
        $grid->payment('价格');
        $grid->find_time('希望用多长时间找设计师');
        $grid->updated_at('更新于')->sortable();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->equal('id', '项目ID');
            $filter->like('user.name', '甲方姓名');
            $filter->like('user.phone', '甲方手机号');
            $filter->equal('status', '项目状态')->multipleSelect($this->statusTexts);
            $filter->between('created_at', '发布时间')->date();
            $filter->scope('reviewing', '待审核')->where('status', Project::STATUS_REVIEWING);
            $filter->scope('canceled', '已取消')->where('status', Project::STATUS_CANCELED);
            $filter->scope('review_failed', '审核未通过')->where('status', Project::STATUS_REVIEW_FAILED);
            $filter->scope('tendering', '招标中')->where('status', Project::STATUS_TENDERING);
            $filter->scope('working', '作标中')->where('status', Project::STATUS_WORKING);
            $filter->scope('completed', '已完成')->where('status', Project::STATUS_COMPLETED);

        });

        $grid->disableCreateButton();

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Project::findOrFail($id));

        $show->id('ID');
        $show->user_id('User id');
        $show->status('Status');
        $show->types('Types');
        $show->title('Title');
        $show->features('Features');
        $show->area('Area');
        $show->description('Description');
        $show->project_file_url('Project file url');
        $show->delivery_time('Delivery time');
        $show->payment('Payment');
        $show->supplement_description('Supplement description');
        $show->supplement_file_url('Supplement file url');
        $show->supplement_at('Supplement at');
        $show->find_time('Find time');
        $show->remark('Remark');
        $show->canceled_at('Canceled at');
        $show->favorite_count('Favorite count');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->deleted_at('Deleted at');

        return $show;
    }

    /**
     * Make a form builder.
     * @return Form
     */
    protected function form($id = null)
    {
        $form = new Form(new Project);

        $project = Project::find($id);

        $form->text('title', '项目标题')->rules('required|string|max:200');
        $form->display('user.name', '发布者');

        // 如果是一个待审核订单，只显示审核通过与未通过，要求审核该订单
        if ($project && $project->status == Project::STATUS_REVIEWING) {
            $form->select('status', '审核状态')->options([
                Project::STATUS_REVIEW_FAILED => '未通过', Project::STATUS_TENDERING => '通过'
            ])->rules('required|in:' . Project::STATUS_REVIEW_FAILED . ',' . Project::STATUS_TENDERING);
            $form->text('review_message', '审核结果说明')
                ->help('审核未通过时，向用户说明未通过的原因')
                ->rules('max:500');
        } else {
            // 如果是一个已审核订单，则显示所有动态
            $form->select('status', '项目状态')
                ->options($this->statusTexts)
                ->default(900)
                ->rules('required');
        }

        $form->textarea('area', '项目面积')->rules('required');
        $form->textarea('description', '项目描述与需求')->rules('required');
        $form->file('project_file_url', '项目附件')->uniqueName();
        $form->text('delivery_time', '交付时间')->rules('required|max:50');
        $form->text('payment', '希望付给设计师的费用')->rules('required|max:200');
        $form->textarea('supplement_description', '补充需求');
        $form->file('supplement_file_url', '补充附件')->uniqueName();
        $form->text('find_time', '希望用多长时间找设计师')->rules('required|max:50');;
        $form->textarea('remark', '申请备注');
        $form->display('created_at', '发布于');
        $form->display('supplement_at', '补充于');
        $form->display('updated_at', '上次更新');

        $form->saving(function (Form $form) {
           // 如果用户没有补充过项目，而管理员新增了"补充内容"，则设置supplement_at字段
            $project = $form->model();
            if(!$project->supplement_at && (request('supplement_description') || request('supplement_file_url'))) {
                $project->supplement_at = new Carbon;
            }
        });
        $form->saved(function ($form) {
            $project = $form->model();
            if(request('project_file_url')) {
                $project->project_file_url = UploadService::getFullUrlByPath($project->project_file_url);
            }
            if(request('supplement_file_url')) {
                $project->supplement_file_url = UploadService::getFullUrlByPath($project->supplement_file_url);
            }
            $project->save();
        });

        return $form;
    }
}
