<?php

namespace App\Admin\Controllers;

use App\Models\Activity;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ActivityController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('动态列表')
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
            ->header('动态详情')
            ->body($this->detail($id));
    }


    // 用于关联关系中的show
    public function showInRelation($show)
    {
        $show->setResource('/admin/activities');

        $show->id('ID');
        $show->user()->name('作者');
        $show->content('内容');
        $show->photo_urls('图片')->as(function ($urls) {
            if(!is_array($urls)) {
                return null;
            }
            $res = '';
            foreach ($urls as $url) {
                $res .= "<img src='$url' style='max-width:200px;max-height:200px;margin:0 8px 8px 0;' class='img' />";
            }
            return $res;
        });
        $show->like_count('点赞数');
        $show->reply_count('评论数');
        $show->created_at('发布于');

        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableDelete();
        });
    }

    /**
     * Make a grid builder.
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Activity);

        $grid->model()->recent();
        $grid->id('ID')->sortable();
        $grid->user('作者')->display(function ($user) {
            $route = 'users/' . $user['id'];
            return "<a href='{$route}'>{$user['name']}</a>";
        });
        $grid->content('内容');
        $grid->photo_urls('图片')->image(null, 50, 50);
        $grid->like_count('点赞数');
        $grid->reply_count('评论数');
        $grid->created_at('发布于')->sortable();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('user.name', '用户名');
            $filter->like('user.phone', '用户手机号');
            $filter->like('content', '动态内容');
            $filter->between('created_at', '发布时间')->date();
        });
        $grid->actions(function ($actions) {
            $actions->disableEdit();
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
        $show = new Show(Activity::findOrFail($id));

        $show->id('ID');
        $show->user()->name('作者');
        $show->content('内容');
        $show->photo_urls('图片')->as(function ($urls) {
            if(!is_array($urls)) {
                return null;
            }

            $res = '';
            foreach ($urls as $url) {
                $res .= "<img src='$url' style='max-width:200px;max-height:200px;margin:0 8px 8px 0;' class='img' />";
            }
            return $res;
        });
        $show->like_count('点赞数');
        $show->reply_count('评论数');
        $show->created_at('发布于');

        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
        });

        $show->replies('评论列表', function ($grid) {
            $grid->setResource('/admin/replies');
            $grid->id('ID')->sortable();
            $grid->user('作者')->display(function ($user) {
                $route = '/admin/users/' . $user['id'];
                return "<a href='{$route}'>{$user['name']}</a>";
            });
            $grid->content('内容');
            $grid->created_at('发表于');

            $grid->actions(function ($actions) {
                $actions->disableEdit();
                $actions->disableView();
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->disableIdFilter();
                $filter->like('user.name', '用户姓名');
                $filter->like('user.phone', '用户手机号');
                $filter->like('content', '评论内容');
                $filter->between('created_at', '发表时间')->date();
            });

            $grid->disableCreateButton();
        });

        return $show;
    }

    protected function form() {
        return new Form(new Activity);
    }
}
