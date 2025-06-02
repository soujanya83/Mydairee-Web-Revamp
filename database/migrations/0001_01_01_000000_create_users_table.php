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
        Schema::create('users', function (Blueprint $table) {
          $table->integer('userid')->primary(); // You can change to increments() if you want auto-increment
            $table->string('username', 80)->nullable();
            $table->string('emailid', 100);
            $table->string('password', 100);
            $table->string('contactNo', 10);
            $table->string('name', 20);
            $table->date('dob');
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHERS'])->nullable();
            $table->string('imageUrl', 60);
            $table->enum('userType', ['Superadmin', 'Staff', 'Parent']);
            $table->string('title', 50)->nullable();
            $table->enum('status', ['ACTIVE', 'IN-ACTIVE', 'PENDING'])->default('ACTIVE');
            $table->string('AuthToken', 128);
            $table->string('deviceid', 32);
            $table->string('devicetype', 32);
            $table->string('companyLogo', 70)->nullable();
            $table->integer('theme')->default(1);
            $table->string('image_position', 150);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
