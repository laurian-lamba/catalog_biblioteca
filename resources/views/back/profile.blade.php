@extends("back.common.master")
@section("page_name")
    {{__("common.my_profile")}}
@endsection
@section("content")
    @livewire("profile")
@endsection
@section("css_loc")
    <link rel="stylesheet" href="{{asset('css/profile.css')}}">
@endsection
