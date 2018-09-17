<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function retrieve(User $user, Payment $payment)
    {
        return $user->isAuthorOf($payment);
    }
}
