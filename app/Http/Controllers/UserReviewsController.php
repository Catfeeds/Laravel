<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Invitation;
use App\Models\Review;
use App\Models\User;
use App\Transformers\ReviewTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserReviewsController extends Controller
{
    // 某个用户的评价列表
    public function index(User $user, Request $request) {
        if($request->type === 'posted') { // 发表的评价
            $reviews = $user->postedReviews()->recent()->paginate($request->per_page ?? 20);
        } else { // 收到的评价
            $reviews = $user->reviews()->recent()->paginate($request->per_page ?? 20);
        }
        return $this->response->paginator($reviews, new ReviewTransformer());
    }

    // 邀请设计师
    public function invite(InvitationRequest $request) {
        $user = $this->user();

        // 不能重复邀请同一个人评价
        if($user->invitations()
            ->where('invited_user_id', $request->invited_user_id)
            ->toReview()
            ->exists()) {
            return $this->response->errorBadRequest(__('不能重复邀请'));
        }
        if($user->reviews()
            ->where('reviewer_id', $request->invited_user_id)
            ->exists()) {
            return $this->response->errorBadRequest(__('不能重复邀请'));
        }

        Invitation::create([
            'user_id' => $user->id,
            'invited_user_id' => $request->invited_user_id,
            'type' => 'review'
        ]);

        return $this->response->noContent();
    }

    // 发表评价
    public function store(User $user, ReviewRequest $request, Review $review) {
        $this->authorize('store', [$review, $user]);

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
    public function destroy(Review $review) {
        $this->authorize('destroy', $review);
        $review->delete();
        return $this->response->noContent();
    }

    // 当前登录用户对另一个用户的评价状态
    public function status(Request $request) {
        $user = User::findOrFail($request->uid);
        $currentUser = $this->user();
        $currentUser->setReviewStatus($user);
        return $this->response->item($currentUser, new UserTransformer());
    }
}
