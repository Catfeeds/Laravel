<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Invitation;
use App\Models\Review;
use App\Models\User;
use App\Policies\ReviewPolicy;
use App\Transformers\ReviewTransformer;
use App\Transformers\UserForReviewTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UserReviewsController extends Controller
{
    // 某个用户的评价列表
    public function index(User $user, Request $request)
    {
        if ($request->type === 'posted') { // 发表的评价
            $reviews = $user->postedReviews()->recent()->paginate($request->per_page ?? 20);
        } else { // 收到的评价
            // 置顶的评价在前面，其他评价按时间排序
            // recent必须放在orderBy后面，表示次要排序条件
            $reviews = $user->reviews()->orderBy('order_id', 'desc')->recent()->paginate($request->per_page ?? 20);
        }
        return $this->response->paginator($reviews, new ReviewTransformer());
    }

    // 邀请设计师
    public function invite(InvitationRequest $request)
    {
        $user = $this->user();

        // 不能重复邀请同一个人评价
        if ($user->invitations()
            ->where('invited_user_id', $request->invited_user_id)
            ->toReview()
            ->exists()) {
            return $this->response->errorBadRequest(__('不能重复邀请'));
        }
        if ($user->reviews()
            ->where('reviewer_id', $request->invited_user_id)
            ->exists()) {
            return $this->response->errorBadRequest(__('该用户已发表过评价，不能重复邀请'));
        }

        Invitation::create([
            'user_id'         => $user->id,
            'invited_user_id' => $request->invited_user_id,
            'type'            => 'review'
        ]);

        return $this->response->noContent();
    }

    // 发表评价
    public function store(User $user, ReviewRequest $request, Review $review)
    {
        $this->authorize('store', [Review::class, $user]);

        $currentUser = $this->user();

        // 保存评价
        $review->user_id = $user->id;
        $review->reviewer_id = $currentUser->id;
        $review->content = $request->input('content');
        $review->save();

        // 评价完就删除邀请
        $user->invitations()
            ->where('invited_user_id', $currentUser->id)
            ->toReview()
            ->delete();

        return $this->response->item($review, new ReviewTransformer());
    }

    // 删除评价
    public function destroy(Review $review)
    {
        $this->authorize('destroy', $review);
        $review->delete();
        return $this->response->noContent();
    }

    // 当前登录用户对另一个用户的评价状态
    public function status(Request $request)
    {
        $user = User::findOrFail($request->uid);
        $currentUser = $this->user();
        $currentUser->setReviewStatusToUser($user);
        return $this->response->item($currentUser, new UserForReviewTransformer());
    }

    // 当前登录用户是否可以评价另一个用户
    public function canReview(Request $request)
    {
        $user = User::findOrFail($request->uid);
        $currentUser = $this->user();

        try {
            $res = (new ReviewPolicy())->store($currentUser, $user);
        } catch (AccessDeniedException $exception) {
            $this->response->errorBadRequest($exception->getMessage());
        }

        if (!$res) {
            $this->response->errorBadRequest(__('无权评价该用户'));
        }

        return ['can' => $res];
    }

    // 置顶
    public function stick(Review $review)
    {
        $this->authorize('stick', $review);
        $review->order_id = 1;
        $review->save();
        return $this->response->item($review, (new ReviewTransformer())->setDefaultIncludes(['reviewer']));
    }

    // 取消置顶
    public function unstick(Review $review)
    {
        $this->authorize('stick', $review);
        $review->order_id = 0;
        $review->save();
        return $this->response->item($review, (new ReviewTransformer())->setDefaultIncludes(['reviewer']));
    }
}
