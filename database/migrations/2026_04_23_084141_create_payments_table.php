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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')->constrained()->onDelete('cascade');

        $table->string('receipt_number')->nullable();
        $table->date('payment_date')->nullable();
        $table->string('program')->nullable();
        $table->string('level')->nullable();
        $table->string('payment_group')->nullable();
        $table->string('payment_type')->nullable();
        $table->string('paid_for_month')->nullable();

        $table->integer('amount_due')->default(0);
        $table->integer('amount_paid')->default(0);

        $table->string('payment_method')->nullable();
        $table->string('status')->default('pending'); // lunas / pending / gagal
        $table->boolean('paid_flag')->default(false);

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
