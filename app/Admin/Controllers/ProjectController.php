<?php

namespace App\Admin\Controllers;

use App\Models\Project;
use App\Http\Controllers\Controller;
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
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('发布项目')
            ->body($this->form());
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
    protected function form()
    {
        $form = new Form(new Project);

        $form->tab('1', function (Form $form) {
            $form->display('user.name', '发布者');
        })->tab('2', function (Form $form) {
            $form->number('status', 'Status');
            $form->disableSubmit()->disableReset();
        });

        $form->display('user.name', '发布者');
        $form->number('status', 'Status');
        $form->text('types', 'Types');
        $form->text('title', 'Title');
        $form->text('features', 'Features');
        $form->textarea('area', 'Area');
        $form->textarea('description', 'Description');
        $form->text('project_file_url', 'Project file url');
        $form->text('delivery_time', 'Delivery time');
        $form->text('payment', 'Payment');
        $form->textarea('supplement_description', 'Supplement description');
        $form->text('supplement_file_url', 'Supplement file url');
        $form->text('supplement_at', 'Supplement at');
        $form->text('find_time', 'Find time');
        $form->textarea('remark', 'Remark');
        $form->text('canceled_at', 'Canceled at');
        $form->number('favorite_count', 'Favorite count');

        return $form;
    }
}
