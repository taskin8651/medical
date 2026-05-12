@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 px-4 py-10">
    <div class="mx-auto w-full max-w-5xl">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-900">{{ trans('panel.site_title') }}</h1>
            <p class="mt-2 text-sm text-gray-600">Wholesale buyer registration</p>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50 px-8 py-5">
                <h2 class="text-lg font-semibold text-gray-900">Create Buyer Account</h2>
                <p class="mt-1 text-sm text-gray-500">Add your business and license details for wholesale product purchases.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="px-8 py-8">
                @csrf

                <div class="mb-8">
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">Account Details</h3>
                    <div class="grid gap-5 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Mobile Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">Business & KYC</h3>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Business / Firm Name <span class="text-red-500">*</span></label>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('business_name') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('business_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Business Type <span class="text-red-500">*</span></label>
                            <select name="business_type" required
                                    class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('business_type') ? 'border-red-500' : 'border-gray-300' }}">
                                <option value="">Select business type</option>
                                @foreach(['Retail Pharmacy', 'Hospital / Clinic', 'Distributor', 'Wholesaler', 'Medical Store', 'Other'] as $type)
                                    <option value="{{ $type }}" {{ old('business_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('business_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">GST Number</label>
                            <input type="text" name="gst_no" value="{{ old('gst_no') }}" placeholder="Optional"
                                   class="w-full rounded-md border px-3 py-2 text-sm uppercase focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('gst_no') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('gst_no')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Drug License Number <span class="text-red-500">*</span></label>
                            <input type="text" name="drug_license_no" value="{{ old('drug_license_no') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm uppercase focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('drug_license_no') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('drug_license_no')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">Billing Address</h3>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-sm font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
                            <textarea name="address" rows="3" required
                                      class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('address') ? 'border-red-500' : 'border-gray-300' }}">{{ old('address') }}</textarea>
                            @error('address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                            <input type="text" name="city" value="{{ old('city') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('city') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">State <span class="text-red-500">*</span></label>
                            <input type="text" name="state" value="{{ old('state') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('state') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('state')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Pincode <span class="text-red-500">*</span></label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('pincode') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('pincode')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
                            <input type="text" name="country" value="{{ old('country', 'India') }}" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('country') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="mb-4 text-sm font-bold uppercase tracking-wide text-gray-500">Password</h3>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required
                                   class="w-full rounded-md border px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}">
                            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4 border-t border-gray-100 pt-6 md:flex-row md:items-center md:justify-between">
                    <p class="text-sm text-gray-500">Account will be created with pending approval for wholesale verification.</p>
                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="rounded-md border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Login</a>
                        <button type="submit" class="rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
