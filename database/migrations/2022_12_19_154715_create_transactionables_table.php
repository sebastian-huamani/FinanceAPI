<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    public function up()
    {
        Schema::create('transactionables', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable');
            $table->foreignId('card_id');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('transactionables');
    }
};
