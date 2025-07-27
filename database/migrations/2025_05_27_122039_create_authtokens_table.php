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
        Schema::create('authtokens', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->string('userid', 60);
            $table->string('token', 60);
            $table->char('isForgotYN', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authtokens');
    }
};
