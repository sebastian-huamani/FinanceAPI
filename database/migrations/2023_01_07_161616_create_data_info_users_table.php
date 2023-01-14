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
        Schema::create('data_info_users', function (Blueprint $table) {
            $table->id();
            $table->decimal('full_credit');
            $table->decimal('aviable_credit');
            $table->decimal('full_debit');
            $table->decimal('aviable_debit');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('data_info_users');
    }
};
