<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->unsignedBigInteger('vendor_product_id')->index();
            $table->unsignedBigInteger('vendor_image_id')->nullable()->index();
            $table->string('name');
            $table->unsignedBigInteger('price')->index();
            $table->string('sku')->index();
            $table->unsignedInteger('position')->nullable()->default(0);
            $table->string('barcode')->nullable();
            $table->integer('stock_qty')->nullable()->default(0)->index();
            $table->string('option1')->nullable();
            $table->string('option2')->nullable();
            $table->string('option3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
}
