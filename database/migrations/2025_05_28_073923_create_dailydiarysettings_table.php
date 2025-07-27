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
        Schema::create('dailydiarysettings', function (Blueprint $table) {
           $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY
            $table->unsignedInteger('centerid')->default(0);
            $table->unsignedInteger('breakfast')->default(0);
            $table->unsignedInteger('morningtea')->default(0);
            $table->unsignedInteger('lunch')->default(0);
            $table->unsignedInteger('sleep')->default(0);
            $table->unsignedInteger('afternoontea')->default(0);
            $table->unsignedInteger('latesnacks')->default(0);
            $table->unsignedInteger('sunscreen')->default(0);
            $table->unsignedInteger('toileting')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailydiarysettings');
    }
};
