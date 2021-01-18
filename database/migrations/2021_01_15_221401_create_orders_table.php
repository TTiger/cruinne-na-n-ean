<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->unsignedBigInteger('vendor_number')->index();
            $table->string('name');
            $table->unsignedBigInteger('number');
            $table->unsignedInteger('subtotal')->nullable()->default(0)->index();
            $table->unsignedInteger('tax')->nullable()->default(0);
            $table->unsignedInteger('total')->nullable()->default(0)->index();
            $table->string('currency')->nullable()->default('USD');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
