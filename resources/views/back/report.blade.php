@extends("back.common.master")
@section("page_name")
    {{__("commonv2.report_printing")}}
@endsection
@section("content")
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{route('reports.store')}}">
                @csrf
                <div class="row no-gutters">
                    <div class="col-md-4 col-12 mb-3">
                        <div class="input-group">
                            <select name="to_do" required class="form-control">
                                <option value="">{{__("common.select")}}</option>
                                <option value="most_issued_books">{{__("commonv2.most_issued_books")}}</option>
                                <option value="damage_books">{{__("commonv2.damage_books")}}</option>
                                <option value="losted_books">{{__("commonv2.lost_books")}}</option>
                                <option value="late_returned">{{__("commonv2.late_returned")}}</option>
                                <option value="fines_collected">{{__("commonv2.fines")}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 col-12 mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Fast</span>
                            </div>
                            <select name="fast_tag" class="form-control">
                                <option value="">{{__("common.select")}}</option>
                                <option value="this_week">{{__("commonv2.this_week")}}</option>
                                <option value="last_30">{{__("commonv2.last_30")}}</option>
                                <option value="this_month">{{__("commonv2.this_month")}}</option>
                                <option value="last_month">{{__("commonv2.last_month")}}</option>
                                <option value="this_year">{{__("commonv2.this_year")}}</option>
                                <option value="last_year">{{__("commonv2.last_year")}}</option>
                            </select>
                            <div class="input-group-append">
                                {!! CForm::inputGroupHeader(__("commonv2.custom"))!!}
                                <input type="text" class="form-control start_date">
                                <input type="hidden" name="start_date" id="start_date">
                                <input type="text" class="form-control end_date">
                                <input type="hidden" name="end_date" id="end_date">
                                {!! CForm::inputGroupFooter() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12 mb-3">
                        <button type="submit" class="btn btn-dark btn-block"><i
                                class="far fa-newspaper mr-2"></i>{{__("commonv2.shw_report")}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section("css_loc")

    <style>

    </style>
@endsection
@section("js_loc")

    <script>
        $(document).ready(function () {
            $(".start_date").datepicker({
                dateFormat: 'dd-mm-yy',
                altField: "#start_date",
                altFormat: 'yy-mm-dd',
            });
            $(".end_date").datepicker({
                dateFormat: 'dd-mm-yy',
                altField: "#end_date",
                altFormat: 'yy-mm-dd',
            });
        });
    </script>
@endsection
