<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Transformers\PaymentTransformer;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Payment $payment) {
        $this->authorize('retrieve', $payment);
        return $this->response->item($payment, new PaymentTransformer());
    }
}
