<div class="d-inline-block">
    {{-- Care about people's approval and you will be their prisoner. --}}
    @include("back.common.spinner")

    <div class="d-block">
        <button class="btn btn-outline-danger btn-sm" type="button"
                onclick="show_preview()"><i
                class="fas fa-eye mr-1"></i>{{__("common.preview_book")}}</button>

        <a class="btn btn-outline-primary btn-sm" target="_blank"
           href="{{$util::getIfNotEmpty($book_obj->preview_url) ?? ($book_obj->custom_file
                                            ? asset("uploads/".$book_obj->custom_file):"#")}}"><i
                class="fas fa-external-link-alt mr-1"></i>{{__("common.open_book")}}</a>
        @can("mng-borrow-book")
            @if($common::getSiteSettings("enable_user_borrow") && Auth::id() && $books_available)
                <button class="btn btn-warning btn-sm" type="button"
                        wire:click="listAvailableBooks">
                    {{__("commonv2.borrow_now")}}</button>
            @endif
        @endif
    </div>
    @if(count($available_books))
        <div class="d-block mt-3">
            <ul class="list-group">
                @foreach($available_books as $book)
                    <li class="list-group-item">

                        @if($common::checkIfInBorrowedRequest($book->sub_book_id,$user_id))
                            <button class="btn btn-danger btn-sm float-right" type="button"
                                    wire:click="cancelThis('{{$book->sub_book_id}}')"><i class="fas fa-ban"></i>
                            </button>
                        @else
                            <button class="btn btn-success btn-sm float-right" type="button"
                                    @if($common::checkIfInBorrowedRequest($book->sub_book_id,$user_id)) disabled @endif
                                    wire:click="borrowThis('{{$book->sub_book_id}}')"><i
                                    class="fas fa-shopping-cart"></i>
                            </button>
                        @endif
                        {{$book->sub_book_id}}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
