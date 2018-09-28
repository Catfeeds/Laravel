<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

// 访问API时，如果用户已经登录，判断用户是否被拉黑。被拉黑时返回401，要求重新登录。
class CheckInBlackList
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::guard('api')->user();

        if ($user && $user->in_blacklist) {
            throw new UnauthorizedHttpException('any', __('当前账号已被拉黑'));
        }

        return $next($request);
    }
}
