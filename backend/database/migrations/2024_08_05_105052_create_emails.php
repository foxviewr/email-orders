<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', static function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('order_uuid');
            $table->string('sender')->index();
            $table->string('recipient')->index();
            $table->string('subject');
            $table->text('body');
            $table->string('messageId')->unique()->index();
            $table->string('inReplyTo')->nullable()->index();
            $table->string('from')->nullable()->index();
            $table->string('to')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
