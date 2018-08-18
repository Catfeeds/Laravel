<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('zh');
        \App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
        \App\Models\ProjectApplication::observe(\App\Observers\ProjectApplicationObserver::class);
        \App\Models\Invitation::observe(\App\Observers\InvitationObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \API::error(function (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
            abort(404);
        });
        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        });
        \API::error(function (\Symfony\Component\Finder\Exception\AccessDeniedException $exception) {
            abort(403, $exception->getMessage());
        });
    }
}
