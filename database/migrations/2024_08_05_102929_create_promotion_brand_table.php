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
        Schema::create('promotion_brand', function (Blueprint $table) {
            $table->id();
            $table->integer('promotion_id')->unsigned();
            $table->integer('brand_id')->unsigned();

            $table->foreign('promotion_id')
                ->references('id')
                ->on('promotion')
                ->onDelete('cascade');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brand')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_brand');
    }
};
