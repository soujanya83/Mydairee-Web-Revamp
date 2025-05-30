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
        Schema::create('announcement', function (Blueprint $table) {
               $table->increments('id'); // Primary key
            $table->string('title', 100);
            $table->text('text');
            $table->date('eventDate');
            $table->enum('status', ['Pending', 'Sent']);
            $table->integer('centerid');
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
        Schema::dropIfExists('announcement');
    }
};
