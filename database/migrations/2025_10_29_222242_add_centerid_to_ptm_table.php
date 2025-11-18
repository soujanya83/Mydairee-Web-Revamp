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
        Schema::table('ptm', function (Blueprint $table) {
             $table->unsignedInteger('centerid')->nullable()->after('id');
        $table->foreign('centerid')->references('id')->on('centers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ptm', function (Blueprint $table) {
             $table->dropForeign(['centerid']);
        $table->dropColumn('centerid');
        });
    }
};
