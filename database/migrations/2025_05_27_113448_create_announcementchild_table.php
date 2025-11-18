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
        Schema::create('announcementchild', function (Blueprint $table) {
              $table->increments('id'); // Primary key
            $table->integer('aid');   // Foreign key to announcement.id (optional)
            $table->integer('childid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcementchild');
    }
};
