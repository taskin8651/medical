@extends('custom.master')

@section('title', 'Contact Us')

@section('content')
@php
    $setting = $frontendSetting ?? null;
    $email = $setting->email ?? 'info@example.com';
    $phone = $setting->phone ?? '+2 123 654 7898';
    $address = $setting->address ?? '25/B Milford Road, New York';
@endphp

<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg"><i class="fas fa-briefcase-medical"></i></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Contact Us</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home') }}"><i class="fas fa-house"></i> Home</a></li>
                <li class="active">Contact Us</li>
            </ul>
        </div>
    </div>
</div>

<div class="contact-area py-100">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                Please check the form and try again.
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="contact-content">
                    <div class="contact-info">
                        <div class="contact-info-icon">
                            <i class="fas fa-location-dot"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5>Office Address</h5>
                            <p>{{ $address }}</p>
                        </div>
                    </div>

                    <div class="contact-info">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5>Call Us</h5>
                            <p><a href="tel:{{ preg_replace('/\s+/', '', $phone) }}">{{ $phone }}</a></p>
                        </div>
                    </div>

                    <div class="contact-info">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-info-content">
                            <h5>Email Us</h5>
                            <p><a href="mailto:{{ $email }}">{{ $email }}</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="contact-form">
                    <div class="contact-form-header">
                        <h2>Get In Touch</h2>
                        <p>Send your question, product inquiry, or support request. We will get back to you soon.</p>
                    </div>

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Your Name" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Your Email" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Your Phone">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Subject">
                                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <textarea name="message" cols="30" rows="6" class="form-control @error('message') is-invalid @enderror" placeholder="Write Your Message" required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="theme-btn">
                            Send Message <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if (!empty($setting?->google_map))
            <div class="contact-map mt-50">
                {!! $setting->google_map !!}
            </div>
        @endif
    </div>
</div>
@endsection
