<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->date('dfrom');
            $table->date('dto');
            $table->foreignId('room_id')->constrained('rooms');
            $table->string('name');
            $table->string('email');
            $table->string('phone_code');
            $table->string('phone_number');
            $table->integer('guests');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('bookings');
    }
};
