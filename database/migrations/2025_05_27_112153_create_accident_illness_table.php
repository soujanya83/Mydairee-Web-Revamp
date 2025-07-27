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
        Schema::create('accident_illness', function (Blueprint $table) {
          $table->increments('id');
            $table->integer('accident_id');
            $table->integer('abrasion')->default(0);
            $table->integer('allergic_reaction')->default(0);
            $table->integer('amputation')->default(0);
            $table->integer('anaphylaxis')->default(0);
            $table->integer('asthma')->default(0);
            $table->integer('bite_wound')->default(0);
            $table->integer('broken_bone')->default(0);
            $table->integer('burn')->default(0);
            $table->integer('choking')->default(0);
            $table->integer('concussion')->default(0);
            $table->integer('crush')->default(0);
            $table->integer('cut')->default(0);
            $table->integer('drowning')->default(0);
            $table->integer('eye_injury')->default(0);
            $table->integer('electric_shock')->default(0);
            $table->integer('infectious_disease')->default(0);
            $table->integer('high_temperature')->default(0);
            $table->integer('ingestion')->default(0);
            $table->integer('internal_injury')->default(0);
            $table->integer('poisoning')->default(0);
            $table->integer('rash')->default(0);
            $table->integer('respiratory')->default(0);
            $table->integer('seizure')->default(0);
            $table->integer('sprain')->default(0);
            $table->integer('stabbing')->default(0);
            $table->integer('tooth')->default(0);
            $table->integer('venomous_bite')->default(0);
            $table->integer('other')->default(0);
            $table->string('remarks', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accident_illness');
    }
};
