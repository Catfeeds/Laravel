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

        $grid->model()->recent();
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
        $grid->created_at('发布于')->sortable();
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
        $project = Project::findOrFail($id);

        $show = new Show($project);

        $show->id('项目ID');
        $show->title('项目标题');
        $show->user()->name('发布者');
        $show->favorite_count('收藏人数');
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
        $show->review_message('审核结果说明');
        $show->types('类型')->as(function ($types) {
            return implode('/', $types);
        });
        $show->features('功能')->as(function ($features) {
            return implode('/', $features);
        });
        $show->payment('希望付给设计师的费用');
        $show->find_time('希望用多长时间找设计师');
        $show->area('项目面积');
        $show->description('项目描述');

        if($project->project_file_url) {
            $show->project_file_url('项目附件')->file();
        } else {
            $show->project_file_url('项目附件');
        }

        $show->delivery_time('交付时间');
        $show->remark('申请备注');
        $show->supplement_description('补充需求');

        if($project->supplement_file_url) {
            $show->supplement_file_url('补充附件')->file();
        } else {
            $show->supplement_file_url('补充附件');
        }
        $show->created_at('发布于');
        $show->supplement_at('补充于');
        $show->canceled_at('取消于');
        $show->updated_at('上次更新');


        $show->user('甲方信息', function ($show) {
            (new UserController())->showInRelation($show);
        });

        $show->applications('报名列表', function ($grid) {
            $grid->id('ID')->sortable();
            $grid->user('设计师')->display(function ($user) {
                $route = 'users/' . $user['id'];
                return "<a href='{$route}'>{$user['name']}</a>";
            });
            $grid->remark('备注');
            $grid->application_file_url('附件')->display(function ($url) {
                return "<a href='{$url}' target='_blank'>下载</a>";
            });
            $grid->created_at('报名时间');

            $grid->disableActions();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
                $filter->like('user.name', '设计师姓名');
                $filter->like('user.phone', '设计师手机号');
                $filter->between('created_at', '报名时间')->date();
            });

            $grid->disableCreateButton();
        });

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
        } else {
            // 如果是一个已审核订单，则显示所有动态
            $form->select('status', '项目状态')
                ->options($this->statusTexts)
                ->default(900)
                ->rules('required');
        }
        $form->text('review_message', '审核结果说明')
            ->help('审核未通过时，向用户说明未通过的原因')
            ->rules('max:500');

        $form->textarea('area', '项目面积')->rules('required');
        $form->textarea('description', '项目描述与需求')->rules('required');
        $form->file('project_file_url', '项目附件')->uniqueName()->removable();;
        $form->text('delivery_time', '交付时间')->rules('required|max:50');
        $form->text('payment', '希望付给设计师的费用')->rules('required|max:200');
        $form->textarea('supplement_description', '补充需求');
        $form->file('supplement_file_url', '补充附件')->uniqueName()->removable();;
        $form->text('find_time', '希望用多长时间找设计师')->rules('required|max:50');;
        $form->textarea('remark', '申请备注');
        $form->display('created_at', '发布于');
        $form->display('supplement_at', '补充于');
        $form->display('updated_at', '上次更新');

        $form->saving(function (Form $form) {
            // 如果用户没有补充过项目，而管理员新增了"补充内容"，则设置supplement_at字段
            $project = $form->model();
            if (!$project->supplement_at && (request('supplement_description') || request('supplement_file_url'))) {
                $project->supplement_at = new Carbon;
            }
        });
        $form->saved(function ($form) {
            $project = $form->model();
            if (request('project_file_url') && $project->project_file_url) {
                $project->project_file_url = UploadService::getFullUrlByPath($project->project_file_url);
            }
            if (request('supplement_file_url') && $project->supplement_file_url) {
                $project->supplement_file_url = UploadService::getFullUrlByPath($project->supplement_file_url);
            }
            $project->save();
        });

        return $form;
    }
}
