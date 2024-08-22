<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuBesarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buku_besar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id');
            $table->enum('jenis', ['kas', 'penjualan']);
            $table->date('periode');
            $table->integer('debit');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')
                ->on('brands')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buku_besar');
    }
}
