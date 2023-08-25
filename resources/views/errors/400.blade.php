@extends("errors.master")
@section("title") 400 |  @if(!empty($exception->getMessage())) {{$exception->getMessage()}} @else {{__("error.bad_request")}} @endif @endsection
@section("content")
    <div class="error">
        <div class="error__title">400</div>
        <div class="error__subtitle">Hmmm...</div>
        @if(empty($exception->getMessage()))
            <div class="error__description">{{__("error.bad_request_issued")}}</div>
        @else
            <div class="error__description">
                <strong>{{\Illuminate\Support\Str::title($exception->getMessage())}}</strong>.
            </div>
        @endif
        <a href="{{url('/')}}" class="error__button error__button--active">{{__("common.homepage")}}</a>
    </div>
@endsection
