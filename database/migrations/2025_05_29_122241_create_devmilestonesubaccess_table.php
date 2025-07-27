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
        Schema::create('devmilestonesubaccess', function (Blueprint $table) {
             $table->increments('id');
            $table->unsignedInteger('idsubactivity');
            $table->unsignedInteger('centerid');
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
        Schema::dropIfExists('devmilestonesubaccess');
    }
};
