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
    Schema::create('students', function (Blueprint $table) {
        $table->id();

        $table->string('registration_number')->unique()->nullable();
        $table->string('name');
        $table->date('registration_date')->nullable();
        $table->string('program')->nullable();
        $table->string('program_type')->nullable();
        $table->string('gender')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->string('status')->nullable();
        $table->integer('age')->nullable();
        $table->string('school')->nullable();
        $table->string('class')->nullable();
        $table->text('address')->nullable();
        $table->string('parent_phone')->nullable();
        $table->string('child_phone')->nullable();
        $table->string('parent_email')->nullable();
        $table->string('parent_instagram')->nullable();
        $table->string('schedule_type')->nullable();
        $table->string('program_category')->nullable();
        $table->string('family_status')->nullable();

        // tambahan dari model kamu
        $table->integer('tagihan')->default(0);
        $table->date('jatuh_tempo')->nullable();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
