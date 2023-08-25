@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
@extends("back.common.master")
@section("page_name") {{__("common.dashboard")}} @endsection
@section("content")
    <div class="card">
        <div class="card-body">
            @if($common::getSiteSettings("enable_auto_update") && $common::isAnyAdmin())
                @can("mng-setting")
                    {!! CForm::calloutSuccess(".","disp-none","","","update_notification") !!}
                @endcan
            @endif
            <div class="row">
                @foreach($notices_for_me as $notice)
                    @if(!empty($notice->notice))
                        <div class="col">
                            <div class="alert alert-dark" role="alert">
                                <h4 class="alert-heading">{{__("common.notice")}}!</h4>
                                <hr/>
                                <p>{{$notice->notice}}</p>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @can("mng-lib-stat")
                <div class="row">
                    <div class="col-sm-6 col-md-3 col-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>

                            <div class="info-box-content">
                            <span class="info-box-text" data-toggle="tooltip" data-placement="left"
                                  title="{{__('common.total_books')}}">{{__("common.t_books")}}</span>
                                <span
                                    class="info-box-number">{{\App\Models\SubBook::where("active",1)->count()}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6 col-md-3 col-6">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i
                                    class="far fa-money-bill-alt"></i></span>

                            <div class="info-box-content">
                            <span class="info-box-text" data-toggle="tooltip" data-placement="left"
                                  title="{{__('common.collected_fine')}}">{{__("common.c_fine")}}</span>
                                <span
                                    class="info-box-number">{{$common::getSiteSettings("currency_symbol")}} {{array_sum(\App\Models\Borrowed::
                                where("working_year",$common::getWorkingYear())->pluck("fine")->toArray())-array_sum(\App\Models\Payment::
                                where("working_year",$common::getWorkingYear())->pluck("refund_amount")->toArray())}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>

                    <div class="col-sm-6 col-md-3 col-6">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i
                                    class="fas fa-shopping-cart"></i></span>

                            <div class="info-box-content">
                                <a href="{{route('indexReceiveBooks')}}">
                                    <span class="info-box-text"
                                          data-toggle="tooltip"
                                          data-placement="left"
                                          title="{{__('common.currently_borrowed')}}">{{__("common.c_borrowed")}}</span></a>
                                <span
                                    class="info-box-number">{{\App\Models\SubBook::where("borrowed",1)->count()}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6 col-md-3 col-6">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i
                                        class="fas fa-users"></i></span>

                            <div class="info-box-content">
                                <a href="{{route('user-mng.index')}}"><span class="info-box-text">{{__("common.users")}}</span></a>
                                <span class="info-box-number">{{\App\Models\User::count()-1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                </div>
            @endcan
            @can("mng-graph-stat")
                <div class="row">
                    <div class="col-md col-12">
                        <div id="visitorStats" style="height: 380px; width: 100%;">
                        </div>
                    </div>

                    <div class="col-md col-12">
                        <div id="popularBooks" style="height: 380px; width: 100%;">
                        </div>
                    </div>
                </div>
            @endcan
            @can("mng-graph-stat")
                <div class="row">
                    <div class="col-md col-12">
                        <div id="weeklyStats" style="height: 380px; width: 100%;">
                        </div>
                    </div>

                    <div class="col-md col-12">
                        <div id="monthlyStats" style="height: 380px; width: 100%;">
                        </div>
                    </div>
                </div>
            @endcan
            @can("mng-borrow-book")
                @if(!$common::isAnyAdmin())
                    <div class="row">
                        <div class="col-12">
                            @livewire("basic-issued-books")
                        </div>
                    </div>
                @endif
            @endcan
        </div>

    </div>
@endsection
@section("css_loc")
    <style>
        .disp-none {
            display: none;
        }
    </style>
@endsection
@section("js_loc")
    <script type="text/javascript">
        window.onload = function () {
                @can("mng-graph-stat")
                @php $book_stats = $common::getPopularBooks(); @endphp
            var popularBooks = new CanvasJS.Chart("popularBooks", {
                    title: {
                        text: "{{__("common.popular_books")}}"
                    },
                    data: [
                        {

                            @if(count($book_stats)<20)
                            type: "column",
                            @else
                            type: "pie",
                            @endif
                            dataPoints: [
                                    @foreach($book_stats as $book)
                                {
                                    label: "{{Str::words($book["TITLE"],3)}}", y: {{$book["CNT"]}} },
                                @endforeach
                            ]
                        }
                    ],
                    theme: "theme2"
                });
            @if(count($book_stats))
            popularBooks.render();
            @else
            $("#popularBooks").html("<img src='{{asset('uploads/no_data_to_show.png')}}' class='img-thumbnail max-both'>'");
                @endif

                @php $visitor_stats = array_reverse($common::getVisitorStats()); @endphp
            var visitorStats = new CanvasJS.Chart("visitorStats", {
                    title: {
                        text: "{{__("common.visitor_stats")}}"
                    },
                    data: [
                        {
                            type: "area",
                            dataPoints: [
                                    @foreach($visitor_stats as $visit)
                                {
                                    label: "{{$visit["date"]}}", y: {{$visit["cnt"]}} },
                                @endforeach
                            ]
                        }
                    ],
                    theme: "theme2"
                });

            @if(count($visitor_stats))
            visitorStats.render();
            @else
            $("#visitorStats").html("<img src='{{asset('uploads/no_data_to_show.png')}}' class='img-thumbnail max-both'>'");
                @endif




                @php
                    $weekly_borrowed_stats = $common::getWeeklyBorrowedStats();
                    $weekly_returned_stats = $common::getWeeklyReturnedStats();
                @endphp
            var weeklyStats = new CanvasJS.Chart("weeklyStats",
                {
                    title: {
                        text: "{{__("common.pre_week_stat")}}"
                    },

                    data: [

                        {
                            type: "column",
                            showInLegend: true,
                            markerType: "circle",
                            legendText: "{{__("common.outgoing")}}",
                            dataPoints: [
                                    @foreach($weekly_borrowed_stats as $mk)

                                {
                                    x: new Date('{{$mk['DBorrowed']}}'), y: {{$mk['Borrowed']}} },
                                @endforeach
                            ]
                        },
                        {
                            type: "column",
                            showInLegend: true,
                            markerType: "circle",
                            legendText: "{{__("common.incoming")}}",
                            dataPoints: [
                                    @foreach($weekly_returned_stats as $mk)
                                {
                                    x: new Date('{{$mk['DReturned']}}'), y: {{$mk['Returned']}}},
                                @endforeach
                            ]
                        },


                    ]
                });


            @if(count($weekly_returned_stats))
            weeklyStats.render();
            @else
            $("#weeklyStats").html("<img src='{{asset('uploads/no_data_to_show.png')}}' class='img-thumbnail max-both'>'");
                @endif

                @php
                    $monthly_borrowed_stats = $common::getMonthBorrowedStats();
                    $monthly_returned_stats = $common::getMonthlyReturnedStats();
                @endphp
            var monthlyStats = new CanvasJS.Chart("monthlyStats",
                {
                    title: {
                        text: "{{__("common.monthly_Stat")}}"
                    },
                    data: [
                        {
                            type: "column",
                            showInLegend: true,
                            markerType: "circle",
                            legendText: "{{__("common.outgoing")}}",
                            dataPoints: [
                                    @foreach($monthly_borrowed_stats as $mk)

                                {
                                    x: new Date('{{$mk['DBorrowed']}}'), y: {{$mk['Borrowed']}} },
                                @endforeach
                            ]
                        },
                        {
                            type: "column",
                            showInLegend: true,
                            markerType: "circle",
                            legendText: "{{__("common.incoming")}}",
                            dataPoints: [
                                    @foreach($monthly_returned_stats as $mk)
                                {
                                    x: new Date('{{$mk['DReturned']}}'), y: {{$mk['Returned']}}},
                                @endforeach
                            ]
                        },


                    ]
                });


            @if(count($monthly_returned_stats))
            monthlyStats.render();
            @else
            $("#monthlyStats").html("<img src='{{asset('uploads/no_data_to_show.png')}}' class='img-thumbnail max-both'>'");
            @endif
            @endcan

        };

        @if($common::getSiteSettings("enable_auto_update"))
        @can("mng-setting")
        $(document).ready(function () {
            $.ajax({
                type: 'GET',
                url: 'updater.check',
                async: true,
                success: function (response) {
                    if (response !== '') {
                        $('#update_notification').append('<strong>Update Available <span class="badge badge-pill badge-info mr-2 border-radius-0">v. ' + response.version + '</span></strong>' +
                            '<a role="button" href="updater.update" target="_blank" class="btn btn-xs btn-dark text-white text-decoration-none pull-right">Update Now</a>');
                        $("#update_notification").find("p").html(response.description);
                        $('#update_notification').fadeIn(500);
                    }
                }
            });
        });
        @endcan
        @endif
    </script>
    <script type="text/javascript" src="{{asset('js/canvasjs.min.js')}}"></script>
@endsection
