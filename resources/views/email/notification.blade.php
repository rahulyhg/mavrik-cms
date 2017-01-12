@extends('layouts.email')

@section('content')
    <div class="message--content">
        <h1>New Message Request!</h1>
        <h3>You have a new visitor to your website that wants to get in touch with you. Message below: </h3>
        <p>From: {{$sender}}</p>
        <p>Subject: {{$subject}}</p>
        <p>{{$text}}</p>
        <p>Please respond back to:  {{$respond}}</p>

        <span>This is an automated response from Mavrik Systems. <br> If you have received this message in error please contact your hosting provider.</span>
    </div>
@endsection
