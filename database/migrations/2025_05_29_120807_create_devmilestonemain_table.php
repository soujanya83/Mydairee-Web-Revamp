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
        Schema::create('devmilestonemain', function (Blueprint $table) {
             $table->increments('id');
            $table->unsignedInteger('ageId');
            $table->string('name', 50);
            $table->unsignedInteger('added_by')->nullable();
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devmilestonemain');
    }
};
