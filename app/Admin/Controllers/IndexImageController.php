<?php

namespace App\Admin\Controllers;

use App\Models\IndexImage;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class IndexImageController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('首页轮播图')
            ->description('最多可添加10张轮播图')
            ->body($this->grid());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('图片信息')
            ->body($this->detail($id));
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑图片信息')
            ->body($this->form()->edit($id));
    }

    public function create(Content $content)
    {
        if (IndexImage::count() >= 10) {
            admin_toastr('最多只能添加10张轮播图', 'warning');
            return redirect('/admin/index_images');
        }

        return $content
            ->header('添加图片')
            ->body($this->form());
    }

    protected function grid()
    {
        $grid = new Grid(new IndexImage);

        $grid->id('ID');
        $grid->author('作者');
        $grid->title('标题');
        $grid->url('图片')->image(null, 100, null);
        $grid->updated_at('更新于');

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(IndexImage::findOrFail($id));

        $show->author('作者');
        $show->title('标题');
        $show->url('图片')->image();
        $show->created_at('创建于');
        $show->updated_at('更新于');

        return $show;
    }

    protected function form()
    {
        $form = new Form(new IndexImage);

        $form->image('url', '图片')
            ->move('images/index/')
//            ->resize(600, null, function ($constraint) {
//                // 设定宽度是 $max_width，高度等比例双方缩放
//                $constraint->aspectRatio();
//                // 防止裁图时图片尺寸变大
//                $constraint->upsize();
//            })
            ->uniqueName()
            ->rules('required')
            ->help('为了获得更好的显示效果，请上传长宽比为3:1的横版图片');
        $form->text('author', '作者')->rules('max:50');
        $form->text('title', '标题')->rules('max:100');

        return $form;
    }
}
