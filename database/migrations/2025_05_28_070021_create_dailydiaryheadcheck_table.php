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
        Schema::create('dailydiaryheadcheck', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->unsignedInteger('headcount');
            $table->date('diarydate');
            $table->string('time', 30);
            $table->string('signature', 100);
            $table->unsignedInteger('roomid');
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
        Schema::dropIfExists('dailydiaryheadcheck');
    }
};
