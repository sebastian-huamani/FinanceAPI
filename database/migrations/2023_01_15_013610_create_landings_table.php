<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landings', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount')->nullable();
            $table->dateTime('payment_date_lending')->nullable();
            $table->string('debtor')->nullable();
            $table->foreignId('state_id')->constrained();
            $table->string('history_quota')->nullable();    
            $table->integer('is_lending')->nullable();    
            $table->integer('is_fee')->nullable();
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
        Schema::dropIfExists('landings');
    }
};
