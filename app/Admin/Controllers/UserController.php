<?php

namespace App\Admin\Controllers;

use App\Admin\Services\UploadService;
use App\Http\Controllers\Controller;
use App\Models\RecommendedUser;
use App\Models\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    use HasResourceActions;

    // form方法需要根据$id来判断使用设计师还是甲方的表单，所以覆盖HasResourceActions中的方法
    public function update($id)
    {
        return $this->form($id)->update($id);
    }

    /**
     * Index interface.
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
     * @param mixed $id
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
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑用户信息')
            ->body($this->form($id)->edit($id));
    }

    /**
     * Create interface.
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
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->model()->orderBy('id', 'desc');
        $grid->id('ID')->sortable();
        $grid->review_status('状态')->display(function ($status) {
            $styles = [
                0 => 'warning',
                1 => 'default',
                2 => 'danger'
            ];
            $texts = [
                0 => '待审核',
                1 => '已通过',
                2 => '未通过'
            ];
            return "<span class='label label-{$styles[$status]}'>$texts[$status]</span>";
        });
        $grid->avatar_url('头像')->image(null, 30, 30);
        $grid->name('姓名');
        $grid->type('用户类型')->display(function ($type) {
            return $type === 'designer' ? '设计师' : '甲方';
        });
        $grid->phone('手机号');
        $grid->created_at('注册时间')->sortable();
        $grid->in_blacklist('是否拉黑')->switch([
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'danger'],
        ]);

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('phone', '手机号');
            $filter->like('name', '姓名');
            $filter->equal('type', '用户类型')
                ->select([
                    'designer' => '设计师',
                    'party'    => '甲方'
                ]);
            $filter->scope('designer', '设计师')->where('type', 'designer');
            $filter->scope('party', '甲方')->where('type', 'party');
            $filter->scope('recommend', '推荐到首页的设计师')->whereHas('recommendation');
            $filter->equal('review_status', '审核状态')->multipleSelect([
                0 => '待审核',
                1 => '已通过',
                2 => '未通过'
            ]);
            $filter->scope('reviewing', '待审核')->where('review_status', 0);
            $filter->scope('blacklist', '黑名单')->where('in_blacklist', 1);
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $user = User::findOrFail($id);
        $show = new Show($user);

        $show->panel()->tools(function ($tools) use ($user) {
            if ($user->review_status == 0 || $user->review_status == 2) {
                $tools->append(
                    "<a class='btn btn-sm btn-success' style='margin-right: 5px' href='/admin/users/$user->id/review_pass'><i class='fa fa-check-circle'></i>&nbsp;&nbsp;审核通过</a>"
                );
            }
            if ($user->review_status == 0) {
                $tools->append(
                    "<a class='btn btn-sm btn-warning' style='margin-right: 5px' href='/admin/users/$user->id/review_fail'><i class='fa fa-times-circle'></i>&nbsp;&nbsp;审核未通过</a>"
                );
            }

            // 设计师置顶推荐
            $this->recommendButton($user, $tools);
        });

        $show->id('ID');
        if ($user->in_blacklist) {
            $show->in_blacklist('是否拉黑')->as(function ($value) {
                $styles = [
                    0 => 'default',
                    1 => 'danger'
                ];
                $texts = [
                    0 => '否',
                    1 => '是'
                ];
                return "<span class='label label-$styles[$value]'>$texts[$value]</span>";
            });
        }
        $show->review_status('审核状态')->as(function ($status) {
            $styles = [
                0 => 'warning',
                1 => 'default',
                2 => 'danger'
            ];
            $texts = [
                0 => '待审核',
                1 => '已通过',
                2 => '未通过'
            ];
            return "<span class='label label-$styles[$status]'>$texts[$status]</span>";
        });
        $show->type('用户类型')->as(function ($type) {
            $types = ['designer' => '设计师', 'party' => '甲方'];
            return "<span class='label label-primary'>$types[$type]</span>";
        });
        $show->name('姓名');
        if ($user->avatar_url) {
            $show->avatar_url('头像')->image(null, 100, 100);
        } else {
            $show->avatar_url('头像');
        }
        $show->type('用户类型')->as(function ($type) {
            $types = ['designer' => '设计师', 'party' => '甲方'];
            return "<span class='label label-primary'>$types[$type]</span>";
        });
        $show->phone('手机号');
        $show->email('邮箱');
        $show->title('职位/公司');
        $show->introduction('简介');

        if ($user->type === 'designer') {
            $show->professional_fields('专业领域')->as(function ($value) {
                if (!is_array($value)) {
                    return null;
                }
                else return implode(' / ', $value);
            });

            $show->qualification_urls('资质证书')->as(function ($urls) {
                if (!is_array($urls)) {
                    return null;
                }

                $res = '';
                foreach ($urls as $url) {
                    $res .= "<img src='$url' style='max-width:300px;max-height:300px;margin:0 8px 8px 0;' class='img' />";
                }
                return $res;
            });

            $show->bank_name('开户行');
            $show->account_name('开户名');
            $show->bank_card_number('银行卡号');
            $show->id_number('身份证号');

            if ($user->id_card_url) {
                $show->id_card_url('身份证照片')->image(null, 300, 300);
            } else {
                $show->id_card_url('身份证照片');
            }
        }

        $show->created_at('注册时间');

        return $show;
    }

    protected function recommendButton(User $user, $tools) {
        if($user->type !== 'designer') return;

        $recommending = RecommendedUser::where('user_id', $user->id)->exists();

        if(!$recommending) {
            $tools->append(
                "<a class='btn btn-sm btn-default' style='margin-right: 5px' href='/admin/users/$user->id/recommend'>推荐到首页</a>"
            );
        } else {
            $tools->append(
                "<a class='btn btn-sm btn-default' style='margin-right: 5px' href='/admin/users/$user->id/cancel_recommend'>取消推荐</a>"
            );
        }
    }

    protected function form($id = null)
    {
        if (User::where([
            'id'   => $id,
            'type' => 'designer'
        ])->exists()) {
            $form = $this->formForDesigner($id);
        } else {
            $form = $this->formForClient($id);
        }
        return $form;
    }

    protected function formForDesigner($id = null)
    {
        $form = new Form(new User());
        $form->text('name', '姓名')->rules('required');
        $form->image('avatar_url', '头像')
            ->uniqueName()
            ->removable()
            ->rules('max:2048', ['max' => '头像最大是2MB']);
        $form->select('type', '用户类型')->options([
            'designer' => '设计师',
            'party'    => '甲方'
        ])->rules('required');
        $form->select('review_status', '审核状态')->options([
            0 => '待审核',
            1 => '已通过',
            2 => '未通过'
        ])->rules('required');
        $form->text('phone', '手机号')->prepend('<i class="fa fa-phone fa-fw"></i>')->rules('required');
        $form->email('email', '邮箱')->rules('nullable');
        $form->text('title', '职位/公司');
        $form->textarea('introduction', '简介');

        // TODO saved中需要查找是否包含根域名，然后设置成完整域名
//            $form->multipleImage('qualification_urls', '资质证书')
//                ->uniqueName()
//                ->removable()
//                ->rules('max:2048', ['max' => '资质证书最大是2MB']);
        $form->text('bank_name', '开户行');
        $form->text('account_name', '开户名');
        $form->text('bank_card_number', '银行卡号');
        $form->text('id_number', '身份证号');
        $form->image('id_card_url', '身份证照片')
            ->uniqueName()
            ->removable()
            ->rules('max:5120', ['max' => '身份证照片最大是5MB']);


        $form->switch('in_blacklist', '是否拉黑')->states([
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'danger'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ]);
        $form->password('password', '密码')->help('填写此项后将覆盖该用户的密码，请谨慎操作。若您不想修改该用户的密码，请将此项留空。');

        $form->saving(function ($form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });
        $form->saved(function ($form) {
            $user = $form->model();

            $attributes = [];
            if (request('avatar_url') && $user->avatar_url) {
                $attributes['avatar_url'] = UploadService::getFullUrlByPath($user->avatar_url);
            }
            if (request('id_card_url') && $user->id_card_url) {
                $attributes['id_card_url'] = UploadService::getFullUrlByPath($user->id_card_url);
            }

            $user->update($attributes);
        });

        return $form;
    }

    protected function formForClient($id = null)
    {
        $form = new Form(new User());
        $form->text('name', '姓名')->rules('required');
        $form->image('avatar_url', '头像')
            ->uniqueName()
            ->removable()
            ->rules('max:2048', ['max' => '头像最大是2MB']);
        $form->select('type', '用户类型')->options([
            'designer' => '设计师',
            'party'    => '甲方'
        ])->rules('required');
        $form->select('review_status', '审核状态')->options([
            0 => '待审核',
            1 => '已通过',
            2 => '未通过'
        ])->rules('required');
        $form->text('phone', '手机号')->prepend('<i class="fa fa-phone fa-fw"></i>')->rules('required');
        $form->email('email', '邮箱')->rules('nullable');
        $form->text('title', '职位/公司');
        $form->textarea('introduction', '简介');

        $form->switch('in_blacklist', '是否拉黑')->states([
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'danger'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
        ]);
        $form->password('password', '密码')->help('填写此项后将覆盖该用户的密码，请谨慎操作。若您不想修改该用户的密码，请将此项留空。');

        $form->saving(function ($form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = bcrypt($form->password);
            }
        });
        $form->saved(function ($form) {
            $user = $form->model();
            if (request('avatar_url') && $user->avatar_url) {
                $attributes['avatar_url'] = UploadService::getFullUrlByPath($user->avatar_url);
                $user->update($attributes);
            }
        });

        return $form;
    }

    // 用于关联关系中的show
    public function showInRelation($show)
    {
        $show->setResource('/admin/users');
        $user = $show->getModel();
        $show->id('ID');
        $show->name('姓名');
        if ($user->avatar_url) {
            $show->avatar_url('头像')->image(null, 100, 100);
        } else {
            $show->avatar_url('头像');
        }
        $show->type('用户类型')->using([
            'designer' => '设计师',
            'party'    => '甲方'
        ]);
        $show->phone('手机号');
        $show->email('邮箱');
        $show->title('职位/公司');
        $show->introduction('简介');

        // TODO 专业领域等
//        if ($user->type === 'designer') {
//            $show->company_name('公司名');
//            $show->registration_number('注册号');
//            if ($user->business_license_url) {
//                $show->business_license_url('营业执照')->image(null, 500, 500);
//            } else {
//                $show->business_license_url('营业执照');
//            }
//            $show->id_number('身份证号');
//            if ($user->id_card_url) {
//                $show->id_card_url('身份证照片')->image(null, 300, 300);
//            } else {
//                $show->id_card_url('身份证照片');
//            }
//        }

        $show->created_at('注册时间');

        $show->panel()->tools(function ($tools) use ($user) {
            $tools->disableEdit();
            $tools->disableDelete();
            $tools->disableList();
            $tools->append(
                "<a class='btn btn-sm btn-primary' href='/admin/users/$user->id''><i class='fa fa-eye'></i>&nbsp;&nbsp;查看详情</a>"
            );
        });
    }
}
