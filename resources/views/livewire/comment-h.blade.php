<div class="card">
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <style>
        .main_comment_holder {
            border: 1px solid lightgray;
            padding: 8px;
            margin-top: 1%;
        }
        .time{
            font-size: 16px;
            color: indianred;
            font-family: inherit;
            font-style: italic;
            font-weight: bold;
        }
    </style>
    <div class="card-header"><h2 class="text-center">Discussion</h2></div>
    <div class="card-body">
        <div class="row" id="form_holder">
            <div class="col">
                @if($replying_to_name)
                    <div class="alert alert-success">Commenting on : <strong>{{$replying_to_name}}</strong> Thread.
                    </div>
                @endif
                <form wire:submit.prevent="saveComment">
                    <div class="form-group row">
                        <div class="col-md-6 col-12 mb-2">
                            <input type="text" wire:model.defer="name" class="form-control"
                                   style="font-size: 18px;color: black;border: 1px solid lightgray;" id="name"
                                   placeholder="Name">
                            @error("name")<span class="badge badge-danger">{{$message}}</span>@enderror
                        </div>
                        <div class="col-md-6 col-12 mb-2">
                            <input type="text" wire:model.defer="email"
                                   style="font-size: 18px;color: black;border: 1px solid lightgray;"
                                   class="form-control"
                                   id="email"
                                   placeholder="email@domain.com">
                            @error("email")<span class="badge badge-danger">{{$message}}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="mb-2 col-12">
                            <textarea wire:model.defer="message" class="form-control" id="comment"
                                      style="font-size: 18px;color: black;border: 1px solid lightgray;"
                                      placeholder="Comment"></textarea>
                            @error("message")<span class="badge badge-danger">{{$message}}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row mb-2" style="padding-left: 1.5%;" wire:ignore>
                        <div class="w-100">
                            {!! app('captcha')->display(["id"=>"g_captcha","data-callback"=>"onReturnCallback",'add-js' => false])!!}
                        </div>
                        @error("g_recaptcha_response")<span class="badge badge-danger">{{$message}}</span>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Comment</button>
                </form>
            </div>
        </div>
        <hr>
        @php $cnt=2;@endphp
        @foreach($comments as $comment)

            <div class="row no-gutters main_comment_holder">
                <div class="col-md-1">
                    <img style="width: 50px;margin: 0 auto;display: block;"
                         src="{{asset($comment->email=="admin@admin.com" ? "uploads/admin.png":"uploads/user.png")}}"
                         class="img img-rounded img-fluid"/>
                </div>
                @php $week = rand(1,$cnt); $cnt++;@endphp
                <div class="col-md-11 pl-0">
                    <p>
                        <a class="float-left"
                           href="#"><strong>{{$comment->name}}</strong></a><br>
                        @if(\Carbon\Carbon::parse($comment->created_at)->isToday())
                            <span class="time">Today</span>
                        @else
                            <span class="time">{{$week}} Week Ago</span>
                        @endif
                    </p>
                    <div class="clearfix"></div>
                    <p>{{$comment->comment}}</p>
                    <p>
                        <button type="button" wire:click="replyTo({{$comment->id}})"
                                class="float-right btn btn-outline-primary ml-2">Reply
                        </button>

                    </p>
                </div>
            </div>

            @if(is_countable($comment->comments) && count($comment->comments))
                @foreach($comment->comments as $replie)
                    <div class="card card-inner" style="padding-left:5%;margin-top: 1%;border: 0;">
                        <div class="card-body" style="border: 1px dashed #2f282885;">
                            <div class="row">
                                <div class="col-md-1">
                                    <img style="width: 50px;margin: 0 auto;display: block;"
                                         src="{{asset($replie->email=="admin@admin.com"?"uploads/admin.png":"uploads/user.png")}}"
                                         class="img img-rounded img-fluid"/>
                                </div>
                                <div class="col-md-11">
                                    <p>
                                        <a href="#"><strong>{{$replie->name}}</strong></a><br>
                                        @if(\Carbon\Carbon::parse($comment->created_at)->isToday())
                                            <span class="time">Today</span>
                                        @else
                                            <span class="time">{{$week}} Week Ago</span>
                                        @endif
                                    </p>
                                    <p>{{$replie->comment}}</p>
                                    <p>
                                        <button type="button" wire:click="replyTo({{$comment->id}})"
                                                class="float-right btn btn-outline-primary ml-2">Reply
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
        @if(is_countable($comments) && !count($comments))
            <div class="alert alert-dark">No comments yet.</div>
        @endif

    </div>
</div>

