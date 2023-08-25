@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <a href="{{route('dashboard.index')}}" class="nav-link">{{__("common.home")}} <span
                    class="badge badge-dark">{{__("WY")}} : {{$common::getYearName($common::getWorkingYear())}}</span>
                @if($common::isAnyAdmin())
                    @if($util::phpVersion(config("app.MINIMUM_PHP_VERSION_REQUIRED")))
                        <span
                            class="badge badge-success ml-1 php_v_ok">{{__("PHP").$util::phpVersion(config("app.MINIMUM_PHP_VERSION_REQUIRED"))}}</span>
                    @else
                        <span
                            class="badge badge-danger ml-1 php_v_not_ok">{{__("PHP")." < ".config("app.MINIMUM_PHP_VERSION_REQUIRED")}}</span>
                    @endif
                    @if(!empty($common::getSiteSettings("bought_on")))
                        <span
                            class="badge badge-success ml-1 lic_active">License Active</span>
                    @endif
                    @if(!empty($common::getSiteSettings("expired_on")))
                        <span
                            class="badge badge-danger ml-1 lic_exp">License Expired</span>
                    @endif
                    @if(empty($common::getSiteSettings("expired_on")) && empty($common::getSiteSettings("bought_on")))
                        <span
                            class="badge badge-warning ml-1 lic_invalid">License Invalid</span>
                    @endif
                @endif
            </a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-chevron-circle-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                <div class="dropdown-divider"></div>
                <a href="{{config('app.APP_URL')}}" target="_blank" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{__("common.visit_front_page")}}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{route("profile.index")}}" class="dropdown-item">
                    <i class="far fa-id-card mr-2"></i> {{__("common.profile")}}
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{route("custom.logout")}}" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{__("logout")}}
                </a>
                <div class="dropdown-divider"></div>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
