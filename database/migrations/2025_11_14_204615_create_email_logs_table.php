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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('parent_email');
            $table->string('parent_name')->nullable();
            $table->unsignedBigInteger('sent_by')->nullable(); 
            $table->string('subject');
            $table->text('message');
            $table->timestamp('sent_at');
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('sent_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
