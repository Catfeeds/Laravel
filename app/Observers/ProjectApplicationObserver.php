<?php

namespace App\Observers;

use App\Models\ProjectApplication;
use App\Notifications\ProjectApplied;

class ProjectApplicationObserver
{
    // 有设计师报名时通知甲方
    public function created(ProjectApplication $application)
    {
        $party = $application->project->user;
        $party->notify(new ProjectApplied($application));

    }
}
