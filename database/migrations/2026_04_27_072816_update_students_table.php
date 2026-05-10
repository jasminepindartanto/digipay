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
        Schema::table('students', function (Blueprint $table) {
        //$table->date('registration_date')->nullable();
        //$table->date('date_of_birth')->nullable();
        //$table->string('status')->nullable();
        //$table->integer('age')->nullable();
        //$table->string('class')->nullable();
        //$table->text('address')->nullable();
        //$table->string('child_phone')->nullable();
        //$table->string('parent_email')->nullable();
        //$table->string('parent_instagram')->nullable();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
