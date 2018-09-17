<?php

namespace App\Observers;

use App\Models\Payment;
use App\Notifications\ProjectPayedViaDatabase;
use App\Notifications\ProjectPayedViaEmail;

class PaymentObserver
{
    // 通知设计师
    public function created(Payment $payment)
    {
        $user = $payment->user;
        $user->notify(new ProjectPayedViaDatabase($payment));
        $user->notifyViaEmail(new ProjectPayedViaEmail($payment));
    }
}
