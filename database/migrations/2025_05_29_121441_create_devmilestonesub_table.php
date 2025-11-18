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
        Schema::create('devmilestonesub', function (Blueprint $table) {
              $table->increments('id');
            $table->unsignedInteger('milestoneid');
            $table->text('name');
            $table->text('subject');
            $table->string('imageUrl', 60);
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
        Schema::dropIfExists('devmilestonesub');
    }
};
