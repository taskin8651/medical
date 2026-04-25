<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();         // e.g. ORD-2025-00001
 
            // --- Buyer Info ---
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('buyer_gst_no', 20)->nullable();   // Buyer's GST number (B2B)
            $table->string('buyer_drug_license')->nullable();  // Drug license number
            $table->text('billing_address');
            $table->text('shipping_address')->nullable();      // null = same as billing
 
            // --- Financials ---
            $table->decimal('subtotal', 12, 2);               // Before GST & discount
            $table->decimal('total_gst', 10, 2)->default(0);  // Total GST amount
            $table->decimal('cgst', 10, 2)->default(0);       // Central GST
            $table->decimal('sgst', 10, 2)->default(0);       // State GST
            $table->decimal('igst', 10, 2)->default(0);       // Inter-state GST
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('total', 12, 2);                  // Final payable amount
 
            // --- Payment ---
            // status: pending, partial, paid, overdue, refunded
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();     // bank_transfer, cheque, cod, online
            // terms: immediate, net_15, net_30, net_45
            $table->string('payment_terms')->default('immediate');
            $table->date('due_date')->nullable();              // For credit orders
            $table->decimal('amount_paid', 12, 2)->default(0);
 
            // --- Invoice ---
            $table->string('invoice_number')->nullable()->unique();
            $table->date('invoice_date')->nullable();
 
            // --- Order Status ---
            // status: draft, pending, confirmed, processing, dispatched, delivered, cancelled
            $table->string('status')->default('pending');
            $table->string('dispatch_mode')->nullable();      // courier, own_vehicle, pickup
            $table->string('tracking_number')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
 
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();       // Staff-only notes
 
            $table->timestamps();
            $table->softDeletes();
 
            $table->index(['user_id', 'status']);
            $table->index('payment_status');
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
