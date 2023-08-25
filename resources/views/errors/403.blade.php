@extends("errors.master")
@section("title") 403 | @if(!empty($exception->getMessage())) {{$exception->getMessage()}} @else {{__("error.unauthorized_person")}} @endif @endsection
@section("content")
    <div class="error">
        <div class="error__title">403</div>
        <div class="error__subtitle">Hmmm...</div>
        @if(empty($exception->getMessage()))
            <div class="error__description">{{__("error.unauthorized_action")}}<strong>{{__("error.access_denied")}}</strong>.</div>
        @else
            <div class="error__description">
                <strong>{{\Illuminate\Support\Str::title($exception->getMessage())}}</strong>.
            </div>
        @endif
        <a href="{{url('/')}}" class="error__button error__button--active">{{__("common.homepage")}}</a>
    </div>
@endsection
