@extends("back.common.master")
@section("page_name") {{__("commonv2.dewey_classification")}} @endsection
@section("content")
    @livewire("dewey-decimal")
@endsection
@section("css_loc")
    <style>
        .parent {
            background-color: #343a40 !important;
            color: white !important;
        }
        .sub_parent{

        }
    </style>
@endsection
