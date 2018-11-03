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
        $client = $delivery->project->user;
        $client->notify(new ProjectDeliveredViaDatabase($delivery));
        $client->notifyViaEmail(new ProjectDeliveredViaEmail($delivery));
    }
}
