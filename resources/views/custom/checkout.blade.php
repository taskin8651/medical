@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Shop Checkout</h4>
            <ul class="breadcrumb-menu">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="far fa-home"></i> Home
                    </a>
                </li>
                <li class="active">Shop Checkout</li>
            </ul>
        </div>
    </div>
</div>
<!-- breadcrumb end -->


<!-- shop checkout -->
<div class="shop-checkout py-90">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix these errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.placeOrder') }}" method="POST">
            @csrf

            <div class="shop-checkout-wrap">
                <div class="row">

                    <div class="col-lg-8">
                        <div class="shop-checkout-step">
                            <div class="accordion" id="shopCheckout">

                                <!-- Billing Address -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#checkoutStep1"
                                                aria-expanded="true"
                                                aria-controls="checkoutStep1">
                                            Your Billing Address
                                        </button>
                                    </h2>

                                    <div id="checkoutStep1"
                                         class="accordion-collapse collapse show"
                                         data-bs-parent="#shopCheckout">

                                        <div class="accordion-body">
                                            <div class="shop-checkout-form">
                                                <div class="row">

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>First Name *</label>
                                                            <input type="text"
                                                                   name="billing_first_name"
                                                                   class="form-control"
                                                                   value="{{ old('billing_first_name') }}"
                                                                   placeholder="First Name"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Last Name</label>
                                                            <input type="text"
                                                                   name="billing_last_name"
                                                                   class="form-control"
                                                                   value="{{ old('billing_last_name') }}"
                                                                   placeholder="Last Name">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Email *</label>
                                                            <input type="email"
                                                                   name="billing_email"
                                                                   class="form-control"
                                                                   value="{{ old('billing_email') }}"
                                                                   placeholder="Email Address"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Phone *</label>
                                                            <input type="text"
                                                                   name="billing_phone"
                                                                   class="form-control"
                                                                   value="{{ old('billing_phone') }}"
                                                                   placeholder="Phone Number"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Buyer GST No</label>
                                                            <input type="text"
                                                                   name="buyer_gst_no"
                                                                   class="form-control"
                                                                   value="{{ old('buyer_gst_no') }}"
                                                                   placeholder="GST Number">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Drug License No</label>
                                                            <input type="text"
                                                                   name="buyer_drug_license"
                                                                   class="form-control"
                                                                   value="{{ old('buyer_drug_license') }}"
                                                                   placeholder="Drug License Number">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label>Address Line 1 *</label>
                                                            <input type="text"
                                                                   name="billing_address_1"
                                                                   class="form-control"
                                                                   value="{{ old('billing_address_1') }}"
                                                                   placeholder="Address Line 1"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label>Address Line 2</label>
                                                            <input type="text"
                                                                   name="billing_address_2"
                                                                   class="form-control"
                                                                   value="{{ old('billing_address_2') }}"
                                                                   placeholder="Address Line 2">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Country *</label>
                                                            <select name="billing_country" class="select form-control" required>
                                                                <option value="India" selected>India</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>State *</label>
                                                            <input type="text"
                                                                   name="billing_state"
                                                                   class="form-control"
                                                                   value="{{ old('billing_state') }}"
                                                                   placeholder="State"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>City *</label>
                                                            <input type="text"
                                                                   name="billing_city"
                                                                   class="form-control"
                                                                   value="{{ old('billing_city') }}"
                                                                   placeholder="City"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>Post Code *</label>
                                                            <input type="text"
                                                                   name="billing_postcode"
                                                                   class="form-control"
                                                                   value="{{ old('billing_postcode') }}"
                                                                   placeholder="Post Code"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <button type="button"
                                                                class="theme-btn"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#checkoutStep2">
                                                            Next Step <i class="fas fa-arrow-right"></i>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Shipping Address -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#checkoutStep2"
                                                aria-expanded="false"
                                                aria-controls="checkoutStep2">
                                            Your Shipping Address
                                        </button>
                                    </h2>

                                    <div id="checkoutStep2"
                                         class="accordion-collapse collapse"
                                         data-bs-parent="#shopCheckout">

                                        <div class="accordion-body">
                                            <div class="shop-checkout-form">
                                                <div class="row">

                                                    <div class="col-lg-12">
                                                        <div class="form-check mb-20">
                                                            <input class="form-check-input"
                                                                   type="checkbox"
                                                                   name="same_as_billing"
                                                                   value="1"
                                                                   id="sameAsBilling"
                                                                   checked>

                                                            <label class="form-check-label" for="sameAsBilling">
                                                                My shipping and billing addresses are the same.
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div id="shippingFields" class="row" style="display:none;">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>First Name</label>
                                                                <input type="text"
                                                                       name="shipping_first_name"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_first_name') }}"
                                                                       placeholder="First Name">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Last Name</label>
                                                                <input type="text"
                                                                       name="shipping_last_name"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_last_name') }}"
                                                                       placeholder="Last Name">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input type="email"
                                                                       name="shipping_email"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_email') }}"
                                                                       placeholder="Email Address">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Phone</label>
                                                                <input type="text"
                                                                       name="shipping_phone"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_phone') }}"
                                                                       placeholder="Phone Number">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label>Address Line 1</label>
                                                                <input type="text"
                                                                       name="shipping_address_1"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_address_1') }}"
                                                                       placeholder="Address Line 1">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label>Address Line 2</label>
                                                                <input type="text"
                                                                       name="shipping_address_2"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_address_2') }}"
                                                                       placeholder="Address Line 2">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Country</label>
                                                                <select name="shipping_country" class="select form-control">
                                                                    <option value="India" selected>India</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>State</label>
                                                                <input type="text"
                                                                       name="shipping_state"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_state') }}"
                                                                       placeholder="State">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>City</label>
                                                                <input type="text"
                                                                       name="shipping_city"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_city') }}"
                                                                       placeholder="City">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Post Code</label>
                                                                <input type="text"
                                                                       name="shipping_postcode"
                                                                       class="form-control"
                                                                       value="{{ old('shipping_postcode') }}"
                                                                       placeholder="Post Code">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="shop-shipping-method">
                                                            <h6>Choose Shipping Method</h6>

                                                            <div class="row">
                                                                <div class="col-md-6 col-lg-4">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                               type="radio"
                                                                               checked
                                                                               name="shipping_method"
                                                                               value="standard"
                                                                               id="shipping-standard">

                                                                        <label class="form-check-label" for="shipping-standard">
                                                                            Standard
                                                                            <span>Shipping Cost - Free</span>
                                                                            <span>5-7 Days</span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 col-lg-4">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                               type="radio"
                                                                               name="shipping_method"
                                                                               value="express"
                                                                               id="shipping-express">

                                                                        <label class="form-check-label" for="shipping-express">
                                                                            Express
                                                                            <span>Shipping Cost - ₹150</span>
                                                                            <span>1-2 Days</span>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 col-lg-4">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input"
                                                                               type="radio"
                                                                               name="shipping_method"
                                                                               value="courier"
                                                                               id="shipping-courier">

                                                                        <label class="form-check-label" for="shipping-courier">
                                                                            Courier
                                                                            <span>Shipping Cost - ₹250</span>
                                                                            <span>2-3 Days</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 mt-3">
                                                        <button type="button"
                                                                class="theme-btn theme-btn2"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#checkoutStep1">
                                                            <span class="fas fa-arrow-left"></span> Previous
                                                        </button>

                                                        <button type="button"
                                                                class="theme-btn"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#checkoutStep3">
                                                            Next Step <i class="fas fa-arrow-right"></i>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Payment Info -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#checkoutStep3"
                                                aria-expanded="false"
                                                aria-controls="checkoutStep3">
                                            Your Payment Info
                                        </button>
                                    </h2>

                                    <div id="checkoutStep3"
                                         class="accordion-collapse collapse"
                                         data-bs-parent="#shopCheckout">

                                        <div class="accordion-body">
                                            <div class="shop-checkout-payment">

                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-check mb-20">
                                                            <input class="form-check-input"
                                                                   type="radio"
                                                                   name="payment_method"
                                                                   value="cod"
                                                                   id="payment-cod"
                                                                   checked>

                                                            <label class="form-check-label" for="payment-cod">
                                                                Cash On Delivery
                                                                <span>Pay when your order is delivered.</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="form-check mb-20">
                                                            <input class="form-check-input"
                                                                   type="radio"
                                                                   name="payment_method"
                                                                   value="upi"
                                                                   id="payment-upi">

                                                            <label class="form-check-label" for="payment-upi">
                                                                UPI Payment
                                                                <span>Order will be created as pending payment.</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="form-check mb-20">
                                                            <input class="form-check-input"
                                                                   type="radio"
                                                                   name="payment_method"
                                                                   value="bank_transfer"
                                                                   id="payment-bank">

                                                            <label class="form-check-label" for="payment-bank">
                                                                Bank Transfer
                                                                <span>Order will be created as pending payment.</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label>Your Message For Order</label>
                                                            <textarea name="notes"
                                                                      cols="30"
                                                                      rows="4"
                                                                      class="form-control"
                                                                      placeholder="Your Message">{{ old('notes') }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12">
                                                        <button type="button"
                                                                class="theme-btn theme-btn2"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#checkoutStep2">
                                                            <span class="fas fa-arrow-left"></span> Previous
                                                        </button>

                                                        <button type="submit" class="theme-btn">
                                                            Place Order <i class="fas fa-arrow-right"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- Cart Summary -->
                    <div class="col-lg-4">
                        <div class="shop-cart-summary mt-0">
                            <h5>Cart Summary</h5>

                            <div class="checkout-products mb-4">
                                @foreach($cart as $item)
                                    @php
                                        $price = $item['price'] ?? 0;
                                        $qty = $item['quantity'] ?? 1;
                                        $gst = $item['gst_amount'] ?? 0;
                                        $lineTotal = ($price + $gst) * $qty;
                                        $packLabel = trim(($item['pack_size'] ?? '') . ' ' . ($item['pack_type'] ?? ''));
                                    @endphp

                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <strong>{{ $item['name'] }}</strong>
                                            <small class="d-block">
                                                Qty: {{ $qty }}
                                                @if($packLabel)
                                                    | Pack: {{ $packLabel }}
                                                @endif
                                            </small>

                                            @if(!empty($item['gst_rate']))
                                                <small class="d-block">
                                                    GST: {{ $item['gst_rate'] }}%
                                                </small>
                                            @endif
                                        </div>

                                        <span>₹{{ number_format($lineTotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <ul>
                                <li>
                                    <strong>Sub Total:</strong>
                                    <span>₹{{ number_format($subTotal, 2) }}</span>
                                </li>

                                <li>
                                    <strong>Discount:</strong>
                                    <span>₹{{ number_format($discount, 2) }}</span>
                                </li>

                                <li>
                                    <strong>Shipping:</strong>
                                    <span>
                                        {{ $shipping > 0 ? '₹'.number_format($shipping, 2) : 'Free' }}
                                    </span>
                                </li>

                                <li>
                                    <strong>GST:</strong>
                                    <span>₹{{ number_format($tax, 2) }}</span>
                                </li>

                                <li class="shop-cart-total">
                                    <strong>Total:</strong>
                                    <span>₹{{ number_format($total, 2) }}</span>
                                </li>
                            </ul>

                            <div class="text-end mt-40">
                                <a href="{{ route('cart.index') }}" class="theme-btn theme-btn2">
                                    Back To Cart
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>
</div>
<!-- shop checkout end -->

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sameAsBilling = document.getElementById("sameAsBilling");
    const shippingFields = document.getElementById("shippingFields");

    function toggleShippingFields() {
        if (!sameAsBilling || !shippingFields) return;

        if (sameAsBilling.checked) {
            shippingFields.style.display = "none";
        } else {
            shippingFields.style.display = "flex";
        }
    }

    toggleShippingFields();

    if (sameAsBilling) {
        sameAsBilling.addEventListener("change", toggleShippingFields);
    }
});
</script>

@endsection