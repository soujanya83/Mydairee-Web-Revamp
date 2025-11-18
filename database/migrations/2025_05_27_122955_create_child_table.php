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
        Schema::create('child', function (Blueprint $table) {
             $table->increments('id'); // Primary key
            $table->string('name', 50);
            $table->string('lastname', 60);
            $table->date('dob');
            $table->date('startDate');
            $table->integer('room');
            $table->text('imageUrl');
            $table->enum('gender', ['Female', 'Male', 'Others']);
            $table->enum('status', ['Enrolled', 'Active']);
            $table->string('daysAttending', 255);
            $table->integer('createdBy');
            $table->timestamp('createdAt')->useCurrent()->useCurrentOnUpdate(); // MySQL 5.6.5+ only

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child');
    }
};
