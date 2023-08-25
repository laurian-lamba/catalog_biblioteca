@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
@extends("front.master")
@section("content")
    <section class="book_holder">
        <div class="" style="background-image: url('{{asset('uploads/cover.png')}}');
            height: 164px;
            margin-bottom: 2%;"></div>
        <div class="container border">
            @if(isset($book_obj))
                @php $pcat = $common::getParentCatOfSubCat($book_obj->category);@endphp
                @php $pcat_name = Str::title($common::getCatName($pcat));@endphp
                @php $scat_name = Str::title($common::getCatName($book_obj->category));@endphp
                <div class="row mb-10">
                    <nav aria-label="breadcrumb" class="w-100">
                        <ol class="breadcrumb" style="background-color: #eaeaea;">
                            <li class="breadcrumb-item"><a
                                    href="{{config("app.APP_URL")}}">{{__("common.home")}}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{config("app.APP_URL")."/?pcat=".$pcat."#books"}}">
                                    {{$pcat_name}}</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{config("app.APP_URL")."/?pcat=".$pcat."&scat=".$book_obj->category."#books"}}">
                                    {{$scat_name}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a href="{{config("app.APP_URL")}}/details/{{$common::utf8Slug($book_obj->title)}}">{{Str::words($book_obj->title,4,"...")}}</a>
                            </li>
                        </ol>
                    </nav>

                </div>
            @endif
            <div class="row justify-content-center">
                <div class="col-md-3 col-12 mb-5 img_main_div_holder">
                    <img alt={{$book_obj->title}} src="{{$book_obj->cover_img()}}" class="img-thumbnail w-100"/>
                </div>
                <div class="col-md-9 col-12 mb-5">
                    <div class="pg_sub_page_header">
                        <h1 class="mb-2">{{isset($book_obj) ? $book_obj->title : "N/A"}}</h1>
                        @php $authors = $book_obj->authors()->pluck("authors.name")->toArray();@endphp
                        @if(count($authors))
                            <strong>{{__("common.by")}} - </strong><span class="">
                                @if(count($authors))
                                    @foreach($authors as $author)
                                        <a class="btn-link"
                                           href="{{url("/")."?search=".$author."#books"}}">{{$author}}</a>
                                        @if(!$loop->last) , @endif @endforeach
                                @else -- @endif
                        </span>
                        @endif
                        <div class="w-100 mt-2">
                            <div class="form-row">
                                @if($book_obj->category)
                                    <div class="mt-2 col-md-6 col-12">
                                        <strong>{{__("common.category")}}
                                            - </strong><a class="btn-link"
                                                          href="{{url('/').'?pcat='.$pcat
.'&scat='.$book_obj->category}}#books">
                                            <span>{{$scat_name}}</span></a>
                                    </div>
                                @endif
                                @php $publishers = $book_obj->publishers()->pluck("publishers.name")->toArray();@endphp
                                @if(count($publishers))
                                    <div class="mt-2 col-md-6 col-12">
                                        <strong>{{__("common.publisher")}}
                                            - </strong>
                                        @if(count($publishers))
                                            @foreach($publishers as $publisher)
                                                <a class="btn-link"
                                                   href="{{url("/")."?search=".$publisher."#books"}}">{{$publisher}}</a>
                                                @if(!$loop->last) , @endif @endforeach
                                        @else -- @endif
                                    </div>
                                @endif
                            </div>
                            <div class="form-row">
                                @if($book_obj->isbn_10)
                                    <div class="mt-2 col-md-6 col-12">
                                        <strong>{{__("common.isbn_10")}} - </strong><span>{{$book_obj->isbn_10}}</span>
                                    </div>
                                @endif
                                @if($book_obj->isbn_13)
                                    <div class="mt-2 col-md-6 col-12">
                                        <strong>{{__("common.isbn_13")}} - </strong><span>{{$book_obj->isbn_13}}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-row">
                                <div class="mt-2 col-12 col-md-6">
                                    <strong>{{__("common.book_status")}}
                                        - </strong>
                                    @php $books_available = $util::countProperty($book_obj->sub_books->toArray(),"borrowed","0"); @endphp
                                    {!! isset($book_obj->sub_books) ? $books_available ?
                    '<span class="badge badge-success">'.__("common.avl_with_us",["count"=>$books_available])
                    .'</span>':'<span class="badge badge-danger">'.__("common.not_avl_with_us").'</span>' :
                     '<span class="badge badge-danger">'.__("common.no_books").'</span>' !!}
                                </div>
                                @php $tags = $book_obj->tags()->pluck("tags.name")->toArray();@endphp
                                @if(count($tags))
                                    <div class="mt-2 col-12 col-md-6">
                                        <strong>{{__("commonv2.tags")}}
                                            - </strong>
                                        @if(count($tags))
                                            @foreach($tags as $tag)
                                                <a class="btn-link"
                                                   href="{{url("/")."?search=".$tag."#books"}}">{{$tag}}</a>
                                                @if(!$loop->last) , @endif @endforeach
                                        @else -- @endif
                                    </div>
                                @endif
                            </div>
                            @if($book_obj->category)
                                <div class="form-row no-gutters">
                                    <div class="mt-2 col-12 col-md-12">
                                        <strong>{{__("commonv2.shelf_no")}}
                                            - </strong>
                                        {{$common::getShelfNo($book_obj->category)}}
                                    </div>
                                </div>
                            @endif

                            @if($book_obj->desc)
                                <div class="mt-2">
                                    <dt>
                                        {{__("common.book_info")}}
                                    </dt>
                                    <dd style="padding-top: 2%;">
                                        {!! $book_obj->desc !!}
                                    </dd>
                                </div>
                            @endif
                            <br>
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- NewLib -->
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-3650616995017646"
                                 data-ad-slot="8804895394"
                                 data-ad-format="auto"
                                 data-full-width-responsive="true"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                            <br><br>
                            <div class="mt-2">

                                <p class="d-inline">
                                    @livewire("partial.borrow-book",["user_id"=>Auth::id(),"book_id"=>$book_obj->id,"book_obj"=>$book_obj
                                    ,"books_available"=>$books_available])
                                </p>

                                <div class="row mt-2 preview_container">
                                    <div class="col">
                                        <div class="collapse multi-collapse" id="mcoll">
                                            <div class="card card-body">
                                                <div id="viewerCanvas" style="width: 100%; height: 500px"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($note_obj) && count($note_obj))
                <div class="row notes_container">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header"><h3
                                    class="text-center w-100 mb-2"><span
                                        class="card-header-title">{{__("common.notes_on")}}</span> {{$book_obj->title}}
                                </h3>
                            </div>
                            <div class="card-body">
                                @if(Auth::check())
                                    <div id="accordion-note">
                                        @foreach($note_obj as $note)
                                            <div class="card">
                                                <div class="card-header" id="heading{{$loop->index}}">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" data-toggle="collapse"
                                                                data-target="#collapse{{$loop->index}}"
                                                                aria-expanded="true"
                                                                aria-controls="collapse{{$loop->index}}">
                                                            <strong>{{intval($loop->index)+1}}
                                                                ) </strong>{{$note->note_title}}
                                                        </button>
                                                        <span
                                                            class="text-sm">{{__("common.notes_by")}} {{\App\Models\User::get_user_name($note->user_id)}}

                                                    </span>
                                                        <span style="padding-top: 1%;"
                                                              class="float-right">{{Util::goodDate($note->created_at)}}</span>

                                                    </h5>
                                                </div>

                                                <div id="collapse{{$loop->index}}"
                                                     class="collapse @if($loop->first) show @endif"
                                                     aria-labelledby="heading{{$loop->index}}"
                                                     data-parent="#accordion-note">
                                                    <div class="card-body">
                                                        {!! $note->note_desc !!}
                                                        @if($note->files_attached)
                                                            <div class="w-100 mt-5">
                                                                {!! CForm::inputGroupHeader(__("common.attached_notes"),"w-100","w-100") !!}
                                                                <ul class="list-group w-100">
                                                                    @foreach($note->files_attached as $file)
                                                                        @php
                                                                            $file_link = asset("uploads/".$file);
                                                                            if(\Illuminate\Support\Str::endsWith($file,[".doc",".docx"])){
                                                                                $file_link ="https://docs.google.com/gview?url=".$file_link;
                                                                            }
                                                                        @endphp
                                                                        <li class="list-group-item ">{{intval($loop->index)+1}}
                                                                            ) {{$file}} <a
                                                                                class="btn btn-sm btn-dark ml-1"
                                                                                href="{{$file_link}}"
                                                                                target="_blank"><i
                                                                                    class="fas fa-external-link-alt"></i></a>
                                                                            <a
                                                                                class="btn btn-sm btn-dark ml-1"
                                                                                href="{{asset("uploads/".$file)}}"
                                                                                target="_blank"><i
                                                                                    class="fas fa-download"></i></a>

                                                                        </li>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-dark">{{__("common.notes_only_for_logged_in_user")}} <a
                                            href="{{route('login')}}"><i
                                                class="fas fa-user mr-1"></i>{{__("common.login")}}</a></div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </section>
