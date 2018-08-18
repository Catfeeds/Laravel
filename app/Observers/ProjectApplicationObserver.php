<?php

namespace App\Observers;

use App\Models\ProjectApplication;
use App\Notifications\ProjectAppliedViaDatabase;
use App\Notifications\ProjectAppliedViaEmail;

class ProjectApplicationObserver
{
    // 有设计师报名时通知甲方
    public function created(ProjectApplication $application)
    {
        $party = $application->project->user;
        $party->notify(new ProjectAppliedViaDatabase($application));

        // 甲方邮箱激活时才通知
        if ($party->email_activated) {
            $party->notify(new ProjectAppliedViaEmail($application));
        }
    }
}
