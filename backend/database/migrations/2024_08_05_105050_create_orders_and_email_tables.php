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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->string('email')->unique()->index();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('number')->unique()->index();
            $table->string('customer_uuid')->index();
            $table->string('status');
            $table->timestamps();
            $table->foreign('customer_uuid')->references('uuid')->on('customers')->onDelete('cascade');
        });

        Schema::create('emails', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('order_uuid')->index();
            $table->string('sender')->index();
            $table->string('recipient')->index();
            $table->string('subject');
            $table->text('body');
            $table->string('messageId')->unique()->index();
            $table->string('inReplyTo')->nullable()->index();
            $table->string('from')->nullable()->index();
            $table->string('to')->nullable()->index();
            $table->timestamps();
            $table->foreign('order_uuid')->references('uuid')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('emails');
    }
};
