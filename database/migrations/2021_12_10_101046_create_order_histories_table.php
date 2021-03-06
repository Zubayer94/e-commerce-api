<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->double('product_price', 8, 2)->nullable();
            $table->string('status', 100)->nullable();
            $table->unsignedSmallInteger('unit')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained("users")->nullOnDelete();
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
        Schema::dropIfExists('order_histories');
    }
}
