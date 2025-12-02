<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('email_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('email_id')->index();
            $table->unsignedBigInteger('child_id')->index();
            $table->timestamps();
            $table->unique(['email_id', 'child_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_child');
    }
};
