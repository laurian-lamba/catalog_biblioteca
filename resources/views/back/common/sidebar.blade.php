@php
    /* @var \App\Facades\Util $util */
    /* @var \App\Facades\Common $common */
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard.index')}}" class="brand-link">
        <img src="{{asset('uploads/satellite.png')}}"
             alt="{{config("app.APP_NAME")}}"
             class="brand-image"
        >
        <span
            class="brand-text font-weight-light">{{$common::getSiteSettings("admin_page_name",config("app.APP_NAME"))}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset("uploads/".\App\Models\User::get_user_photo())}}"
                     class="img-thumbnail border-0 p-0"
                     alt="{{\App\Models\User::get_current_user_name()}}">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{\App\Models\User::get_current_user_name()}}</a>
                @foreach(\App\Models\User::get_current_user_roles() as $role)
                    <span class="badge badge-danger">{{Str::title($role->name)}}</span>
                @endforeach
                @php
                    $assigned_on=$common::getStandardDivisionAssignedToLoggedInUser();
                @endphp
                @if($assigned_on)
                    @foreach($assigned_on as $items)
                        @foreach($items as $course=>$year)
                            <span
                                class="badge badge-primary">
                                {{Str::title($common::getCourseName($course))}} | {{$common::getCourseYearName($year)}}</span>
                            <br/>
                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent text-sm" data-widget="treeview"
                role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{route('dashboard.index')}}"
                       class="nav-link @if(request()->is("dashboard")) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            {{__("common.dashboard")}}
                        </p>
                    </a>
                </li>

                @can("view-transaction")
                    <li class="nav-item">
                        <a href="{{route('transaction.index')}}"
                           class="nav-link @if(request()->is("transaction*")) active @endif">
                            <i class="nav-icon fab fa-paypal"></i>
                            <p>
                                {{__("common.receipts")}}
                            </p>
                        </a>
                    </li>
                @endcan

                @can("mng-note")

                    <li class="nav-item has-treeview @if(request()->is("notes-mng*")) menu-open @endif">
                        <a href="#" class="nav-link  @if(request()->is("notes-mng*")) active @endif">
                            <i class="nav-icon fas fa-book-reader"></i>
                            <p>
                                {{__("common.mng_notes")}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route("notes-mng.index")}}"
                                   class="nav-link @if(request()->is("notes-mng")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("common.view_note")}}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('notes-mng.create')}}"
                                   class="nav-link @if(request()->is("notes-mng/create")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("common.add_edit_note")}}</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                @endcan


                @can("mng-class")
                    <li class="nav-item has-treeview @if(request()->is("author-mng*")
                || request()->is("publisher-mng*") || request()->is("classification-mng*") || request()->is("tag-mng*")) menu-open @endif">
                        <a href="#"
                           class="nav-link @if(request()->is("author-mng/*")
                || request()->is("publisher-mng/*") || request()->is("classification-mng/*") || request()->is("tag-mng/*")) active @endif">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                {{__('commonv2.mng_classification')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>


                        <ul class="nav nav-treeview">
                            @can("mng-author")
                                <li class="nav-item">
                                    <a href="{{route('author-mng.index')}}"
                                       class="nav-link @if(request()->is("author-mng") || request()->is("author-mng/*")) active @endif">
                                        <i class="fab fa-gg-circle nav-icon"></i>
                                        <p>{{__("commonv2.mng_author")}}</p>
                                    </a>
                                </li>
                            @endcan
                            @can("mng-publisher")
                                <li class="nav-item">
                                    <a href="{{route('publisher-mng.index')}}"
                                       class="nav-link @if(request()->is("publisher-mng") || request()->is("publisher-mng/*")) active @endif">
                                        <i class="fab fa-gg-circle nav-icon"></i>
                                        <p>{{__("commonv2.mng_publisher")}}</p>
                                    </a>
                                </li>
                            @endcan
                            @can("mng-tag")
                                <li class="nav-item">
                                    <a href="{{route('tag-mng.index')}}"
                                       class="nav-link @if(request()->is("tag-mng") || request()->is("tag-mng/*")) active @endif">
                                        <i class="fab fa-gg-circle nav-icon"></i>
                                        <p>{{__("commonv2.mng_tag")}}</p>
                                    </a>
                                </li>
                            @endcan
                            @can("mng-classification")
                                <li class="nav-item">
                                    <a href="{{route('classification-mng.index')}}"
                                       class="nav-link @if(request()->is("classification-mng")) active @endif">
                                        {{--                            <i class="nav-icon fas fa-tachometer-alt"></i>--}}
                                        <i class="fab fa-gg-circle nav-icon"></i>
                                        <p>
                                            {{__("commonv2.dewey_class_short")}}
                                        </p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan




                @can("mng-class")
                    <li class="nav-item has-treeview @if(request()->is("standard")
                || request()->is("course*") || request()->is("course-year/*") || request()->is("course-year") || request()->is("year") || request()->is("year/*")
                ) menu-open @endif">
                        <a href="#"
                           class="nav-link @if(request()->is("year") || request()->is("course-year") || request()->is("course")) active @endif">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                {{__('common.mng_aca')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>


                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{route('course.index')}}"
                                   class="nav-link @if(request()->is("course") || request()->is("course/*")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("course.short_mng_cy")}}</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('course-year.index')}}"
                                   class="nav-link @if(request()->is("course-year") || request()->is("course-year/*")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("course_year.short_mng_cy")}}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('year.index')}}"
                                   class="nav-link @if(request()->is("year") || request()->is("year/*")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>
                                        {{__("common.mng_working_year")}}
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan
                @can("mng-book")
                    <li class="nav-item has-treeview @if(request()->is("books*") || request()->is("sub-books*") || request()->is("cycle-books*") || request()->is("issued-books*")) menu-open @endif">
                        <a href="#"
                           class="nav-link @if(request()->is("books*") || request()->is("sub-books*") || request()->is("cycle-books*") || request()->is("issued-books*")) active @endif">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                {{__('common.mng_lib')}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{route('books.index')}}"
                                   class="nav-link @if(request()->is("books") || request()->is("books/*")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("common.add_books")}}</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('sub-books.index')}}"
                                   class="nav-link @if(request()->is("sub-books") || request()->is("sub-books/*")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("common.mng_books")}}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('cycle-books.index')}}"
                                   class="nav-link @if(request()->is("cycle-books") || request()->is("cycle-books/*")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("common.issue_books")}}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('indexReceiveBooks')}}"
                                   class="nav-link @if(request()->is("issued-books") || request()->is("issued-books/*")) active @endif">
                                    <i class="fab fa-gg-circle nav-icon"></i>
                                    <p>{{__("common.issued_books")}}</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcan

                @can("mng-slide")
                    <li class="nav-item">
                        <a href="{{route('slider-mng.index')}}"
                           class="nav-link @if(request()->is("slider-mng*")) active @endif">
                            <i class="nav-icon far fa-image"></i>
                            <p>
                                {{__("common.mng_slides")}}
                            </p>
                        </a>
                    </li>
                @endcan

                @can("mng-subscriber")
                    <li class="nav-item">
                        <a href="{{route('subscriber-mng.index')}}"
                           class="nav-link @if(request()->is("subscriber-mng*")) active @endif">
                            <i class="nav-icon fas fa-paw"></i>
                            <p>
                                {{__("common.mng_subscriber")}}
                            </p>
                        </a>
                    </li>
                @endcan
                @can("mng-notice")
                    <li class="nav-item">
                        <a href="{{route('notice-mng.index')}}"
                           class="nav-link @if(request()->is("notice-mng*")) active @endif">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>
                                {{__("common.mng_notice")}}
                            </p>
                        </a>
                    </li>
                @endcan
                @can("mng-user")
                    <li class="nav-item">
                        <a href="{{route('user-mng.index')}}"
                           class="nav-link @if(request()->is("user-mng*")) active @endif">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>
                                {{__("common.mng_user")}}
                            </p>
                        </a>
                    </li>
                @endcan

                @can("mng-role-permission")
                    <li class="nav-item">
                        <a href="{{route('role-perm-mng.index')}}"
                           class="nav-link @if(request()->is("role-perm-mng*")) active @endif">
                            <i class="nav-icon fas fa-traffic-light"></i>
                            <p>
                                {{__("common.mng_permission")}}
                            </p>
                        </a>
                    </li>
                @endcan

                @can("mng-enquiry")
                    <li class="nav-item">
                        <a href="{{route('enquiry-mng.index')}}"
                           class="nav-link @if(request()->is("enquiry-mng*")) active @endif">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>
                                {{__("common.mng_enquiry")}}
                            </p>
                        </a>
                    </li>
                @endcan
                @can("mng-slider")
                    <li class="nav-item">
                        <a href="{{route('slider-mng.index')}}"
                           class="nav-link @if(request()->is("slider-mng*")) active @endif">
                            <i class="nav-icon far fa-image"></i>
                            <p>
                                {{__("common.mng_slides")}}
                            </p>
                        </a>
                    </li>
                @endcan
                @can("mng-report")
                    <li class="nav-item">
                        <a href="{{route('reports.index')}}"
                           class="nav-link @if(request()->is("reports*")) active @endif">
                            <i class="nav-icon fas fa-print"></i>
                            <p>
                                {{__("commonv2.mng_Report")}}
                            </p>
                        </a>
                    </li>
                @endcan
                @can("mng-setting")
                    <li class="nav-item">
                        <a href="{{route('setting.index')}}"
                           class="nav-link @if(request()->is("setting*")) active @endif">
                            <i class="nav-icon fas fa-cog"></i>

                            <p>
                                {{__("common.mng_setting")}}
                            </p>
                        </a>
                    </li>
                @endcan
                @can("mng-translation")
                    <li class="nav-item">
                        <a href="{{route('lang-mng.index')}}"
                           class="nav-link @if(request()->is("lang-mng*")) active @endif">

                            <i class="nav-icon fas fa-language"></i>
                            <p>
                                {{__("common.lng_translation")}}
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
