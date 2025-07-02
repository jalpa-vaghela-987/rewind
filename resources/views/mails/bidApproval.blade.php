<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.name')}}</title>
</head>
<body>
<h1>{{ $data['title'] }}</h1>
<p>{{ $data['body'] }}</p>
<a href="{{ $data['url'] }}">Negotiation</a>
</body>
</html>
