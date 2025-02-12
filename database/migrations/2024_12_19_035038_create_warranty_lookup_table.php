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
        Schema::create('warranty_lookup', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('sn_id');
            $table->integer('customer_id')->nullable();
            $table->string('name_warranty')->nullable();
            $table->dateTime('export_return_date');
            $table->integer('warranty');
            $table->integer('status')->comment("0: Bảo hành, 1: Dịch vụ, 2: Bảo hành dịch vụ");
            $table->dateTime('warranty_expire_date')->comment("Ngày hết hạn bảo hành")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_lookup');
    }
};
