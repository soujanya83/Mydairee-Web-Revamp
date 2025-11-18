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
        Schema::create('dailydiarysunscreen', function (Blueprint $table) {
        $table->increments('id'); // Primary key with auto-increment
            $table->unsignedInteger('childid');
            $table->date('diarydate');
            $table->string('startTime', 100);
            $table->string('comments', 100);
            $table->string('createdBy', 100);
            $table->dateTime('createdAt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailydiarysunscreen');
    }
};
