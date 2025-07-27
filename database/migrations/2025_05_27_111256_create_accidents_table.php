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
            $table->increments('id');
            $table->integer('centerid')->nullable();
            $table->integer('roomid')->nullable();
            $table->string('person_name', 80)->nullable();
            $table->string('person_role', 60)->nullable();
            $table->date('date')->nullable();
            $table->string('time', 8)->nullable();
            $table->binary('person_sign')->nullable();
            $table->integer('childid')->nullable();
            $table->string('child_name', 80)->nullable();
            $table->date('child_dob')->nullable();
            $table->string('child_age', 20)->nullable();
            $table->enum('child_gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('incident_date')->nullable();
            $table->string('incident_time', 8)->nullable();
            $table->string('incident_location', 80)->nullable();
            $table->string('witness_name', 80)->nullable();
            $table->binary('witness_sign')->nullable();
            $table->dateTime('witness_date')->nullable();
            $table->binary('injury_image')->nullable();
            $table->text('gen_actyvt')->nullable();
            $table->text('cause')->nullable();
            $table->text('illness_symptoms')->nullable();
            $table->text('missing_unaccounted')->nullable();
            $table->text('taken_removed')->nullable();
            $table->text('action_taken')->nullable();
            $table->enum('emrg_serv_attend', ['Yes', 'No'])->nullable();
            $table->enum('med_attention', ['Yes', 'No'])->nullable();
            $table->text('med_attention_details')->nullable();
            $table->string('prevention_step_1', 200)->nullable();
            $table->string('prevention_step_2', 200)->nullable();
            $table->string('prevention_step_3', 200)->nullable();
            $table->string('parent1_name', 80)->nullable();
            $table->string('contact1_method', 80)->nullable();
            $table->date('contact1_date')->nullable();
            $table->string('contact1_time', 8)->nullable();
            $table->enum('contact1_made', ['Yes', 'No'])->nullable();
            $table->enum('contact1_msg', ['Yes', 'No'])->nullable();
            $table->string('parent2_name', 80)->nullable();
            $table->string('contact2_method', 80)->nullable();
            $table->date('contact2_date')->nullable();
            $table->string('contact2_time', 8)->nullable();
            $table->enum('contact2_made', ['Yes', 'No'])->nullable();
            $table->enum('contact2_msg', ['Yes', 'No'])->nullable();
            $table->string('responsible_person_name', 80)->nullable();
            $table->binary('responsible_person_sign')->nullable();
            $table->date('rp_internal_notif_date')->nullable();
            $table->string('rp_internal_notif_time', 8)->nullable();
            $table->string('nominated_supervisor_name', 80)->nullable();
            $table->binary('nominated_supervisor_sign')->nullable();
            $table->date('nominated_supervisor_date')->nullable();
            $table->string('nominated_supervisor_time', 8)->nullable();
            $table->string('ext_notif_other_agency', 80)->nullable();
            $table->date('enor_date')->nullable();
            $table->string('enor_time', 8)->nullable();
            $table->string('ext_notif_regulatory_auth', 80)->nullable();
            $table->date('enra_date')->nullable();
            $table->string('enra_time', 8)->nullable();
            $table->string('ack_parent_name', 80)->nullable();
            $table->date('ack_date')->nullable();
            $table->string('ack_time', 8)->nullable();
            $table->text('add_notes')->nullable();
            $table->integer('added_by')->nullable();
            $table->date('added_at')->nullable();
            $table->timestamps();

        });

    }
    public function down(): void
    {
        Schema::dropIfExists('accidents');
    }
};
