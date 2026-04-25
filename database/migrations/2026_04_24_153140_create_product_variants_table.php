<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
 
            // --- Variant Identity ---
            $table->string('name');                           // e.g. "500mg Strip of 10", "100ml Bottle"
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();            // Barcode / UPC for scanning
 
            // --- Medical / Batch Info ---
            $table->string('strength')->nullable();           // Override parent if different
            $table->string('pack_size')->nullable();          // Override parent if different
            $table->string('pack_type')->nullable();
            $table->string('batch_number')->nullable();       // Current batch in stock
            $table->date('expiry_date')->nullable();          // Expiry of current batch
            $table->string('manufacturer_batch_no')->nullable();
 
            // --- Pricing (per variant) ---
            $table->decimal('mrp', 10, 2)->nullable();        // MRP printed on pack
            $table->decimal('ptr', 10, 2)->nullable();        // Price to Retailer
            $table->decimal('pts', 10, 2)->nullable();        // Price to Stockist
            $table->decimal('price', 10, 2);                  // Your selling price
            $table->decimal('gst_rate', 5, 2)->nullable();    // Override parent GST if needed
 
            // --- Stock ---
            $table->integer('stock')->default(0);
            $table->integer('low_stock_alert')->default(10); // Alert when stock reaches this
 
            // --- Status ---
            $table->boolean('is_active')->default(true);
 
            $table->timestamps();
            $table->softDeletes();
 
            $table->index(['product_id', 'is_active']);
            $table->index('expiry_date');
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
