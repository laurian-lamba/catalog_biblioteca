@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
<div class="w-100">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    @php
        $books=[\App\Models\Book::count()];
    @endphp
    <style>
        .wp_hv {
            padding: 5px;
            border: 1px solid lightgray;
            height: 100%;
            margin-bottom: 10px;
        }

        .cat_name {
            padding-top: 6px;
            font-size: 15px;
        }

        .breadcrumb {
            list-style-type: none;
            padding: 0;
            background-color: unset;
        }

        .custom_li {
            display: inline-block;
            position: relative;
        }

        /*.custom_li:last-child a {*/
        /*    cursor: default;*/
        /*}*/


        .custom_li:last-child::before, .custom_li:last-child::after {
            background: #e9ecef;
        }

        /*.custom_li:not(:last-child):hover::before, .custom_li:not(:last-child):hover::after {*/
        /*    background: lightsalmon;*/
        /*}*/

        .custom_li::before, .custom_li::after {
            content: "";
            position: absolute;
            left: 0;
            height: 56%;
            width: 105%;
            background: white;
            border-left: 2px solid #666;
            border-right: 2px solid #666;
            z-index: -2;
        }

        .custom_li::before {
            top: -2px;
            transform: skew(30deg);
            border-top: 2px solid #666;
        }

        .custom_li::after {
            bottom: -2px;
            transform: skew(-30deg);
            border-bottom: 2px solid #666;
        }

        .custom_li a {
            display: inline-block;
            position: relative;
            line-height: 2.5;
            padding: 0 20px;
            color: #666;
            text-decoration: none;
        }

        .custom_li:first-child {
            background-color: white;
            border-left: 2px solid #666;
            left: -5px;
            box-sizing: content-box;
        }

        /*.custom_li:first-child:hover {*/
        /*    background-color: lightsalmon;*/
        /*}*/

        .custom_li:first-child::before, .custom_li:first-child::after {
            left: 5px;
        }

        .mouse {
            cursor: pointer;
        }

        .dv_img_wrapper {
            margin: auto;
            width: 100%;
            display: flex;
            height: 180px;
        }

        .h-inherit {
            height: inherit;
        }

        .w-80 {
            width: 80%;
        }

        h5 {
            font-size: 13px !important;
        }

        .w-95 {
            width: 95%;
        }

        .w-90 {
            width: 90%;
        }

        .adv_search_box {
            font-size: 20px;
            background: #fafad2;
        }

        .book_fnd_holder {
            padding: 7px;
            margin-bottom: 10px;
            padding-left: 0;
            padding-right: 0;
        }

        @media screen and (max-width: 1024px) {
        }

        @media screen and (max-width: 768px) {
        }

        @media screen and (max-width: 480px) {
            .book_bread_nav {
                font-size: 13px;
            }
        }

        @media screen and (max-width: 378px) {
        }
    </style>

    @if(is_countable($books) && count($books)>0)
        @include("back.common.spinner")
        <div class="row" style="padding: 20px;padding-bottom: 0;">
            <nav class="book_bread_nav"> <!--To provide navigation links-->
                <ol class="breadcrumb " itemscope itemtype="http://schema.org/BreadcrumbList">

                    <li class="custom_li mouse" itemprop="itemListElement" itemscope
                        itemtype="http://schema.org/ListItem">
                        <a itemprop="item" id="home_bread">
                            <span itemprop="name">{{__("common.home")}}</span>
                        </a>
                        <meta itemprop="position" content="1"/>
                    </li>
                    @if(!empty($sel_main_cat))
                        <li class="custom_li mouse" itemprop="itemListElement" itemscope
                            itemtype="http://schema.org/ListItem">
                            <a itemprop="item" wire:click="$set('sel_sub_cat','')">
                                <span itemprop="name">{{$common::getCatName($sel_main_cat)}}</span>
                            </a>
                            <meta itemprop="position" content="2"/>
                        </li>
                        @if(!empty($sel_sub_cat))
                            <li class="custom_li" itemprop="itemListElement" itemscope
                                itemtype="http://schema.org/ListItem">
                                <a itemprop="item">
                                    <span itemprop="name">{{$common::getCatName($sel_sub_cat)}}</span>
                                </a>
                                <meta itemprop="position" content="3"/>
                            </li>
                        @endif
                    @endif

                </ol>
            </nav>
        </div>
        <div class="row no-gutters" style="border: 1px solid lightgray;padding: 10px;">
            @if(empty($sel_main_cat))
                <div class="col-12 mb-3">
                    <div class="input-group">
                        <input type="text" wire:model.lazy="search_keyword" class="form-control adv_search_box"
                               placeholder="{{__("commonv2.pl_search_text")}}">
                        <div class="input-group-append">
                            <button class="btn btn-danger" type="button" wire:click="clearKeyword()">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

                @if(session()->has("books-found-searching"))
                    <div
                        class="col-12 book_fnd_holder">{!! CForm::calloutInfo(session()->get("books-found-searching")) !!}</div>
                @endif
                @if(empty($search_keyword))
                    @foreach($parentCats as $item)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2">
                            <div wire:key="{{$item->id}}" class="w-100 wp_hv mouse"
                                 wire:click="$set('sel_main_cat',{{$item->id}})">
                                @if($common::getSiteSettings("enable_image_classificaiton"))
                                    <div class="dv_img_wrapper"><img class="w-90 m-auto"
                                                                     src="{{asset('uploads/'.$item->catImage())}}"/>
                                    </div>
                                    <a class="btn btn-link w-100">
                                        <h4 class="text-center cat_name">{{$item->cat_name}}</h4>
                                    </a>
                                @else
                                    <div class="m-auto"
                                         style="width: 150px;height: 150px;background-color: {{$item->bg_color}}">
                                        <h4 style="color: {{$item->txt_color}};position: relative;top: 35%;"
                                            class="text-center cat_name">{{$item->cat_name}}</h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <script>window.location.hash = "#books";</script>
                    @endforeach
                @else
                    @php //dump($matched_books) @endphp
                    @foreach($matched_books as $item)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2">
                            <div class="w-100 wp_hv">
                                <a class="w-100" target="_blank"
                                   href="{{url("/")."/details/".$common::utf8Slug($item->title)}}">
                                    <div class="dv_img_wrapper"><img class="w-90 m-auto h-inherit"
                                                                     src="{{$item->cover_img()}}"/>
                                    </div>
                                    <h5 class="text-center cat_name w-100">{{$item->title}}</h5>
                                    <h5 class="text-center cat_name w-100">
                                        {{__("commonv2.shelf_no")}} : {{$common::getShelfNo($item->category)}}</h5>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    @if(!count($matched_books))
                        <div class="col-12" wire:loading.remove>
                            <div class="alert alert-warning">{{__("commonv2.no_match_book_found")}}</div>
                        </div>
                        <script>window.location.hash = "#books";</script>
                    @endif

                @endif
            @endif
            {{--            <div class="col-12 d-none mb-1" wire:loading.class="d-block">--}}
            {{--                <div class="alert alert-warning">{{__("commonv2.plz_wait_searching")}}</div>--}}
            {{--            </div>--}}
            @if(session()->has("books-found"))
                <div class="col-12 book_fnd_holder">{!! CForm::calloutInfo(session()->get("books-found")) !!}</div>
            @endif
            @if(!empty($sel_main_cat) && empty($sel_sub_cat))
                @php $items = \App\Models\DeweyDecimal::where("parent",$sel_main_cat)->get() ;@endphp
                @foreach($items as $item)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2">
                        <div wire:key="{{$item->id}}" class="w-100 wp_hv mouse"
                             wire:click="$set('sel_sub_cat',{{$item->id}})">
                            @if($common::getSiteSettings("enable_image_classificaiton"))
                                <div class="dv_img_wrapper"><img class="w-90 m-auto"
                                                                 src="{{asset('uploads/'.$item->catImage())}}"/>
                                </div>
                                <h4 class="text-center cat_name w-100">{{$item->cat_name}}</h4>
                            @else
                                <div class="m-auto"
                                     style="width: 150px;height: 150px;background-color: {{$item->bg_color}}">
                                    <h4 style="color: {{$item->txt_color}};position: relative;top: 35%;"
                                        class="text-center cat_name">{{$item->cat_name}}</h4>
                                </div>
                            @endif
                            {{--                            <h5 class="text-center cat_name w-100">Dc:{{$item->dewey_no}}</h5>--}}
                        </div>
                    </div>
                    <script>window.location.hash = "#books";</script>
                @endforeach
                @if(!count($items))
                    <div class="col">
                        <div class="alert alert-warning">{{__("commonv2.no_book_in_this_cat")}}</div>
                    </div>
                    <script>window.location.hash = "#books";</script>
                @endif
            @endif
            @if(!empty($sel_main_cat) && !empty($sel_sub_cat))
                @php $items = \App\Models\Book::where("category",$sel_sub_cat)->get();@endphp
                @foreach($items as $item)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2">
                        <div class="w-100 wp_hv">
                            <a class="w-100" target="_blank" href="{{url("/")."/details/".$common::utf8Slug($item->title)}}">
                                <div class="dv_img_wrapper"><img class="w-90 m-auto h-inherit"
                                                                 src="{{$item->cover_img()}}"/></div>
                                <h5 class="text-center cat_name w-100">{{$item->title}}</h5>
                                {{--                                <h5 class="text-center cat_name w-100">--}}
                                {{--                                    Dc: {{$common::formatDeweyNo($common::getDeweyNos($item->category))}}</h5> --}}
                                <h5 class="text-center cat_name w-100">
                                    {{__("commonv2.shelf_no")}} : {{$common::getShelfNo($item->category)}} </h5>
                            </a>
                        </div>
                    </div>
                    <script>window.location.hash = "#books";</script>
                @endforeach
                @if(!count($items))
                    <div class="col">
                        <div class="alert alert-warning">{{__("commonv2.no_book_in_this_cat")}}</div>
                    </div>
                    <script>window.location.hash = "#books";</script>
                @endif
            @endif
        </div>
    @else
        <div class="row">
            <div class="col">
                <div class="alert alert-warning">{{__("commonv2.no_books_are_added_yet")}}</div>
            </div>
        </div>
    @endif
</div>
