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
        Schema::create('child_group', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->string('name', 64);
            $table->text('description');
            $table->integer('userid');
            $table->integer('centerid');
            $table->timestamp('date_added')->useCurrent()->useCurrentOnUpdate(); // default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamp('date_modified')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_group');
    }
};
