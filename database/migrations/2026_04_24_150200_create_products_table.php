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
   Schema::create('products', function (Blueprint $table) {
            $table->id();
 
            // --- Relationships ---
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
 
            // --- Basic Info ---
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();      // Parent SKU (optional)
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
 
            // --- Medical-Specific Fields ---
            $table->string('generic_name')->nullable();       // e.g. Paracetamol
            $table->text('composition')->nullable();          // e.g. Paracetamol 500mg + Caffeine 30mg
            $table->string('hsn_code', 20)->nullable();       // HSN code for GST (e.g. 30049099)
            $table->string('drug_schedule')->nullable();      // Schedule H / H1 / X / OTC / G
            $table->boolean('requires_prescription')->default(false);
            $table->string('form')->nullable();               // tablet, capsule, syrup, injection, cream, etc.
            $table->string('strength')->nullable();           // e.g. 500mg, 10ml
            $table->text('storage_conditions')->nullable();   // e.g. Store below 25°C, away from light
            $table->text('side_effects')->nullable();
            $table->text('contraindications')->nullable();
            $table->string('shelf_life')->nullable();         // e.g. 24 months
 
            // --- Pricing ---
            $table->decimal('mrp', 10, 2)->nullable();        // Maximum Retail Price
            $table->decimal('ptr', 10, 2)->nullable();        // Price to Retailer
            $table->decimal('pts', 10, 2)->nullable();        // Price to Stockist
            $table->decimal('price', 10, 2);                  // Your base selling price
            $table->decimal('sale_price', 10, 2)->nullable(); // Discounted price if any
            $table->decimal('gst_rate', 5, 2)->default(12);   // GST % (0, 5, 12, 18)
 
            // --- Pack Info ---
            $table->string('pack_size')->nullable();          // e.g. "1x10", "30ml", "100 gm"
            $table->string('pack_type')->nullable();          // strip, bottle, box, vial, tube, etc.
            $table->integer('units_per_pack')->default(1);    // Units in 1 pack/box
 
            // --- Wholesale Settings ---
            $table->integer('min_qty')->default(1);           // Min qty per order
            $table->integer('max_qty')->nullable();           // Max qty per order
            $table->integer('stock')->default(0);
 
            // --- Status ---
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
 
            $table->timestamps();
            $table->softDeletes();
 
            // Useful indexes
            $table->index(['category_id', 'is_active']);
            $table->index('generic_name');
            $table->index('drug_schedule');
            $table->index('hsn_code');
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
