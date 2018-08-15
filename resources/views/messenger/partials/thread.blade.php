<?php $class = $thread->isUnread(1) ? 'alert-info' : ''; ?>

<div class="media alert {{ $class }}">
    <h4 class="media-heading">
        <a href="{{ route('messages.show', $thread->id) }}">{{ $thread->subject }}</a>
        ({{ $thread->userUnreadMessagesCount(1) }} unread)</h4>
    <p>
        {{ $thread }}
        {{ $thread->latestMessage }}
        {{ $thread->latestMessage['body'] }}
    </p>
    <p>
        <small><strong>Creator:</strong> {{ $thread->creator()->name }}</small>
    </p>
    <p>
        <small><strong>Participants:</strong> {{ $thread->participantsString(1) }}</small>
    </p>
</div>