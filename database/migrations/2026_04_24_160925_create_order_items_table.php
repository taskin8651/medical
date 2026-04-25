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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained()->restrictOnDelete();
 
            // Snapshot at time of order (prices can change later)
            $table->string('product_name');
            $table->string('variant_name');
            $table->string('sku');
            $table->string('hsn_code', 20)->nullable();
 
            // Batch info at time of dispatch
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
 
            $table->integer('qty');
            $table->decimal('mrp', 10, 2)->nullable();        // MRP on pack
            $table->decimal('unit_price', 10, 2);             // Price charged
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('taxable_amount', 10, 2);         // After discount, before GST
            $table->decimal('gst_rate', 5, 2)->default(0);
            $table->decimal('gst_amount', 10, 2)->default(0);
            $table->decimal('cgst', 10, 2)->default(0);
            $table->decimal('sgst', 10, 2)->default(0);
            $table->decimal('igst', 10, 2)->default(0);
            $table->decimal('total', 10, 2);                  // Final line total
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
