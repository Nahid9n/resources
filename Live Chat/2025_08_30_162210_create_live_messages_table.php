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
        Schema::create('live_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_session_id');
            $table->enum('sender_type', ['guest','admin']);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('message');
            $table->boolean('seen_by_guest')->default(false);
            $table->boolean('seen_by_admin')->default(false);
            $table->timestamps();
            $table->foreign('chat_session_id')
                ->references('id')->on('chat_sessions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_messages');
    }
};
