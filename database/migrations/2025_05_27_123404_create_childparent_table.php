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
        Schema::create('childparent', function (Blueprint $table) {
              $table->increments('id'); // Primary key
            $table->integer('childid');
            $table->integer('parentid');
            $table->enum('relation', ['Mother', 'Father', 'Brother', 'Sister', 'Relative'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childparent');
    }
};