@endsection
@section("css_loc")
    <link rel="stylesheet" href="{{asset('css/book_detail.css')}}">
    <style>
        p {
            margin-bottom: 4%;
        }
    </style>
@endsection
@section("js_loc")
    <script type="text/javascript" src="https://www.google.com/books/jsapi.js"></script>
    <script>
        $(document).ready(function () {
            @if(!$book_obj->custom_file)
            google.books.load();

            function initialize() {
                var viewer = new google.books.DefaultViewer(document.getElementById('viewerCanvas'));
                viewer.load('ISBN:{{$book_obj->isbn_10??$book_obj->isbn_13}}');
            }

            google.books.setOnLoadCallback(initialize);
            show_preview = function () {
                $(".cust_overlay").fadeIn(300);
                $(".preview_container .collapse").toggle("show");
                initialize();
                setTimeout(
                    function () {
                        if (jQuery("#viewerCanvas :first-child").length < 5) {
                            show_message("info", "Alert", '{{__("common.no_preview_avl")}}');
                            $(".preview_container .collapse").toggle("hide");
                        }
                        $(".cust_overlay").fadeOut(300);
                    }, 8000);
            };
            @else
                show_preview = function () {
                $(".cust_overlay").fadeIn(300);
                $(".preview_container .collapse").toggle("show");
                @php $file_link = asset("uploads/".$book_obj->custom_file);@endphp
                @if(\Illuminate\Support\Str::endsWith($book_obj->custom_file,".pdf"))
                $("#viewerCanvas").html("<iframe width='100%' height='500px' src='{{$file_link}}'></iframe>");
                @elseif(\Illuminate\Support\Str::endsWith($book_obj->custom_file,[".doc",".docx"]))
                $("#viewerCanvas").html("<iframe src=\"https://docs.google.com/gview?url={{$file_link}}&embedded=true\"></iframe>");
                @else
                $("#viewerCanvas").html("<img src='{{$file_link}}' class='w-100'/>");
                @endif
                $(".cust_overlay").fadeOut(300);
            };
            @endif
        });
    </script>
@endsection
