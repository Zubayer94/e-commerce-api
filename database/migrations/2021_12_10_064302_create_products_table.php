<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sku');
            $table->double('price', 8, 2);
            $table->text('description');
            $table->foreignId('main_image_id')->nullable()->constrained("image_libraries")->nullOnDelete();
            $table->longText('image');
            $table->unsignedSmallInteger('qty');
            $table->foreignId('category_id')->nullable()->constrained("categories");
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
        Schema::dropIfExists('products');
    }
}
