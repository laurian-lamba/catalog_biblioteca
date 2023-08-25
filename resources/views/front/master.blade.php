@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
    <!doctype html>
<html class="no-js" lang="en">
@php
    $meta_title = $common::getSiteSettings("index_meta_title",config("app.APP_URL"));
    $meta_desc = $common::getSiteSettings("index_meta_desc");
    $fav_icon = $common::getSiteSettings("web_ico_file");
    $google_analytics = $common::getSiteSettings("google_analytics");
    $google_verification = $common::getSiteSettings("google_site_verification");
@endphp
<head>
    <meta charset="utf-8">
    @includeIf("rico.rico_title")
    @if(!empty($meta_title) && !File::exists(base_path("resources".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."rico".DIRECTORY_SEPARATOR)))
    <!--====== Title ======-->
        <title>{{$common::getSiteSettings("index_meta_title",config("app.APP_URL"))}}</title>
    @endif
    @if(isset($cust_title) && !blank($cust_title))
        <title>{{$cust_title}}</title>
    @endif
    @if(!empty($google_verification))
        <meta name="google-site-verification" content="{{$google_verification}}"/>
    @endif
    @if(!empty($google_analytics))
        {!! $google_analytics !!}
    @endif

    @includeIf("rico.rico_meta_desc")

    @if(!empty($meta_desc) && !File::exists(base_path("resources".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR."rico".DIRECTORY_SEPARATOR)))
        <meta name="description" content="{{$meta_desc}}">
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!--====== Magnific Popup CSS ======-->
    <link rel="stylesheet" href="{{asset('front/css/magnific-popup.css')}}">

    <!--====== Slick CSS ======-->
    <link rel="stylesheet" href="{{asset('front/css/slick.css')}}">

    <!--====== Line Icons CSS ======-->
    <link rel="stylesheet" href="{{asset('front/css/LineIcons.css')}}">
    <!--====== Bootstrap CSS ======-->
    <link rel="stylesheet" href="{{asset('front/css/bootstrap.min.css')}}">

    <!--====== Default CSS ======-->
    <link rel="stylesheet" href="{{asset('front/css/default.css')}}">

    <!--====== Style CSS ======-->
    <link rel="stylesheet" href="{{asset('front/css/callout.css')}}">
    <link rel="stylesheet" href="{{asset('front/css/style.css')}}">
    <script type="text/javascript" src="https://www.google.com/books/jsapi.js"></script>
    <script src="//kit.fontawesome.com/aba9065474.js" crossorigin="anonymous"></script>
    @yield("css_loc")
    <style>

        @php
            $primary_color  = $util::fallBack($common::getSiteSettings("front_primary"),"#de3b69");
            $secondary_color  = $util::fallBack($common::getSiteSettings("front_secondary"),"#FF9F16");
            $logo_css  = $util::fallBack($common::getSiteSettings("org_logo_css"),"width:140px;");
        @endphp

        .carousel-item {
            background-color: #000000;
        }

        .back-to-top, .team-content::before, .portfolio-menu ul li::before {
            background-color: {{$secondary_color}};
        }

        .single-features .features-title-icon .features-icon i {
            color: {{$secondary_color}};
        }

        .pricing-style, .team-style-eleven {
            background: linear-gradient({{$primary_color}} 0%, {{$secondary_color}} 100%);
        }

        .btn {
            outline: 0 !important;
        }

        .light-rounded-buttons .light-rounded-two, .navbar-area.sticky .navbar .navbar-btn li a.solid,
        .btn_preview, .btn_open_link, .btn_subscribe, .btn_preview:hover, .btn_open_link:hover, .btn_subscribe:hover {
            background-color: {{$secondary_color}};
            border-color: {{$secondary_color}};
        }

        .form-input .input-items.default input:focus, .form-input .input-items.default textarea:focus {
            border-color: {{$secondary_color}}




        }

        .logo_img {
        {{$logo_css}}

        }

    </style>
</head>

<body>
<!--[if IE]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade
    your browser to google/firefox </a> to improve your experience and security.</p>
<![endif]-->


<!--====== NAVBAR TWO PART START ======-->

@include("front.nav")

@yield("content")
<!--====== BACK TOP TOP PART START ======-->

<a href="#" class="back-to-top"><i class="lni lni-chevron-up"></i></a>

<!--====== BACK TOP TOP PART ENDS ======-->


<!--====== Jquery js ======-->
@include("front.footer")
<script src="{{asset('js/jquery-1.12.4.js')}}"></script>
<script src="{{asset('js/jquery-ui.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}">
<script src="{{asset('front/js/vendor/modernizr-3.7.1.min.js')}}"></script>

<!--====== Bootstrap js ======-->
<script src="{{asset('front/js/popper.min.js')}}"></script>
<script src="{{asset('front/js/bootstrap.min.js')}}"></script>

<!--====== Slick js ======-->
<script src="{{asset('front/js/slick.min.js')}}"></script>

<!--====== Magnific Popup js ======-->
<script src="{{asset('front/js/jquery.magnific-popup.min.js')}}"></script>


<!--====== Isotope js ======-->
<script src="{{asset('front/js/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('front/js/isotope.pkgd.min.js')}}"></script>

<!--====== Scrolling Nav js ======-->
<script src="{{asset('front/js/jquery.easing.min.js')}}"></script>
<script src="{{asset('front/js/scrolling-nav.js')}}"></script>
@livewireScripts
<script src="{{asset('js/vue.js')}}"></script>
<link rel="stylesheet" href="{{asset('plugins/sweetalert2/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('css/jquery-confirm.min.css')}}">
<script src="{{asset('js/jquery-confirm.min.js')}}"></script>
<script src="{{asset('js/default.js')}}"></script>
<script src="{{asset('front/js/main.js')}}"></script>
@include("back.common.spinner")
@yield("js_loc")
@includeif("rico.comment")
</body>
</html>

