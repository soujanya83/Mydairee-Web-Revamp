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
        Schema::create('dailydiarysleepchecklist', function (Blueprint $table) {
                  $table->bigIncrements('id'); // Auto-increment primary key
            $table->unsignedBigInteger('childid');
            $table->unsignedBigInteger('roomid');
            $table->date('diarydate');
            $table->string('time', 255)->nullable();
            $table->string('breathing', 255)->nullable();
            $table->string('body_temperature', 255)->nullable();
            $table->string('notes', 255)->nullable();
            $table->string('createdBy', 100)->nullable();
            // $table->timestamp('created_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailydiarysleepchecklist');
    }
};
