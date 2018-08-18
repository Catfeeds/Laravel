<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Models\User;
use App\Notifications\InvitedViaDatabase;
use App\Notifications\InvitedViaEmail;

class InvitationObserver
{
    // 通知被邀请方
    public function created(Invitation $invitation)
    {
        $invitedUser = $invitation->invitedUser;
        $invitedUser->notify(new InvitedViaDatabase($invitation));
        $invitedUser->notifyViaEmail(new InvitedViaEmail($invitation));
    }
}
