<div>
    <div class="logo-container text-center">
        <img src="{{asset('img/logo_white.png')}}" class="logo icon-logo-green col-auto p-0" alt="Logo" />
    </div>
    @if ((Auth::user()) && Auth::user()->hasRole('admin') && Auth::user()->email == 'admin@gmail.com')
        <ul class="navflex-column mb-auto list">
            <li class="nav-item  @if(Route::currentRouteName() == 'admin.dashboard') active @endif">
                <a href="{{ route('admin.dashboard') }}" class="nav-link " aria-current="page">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-home')}}"></use>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'admin.users') active @endif">
                <a href="{{ route('admin.users') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                    </svg>
                    Users
                </a>
            </li>
            </li>

            <li class="nav-item @if(Route::currentRouteName() == 'admin.deals') active @endif">
                <a href="{{ route('admin.deals') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                    </svg>
                    Deals
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'admin.bids') active @endif">
                <a href="{{ route('admin.bids') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                    </svg>
                    Bids
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'admin.certificates') active @endif">
                <a href="{{ route('admin.certificates') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                    </svg>
                    Carbon Credits
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'admin.notifications') active @endif">
                <a href="{{ route('admin.notifications') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                    </svg>
                    Notifications
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'admin.settings') active @endif">
                <a href="{{ route('admin.settings') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                    </svg>
                    Setting
                </a>
            </li>
        </ul>
        <ul class="navflex-column ms-auto list bottom-list">
            <li class="nav-item ">
                <a type="button" class="nav-link link-notifications" aria-current="page"
                   wire:click="$emit('openNotificationModal')"
                   data-bs-container="body" data-bs-placement="right" data-bs-content="Right popover">
                    <svg class="icon icon-bell-outline me-2" width="16" height="16">
                        <use href="{{asset('img/icons.svg#icon-bell-outline')}}"></use>
                    </svg>
                    Notifications
                    @php
                        $notify= auth()->user()->unreadNotifications()->count('id');
                    @endphp
                    @if($notify > 0)
                        <span class="badge bg-danger">
                            {{$notify}}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>
            </li>
        </ul>
    @else
        <ul class="navflex-column mb-auto list">
            <li class="nav-item  @if(Route::currentRouteName() == 'dashboard') active @endif">
                <a href="{{ route('dashboard') }}" class="nav-link " aria-current="page">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-home')}}"></use>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'buy') active @endif">
                <a href="{{ route('buy') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-buy')}}"></use>
                    </svg>
                    Buy
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'sell') active @endif">
                <a href="{{ route('sell') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="17" height="17">
                        <use href="{{asset('/img/icons.svg#icon-sell')}}"></use>
                    </svg>
                    Sell
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'offers') active @endif">
                <a href="{{ route('offers') }}" class="nav-link">
                    <img src="{{asset('/img/icon-negotation.svg')}}" alt="" width="18" height="18" class="icon me-2">Offers
                </a>
            </li>
            <li class="nav-item @if(Route::currentRouteName() == 'my-portfolio') active @endif">
                <a href="{{ route('my-portfolio') }}" class="nav-link">
                    <svg class="icon icon-home me-2" width="16" height="16">
                        <use href="{{asset('/img/icons.svg#icon-portfolio')}}"></use>
                    </svg>
                    My PortFolio
                </a>
            </li>
        </ul>

        <ul class="navflex-column ms-auto list bottom-list">
            <li class="nav-item ">
                <a type="button" class="nav-link link-notifications" aria-current="page"
                   wire:click="$emit('openNotificationModal')"
                   data-bs-container="body" data-bs-placement="right" data-bs-content="Right popover">
                    <svg class="icon icon-bell-outline me-2" width="16" height="16">
                        <use href="{{asset('img/icons.svg#icon-bell-outline')}}"></use>
                    </svg>
                    Notifications
                    @php
                        $notify= auth()->user()->unreadNotifications()->count('id');
                    @endphp
                    @if($notify > 0)
                        <span class="badge bg-danger">
                            {{$notify}}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    @endif
                </a>
            </li>
        </ul>

    @endif

    <div class="user-panel mb-0 mt-auto dropdown">
        <div class="image d-flex align-items-center me-4" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="box image rounded-circle">
                @if (Auth::user()->profile_photo_path)
                    <img src="{{Auth::user()->profile_photo_path}}" alt="{{ Auth::user()->name }}">
                @endif

            </div>
            <h6 class="text-left mb-0 long text-white d-flex col h-100">
                <div class="d-flex flex-column col-9">
                    {{ Auth::user()->name }}
                </div>
            </h6>
        </div>
        <ul class="dropdown-menu dropdown-menu-actions">
            <li><a class="dropdown-item" href="{{ url('profile/details') }}">My Details</a></li>
            <li><a class="dropdown-item" href="{{ url('profile/payment') }}">Manage Payments</a></li>
            <li><a class="dropdown-item" href="{{ url('profile/transaction') }}">Transactions</a></li>
            <li><a class="dropdown-item" href="{{ url('profile/log') }}">Activity Log</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Support</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item" href="javascript:void(0);" wire:click.prevent="$emit('openCloseLogoutModal')">
                    <svg width="24" height="24" class="icon icon-logout text-white">
                        <use href="{{asset('img/icons.svg#icon-logout')}}"></use>
                    </svg>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</div>
@push('modals')
    @livewire('auth.logout')
@endpush
