<div class="col-md-6">
    <div class="id-card-holder">
        <div class="id-card">
            <div class="header">
                <img
                    src="{{asset('uploads/'.$util::fileChecker(public_path("uploads"),
                                $common::getSiteSettings("org_logo"),config('app.DEFAULT_LOGO')))}}">
            </div>
            <div class="photo">
                <img
                    src="{{asset('uploads/'.$image)}}">
            </div>
            <h2>{{$user ? \Illuminate\Support\Str::title($user) : "N/A"}}</h2>
            <h1 class="userid">{{$id??"000"}}</h1>
            <h3 class="url_holder">{{$util::goodUrl(config('app.APP_URL'))}}</h3>
            <hr>
            {!! $util::goodFormatAddress($common::getSiteSettings("org_address")) !!}
            <p>Ph: {{$common::getSiteSettings("org_phone")}} |
                E-mail: {{$common::getSiteSettings("org_email")}}</p>
            <p class="return_msg">{{__("commonv2.ids_card_below_msg")}}</p>
        </div>
    </div>
</div>
