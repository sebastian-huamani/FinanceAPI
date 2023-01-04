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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('bottom_line');
            $table->decimal('amount');
            $table->string('name_banck');
            $table->date('card_expiration_date');
            $table->foreignId('type_card_id')->constrained();
            $table->foreignId('date_card_id')->nullable()->unique()->constrained();
            $table->foreignId('state_id')->constrained();
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
        Schema::dropIfExists('cards');
    }
};
