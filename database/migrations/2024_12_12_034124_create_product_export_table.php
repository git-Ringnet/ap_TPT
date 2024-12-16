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
        Schema::create('product_export', function (Blueprint $table) {
            $table->id();
            $table->integer('export_id');
            $table->integer('product_id');
            $table->integer('quantity')->default(1);
            $table->integer('sn_id');
            $table->integer('warranty');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_export');
    }
};
