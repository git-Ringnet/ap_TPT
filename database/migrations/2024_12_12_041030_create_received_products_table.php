<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedProductsTable extends Migration
{
    public function up()
    {
        Schema::create('received_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reception_id')->comment('ID phiếu tiếp nhận');
            $table->unsignedBigInteger('product_id')->comment('ID hàng hóa');
            $table->integer('quantity')->comment('Số lượng');
            $table->json('serial_numbers')->nullable()->comment('Danh sách số serial ở định dạng JSON');
            $table->integer('status')->default(1)->comment('Trạng thái');
            $table->text('note')->nullable()->comment('Ghi chú');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('received_products');
    }
}
