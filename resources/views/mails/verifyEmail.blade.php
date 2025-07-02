<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name')}}</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
    <a href="{{ $details['url'] }}">Click to verify</a>
    <span>OR</span>
    <p> Use OTP:<b>{{$details['token']}}</b> to verify via mobile</p>
    <p>Thank you</p>
</body>
</html>
