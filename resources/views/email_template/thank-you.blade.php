<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{$title ?? __("subscriber.thanks_subscribing")}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center" style="height: 100vh;">
    <h1 class="display-3">{{__("common.thank_you")}}</h1>
    <p class="lead"><strong>{{__("common.subs_confirmed")}}</strong> {{__("common.will_hear_from_us_soon")}}</p>
    <hr>
    <p>
        {{__("common.hvn_trouble")}} <a href="mailto:{{\App\Facades\Common::getAdminEmailId()}}">{{__("common.cnt_us")}}</a>
    </p>
    <p class="lead">
        <a class="btn btn-primary btn-sm" href="{{url('/')}}" role="button">{{__("common.continue_to_homepage")}}</a>
    </p>
</div>


</body>
</html>
