<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name')}}</title>
</head>
<body>
    <h1>Forget Password Email</h1>

    You can reset password from bellow link:
    <a href="{{ route('forgot-password-verify', $data->token) }}">Reset Password</a>
</body>
</html>
