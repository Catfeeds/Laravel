<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Requests\ProjectSupplementRequest;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\ProjectInvitation;
use App\Models\Reply;
use App\Models\Upload;
use App\Models\User;
use App\Transformers\ProjectForDesignerTransformer;
use App\Transformers\ProjectForPublisherTransformer;
use App\Transformers\ProjectTransformer;
use App\Transformers\SimpleProjectTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProjectsController extends Controller
{
    // 发布项目
    public function store(ProjectRequest $request)
    {
        $this->authorize('store', Project::class);

        $attrubutes = $request->only(['title', 'types', 'features', 'keywords', 'depth', 'description', 'project_file_url', 'delivery_time', 'payment', 'find_time', 'mode', 'remark']);
        $attrubutes['user_id'] = $this->user()->id;
        $attrubutes['status'] = Project::STATUS_REVIEWING;

        $project = Project::create($attrubutes);

        // 邀请每个用户
        if ($attrubutes['mode'] === 'invite' || $attrubutes['mode'] === 'specify') {
            foreach ($request->invited_designer_ids as $designerId) {
                ProjectInvitation::create([
                    'user_id'    => $designerId,
                    'project_id' => $project->id
                ]);
            }
        }

        return $this->response->item($project, new ProjectForPublisherTransformer())
            ->setStatusCode(201);
    }

    // 获取项目详情
    public function index(Project $project)
    {
        $currentUser = $this->user();

        // 未登录时只能查看公开项目，且只有部分信息
        if (!$currentUser) {
            if ($project->isPublic()) {
                return $this->response->item($project, new SimpleProjectTransformer());
            } else {
                return $this->response->errorUnauthorized();
            }
        }

        // 已登录的话看是否有权限查看项目
        $this->authorizeForUser($currentUser, 'retrieve', $project); // 不能用authorize

        $project->setExtraAttributes($currentUser);


        // 添加当前用户的报名信息、邀请信息
        if ($currentUser->type === 'designer') {
            $project['application'] = $project
                ->applications()
                ->where('user_id', $currentUser->id)
                ->first();
            $project['invitation'] = $project
                ->invitations()
                ->where('user_id', $currentUser->id)
                ->first();
            $project['delivery'] = $project
                ->deliveries()
                ->where('user_id', $currentUser->id)
                ->first();
            return $this->response->item($project, new ProjectForDesignerTransformer());
        } else {
            if ($currentUser->isAuthorOf($project)) {
                return $this->response->item($project, new ProjectForPublisherTransformer());
            } else {
                return $this->response->item($project, new ProjectTransformer());
            }
        }
    }

    // 修改项目
    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        if($project->status != Project::STATUS_REVIEWING && $project->status != Project::STATUS_REVIEW_FAILED) {
            throw new BadRequestHttpException(__('该项目无法修改'));
        }

        $attrubutes = $request->only(['title', 'types', 'features', 'keywords', 'depth', 'description', 'project_file_url', 'delivery_time', 'payment', 'find_time', 'remark']);

        // 是否重新审核
        if ($request->re_review && $project->status === Project::STATUS_REVIEW_FAILED) {
            $attrubutes['status'] = Project::STATUS_REVIEWING;
        }

        $project->update($attrubutes);

        return $this->response->item($project, new ProjectForPublisherTransformer());
    }

    // 填写项目汇款信息
    public function updateRemittance(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $this->validate($request, [
            'remittance' => 'required|string|max:1000'
        ]);

        $project->update([
            'remittance'              => $request->remittance,
            'remittance_submitted_at' => new Carbon
        ]);

        return $this->response->item($project, new ProjectForPublisherTransformer());
    }

    // 填写项目设计费的分配方案信息
    public function updatePayment(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $this->validate($request, ['payment_remark' => 'required|string|max:1000']);

        $attributes = [
            'payment_remark'            => $request->payment_remark,
            'payment_remark_updated_at' => new Carbon
        ];

        // 是否设置项目状态为"已完成"
        if ($request->mark_as_completed) {
            if ($project->status != Project::STATUS_WORKING) {
                throw new BadRequestHttpException(__('不是工作中的项目'));
            } else {
                $attributes = array_merge($attributes, [
                    'status'       => Project::STATUS_COMPLETED,
                    'completed_at' => new Carbon
                ]);
            }
        }

        $project->update($attributes);

        return $this->response->item($project, new ProjectForPublisherTransformer());
    }

    // 删除项目
    public function destroy(Project $project)
    {
        $this->authorize('destroy', $project);
        $project->delete();
        return $this->response->noContent();
    }

    // 取消项目
    public function cancel(Project $project)
    {
        $this->authorize('cancel', $project);

        // 已取消，直接返回
        if ($project->status == Project::STATUS_CANCELED) {
            return $this->response->noContent();
        }

        if (!in_array($project->status, [
            Project::STATUS_REVIEWING,
            Project::STATUS_REVIEW_FAILED,
            Project::STATUS_TENDERING,
        ])) {
            return $this->response->errorBadRequest(__('该项目无法取消'));
        }

        $project->status = Project::STATUS_CANCELED;
        $project->canceled_at = Carbon::now()->toDateTimeString();
        $project->save();

        return $this->response->noContent();
    }

    // 某个业主发布的公开项目
    public function partyIndex(User $user, Request $request)
    {
        if ($user->type !== 'party') {
            return $this->response->errorUnauthorized('公开接口只能访问甲方发布的项目');
        }

        $projects = $this->getQueryFromRequest($request, Project::PUBLIC_STATUS)
            ->where('user_id', $user->id)
            ->where('mode', 'free')
            ->recent()
            ->paginate(20);

        return $this->response->paginator($projects, new ProjectTransformer());
    }

    // 当前登录用户的项目列表
    public function userIndex(Request $request)
    {
        $currentUser = $this->user();

        // 当前用户是甲方：返回发布的项目
        if ($currentUser->type == 'party') {
            $projects = $this->getQueryFromRequest($request, Project::ALL_STATUS)
                ->where('user_id', $currentUser->id)
                ->recent()
                ->paginate(20);
            return $this->response->paginator($projects, new ProjectForPublisherTransformer());
        } else {
            // 当前用户是设计师：返回报名和接受邀请的项目的项目
            $projects = $this->getQueryFromRequest($request, Project::DESIGNER_ORDER_STATUS)
                ->where(function ($query) use ($currentUser) {
                    $query->whereHas('applications', function ($query) use ($currentUser) {
                        $query->where('user_id', $currentUser->id);
                    })->orWhereHas('invitations', function ($query) use ($currentUser) {
                        $query->where([
                            'user_id' => $currentUser->id,
                            'status' => ProjectInvitation::STATUS_ACCEPTED
                        ]);
                    });
                })
                ->recent()
                ->paginate(20);

            // 设计费信息
            $projects->getCollection()->transform(function ($project) use ($currentUser) {
                $project->designerPayment = Payment::where([
                    'user_id'    => $currentUser->id,
                    'project_id' => $project->id
                ])->first();
                return $project;
            });

            return $this->response->paginator($projects, new ProjectForDesignerTransformer());
        }

    }

    // 当前登录的用户进行中的项目
    public function processing(Request $request)
    {
        $currentUser = $this->user();

        // 当前用户是甲方：返回发布的项目
        if ($currentUser->type == 'party') {
            $projects = $currentUser->projects()
                ->whereIn('status', [
                    Project::STATUS_REVIEWING,
                    Project::STATUS_REVIEW_FAILED,
                    Project::STATUS_TENDERING,
                    Project::STATUS_WORKING
                ])
                ->recent()
                ->paginate(20);
            return $this->response->paginator($projects, new ProjectForPublisherTransformer());
        } else {
            // 当前用户是设计师：返回参与的项目
            $projects = Project::whereIn('status', [
                Project::STATUS_TENDERING,
                Project::STATUS_WORKING
            ])->where(function ($query) use ($currentUser) {
                $query->whereHas('applications', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                })->orWhereHas('projectInvitations', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                });
            })->recent()->paginate(20);
            return $this->response->paginator($projects, new ProjectForDesignerTransformer());
        }
    }

    // 当前登录用户收藏的项目
    public function favorite(Request $request)
    {
        $currentUser = $this->user();

        $projects = $this->getQueryFromRequest($request, Project::DESIGNER_ORDER_STATUS)
            ->whereHas('favoriteUser', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
            ->recent()
            ->paginate(20);

        $projects->each(function ($project) use ($currentUser) {
            $project->setExtraAttributes($currentUser);
        });

        return $this->response->paginator($projects, new ProjectTransformer());
    }

    // 搜索项目
    public function search(Request $request)
    {
        $projects = $this->getQueryFromRequest($request, Project::PUBLIC_STATUS)
            ->where('mode', 'free')
            ->recent()
            ->paginate(20);
        return $this->response->paginator($projects, new ProjectTransformer());
    }

    // 给当前设计师推荐他没报名的自由式项目
    public function recommend()
    {
        $currentUser = $this->user();
        $projects = Project::where('status', Project::STATUS_TENDERING)
            ->where('mode', 'free')
            ->whereDoesntHave('applications', function ($query) use ($currentUser) {
                $query->where('user_id', $currentUser->id);
            })
//            ->whereDoesntHave('invitations', function ($query) use ($currentUser) {
//                $query->where('user_id', $currentUser->id);
//            })
            ->recent()
            ->paginate(20);
        return $this->response->paginator($projects, new ProjectTransformer());
    }

    // 根据query中的参数初始化查询器：status（项目状态）、title（项目标题）、keywords（项目关键字）
    private function getQueryFromRequest(Request $request, $allowedStatus)
    {
        if ($request->status) {
            // status可以是字符串也可以是数组
            if (is_array($request->status)) {
                $status = $request->status;
            } else {
                $status[] = $request->status;
            }

            // 表单验证
            foreach ($status as $s) {
                if (!in_array($s, $allowedStatus)) $this->response->errorBadRequest('status 参数错误');
            }

        } else {
            $status = $allowedStatus; // 如果没有设置status，则默认搜索所有项目
        }
        $query = Project::whereIn('status', $status)->recent();

        if (is_string($request->title)) {
            $query = $query->where('title', 'like', "%$request->title%");
        }

        if ($request->keywords) {
            // keywords可以是字符串也可以是数组
            if (is_array($request->keywords)) {
                $query->where(function ($query) use ($request) {
                    $keywords = $request->keywords;
                    $firstKeyword = array_shift($keywords);
                    $query->whereJsonContains('keywords', $firstKeyword);
                    foreach ($request->keywords as $keyword)
                        $query->orWhereJsonContains('keywords', $keyword);
                });
            } else {
                $query->whereJsonContains('keywords', $request->keywords);
            }
        }

        return $query;
    }
}
