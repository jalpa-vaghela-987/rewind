<div>
    <div class="logo-container text-center">
        <img src="{{asset('img/logo_white.png')}}" class="logo icon-logo-green col-auto p-0" alt="Logo"/>
    </div>
    <ul class="navflex-column mb-auto list">
        <li class="nav-item  @if(Route::currentRouteName() == 'guest.dashboard') active @endif">
            <a href="{{ route('guest.dashboard') }}" class="nav-link " aria-current="page">
                <svg class="icon icon-home me-2" width="17" height="17">
                    <use href="{{asset('/img/icons.svg#icon-home')}}"></use>
                </svg>Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="#" wire:click="$emit('openCloseRestrictionModal')" class="nav-link">
                <svg class="icon icon-home me-2" width="17" height="17">
                    <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                </svg>Buy
            </a>
        </li>
        <li class="nav-item">
            <a href="#" wire:click="$emit('openCloseRestrictionModal')" class="nav-link">
                <svg class="icon icon-home me-2" width="17" height="17">
                    <use href="{{asset('/img/icons.svg#icon-sell')}}"></use>
                </svg>Sell
            </a>
        </li>
        <li class="nav-item">
            <a href="#" wire:click="$emit('openCloseRestrictionModal')" class="nav-link">
                <svg class="icon icon-home me-2" width="17" height="17">
                    <use href="{{asset('/img/icons.svg#icon-negotation')}}"></use>
                </svg>Negotiation
            </a>
        </li>
    </ul>

    <div class="user-panel mb-0 mt-auto">
        <div class="image d-flex align-items-center me-4">
            <h6 class="text-left mb-0 long text-white d-flex col h-100">
                <div class="d-flex flex-column col-9">
                    <a class="button-green col white-button" href="{{route('register')}}">Register</a>
                </div>
            </h6>
        </div>
    </div>
</div>
