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
    public function up()
    {
        Schema::create('chat_room_details', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('chat_room_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('target_user_id')->constrained('users');
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
        Schema::dropIfExists('chat_room_details');
    }
};
