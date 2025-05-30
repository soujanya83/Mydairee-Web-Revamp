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
        Schema::create('dailydiarytoileting', function (Blueprint $table) {
             $table->increments('id');
            $table->integer('childid')->unsigned();
            $table->date('diarydate');
            $table->string('startTime', 100);
            $table->string('nappy', 100)->nullable();
            $table->string('potty', 100)->nullable();
            $table->string('toilet', 100)->nullable();
            $table->string('signature', 100);
            $table->string('comments', 100);
            $table->string('status', 255)->nullable();
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
        Schema::dropIfExists('dailydiarytoileting');
    }
};
