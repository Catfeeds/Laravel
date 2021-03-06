<?php

namespace App\Admin\Controllers;

use App\Models\Reply;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ReplyController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('评论列表')
            ->body($this->grid());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('评论详情')
            ->body($this->detail($id));
    }

    protected function grid()
    {
        $grid = new Grid(new Reply);

        $grid->model()->recent();
        $grid->id('ID')->sortable();
        $grid->user('作者')->display(function ($user) {
            $route = 'users/' . $user['id'];
            return "<a href='{$route}'>{$user['name']}</a>";
        });
        $grid->content('内容');
        $grid->created_at('发表于');

        $grid->actions(function ($actions) {
            $actions->disableEdit();
        });

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->equal('activity.id', '动态ID');
            $filter->like('user.name', '用户姓名');
            $filter->like('user.phone', '用户手机号');
            $filter->like('content', '评论内容');
            $filter->between('created_at', '发表时间')->date();
        });

        $grid->disableCreateButton();

        return $grid;
    }

    protected function detail($id){
        $show = new Show(Reply::findOrFail($id));
        $show->id('评论ID');
        $show->user()->name('用户');
        $show->content('内容');
        $show->created_at('发表于');
        $show->activity('评论动态', function ($show) {
            (new ActivityController())->showInRelation($show);
        });
        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
        });
        return $show;
    }

    protected function form()
    {
        $form = new Form(new Reply);
        return $form;
    }
}
