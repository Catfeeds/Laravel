<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/31
 * Time: 上午9:54
 */

namespace App\Transformers;

use App\Models\ProjectRemittance;
use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class RemittanceTransformer extends TransformerAbstract
{
    public function transform(ProjectRemittance $remittance)
    {
        return [
            'id'          => $remittance->id,
            'project_id'  => $remittance->project_id,
            'amount'      => $remittance->amount,
            'number'      => $remittance->number,
            'bank'        => $remittance->bank,
            'name'        => $remittance->name,
            'remitted_at' => $remittance->remitted_at->toDateString(),
            'created_at'  => $remittance->created_at->toDateTimeString(),
            'updated_at'  => $remittance->updated_at->toDateTimeString()
        ];
    }
}