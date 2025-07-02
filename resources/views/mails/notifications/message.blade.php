<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name')}}</title>
</head>
<body>
    <h1>Hello {{$data['receiver']}}!</h1>
    <h6>The Message send By {{$data['sender']}}</h6>
    <p>
        {!! $data['message'] !!}
    </p>
    <p>Thank you</p>
</body>
</html>
