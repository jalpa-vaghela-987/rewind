<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name')}}</title>
</head>
<body>
    <h1>Forget Password Verification Code</h1>

    Your forgot password verification code: <strong>{{ $data->token }}</strong> .

</body>
</html>
