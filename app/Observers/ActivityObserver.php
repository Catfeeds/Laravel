<?php
/**
 * User: ZhuKaihao
 * Date: 2018/7/31
 * Time: 下午10:26
 */

namespace App\Observers;


use App\Models\Activity;

class ActivityObserver
{
    // 删除动态时，连带删除此动态下的所有回复
    public function deleted(Activity $activity)
    {
        $activity->replies()->delete(); // 不会再次触发ReplyObserver的deleted事件
    }
}