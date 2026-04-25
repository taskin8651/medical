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
       Schema::create('tier_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained()->cascadeOnDelete();
 
            $table->integer('min_qty');                       // Buy from this qty...
            $table->integer('max_qty')->nullable();           // ...up to this qty (null = unlimited)
            $table->decimal('price_per_unit', 10, 2);         // Price at this tier
            $table->decimal('discount_percent', 5, 2)->nullable(); // Or discount % on base price
 
            // e.g. 'retailer', 'stockist', 'hospital', 'all'
            $table->string('customer_type')->default('all');
            $table->boolean('is_active')->default(true);
 
            $table->timestamps();
 
            $table->index(['product_variant_id', 'customer_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tierpricings');
    }
};
