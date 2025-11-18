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
        Schema::create('child_group_member', function (Blueprint $table) {
               $table->id(); // Adds an auto-increment primary key 'id'
            $table->unsignedInteger('c_g_id');
            $table->unsignedInteger('child_id');
            $table->unsignedInteger('group_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_group_member');
    }
};
