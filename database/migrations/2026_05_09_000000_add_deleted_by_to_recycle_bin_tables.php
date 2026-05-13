<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programplantemplatedetailsadd', function (Blueprint $table) {
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
        });

        Schema::table('observation', function (Blueprint $table) {
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
        });

        Schema::table('reflection', function (Blueprint $table) {
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
        });

        Schema::table('snapshot', function (Blueprint $table) {
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::table('programplantemplatedetailsadd', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
        });

        Schema::table('observation', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
        });

        Schema::table('reflection', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
        });

        Schema::table('snapshot', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
        });
    }
};