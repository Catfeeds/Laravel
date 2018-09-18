<?php

namespace App\Observers;

use App\Models\ProjectDelivery;
use App\Notifications\ProjectDeliveredViaDatabase;
use App\Notifications\ProjectDeliveredViaEmail;

class ProjectDeliveryObserver
{
    // 有设计师提交设计文件时通知甲方
    public function created(ProjectDelivery $delivery)
    {
        $party = $delivery->project->user;
        $party->notify(new ProjectDeliveredViaDatabase($delivery));
        $party->notifyViaEmail(new ProjectDeliveredViaEmail($delivery));
    }
}
