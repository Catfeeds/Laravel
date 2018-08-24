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
            ->header('用户详情')
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
        $grid->avatar_url('头像')->image(null, 30, 30);
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
        $user = User::findOrFail($id);
        $show = new Show($user);

        $show->id('ID');
        $show->name('姓名');
        if($user->avatar_url) {
            $show->avatar_url('头像')->image(null, 100, 100);
        } else {
            $show->avatar_url('头像');
        }
        $show->type('用户类型')->using( [
            'designer' => '设计师',
            'party' => '甲方'
        ]);
        $show->phone('手机号');
        $show->email( '邮箱');
        $show->title('职位/公司');
        $show->introduction('简介');
        $show->company_name('公司名');
        $show->registration_number('注册号');
        if($user->business_license_url) {
            $show->business_license_url('营业执照')->image(null, 500, 500);
        } else {
            $show->business_license_url('营业执照');
        }
        $show->id_number('身份证号');
        if($user->id_card_url) {
            $show->id_card_url('身份证照片')->image(null, 300, 300);
        } else {
            $show->id_card_url('身份证照片');
        }
        $show->email_activated('邮箱是否激活')->using([
            1  => '是',
            0 => '否'
        ])->label($user->email_activated ? 'success' : 'danger');
        $show->created_at('注册时间');

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

        $form->text('name', '姓名')->rules('required');
        $form->select('type', '用户类型')->options([
            'designer' => '设计师',
            'party' => '甲方'
        ])->rules('required');
        $form->text('phone', '手机号')->rules('required');
        $form->email('email', '邮箱')->rules('nullable');
        $form->text('title', '职位/公司');
        $form->textarea('introduction', '简介');
        $form->text('company_name', '公司名');
        $form->text('registration_number', '注册号');
        $form->text('id_number', '身份证号');
        $form->switch('email_activated', '邮箱是否激活')->states([
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default']
        ])->rules('required');

//        $form->password('password', '密码');
//        $form->saving(function ($form) {
//            $form->password = bcrypt($form->password);
//        });

        return $form;
    }
}
