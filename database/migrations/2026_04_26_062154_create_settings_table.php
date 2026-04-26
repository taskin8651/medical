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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();

        // 🟢 General
        $table->string('site_name')->nullable();
        $table->string('site_title')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->text('google_map')->nullable();

        // 🟢 Media
        $table->string('logo')->nullable();
        $table->string('favicon')->nullable();
        $table->string('loader')->nullable();
        $table->string('og_image')->nullable();

        // 🟢 Social
        $table->string('facebook')->nullable();
        $table->string('instagram')->nullable();
        $table->string('twitter')->nullable();
        $table->string('linkedin')->nullable();
        $table->string('youtube')->nullable();
        $table->string('whatsapp')->nullable();

        // 🟢 SEO
        $table->string('meta_title')->nullable();
        $table->text('meta_description')->nullable();
        $table->text('meta_keywords')->nullable();

        // 🟢 Footer
        $table->text('footer_description')->nullable();
        $table->string('footer_copyright')->nullable();

        // 🟢 Popup
        $table->boolean('popup_status')->default(0);
        $table->text('popup_text')->nullable();
        $table->string('popup_image')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
