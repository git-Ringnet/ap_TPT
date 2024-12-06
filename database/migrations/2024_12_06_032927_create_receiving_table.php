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
        Schema::create('receiving', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->default(null)->comment('Hãng tiếp nhận');
            $table->unsignedInteger('form_type')->default(null)->comment('Loại phiếu');
            $table->string('form_code')->default(null)->comment('Mã phiếu');
            $table->unsignedBigInteger('customer_id')->default(null)->comment('Khách hàng');
            $table->text('address')->default(null)->comment('Địa chỉ');
            $table->dateTime('date_created')->comment('Ngày lập phiếu');
            $table->string('contact_person')->nullable()->comment('Người liên hệ');
            $table->text('notes')->nullable()->comment('Ghi chú');
            $table->unsignedBigInteger('user_id')->comment('Người lập phiếu');
            $table->string('phone')->nullable()->comment('Số điện thoại');
            $table->dateTime('closed_at')->nullable()->comment('Ngày đóng phiếu');
            $table->unsignedInteger('status')->default(0)->comment('Tình trạng');
            $table->unsignedInteger('state')->default(0)->comment('Trạng thái');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiving');
    }
};
