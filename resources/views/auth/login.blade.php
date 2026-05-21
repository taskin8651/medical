@extends('custom.master')

@section('title', trans('global.login'))

@push('styles')
    <style>
        .auth-area {
            background: var(--theme-bg-light);
        }

        .auth-shell {
            max-width: 520px;
            margin: 0 auto;
        }

        .auth-alert {
            border: 0;
            border-radius: 12px;
            background: var(--theme-color-light);
            color: var(--color-dark);
        }

        .auth-inline-action {
            color: var(--theme-color);
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="site-breadcrumb">
        <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.html') }})"></div>
        <div class="container">
            <div class="site-breadcrumb-wrap">
                <h4 class="breadcrumb-title">{{ trans('global.login') }}</h4>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="active">{{ trans('global.login') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="auth-area py-100">
        <div class="container">
            <div class="auth-shell">
                <div class="login-form">
                    <div class="login-header">
                        <a href="{{ route('home') }}">
                            <h1>{{ trans('panel.site_title') }}</h1>
                        </a>
                        <h3>Welcome Back</h3>
                        <p>Sign in to manage your wholesale account.</p>
                    </div>

                    @if(session('message'))
                        <div class="alert auth-alert mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">{{ trans('global.login_email') }}</label>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="Enter your email"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ trans('global.login_password') }}</label>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Enter your password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-3 mb-4 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">{{ trans('global.remember_me') }}</label>
                            </div>

                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-pass">
                                    {{ trans('global.forgot_password') }}
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="theme-btn">
                            <i class="fas fa-right-to-bracket"></i> {{ trans('global.login') }}
                        </button>
                    </form>

                    <div class="login-footer">
                        <p>New wholesale buyer? <a href="{{ route('register') }}">Create an account</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
