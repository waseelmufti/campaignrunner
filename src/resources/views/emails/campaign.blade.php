<h1>{{ $title }}</h1>
<p>{!! nl2br(e($content)) !!}</p>
<p>Dear {{ $recipient->name }},</p>
<p>Thank you!</p>
