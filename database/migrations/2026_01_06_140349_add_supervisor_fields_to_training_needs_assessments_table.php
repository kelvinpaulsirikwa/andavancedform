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
        Schema::table('training_needs_assessments', function (Blueprint $table) {
            $table->string('supervisor_email')->nullable()->after('immediate_supervisor_name');
            $table->string('supervisor_token', 64)->nullable()->unique()->after('supervisor_email');
            $table->boolean('part_a_submitted')->default(false)->after('supervisor_token');
            $table->boolean('part_b_submitted')->default(false)->after('part_a_submitted');
            $table->json('part_a_data')->nullable()->after('part_b_submitted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_needs_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'supervisor_email',
                'supervisor_token',
                'part_a_submitted',
                'part_b_submitted',
                'part_a_data',
            ]);
        });
    }
};
