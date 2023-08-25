<div>
    @php
        /* @var \App\Facades\Util $util */
        /* @var \App\Facades\Common $common */
    @endphp
    @include("back.common.spinner")
    <div class="content">
        <div class="container-fluid">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="card">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        @php $photo_link = $photo_link ? $photo_link : "default.png"; @endphp
                                        <img class="profile-user-img img-fluid img-thumbnail border-0"
                                             src="{{asset("uploads/".$photo_link)}}"
                                             alt="{{$name}}">
                                    </div>

                                    <h3 class="profile-username text-center">{{$name}}</h3>

                                    <p class="text-muted text-center">
                                        @foreach($current_user->roles as $role)
                                            <span
                                                class="badge badge-dark">{{\Illuminate\Support\Str::title($role->name)}}</span>
                                        @endforeach
                                        @php
                                            use App\Models\UserMeta;
                                            $assigned_on = null;
                                            $user_meta = UserMeta::where("user_id",\Illuminate\Support\Facades\Auth::id())->first();
                                            if($user_meta){
                                            $assigned_on = json_decode($user_meta->get_assign());
                                            }
                                        @endphp
                                        @if($assigned_on)
                                            @foreach($assigned_on as $items)
                                                @foreach($items as $std=>$div)
                                                    <span
                                                        class="badge badge-dark">
                                                        {{Str::title($common::getCourseName($std))}} | {{Str::title($common::getCourseYearName($div))}}</span>
                                                    <br/>
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </p>

                                    @if(!empty($education))
                                        <strong><i class="fas fa-book mr-1"></i> {{__("common.education")}}</strong>
                                        <p class="text-muted">
                                            {{$education}}
                                        </p>
                                        <hr>
                                    @endif
                                    @if(!empty($address))
                                        <strong><i class="fas fa-map-marker-alt mr-1"></i> {{__("common.location")}}
                                        </strong>
                                        <p class="text-muted">{{$address}}</p>
                                        <hr>
                                    @endif

                                    @if(!empty($about_me))
                                        <strong><i class="far fa-file-alt mr-1"></i> {{__("common.about_me")}}</strong>
                                        <p class="text-muted">{{$about_me}}</p>
                                    @endif
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>

                        <div class="col-md-9">
                            <div class="card">

                                <div class="card-header p-2">

                                    <ul class="nav nav-pills">

                                        <li class="nav-item"><a class="nav-link {{$tab==1?'active':''}}"
                                                                wire:click="$set('tab', 1)" href="#"
                                                                data-toggle="tab">{{__('common.my_details')}}</a></li>
                                        <li class="nav-item"><a class="nav-link {{$tab==2?'active':''}}"
                                                                wire:click="$set('tab', 2)" href="#"
                                                                data-toggle="tab">{{__('common.change_password')}}</a>
                                        </li>

                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">

                                        <div class="tab-pane {{$tab==1?'active':''}}">


                                            @if(session()->has("form_profile") && session()->get("form_profile"))
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include("common.messages")
                                                    </div>
                                                </div>
                                            @endif
                                            <form class="form-horizontal" wire:submit.prevent="saveProfile">
                                                @csrf

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.upload_image"),"prf") !!}
                                                    <input type="file" class="form-control  text-sm"
                                                           accept=".jpg,.jpeg,.png"
                                                           wire:model="photo">

                                                    @error('photo')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.upload_proof").CForm::generateInfoToolTip(__("common.either_image_plus_pdf")),"prf") !!}
                                                    <input type="file" class="form-control  text-sm"
                                                           wire:model="proof" accept=".jpg,.jpeg,.png,.pdf">

                                                    @if($proof_link)
                                                        <div class="input-group-append">
                                                            <a target="_blank" href="{{asset('uploads/'.$proof_link)}}"
                                                               class="btn btn-dark"><i
                                                                    class="fas fa-download"></i></a>
                                                        </div>
                                                    @endif

                                                    @error('proof')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.full_name"),"prf") !!}
                                                    <input type="text" class="form-control"
                                                           wire:model.defer="name">
                                                    @error('name')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.email"),"prf") !!}
                                                    <input type="email" wire:model="email" class="form-control"
                                                           readonly>
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>


                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.phone"),"prf") !!}
                                                    <input type="text" class="form-control" wire:model.defer="phone"
                                                    >
                                                    @error('phone')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.education"),"prf") !!}
                                                    <input type="text" class="form-control" wire:model.defer="education"
                                                    >
                                                    @error('education')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>


                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.address"),"prf") !!}
                                                    <textarea class="form-control" wire:model.defer="address"
                                                    ></textarea>
                                                    @error('address')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.about_me"),"prf") !!}
                                                    <textarea class="form-control" wire:model.defer="about_me"
                                                    ></textarea>
                                                    @error('about_me')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="form-check">
                                                                <input wire:model.defer="check" class="form-check-input"
                                                                       type="checkbox" value="" id="tacCheckChecked"
                                                                       checked>
                                                                <label class="form-check-label" for="tacCheckChecked">
                                                                    {{__("common.i_agree")}} <a target="_blank"
                                                                     href="@if(!empty(trim(strip_tags($common::getSiteSettings("toi_heading")))))/terms-and-conditions @else#@endif">{{__("common.terms_and_condition")}}</a>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @error('check')
                                                        <div
                                                            class="error_holder">@include('back.common.validation', ['message' =>  $message ])</div> @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <div class="input-group">
                                                        <button type="submit"
                                                                class="btn btn-sm btn-dark"><i
                                                                class="fas fa-save mr-1"></i>{{__("common.save_profile")}}
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="{{$tab==2?'active':''}} tab-pane">
                                            @if(session()->has("form_change_password") && session()->get("form_change_password"))
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include("common.messages")
                                                    </div>
                                                </div>
                                            @endif

                                            <form class="form-horizontal" wire:submit.prevent="savePassword">
                                                @csrf
                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.current_password"),"pass") !!}
                                                    <input type="password" class="form-control"
                                                           wire:model.defer="current_password"
                                                    >
                                                    @error('current_password')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.new_password"),"pass") !!}
                                                    <input type="password" class="form-control"
                                                           wire:model.defer="password"
                                                    >
                                                    @error('password')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    {!! CForm::inputGroupHeader(__("common.confirm_password"),"pass") !!}
                                                    <input type="password" class="form-control"
                                                           wire:model.defer="password_confirmation"
                                                    >
                                                    @error('password_confirmation')
                                                    <div
                                                        class="error_holder"> @include('back.common.validation', ['message' =>  $message ]) </div> @enderror
                                                    {!! CForm::inputGroupFooter()!!}
                                                </div>

                                                <div class="mb-2">
                                                    <div class="input-group">
                                                        <button type="submit"
                                                                class="btn btn-sm btn-dark"><i
                                                                class="fas fa-save mr-1"></i>{{__("common.change_password")}}
                                                        </button>
                                                    </div>
                                                </div>


                                            </form>

                                        </div>
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.nav-tabs-custom -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
        </div>
    </div>
</div>

