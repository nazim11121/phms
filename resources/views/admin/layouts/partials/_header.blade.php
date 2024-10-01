<!-- Top Bar Start -->
<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <span>
                <img src={{ site_logo() }} class="ic-logo-height" alt="logo">
            </span>
            <i>
                <img src={{ site_logo() }} class="ic-logo-small" alt="logo">
            </i>
        </a>
        <!-- <div class="float-right">
            <button class="button-menu-mobile ic-collapsed-btn mobile-device-arrow open-left">
                <div class="ic-medi-menu">
                    <div class="ic-bar"></div>
                    <div class="ic-bar"></div>
                    <div class="ic-bar"></div>
                </div>
            </button>
        </div> -->
    </div>
    <nav class="navbar-custom">
        <div class="float-left">
            <button class="button-menu-mobile ic-collapsed-btn mobile-device-arrow open-left ml-4">
                <div class="ic-medi-menu">
                    <div class="ic-bar"></div>
                    <div class="ic-bar"></div>
                    <div class="ic-bar"></div>
                </div>
            </button>
        </div>
        
        <ul class="navbar-right d-flex list-inline float-right mb-0 align-items-center">
            <!-- Profile-->
            <li class="dropdown notification-list">
                <div class="dropdown notification-list nav-pro-img">
                    <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#"
                       role="button" aria-haspopup="false" aria-expanded="false">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar_url }}" alt="user" class="rounded-circle">
                        @else
                            <img src="{{ Auth::user()->avatar_url }}" alt="user" class="rounded-circle">
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">

                        <a href="/admin/profile" class="dropdown-item">
                            {{ \Illuminate\Support\Str::limit(Auth::user()->name, 15) ?? '' }}<br>
                            <small>{{ Auth::user()->email ?? '' }}</small>
                        </a>

                        <a class="dropdown-item logout-btn" href="#">
                            <i class="mdi mdi-power text-danger"></i>
                            {{ __('logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>

        <ul class="list-inline menu-left mb-0 ic-left-content">
            <li class="float-left ic-larged-deviced">
                <button class="button-menu-mobile">
                    <i class="mdi mdi-arrow-right open-left ic-mobile-arrow"></i>
                    <div class="ic-medi-menu ic-humbarger-bar">
                        <div class="ic-bar"></div>
                        <div class="ic-bar"></div>
                        <div class="ic-bar"></div>
                    </div>
                </button>
            </li>
        </ul>
        <div class="nav-btn ml-8">
            <a href="{{route('admin.invoice.create')}}" class="btn btn-success">+ New Invoice</a>
        </div>
    </nav>
</div>
<!-- Top Bar End -->
