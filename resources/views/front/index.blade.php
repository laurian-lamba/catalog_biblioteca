@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
@extends("front.master")
@section("content")

    <!--====== SLIDER PART START ======-->

    <section id="home" class="slider_area">
        @includeIf("rico.rico_front_message")
        @php $all_sliders = \App\Models\Slider::all(); @endphp
        @if(is_countable($all_sliders) && count($all_sliders)>0)
        <div id="carouselThree" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselThree" data-slide-to="0" class="active"></li>
                <li data-target="#carouselThree" data-slide-to="1"></li>
                <li data-target="#carouselThree" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                @foreach($all_sliders as $slider_obj)
                    <div class="carousel-item @if($loop->index==0) active @endif">
                        <div class="container-fluid p-0">
                            <div class="row no-gutters">
                                <div class="col-lg-12">
                                    <div class="slider-content p-0">
                                        <img src="{{asset('uploads/'.$slider_obj->image)}}" alt="Slider_{{$loop->index}}" class="w-100 slider_img">
                                        <div class="slider_h1_holder"><h1 class="title">{{$slider_obj->header}}</h1></div>
                                        <div class="slider_h3_holder"><h3 class="text m-0 slider_h3">{{$slider_obj->sub_header}}</h3></div>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div> <!-- container -->
                    </div> <!-- carousel-item -->
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselThree" role="button" data-slide="prev">
                <i class="lni lni-arrow-left"></i>
            </a>
            <a class="carousel-control-next" href="#carouselThree" role="button" data-slide="next">
                <i class="lni lni-arrow-right"></i>
            </a>
        </div>
        @endif
    </section>


    <!--====== SLIDER PART ENDS ======-->


    <!--====== BOOK PORTFOLIO PART START ======-->

    <section id="books" class="portfolio-area portfolio-four pb-100">
        @php $notices = \App\Models\NoticeManager::where("active",1)->where("show_in","front_end")->first();@endphp
        @if($notices)
            <div class="container">
                <div class="row">
                    <h3>{{__("common.notice")}}</h3>
                    <div class="alert alert-dark" role="alert">{{$notices->notice}}</div>
                </div>
            </div>
        @endif


        <div class="container">

            @if(!empty($common::getSiteSettings("book_heading")))
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12">
                        <div class="section-title text-center pb-10">
                            <h3 class="title">{!! Str::title($common::getSiteSettings("book_heading"))!!}</h3>
                            @if(!empty($common::getSiteSettings("book_sub_heading")))
                                <p class="text">{!! $common::getSiteSettings("book_sub_heading") !!}</p>
                            @endif
                        </div> <!-- section title -->
                    </div>
                </div> <!-- row -->
                @if($common::getSiteSettings("enable_simple_search"))
                    @php
                        $books = $common::getBooksDetailsForFrontEnd();
                        $unq_cat = array_column($books,"PARENT_CAT");
                        $unq_cat = array_unique($unq_cat);
                    @endphp
                    @if(is_countable($books) && count($books)>0)
                        <div class="row book_wrapper" id="basic_mode">
                            <div class="col-lg-3 col-md-3">
                                <div class="portfolio-menu text-center" style="margin-top: 4%;">
                                    <ul>
                                        <li data-filter="*"
                                            class="active border-radius-0">{{__("common.all_books")}}</li>
                                        @php $cat_slugs=[] @endphp
                                        @foreach($unq_cat as $cat)
                                            <li class="border-radius-0"
                                                data-filter=".mkr-{{$common::utf8Slug($common::getCatName($cat))}}">{{Str::title($common::getCatName($cat))}}</li>
                                        @php $cat_slugs[$cat]='.mkr-'.$common::utf8Slug($common::getCatName($cat)); @endphp
                                        @endforeach
                                        <script>
                                            window.basic_slugs = {!! json_encode($cat_slugs) !!}
                                        </script>

                                    </ul>
                                </div> <!-- portfolio menu -->
                            </div>
                            <div class="col-lg-9 col-md-9">
                                <div class="row">
                                    <div class="col-12">
                                        <input id="search_book" type="text" class="form-control search_txtbox"
                                               placeholder="{{__("commonv2.pl_search_text")}}">
                                    </div>
                                </div>
                                <div class="row no-gutters grid mt-15">
                                    @foreach($books as $book)
                                        <div
                                            class="col-lg-2 col-sm-3 col-4 mb-2 {{$common::utf8Slug(isset($book["PARENT_CAT"])?"mkr-".$common::getCatName($book["PARENT_CAT"]):"everyone")}}">
                                            <div class="single-portfolio">
                                                <div class="portfolio-image">
                                                    <div class="dewey_holder">{{__("commonv2.shelf_no")}}
                                                        : {{$book["SHELF_NO"]??'--'}}</div>
                                                    <figure class="mb-0">
                                                        <a class="w-100"
                                                           href="{{config('app.APP_URL')}}/details/{{$common::utf8Slug($book["TITLE"])}}">
                                                            <img style="padding: 5px;height: 162px;" src="{{asset("uploads/".$util::fileChecker(public_path("uploads"),
                                                    $book["CIMG"],config("app.BOOK_IMG_NOT_FOUND")))}}"
                                                                 alt="{{$book["TITLE"]}}">
                                                        </a>
                                                        <figcaption
                                                            class="fig_cap_holder @if(intval($book["TBOOKS"])-intval($book["BORROWED"])==0) all_issued @endif">
                                                            @if(intval($book["TBOOKS"])-intval($book["BORROWED"])==0)
                                                                <a>{{__("common.all_issued")}}</a>
                                                            @else
                                                                <a>{{__("common.available")}}</a>
                                                            @endif
                                                        </figcaption>
                                                    </figure>
                                                </div>
                                            </div> <!-- single portfolio -->
                                            <div class="d-none title">
                                                {{Str::lower($book["TITLE"])}},
                                                {{Str::lower($common::getCatName($book["CATEGORY"]))}}
                                                {{implode(",",array_map(function ($data) use($common){return Str::lower($common::getAuthorName($data));},$book["AUTHOR"]))}}
                                                {{implode(",",array_map(function ($data) use($common){return Str::lower($common::getPublisherName($data));},$book["PUBLISHER"]))}}
                                                {{implode(",",array_map(function ($data) use($common){return Str::lower($common::getTagName($data));},$book["TAG"]))}}

                                            </div>
                                        </div>
                                    @endforeach

                                </div> <!-- row -->
                            </div>
                        </div> <!-- row -->
                    @else
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-dark">{{__("commonv2.no_books_are_added_yet")}}</div>
                            </div>
                        </div>
                    @endif
                @else
                    @livewire("front-book-search",["sel_main_cat"=>request()->pcat ,
                    "sel_sub_cat"=>request()->scat,"search_keyword"=>request()->search])
                @endif
            @endif
        </div> <!-- container -->

    </section>

    <!--====== BOOK PORTFOLIO PART ENDS ======-->


    <!--====== FAQ PART START ======-->
    @if($common::getSiteSettings("no_of_faqs",0)>0)
        <section id="about" class="about-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="faq-content mt-45">
                            <div class="about-title">
                                <h6 class="sub-title">{{__("common.a_little_more_about_us")}}</h6>
                                <h4 class="title">{!! $common::getSiteSettings("faq_heading") !!}</h4>
                            </div> <!-- faq title -->
                            <div class="about-accordion">
                                <div class="accordion" id="accordion">
                                    @foreach(range(1,$common::getSiteSettings("no_of_faqs")) as $i)
                                        <div class="card">
                                            <div class="card-header" id="heading{{$i}}">
                                                <a href="#" data-toggle="collapse" data-target="#collapse{{$i}}"
                                                   aria-expanded="@if($loop->first) true @else false @endif"
                                                   aria-controls="collapseOne">{!! Str::title($common::getSiteSettings("faq_que_".$i)) !!}</a>
                                            </div>

                                            <div id="collapse{{$i}}" class="collapse @if($loop->first) show @endif"
                                                 aria-labelledby="heading{{$i}}"
                                                 data-parent="#accordion">
                                                <div class="card-body">
                                                    <p class="text">{!!  Str::title($common::getSiteSettings("faq_ans_".$i))!!}</p>
                                                </div>
                                            </div>
                                        </div> <!-- card -->
                                    @endforeach

                                </div>
                            </div> <!-- faq accordion -->
                        </div> <!-- faq content -->
                    </div>
                    <div class="col-lg-7">
                        <div class="about-image">
                            <img src="{{asset('uploads/faq-banner.png')}}" alt="about">
                        </div> <!-- faq image -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
    @endif
    <!--====== FAQ PART ENDS ======-->


    @if(is_numeric($common::getSiteSettings("no_of_testimonials")) && $common::getSiteSettings("no_of_testimonials",0)>0)
        <!--====== TESTIMONIAL PART START ======-->
        <section id="testimonial" class="testimonial-area">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-xl-5 col-lg-6">
                        <div class="testimonial-left-content mt-45">
                            <h6 class="sub-title">{!!  __("common.testimonials")!!}</h6>
                            <h4 class="title">{!!$common::getSiteSettings("testimonial_heading")!!}</h4>
                            <ul class="testimonial-line">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <p class="text">{!!$common::getSiteSettings("testimonial_sub_heading")!!}</p>
                        </div> <!-- testimonial left content -->
                    </div>
                    <div class="col-lg-6">
                        <div class="testimonial-right-content mt-50">
                            <div class="quota">
                                <i class="lni lni-quotation"></i>
                            </div>
                            <div class="testimonial-content-wrapper testimonial-active">
                                @foreach(range(1,$common::getSiteSettings("no_of_testimonials")) as $index)
                                    @php
                                        $icon = $common::getSiteSettings("testimonial_pic_".$index);
                                        $name = $common::getSiteSettings("testimonial_name_".$index);
                                        $comment = $common::getSiteSettings("testimonial_desc_".$index);
                                    @endphp
                                    @if($comment)
                                        <div class="single-testimonial">
                                            <div class="testimonial-text">
                                                <p class="text">“{{$comment}}”</p>
                                            </div>
                                            <div class="testimonial-author d-sm-flex justify-content-between">
                                                <div class="author-info d-flex align-items-center">
                                                    <div class="author-image">
                                                        <img src="{{asset('uploads/'.$icon.".png")}}" alt="{{$name}}">
                                                    </div>
                                                    <div class="author-name media-body">
                                                        <h5 class="name">{{Str::title($name)}}</h5>
                                                        <span class="sub-title">{{__("common.user")}}</span>
                                                    </div>
                                                </div>
                                                <div class="author-review">
                                                    <ul class="star">
                                                        @php $star = rand(4,10) ;@endphp
                                                        @foreach(range(1,$star) as $i)
                                                            <li><i class="lni lni-star-filled"></i></li>
                                                        @endforeach
                                                    </ul>
                                                    <span class="review">( {{$star}} {{__("common.reviews")}}  )</span>
                                                </div>
                                            </div>
                                        </div> <!-- single testimonial -->
                                    @endif
                                @endforeach


                            </div> <!-- testimonial content wrapper -->
                        </div> <!-- testimonial right content -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </section>
        <!--====== TESTIMONIAL PART ENDS ======-->
    @endif


    <!--====== CONTACT PART START ======-->

    <section id="contact" class="contact-area">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-12">
                    <div class="section-title text-center pb-30">
                        <h3 class="title">{{htmlspecialchars_decode(trim(strip_tags($common::getSiteSettings("contact_heading",__("common.contact_us")))))}}</h3>
                        @if($common::getSiteSettings("contact_sub_heading"))
                            <p class="text">{!! $common::getSiteSettings("contact_sub_heading") !!}</p>
                        @endif
                    </div> <!-- section title -->
                </div>
            </div> <!-- row -->
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="contact-map">
                        <iframe id="gmap_canvas"
                                src="{{$common::getSiteSettings("google_map",
                            "https://maps.google.com/maps?q=Mission%20District%2C%20San%20Francisco%2C%20CA%2C%20USA&t=&z=13&ie=UTF8&iwloc=&output=embed")}}"
                                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                    </div> <!-- row -->
                </div>
                <div class="col-12 col-md-6">
                    <h4 class="contact-title text-center"><i class="lni lni-envelope"></i> Leave <span>A Message.</span>
                    </h4>
                    <div class="col-12">
                        @if($common::getSiteSettings("org_address"))
                            <div class="single-contact-info contact-color-1 mt-20 d-flex ">
                                <div class="contact-info-icon">
                                    <i class="lni lni-map-marker"></i>
                                </div>
                                <address class="pl-2">
                                    <dt>{{__("common.address")}}</dt>
                                    {{$common::getSiteSettings("org_address")}}
                                </address>
                            </div>
                        @endif
                        @if($common::getSiteSettings("org_email"))
                            <div
                                class="single-contact-info contact-color-2 mt-20 d-flex w-50 float-left email_text_holder">
                                <div class="contact-info-icon">
                                    <i class="lni lni-envelope"></i>
                                </div>
                                <address class="pl-2">
                                    <dt>{{__("common.email_address")}}</dt>
                                    {{$common::getSiteSettings("org_email")}}
                                </address>
                            </div>
                        @endif
                        @if($common::getSiteSettings("org_fax"))
                            <div class="single-contact-info contact-color-3 mt-20 d-flex w-50 pl-5p fax_text_holder">
                                <div class="contact-info-icon">
                                    <i class="lni lni-phone"></i>
                                </div>
                                <address class="pl-2">
                                    <dt>{{__("common.our_fax_id")}}</dt>
                                    {{$common::getSiteSettings("org_fax")}}
                                </address>
                            </div>
                        @endif
                        @if($common::getSiteSettings("org_phone"))
                            <div class="single-contact-info contact-color-3 mt-20 d-flex ">
                                <div class="contact-info-icon">
                                    <i class="lni lni-phone"></i>
                                </div>
                                <address class="pl-2">
                                    <dt>{{__("common.contact_phones")}}</dt>
                                    {{$common::getSiteSettings("org_phone")}}
                                </address>
                            </div>
                        @endif

                    </div>
                    <div class="col-md-12">
                        @livewire("send-enquiry")
                    </div>
                </div> <!-- row -->
            </div> <!-- row -->
            <div class="contact-info pt-30">

            </div> <!-- contact info -->

        </div> <!-- container -->
    </section>

    <!--====== CONTACT PART ENDS ======-->
