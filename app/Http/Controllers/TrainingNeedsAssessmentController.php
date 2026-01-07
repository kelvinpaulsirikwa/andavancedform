<?php

namespace App\Http\Controllers;

use App\Models\TrainingNeedsAssessment;
use App\Mail\SupervisorAssessmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TrainingNeedsAssessmentController extends Controller
{
    /**
     * Show the HESLB Training Needs Assessment form (Part A only).
     */
    public function create()
    {
        // Regenerate session to ensure fresh CSRF token
        request()->session()->regenerateToken();
        
        return response()->view('forms.training_needs_assessment')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Store Part A and send email to supervisor.
     */
    public function storePartA(Request $request)
    {
        // Basic validation
        $data = $request->validate([
            // Personal information - ALL REQUIRED
            'gender' => ['required', 'string', 'in:Male,Female'],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['required', 'string', 'max:100'],
            'surname' => ['required', 'string', 'max:100'],
            'job_title' => ['required', 'string', 'max:150'],
            'work_station' => ['required', 'string', 'max:150'],
            'immediate_supervisor_name' => ['required', 'string', 'max:150'],
            'supervisor_email' => ['required', 'email', 'max:255'],

            // Arrays / JSON fields
            'qualifications' => ['nullable', 'array'],
            'past_trainings' => ['nullable', 'array'],
            'competencies' => ['nullable', 'array'],
            'desired_trainings' => ['nullable', 'array'],
            'training_methods' => ['nullable', 'array'],

            'other_comments' => ['nullable', 'string'],
            'signature_name' => ['required', 'string', 'max:150'],
            'signature_date' => ['required', 'date'],
        ]);

        // Custom validation: At least one qualification must be selected
        $qualifications = $request->input('qualifications', []);
        $hasQualification = false;
        $qualificationErrors = [];

        foreach ($qualifications as $key => $qual) {
            if (isset($qual['selected']) && $qual['selected'] == 1) {
                $hasQualification = true;
                // If qualification is selected, award must be filled
                if (empty($qual['award']) || trim($qual['award']) === '' || trim($qual['award']) === 'Award / Institution') {
                    $qualificationErrors[] = "Award and Institution is required for the selected qualification.";
                }
                
                // If it's an "other" qualification, specify field must be filled
                if ($key === 'other' || str_starts_with($key, 'other_')) {
                    if (empty($qual['specify']) || trim($qual['specify']) === '') {
                        $qualificationErrors[] = "Please specify the qualification name for 'Other' qualification.";
                    }
                }
            }
        }

        if (!$hasQualification) {
            return back()
                ->withInput()
                ->withErrors(['qualifications' => 'Please select at least one qualification.']);
        }

        if (!empty($qualificationErrors)) {
            return back()
                ->withInput()
                ->withErrors(['qualifications' => implode(' ', $qualificationErrors)]);
        }

        // Clean up qualifications: remove unchecked ones and empty entries
        $cleanedQualifications = [];
        foreach ($qualifications as $key => $qual) {
            if (isset($qual['selected']) && $qual['selected'] == 1) {
                if (!empty($qual['award']) && trim($qual['award']) !== '' && trim($qual['award']) !== 'Award / Institution') {
                    $cleanedQual = [
                        'selected' => 1,
                        'award' => trim($qual['award'])
                    ];
                    
                    // Add specify field for "other" qualifications (required)
                    if ($key === 'other' || str_starts_with($key, 'other_')) {
                        if (!empty($qual['specify'])) {
                            $cleanedQual['specify'] = trim($qual['specify']);
                        }
                    }
                    
                    $cleanedQualifications[$key] = $cleanedQual;
                }
            }
        }
        $data['qualifications'] = $cleanedQualifications;

        // Store Part A data
        $partAData = $data;
        unset($partAData['supervisor_email']);

        // Generate unique token
        $token = Str::random(64);
        
        // Ensure token is unique
        while (TrainingNeedsAssessment::where('supervisor_token', $token)->exists()) {
            $token = Str::random(64);
        }

        // Create assessment record
        $assessment = TrainingNeedsAssessment::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'supervisor_email' => $data['supervisor_email'],
            'supervisor_token' => $token,
            'part_a_submitted' => true,
            'part_b_submitted' => false,
            'part_a_data' => $partAData,
            // Store Part A data in main fields for easy access
            'gender' => $data['gender'] ?? null,
            'age' => $data['age'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'middle_name' => $data['middle_name'] ?? null,
            'surname' => $data['surname'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'work_station' => $data['work_station'] ?? null,
            'immediate_supervisor_name' => $data['immediate_supervisor_name'] ?? null,
            'qualifications' => $data['qualifications'] ?? null,
            'past_trainings' => $data['past_trainings'] ?? null,
            'competencies' => $data['competencies'] ?? null,
            'desired_trainings' => $data['desired_trainings'] ?? null,
            'training_methods' => $data['training_methods'] ?? null,
            'other_comments' => $data['other_comments'] ?? null,
            'signature_name' => $data['signature_name'] ?? null,
            'signature_date' => $data['signature_date'] ?? null,
        ]);

        // Send email to supervisor
        try {
            Mail::to($data['supervisor_email'])->send(new SupervisorAssessmentNotification($assessment, $token));
        } catch (\Exception $e) {
            // Log error but don't fail the submission
            \Log::error('Failed to send supervisor email: ' . $e->getMessage());
        }

        $supervisorLink = route('training.supervisor.form', $token);
        
        return redirect('/')
            ->with('status', 'Part A has been submitted successfully. Your supervisor will receive an email with a link to complete Part B.')
            ->with('supervisor_link', $supervisorLink);
    }

    /**
     * Show supervisor form (Part A read-only + Part B editable).
     */
    public function showSupervisorForm($token)
    {
        $assessment = TrainingNeedsAssessment::where('supervisor_token', $token)
            ->where('part_a_submitted', true)
            ->firstOrFail();

        if ($assessment->part_b_submitted) {
            return redirect('/')
                ->with('error', 'This assessment has already been completed.');
        }

        return view('forms.supervisor_assessment', [
            'assessment' => $assessment,
            'token' => $token,
        ]);
    }

    /**
     * Store Part B submitted by supervisor.
     */
    public function storePartB(Request $request, $token)
    {
        $assessment = TrainingNeedsAssessment::where('supervisor_token', $token)
            ->where('part_a_submitted', true)
            ->where('part_b_submitted', false)
            ->firstOrFail();

        $data = $request->validate([
            'supervisor_performance_comment' => ['nullable', 'string'],
            'supervisor_training_suggestions' => ['nullable', 'array'],
            'supervisor_name' => ['nullable', 'string', 'max:150'],
            'supervisor_signature' => ['nullable', 'string', 'max:150'],
            'supervisor_date' => ['nullable', 'date'],
        ]);

        $assessment->update([
            'supervisor_performance_comment' => $data['supervisor_performance_comment'] ?? null,
            'supervisor_training_suggestions' => $data['supervisor_training_suggestions'] ?? null,
            'supervisor_name' => $data['supervisor_name'] ?? null,
            'supervisor_signature' => $data['supervisor_signature'] ?? null,
            'supervisor_date' => $data['supervisor_date'] ?? null,
            'part_b_submitted' => true,
        ]);

        return redirect('/')
            ->with('status', 'Part B has been submitted successfully. The assessment is now complete.');
    }

    /**
     * Show form to retrieve supervisor link with security questions.
     */
    public function showRetrieveLink()
    {
        return view('forms.retrieve_supervisor_link');
    }

    /**
     * Validate security answers and return supervisor link if 50%+ match.
     */
    public function retrieveLink(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'surname' => ['required', 'string', 'max:100'],
            'job_title' => ['required', 'string', 'max:150'],
            'work_station' => ['required', 'string', 'max:150'],
            'immediate_supervisor_name' => ['required', 'string', 'max:150'],
        ]);

        // Find assessment by matching answers (check both completed and pending)
        $allAssessments = TrainingNeedsAssessment::where('part_a_submitted', true)->get();

        $matchedAssessment = null;
        $maxMatches = 0;
        $totalQuestions = 5; // We're asking 5 questions

        foreach ($allAssessments as $assessment) {
            $matches = 0;
            
            // Check each field (case-insensitive, trimmed)
            if (strtolower(trim($assessment->first_name ?? '')) === strtolower(trim($request->first_name))) {
                $matches++;
            }
            if (strtolower(trim($assessment->surname ?? '')) === strtolower(trim($request->surname))) {
                $matches++;
            }
            if (strtolower(trim($assessment->job_title ?? '')) === strtolower(trim($request->job_title))) {
                $matches++;
            }
            if (strtolower(trim($assessment->work_station ?? '')) === strtolower(trim($request->work_station))) {
                $matches++;
            }
            if (strtolower(trim($assessment->immediate_supervisor_name ?? '')) === strtolower(trim($request->immediate_supervisor_name))) {
                $matches++;
            }

            // Calculate percentage
            $matchPercentage = ($matches / $totalQuestions) * 100;

            // If 50% or more match and this is the best match so far
            if ($matchPercentage >= 50 && $matches > $maxMatches) {
                $maxMatches = $matches;
                $matchedAssessment = $assessment;
            }
        }

        if ($matchedAssessment && $maxMatches > 0) {
            $matchPercentage = ($maxMatches / $totalQuestions) * 100;
            
            // Check if supervisor has already filled Part B
            if ($matchedAssessment->part_b_submitted) {
                return back()
                    ->withInput()
                    ->with('error', 'Supervisor already fill it. The assessment has already been completed by your supervisor.');
            }
            
            // Supervisor hasn't filled yet, return the link
            $supervisorLink = route('training.supervisor.form', $matchedAssessment->supervisor_token);
            
            return view('forms.retrieve_supervisor_link', [
                'success' => true,
                'supervisor_link' => $supervisorLink,
                'match_percentage' => round($matchPercentage, 1),
            ]);
        }

        return back()
            ->withInput()
            ->with('error', 'Cannot get other link. The information provided does not match our records. Please verify your details and try again.');
    }
}


