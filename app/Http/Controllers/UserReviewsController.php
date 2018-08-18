<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Invitation;
use App\Models\Review;
use App\Models\User;
use App\Models\Work;
use App\Transformers\ReviewTransformer;
use Illuminate\Http\Request;

class UserReviewsController extends Controller
{
    // 某个用户的评价列表
    public function index(User $user, Request $request) {
        $reviews = $user->reviews()->recent()->paginate($request->per_page ?? 1);
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
            return $this->response->errorBadRequest(__('Cannot repeat invitation'));
        }
        if($user->reviews()
            ->where('reviewer_id', $request->invited_user_id)
            ->exists()) {
            return $this->response->errorBadRequest(__('Cannot repeat invitation'));
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
}
