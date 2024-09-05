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
        Schema::create('product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->float('price');
            $table->integer('subcategory_id')->unsigned()->nullable(); 
            $table->integer('brand_id')->unsigned()->nullable(); 
            $table->date('release_date');
            $table->string('image')->nullable();

            $table->foreign('subcategory_id')
                ->references('id')
                ->on('subcategory');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
