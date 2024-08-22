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

        Schema::create('orders', static function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('number')->unique()->index();
            $table->foreignUuid('customer_uuid');
            $table->string('status');
            $table->timestamps();
            $table->foreign('customer_uuid')->references('uuid')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
