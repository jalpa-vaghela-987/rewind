<div>
    <h1>Email verified successfully</h1>
    @if(auth()->check())
        <a href="route('dashboard')"> Go to dashboard </a>
    @else
        <a href="route('login')"> Go to Login </a>
    @endif
</div>
