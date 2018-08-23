<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserController extends Controller
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
            ->header('用户列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('用户信息')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑用户信息')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('创建用户')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->id('ID')->sortable();
        $grid->name('姓名');
        $grid->type('用户类型')->display(function ($type) {
            return $type === 'designer' ? '设计师' : '甲方';
        });
        $grid->phone('手机号');
        $grid->created_at('注册时间');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->id('ID');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', '姓名');
        $form->select('type', '用户类型')->options([
            'designer' => '设计师',
            'party' => '甲方'
        ]);
        $form->text('phone', '手机号');
        $form->password('password', '密码');

        $form->saving(function ($form) {
            $form->password = bcrypt($form->password);
        });

        return $form;
    }
}
