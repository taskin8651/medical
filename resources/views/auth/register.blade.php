@extends('custom.master')

@section('title', trans('global.register'))

@push('styles')
    <style>
        .auth-area {
            background: var(--theme-bg-light);
        }

        .register-shell {
            max-width: 1080px;
            margin: 0 auto;
        }

        .register-form {
            border-radius: 30px;
        }

        .auth-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-info-color);
        }

        .auth-section-title i {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--theme-color-light);
            color: var(--theme-color);
        }

        .auth-section-title h5 {
            font-size: 18px;
        }

        .register-form textarea.form-control {
            min-height: 120px;
        }

        .auth-note {
            margin: 0;
            color: var(--body-text-color);
        }

        .auth-link-btn {
            padding: 12px 24px;
            border: 1px solid var(--theme-color);
            border-radius: 50px;
            color: var(--theme-color);
            font-weight: 600;
        }

        .auth-link-btn:hover {
            background: var(--theme-color);
            color: var(--color-white);
        }

        @media only screen and (max-width: 767px) {
            .register-form {
                border-radius: 20px;
            }

            .auth-actions {
                align-items: stretch !important;
            }

            .auth-actions .theme-btn,
            .auth-actions .auth-link-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="site-breadcrumb">
        <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.html') }})"></div>
        <div class="container">
            <div class="site-breadcrumb-wrap">
                <h4 class="breadcrumb-title">{{ trans('global.register') }}</h4>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="active">{{ trans('global.register') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="auth-area py-100">
        <div class="container">
            <div class="register-shell">
                <div class="login-form register-form">
                    <div class="login-header">
                        <a href="{{ route('home') }}">
                            <h1>{{ trans('panel.site_title') }}</h1>
                        </a>
                        <h3>Create Buyer Account</h3>
                        <p>Register your business for wholesale medical purchases.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-5">
                            <div class="auth-section-title">
                                <i class="fas fa-user"></i>
                                <h5>Account Details</h5>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                               placeholder="Your full name">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                               class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               placeholder="Email address">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="phone">Mobile Number <span class="text-danger">*</span></label>
                                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required
                                               class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                               placeholder="Mobile number">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="auth-section-title">
                                <i class="fas fa-briefcase-medical"></i>
                                <h5>Business & KYC</h5>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="business_name">Business / Firm Name <span class="text-danger">*</span></label>
                                        <input id="business_name" type="text" name="business_name" value="{{ old('business_name') }}" required
                                               class="form-control {{ $errors->has('business_name') ? 'is-invalid' : '' }}"
                                               placeholder="Business name">
                                        @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="business_type">Business Type <span class="text-danger">*</span></label>
                                        <select id="business_type" name="business_type" required
                                                class="form-control {{ $errors->has('business_type') ? 'is-invalid' : '' }}">
                                            <option value="">Select business type</option>
                                            @foreach(['Retail Pharmacy', 'Hospital / Clinic', 'Distributor', 'Wholesaler', 'Medical Store', 'Other'] as $type)
                                                <option value="{{ $type }}" {{ old('business_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                        @error('business_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gst_no">GST Number</label>
                                        <input id="gst_no" type="text" name="gst_no" value="{{ old('gst_no') }}"
                                               class="form-control text-uppercase {{ $errors->has('gst_no') ? 'is-invalid' : '' }}"
                                               placeholder="Optional">
                                        @error('gst_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="drug_license_no">Drug License Number <span class="text-danger">*</span></label>
                                        <input id="drug_license_no" type="text" name="drug_license_no" value="{{ old('drug_license_no') }}" required
                                               class="form-control text-uppercase {{ $errors->has('drug_license_no') ? 'is-invalid' : '' }}"
                                               placeholder="License number">
                                        @error('drug_license_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="auth-section-title">
                                <i class="fas fa-location-dot"></i>
                                <h5>Billing Address</h5>
                            </div>

                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address">Address <span class="text-danger">*</span></label>
                                        <textarea id="address" name="address" rows="3" required
                                                  class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                                  placeholder="Complete billing address">{{ old('address') }}</textarea>
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="city">City <span class="text-danger">*</span></label>
                                        <input id="city" type="text" name="city" value="{{ old('city') }}" required
                                               class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}">
                                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="state">State <span class="text-danger">*</span></label>
                                        <input id="state" type="text" name="state" value="{{ old('state') }}" required
                                               class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}">
                                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="pincode">Pincode <span class="text-danger">*</span></label>
                                        <input id="pincode" type="text" name="pincode" value="{{ old('pincode') }}" required
                                               class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}">
                                        @error('pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label for="country">Country <span class="text-danger">*</span></label>
                                        <input id="country" type="text" name="country" value="{{ old('country', 'India') }}" required
                                               class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}">
                                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="auth-section-title">
                                <i class="fas fa-lock"></i>
                                <h5>Password</h5>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input id="password" type="password" name="password" required
                                               class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                               placeholder="Minimum 8 characters">
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" required
                                               class="form-control"
                                               placeholder="Confirm password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="auth-actions d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 pt-4 border-top">
                            <p class="auth-note">Your account will be pending until wholesale verification is complete.</p>
                            <div class="d-flex flex-column flex-sm-row gap-3">
                                <a href="{{ route('login') }}" class="auth-link-btn">Login</a>
                                <button type="submit" class="theme-btn">
                                    Register <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
