<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Transformers\ReviewTransformer;
use Illuminate\Http\Request;

class UserReviewsController extends Controller
{
    public function index(User $user, Request $request) {
        $reviews = Review::where('user_id', $user->id)->recent()->paginate($request->per_page ?? 20);
        return $this->response->paginator($reviews, new ReviewTransformer());
    }
}
