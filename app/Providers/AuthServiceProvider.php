<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        \App\Models\Activity::class  => \App\Policies\ActivityPolicy::class,
        \App\Models\Reply::class  => \App\Policies\ReplyPolicy::class,
        \App\Models\Project::class  => \App\Policies\ProjectPolicy::class,
        \App\Models\ProjectApplication::class  => \App\Policies\ProjectApplicationPolicy::class,
        \App\Models\Work::class  => \App\Policies\WorkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
