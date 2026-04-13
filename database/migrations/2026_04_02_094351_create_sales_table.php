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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_account_id');
            $table->foreignId('payment_account_id')->constrained('accounts');
            $table->foreignId('transaction_id');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('received_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2);
            $table->date('date');
            $table->timestamp('reversed_at')->nullable();
            // $table->foreignId('customer_account_id_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
