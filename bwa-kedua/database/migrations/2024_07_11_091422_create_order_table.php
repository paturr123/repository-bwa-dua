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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->nullable()->index('fk_order_buyer_to_users');
            $table->foreignId('freelancer_id')->nullable()->index('fk_order_freelancer_to_users');
            $table->foreignId('service_id')->nullable()->index('fk_order_to_service');
            $table->longText('file')->nullable();
            $table->longText('note')->nullable();
            $table->date('expired')->nullable();
            $table->foreignId('order_status_id')->nullable()->index('fk_order_to_order_status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
