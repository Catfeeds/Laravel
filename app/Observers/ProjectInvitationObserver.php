<?php

namespace App\Observers;

use App\Models\ProjectInvitation;
use App\Notifications\ProjectInvitedViaDatabase;
use App\Notifications\ProjectInvitedViaEmail;

class ProjectInvitationObserver
{
    // 有新邀请时通知设计师
    public function created(ProjectInvitation $invitation)
    {
        // 见ProjectServices
    }
}
