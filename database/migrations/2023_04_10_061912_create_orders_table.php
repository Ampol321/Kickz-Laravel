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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('shipment_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('payment_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('credit_card')->nullable();
            $table->string('shipping_address');
            $table->date('date_ordered');
            $table->date('date_shipped')->nullable();
            $table->timestamps();
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
