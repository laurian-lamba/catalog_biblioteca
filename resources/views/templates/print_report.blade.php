@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
@extends("common.master")
@section("css_loc")
    <style>
        * {
            color: black;
        }

        td {
            padding: 10px;
        }

        h3 p {
            margin: 0px;
        }

        @media print {
            table {
                /* tables don't split across pages if possible. */
                page-break-inside: avoid;
            }

            .dashbtn {
                display: none !important;
            }
        }
    </style>
@endsection
@section("title") {{__("commonv2.print_report")}} @endsection
@section("content")
    <div class="container-fluid">
        <div class="row dashbtn">
            <div class="col"><a href="{{route('reports.index')}}"
                                class="btn btn-dark">{{__("commonv2.go_back_to_report")}}</a>
            </div>
        </div>
        <div class="row">
            <div>
                <img style="margin: auto;width: 15%;display: block;"
                     src="{{asset('uploads/'.(!blank($common::getSiteSettings("org_logo"))
                    ?$common::getSiteSettings("org_logo"):config("app.DEFAULT_LOGO")))}}"/>
                <h2 style="text-align: center;padding: 0;margin: 0;">{{$common::getSiteSettings("org_name")}}</h2>
                <h3 style="text-align: center;margin: 0;">
                    {!!$util::goodFormatAddress($common::getSiteSettings("org_address"))!!}</h3>
                <h4 style="    text-align: center;
    text-decoration: underline;
    font-size: 28px;
    font-weight: bold;
    font-family: inherit;
    font-variant: all-small-caps;
    margin: 10px;">{{$title}}</h4>
            </div>
            <table border="1" style="margin: auto;font-size: 13px;font-family: monospace;">
                @if($book_details && $todo=="damage_books")
                    <thead>
                    <tr>
                        <td>{{__("common.book_id")}}</td>
                        <td>{{__("common.book_name")}}</td>
                        <td>{{__("common.user_id")}}</td>
                        <td>{{__("commonv2.user_name")}}</td>
                        <td>{{__("commonv2.date_updated")}}</td>
                        <td>{{__("common.remark")}}</td>
                    </tr>
                    </thead>
                    @foreach($book_details as $item)
                        <tr>
                            <td>{{$item->sub_book_id}}</td>
                            <td>{{$item->book->title}}</td>
                            <td>{{$item->user_damaged->id}}</td>
                            <td>{{$item->user_damaged->name}}</td>
                            <td>{{Util::goodDate($item->updated_at)}}</td>
                            <td>{{$item->remark}}</td>
                        </tr>
                    @endforeach
                @elseif($book_details && $todo=="most_issued_books")

                    <thead>
                    <tr>
                        <td>{{__("common.book_id")}}</td>
                        <td>{{__("common.book_name")}}</td>
                        <td>{{__("common.count")}}</td>
                    </tr>
                    </thead>
                    @foreach($book_details as $item)
                        <tr>
                            <td>{{$item->book_id}}</td>
                            <td>{{$item->book->title}}</td>
                            <td>{{$item->count}}</td>
                        </tr>
                    @endforeach
                @elseif($book_details && $todo=="losted_books")
                    <thead>
                    <tr>
                        <td>{{__("common.book_id")}}</td>
                        <td>{{__("common.book_name")}}</td>
                        <td>{{__("common.user_id")}}</td>
                        <td>{{__("common.lost_by")}}</td>
                        <td>{{__("common.date_returned")}}</td>
                    </tr>
                    </thead>
                    @foreach($book_details as $item)
                        <tr>
                            <td>{{$item->sub_book_id}}</td>
                            <td>{{$item->book->title}}</td>
                            <td>{{$item->user_lost->id}}</td>
                            <td>{{$item->user_lost->name}}</td>
                            <td>{{Util::goodDate($item->updated_at)}}</td>
                        </tr>
                    @endforeach
                @elseif($book_details && $todo=="late_returned")
                    <thead>
                    <tr>
                        <td>{{__("common.book_id")}}</td>
                        <td>{{__("common.book_name")}}</td>
                        <td>{{__("common.user_id")}}</td>
                        <td>{{__("common.full_name")}}</td>
                        <td>{{__("common.issued_by")}}</td>
                        <td>{{__("commonv2.delayed_days")}}</td>
                    </tr>
                    </thead>
                    @foreach($book_details as $item)
                        <tr>
                            <td>{{$item->sub_book->sub_book_id}}</td>
                            <td>{{$item->book->title}}</td>
                            <td>{{$item->user->id}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->issued_by_person->name}}</td>
                            <td>{{\Carbon\Carbon::parse($item->date_to_return)
                            ->diffInDays(\Carbon\Carbon::parse($item->date_returned))}}</td>
                        </tr>
                    @endforeach
                @elseif($book_details && $todo=="fines_collected")
                    <thead>
                    <tr>
                        <td>{{__("common.book_id")}}</td>
                        <td>{{__("common.book_name")}}</td>
                        <td>{{__("common.user_id")}}</td>
                        <td>{{__("common.full_name")}}</td>
                        <td>{{__("common.issued_by")}}</td>
                        <td>{{__("commonv2.delayed_days")}}</td>
                        <td>{{__("commonv2.fine_paid")}}</td>
                        <td>{{__("commonv2.issued_on")}}</td>
                        <td>{{__("commonv2.returned_on")}}</td>
                    </tr>
                    </thead>
                    @php $fine_collected = 0; @endphp

                    @foreach($book_details as $item)
                        <tr>
                            <td>{{$item->sub_book->sub_book_id}}</td>
                            <td>{{$item->book->title}}</td>
                            <td>{{$item->user->id}}</td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->issued_by_person->name}}</td>
                            <td>{{\Carbon\Carbon::parse($item->date_to_return)
                            ->diffInDays(\Carbon\Carbon::parse($item->date_returned))}}</td>
                            <td>{{$item->fine}}</td>
                            <td>{{Util::goodDate($item->date_borrowed)}}</td>
                            <td>{{Util::goodDate($item->date_returned)}}</td>
                        </tr>
                        @php $fine_collected += $item->fine @endphp
                    @endforeach
                    <tr>
                        <td>{{__("commonv2.total_fine_collected")}}</td>
                        <td colspan="20"><span
                                class="mr-2">{{$common::getSiteSettings("currency_symbol")}}</span>{{$fine_collected}}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="15">{{__("common.no_data_exist")}}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
@endsection

