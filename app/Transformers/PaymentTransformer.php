<?php
/**
 * User: ZhuKaihao
 * Date: 2018/8/4
 * Time: 下午5:21
 */

namespace App\Transformers;


use App\Models\Payment;
use App\Models\Review;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
    public function transform(Payment $payment)
    {
        return [
            'id'          => $payment->id,
            'user_id'     => (int)$payment->user_id,
            'project_id'  => (int)$payment->project_id,
            'amount'      => $payment->amount,
            'number'      => $payment->number,
            'bank'        => $payment->bank,
            'name'        => $payment->name,
            'remitted_at' => $payment->remitted_at->toDateTimeString(),
            'created_at'  => $payment->created_at->toDateTimeString(),
            'updated_at'  => $payment->updated_at->toDateTimeString()
        ];
    }
}