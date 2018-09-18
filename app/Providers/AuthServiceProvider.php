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
        \App\Models\Notification::class  => \App\Policies\NotificationPolicy::class,
        \App\Models\Review::class  => \App\Policies\ReviewPolicy::class,
        \App\Models\ProjectDelivery::class  => \App\Policies\ProjectDeliveryPolicy::class,
        \App\Models\Payment::class  => \App\Policies\PaymentPolicy::class,
        \App\Models\User::class  => \App\Policies\UserPolicy::class,
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
