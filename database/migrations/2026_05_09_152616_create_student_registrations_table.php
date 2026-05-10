<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_registrations', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('gender')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->string('school')->nullable();
            $table->string('class')->nullable();

            $table->string('program')->nullable();

            $table->string('parent_phone')->nullable();

            $table->text('address')->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};