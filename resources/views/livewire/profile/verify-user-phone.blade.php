<div>
    <h1>Phone number verified successfully</h1>
    @if(auth()->check())
        <a href="route('buy')"> Go to Buy </a>
    @else
        <a href="route('login')"> Go to Login </a>
    @endif
</div>
