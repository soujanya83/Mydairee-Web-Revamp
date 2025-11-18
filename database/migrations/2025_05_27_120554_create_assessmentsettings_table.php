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
        Schema::create('assessmentsettings', function (Blueprint $table) {
            $table->increments('id'); // Primary key
            $table->integer('centerid');
            $table->integer('montessori');
            $table->integer('eylf');
            $table->integer('devmile');
            $table->integer('added_by');
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessmentsettings');
    }
};
