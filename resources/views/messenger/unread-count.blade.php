<?php use App\Models\User;

$count = User::find(2)->unreadMessagesCount(); ?>
@if($count > 0)
    <span class="label label-danger">{{ $count }}</span>
@endif
