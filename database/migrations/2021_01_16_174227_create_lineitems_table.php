<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('lineitems', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('vendor_id')->index();
            $table->unsignedBigInteger('vendor_order_id')->index();
            $table->unsignedBigInteger('vendor_variant_id')->nullable()->index();
            $table->unsignedBigInteger('vendor_product_id')->nullable()->index();
            $table->unsignedInteger('price')->nullable()->default(0)->index();
            $table->unsignedInteger('quantity')->nullable()->default(0)->index();
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
        Schema::dropIfExists('lineitems');
    }
}
