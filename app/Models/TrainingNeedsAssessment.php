<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class TrainingNeedsAssessment extends Model
{
    use HasFactory;

    public function readByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    protected $fillable = [
        'user_id',
        'read_by',
        'read_at',
        'gender',
        'age',
        'first_name',
        'middle_name',
        'surname',
        'job_title',
        'work_station',
        'immediate_supervisor_name',
        'supervisor_email',
        'supervisor_token',
        'part_a_submitted',
        'part_b_submitted',
        'part_a_data',
        'qualifications',
        'past_trainings',
        'competencies',
        'desired_trainings',
        'training_methods',
        'other_comments',
        'signature_name',
        'signature_date',
        'supervisor_performance_comment',
        'supervisor_training_suggestions',
        'supervisor_name',
        'supervisor_signature',
        'supervisor_date',
    ];

    protected $casts = [
        'qualifications' => 'array',
        'past_trainings' => 'array',
        'competencies' => 'array',
        'desired_trainings' => 'array',
        'training_methods' => 'array',
        'supervisor_training_suggestions' => 'array',
        'part_a_data' => 'array',
        'part_a_submitted' => 'boolean',
        'part_b_submitted' => 'boolean',
        'signature_date' => 'date',
        'supervisor_date' => 'date',
        'read_at' => 'datetime',
    ];
}