@endsection

@section("css_loc")
    <style>
        .all_issued {
            background-color: red;
            color: white;
        }

        .search_txtbox {
            font-size: 17px !important;
            border: 2px solid #e85454 !important;
            padding: 5px !important;
            height: 68px !important;
            box-shadow: 0px 2px 19px 0px rgb(21 18 18 / 18%);
        }

        .border-radius-0 {
            border-radius: 0 !important;
        }

        .dewey_holder {
            right: 0;
            text-align: right;
            bottom: -24px;
            background-color: black;
            color: white;
        }

        .book_wrapper {
            box-shadow: 0px 19px 50px 0px rgb(21 18 18 / 25%);
            padding-bottom: 3%;
        }

    </style>

@endsection
@section("js_loc")
    <script>
        $(document).ready(function () {
            $(".bhoechie-tab-menu>div.list-group>a").click(function (e) {
                e.preventDefault();
                $(this).siblings('a.active').removeClass("active");
                $(this).addClass("active");
                var index = $(this).index();
                $(".bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
                $(".bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
            });
            $("#home_bread").on("click", function (e) {
                e.preventDefault();
                window.livewire.emit('data_manager', {'sel_main_cat': '', "sel_sub_cat": ''});
            });
            $("#main_bread").on("click", function (e) {
                e.preventDefault();
                debugger;
                window.livewire.emit('data_manager', {"sel_sub_cat": ''});
            });
        });
    </script>
@endsection
