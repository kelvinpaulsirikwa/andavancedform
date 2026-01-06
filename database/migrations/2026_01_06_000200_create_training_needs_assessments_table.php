<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_needs_assessments', function (Blueprint $table) {
            $table->id();

            // Optional link to authenticated user who filled the form
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // 1.0 Personal information
            $table->string('gender', 10)->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('job_title')->nullable();
            $table->string('work_station')->nullable();
            $table->string('immediate_supervisor_name')->nullable();

            // 2.0 Academic and professional qualification
            $table->json('qualifications')->nullable(); // store ticked qualifications + award/institution

            // 3.0 Other training attended for the past three years
            $table->json('past_trainings')->nullable(); // array of {name, date_attended}

            // 4.0 Competencies (all checkboxes)
            $table->json('competencies')->nullable(); // array/list of selected competency keys

            // 5.0 Training / courses to be attended
            $table->json('desired_trainings')->nullable(); // up to three items

            // 6.0 Training method ratings (per method: Not very / Somewhat / Very effective)
            $table->json('training_methods')->nullable();

            // 7.0 Other comments + signature/date
            $table->text('other_comments')->nullable();
            $table->string('signature_name')->nullable();
            $table->date('signature_date')->nullable();

            // Part B – supervisor section
            $table->text('supervisor_performance_comment')->nullable();
            $table->json('supervisor_training_suggestions')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_signature')->nullable();
            $table->date('supervisor_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_needs_assessments');
    }
};


