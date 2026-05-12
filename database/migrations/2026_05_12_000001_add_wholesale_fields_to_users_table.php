<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('business_name')->nullable()->after('phone');
            $table->string('business_type')->nullable()->after('business_name');
            $table->string('gst_no', 20)->nullable()->after('business_type');
            $table->string('drug_license_no')->nullable()->after('gst_no');
            $table->text('address')->nullable()->after('drug_license_no');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 20)->nullable()->after('state');
            $table->string('country')->default('India')->after('pincode');
            $table->string('approval_status')->default('pending')->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'business_name',
                'business_type',
                'gst_no',
                'drug_license_no',
                'address',
                'city',
                'state',
                'pincode',
                'country',
                'approval_status',
            ]);
        });
    }
};
