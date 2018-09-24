<?php

namespace App\Admin\Controllers;

use App\Models\Activity;
use App\Http\Controllers\Controller;
use App\Models\Work;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class WorkController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('作品集列表')
            ->body($this->grid());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('作品集详情')
            ->body($this->detail($id));
    }

    protected function grid()
    {
        $grid = new Grid(new Work);

        $grid->model()->recent();
        $grid->id('ID')->sortable();
        $grid->user('设计师')->display(function ($user) {
            $route = 'users/' . $user['id'];
            return "<a href='{$route}'>{$user['name']}</a>";
        });
        $grid->visible_range('权限')->display(function ($value) {
            $labels = [
                'public'  => '公开',
                'private' => '私密'
            ];
            return "<span class='label label-primary'>$labels[$value]</span>";
        });
        $grid->title('标题');
        $grid->description('描述');
        $grid->photo_urls('图片')->image(null, 50, 50);
        $grid->like_count('点赞数');
        $grid->created_at('发布于')->sortable();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('user.name', '设计师姓名');
            $filter->like('user.phone', '设计师手机号');
            $filter->like('title', '作品标题');
            $filter->like('description', '作品描述');
            $filter->equal('visible_range', '权限')->multipleSelect([
                'public'  => '公开',
                'private' => '私密'
            ]);
            $filter->between('created_at', '发布时间')->date();
            $filter->scope('public', '公开')->where('visible_range', 'public');
            $filter->scope('private', '私密')->where('visible_range', 'private');
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
        $show = new Show(Work::findOrFail($id));

        $show->id('ID');
        $show->user()->name('作者');
        $show->visible_range('权限')->using([
            'public'  => '公开',
            'private' => '私密'
        ])->label('primary');
        $show->title('标题');
        $show->description('描述');
        $show->photo_urls('图片')->as(function ($urls) {
            if (!is_array($urls)) {
                return null;
            }

            $res = '';
            foreach ($urls as $url) {
                $res .= "<img src='$url' style='max-width:200px;max-height:200px;margin:0 8px 8px 0;' class='img' />";
            }
            return $res;
        });
        $show->like_count('点赞数');
        $show->created_at('发布于');

        $show->user('设计师信息', function ($show) {
            (new UserController())->showInRelation($show);
        });

        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
        });
        return $show;
    }
}
