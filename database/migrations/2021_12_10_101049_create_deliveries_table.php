<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('UID');
            $table->double('product_price', 8, 2);
            $table->string('status', 100);
            $table->unsignedInteger('unit');
            $table->foreignId('user_id')->nullable()->constrained("users")->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained("products")->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained("orders")->nullOnDelete();

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
        Schema::dropIfExists('deliveries');
    }
}
