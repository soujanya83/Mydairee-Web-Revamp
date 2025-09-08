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
        Schema::create('accidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('centerid')->nullable();
            $table->unsignedBigInteger('roomid')->nullable();
            $table->string('person_name')->nullable();
            $table->string('person_role')->nullable();
            $table->string('service_name')->nullable();
            $table->date('made_record_date')->nullable();
            $table->time('made_record_time')->nullable();

            $table->unsignedBigInteger('childid')->nullable();
            $table->string('child_name')->nullable();
            $table->date('child_dob')->nullable();
            $table->string('child_age')->nullable();
            $table->enum('child_gender', ['Male', 'Female', 'Others'])->nullable();

            $table->date('incident_date')->nullable();
            $table->time('incident_time')->nullable();
            $table->string('incident_location')->nullable();
            $table->string('location_of_incident')->nullable();

            $table->string('witness_name')->nullable();
            $table->date('witness_date')->nullable();
            $table->longText('witness_sign')->nullable();

            $table->longText('details_injury')->nullable();
            $table->longText('circumstances_leading')->nullable();
            $table->longText('circumstances_child_missingd')->nullable();
            $table->longText('circumstances_child_removed')->nullable();
            $table->string('injury_image')->nullable();
            $table->longText('remarks')->nullable();
            $table->longText('action_taken')->nullable();

            $table->enum('emrg_serv_attend', ['Yes', 'No'])->default('No');
            $table->time('emrg_serv_time')->nullable();
            $table->string('emrg_serv_arrived')->nullable();

            $table->enum('med_attention', ['Yes', 'No'])->default('No');
            $table->enum('ack_incident', ['1', '0'])->default('0');
            $table->enum('ack_injury', ['1', '0'])->default('0');
            $table->enum('ack_trauma', ['1', '0'])->default('0');
            $table->enum('ack_illness', ['1', '0'])->default('0');
            $table->longText('med_attention_details')->nullable();
            $table->longText('provideDetails_minimise')->nullable();

            $table->string('parent1_name')->nullable();
            $table->date('carers_date')->nullable();
            $table->time('carers_time')->nullable();

            $table->string('director_educator_coordinator')->nullable();
            $table->date('educator_date')->nullable();
            $table->time('educator_time')->nullable();

            $table->string('other_agency')->nullable();
            $table->date('other_agency_date')->nullable();
            $table->time('other_agency_time')->nullable();

            $table->string('regulatory_authority')->nullable();
            $table->date('regulatory_authority_date')->nullable();
            $table->time('regulatory_authority_time')->nullable();

            $table->string('ack_parent_name')->nullable();
            $table->date('ack_date')->nullable();
            $table->time('ack_time')->nullable();

            $table->longText('final_sign')->nullable();
            $table->longText('add_notes')->nullable();

            $table->unsignedBigInteger('added_by')->nullable();
            $table->timestamp('added_at')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('accidents');
    }
};
