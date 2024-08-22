<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalUmumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_umum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id');
            $table->foreignId('coa_id');
            $table->date('periode')->nullable();
            $table->integer('kredit');
            $table->integer('debit');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')
                ->on('brands')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('coa_id')->references('id')
                ->on('coa')
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
        Schema::dropIfExists('jurnal_umum');
    }
}
