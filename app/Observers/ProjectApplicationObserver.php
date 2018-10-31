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
        $client = $application->project->user;
        $client->notify(new ProjectAppliedViaDatabase($application));
        $client->notifyViaEmail(new ProjectAppliedViaEmail($application));
    }
}
