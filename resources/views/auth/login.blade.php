@extends('auth.master')

@section('content')

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{ __('common.sign_in_to_start_session')}}</p>
            @if(\Illuminate\Support\Facades\Session::has("login_form") && \Illuminate\Support\Facades\Session::get("login_form"))
                @include("common.messages")
            @endif
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="input-group mb-3">

                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                           value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="{{ __('E-Mail Address') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                           required autocomplete="current-password" placeholder="{{ __('Password') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-sm btn-dark btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            @includeIf("rico.rico_login_cred")
            <!-- /.social-auth-links -->
            @if (Route::has('password.request'))
                <p class="mb-1">
                    <a href="{{ route('password.request') }}">{{ __('I forgot my password') }}</a>
                </p>
            @endif
            @if (Route::has('register'))
                <p class="mb-0">
                    <a href="{{route('register')}}" class="text-center">Register</a>
                </p>
            @endif
        </div>
        <!-- /.login-card-body -->
    </div>
@endsection
