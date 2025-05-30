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
        Schema::create('centers', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->string('centerName', 70);
            $table->string('adressStreet', 100);  // Note: is "adress" a typo?
            $table->string('addressCity', 50);
            $table->string('addressState', 50);
            $table->string('addressZip', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
