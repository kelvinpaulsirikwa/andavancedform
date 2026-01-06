<?php

namespace App\Http\Controllers;

use App\Models\TrainingNeedsAssessment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuthController extends Controller
{
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard.questions');
        }
        
        return response()->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.questions'))
                ->withHeaders([
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ]);
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->onlyInput('email');
    }

    public function questions()
    {
        $responsesCount = TrainingNeedsAssessment::count();
        
        return view('dashboard', [
            'activeTab' => 'questions',
            'responsesCount' => $responsesCount,
            'responses' => collect(),
        ]);
    }

    public function responses()
    {
        $responsesCount = TrainingNeedsAssessment::count();
        
        // Order by: unread first (read_by is null or not current user), then by latest
        $responses = TrainingNeedsAssessment::with('readByUser')
            ->orderByRaw('CASE 
                WHEN read_by IS NULL THEN 0 
                WHEN read_by != ? THEN 0 
                ELSE 1 
            END', [Auth::id()])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('dashboard', [
            'activeTab' => 'responses',
            'responsesCount' => $responsesCount,
            'responses' => $responses,
        ]);
    }

    public function report()
    {
        $responsesCount = TrainingNeedsAssessment::count();
        $unreadCount = TrainingNeedsAssessment::where(function($query) {
            $query->whereNull('read_by')
                  ->orWhere('read_by', '!=', Auth::id());
        })->count();
        $allResponses = TrainingNeedsAssessment::all();
        
        // Gender statistics (normalize to title case)
        $genderStats = [];
        foreach ($allResponses as $response) {
            if ($response->gender) {
                // Normalize gender to title case (Male, Female)
                $normalizedGender = ucfirst(strtolower(trim($response->gender)));
                $genderStats[$normalizedGender] = ($genderStats[$normalizedGender] ?? 0) + 1;
            }
        }
        // Ensure Male and Female are always present, even if count is 0
        if (!isset($genderStats['Male'])) {
            $genderStats['Male'] = 0;
        }
        if (!isset($genderStats['Female'])) {
            $genderStats['Female'] = 0;
        }
        // Sort to show Male and Female first, then others
        $sortedGenderStats = [];
        if (isset($genderStats['Male'])) {
            $sortedGenderStats['Male'] = $genderStats['Male'];
            unset($genderStats['Male']);
        }
        if (isset($genderStats['Female'])) {
            $sortedGenderStats['Female'] = $genderStats['Female'];
            unset($genderStats['Female']);
        }
        // Add any other gender values
        foreach ($genderStats as $gender => $count) {
            $sortedGenderStats[$gender] = $count;
        }
        $genderStats = $sortedGenderStats;
        
        $totalWithGender = array_sum($genderStats);
        $genderPercentages = [];
        foreach ($genderStats as $gender => $count) {
            $genderPercentages[$gender] = $totalWithGender > 0 ? round(($count / $totalWithGender) * 100, 1) : 0;
        }
        
        // Age ranges (3-year ranges: 22-24, 25-27, 28-30, 31-33, etc.)
        $ageRanges = [];
        foreach ($allResponses as $response) {
            if ($response->age) {
                $age = (int)$response->age;
                // Group ages into 3-year ranges
                // Ages 22-24 should be grouped as 22-24
                // Ages 25+ should be grouped into 3-year ranges: 25-27, 28-30, 31-33, etc.
                if ($age >= 22 && $age <= 24) {
                    $rangeStart = 22;
                } elseif ($age >= 25) {
                    // For ages 25+, calculate proper 3-year ranges
                    $rangeStart = floor(($age - 25) / 3) * 3 + 25;
                } else {
                    // For ages below 22, group into 3-year ranges: 19-21, 16-18, etc.
                    $rangeStart = floor($age / 3) * 3;
                }
                $rangeEnd = $rangeStart + 2;
                $rangeKey = $rangeStart . '-' . $rangeEnd;
                $ageRanges[$rangeKey] = ($ageRanges[$rangeKey] ?? 0) + 1;
            }
        }
        ksort($ageRanges);
        
        // Work station statistics
        $workStationStats = [];
        foreach ($allResponses as $response) {
            if ($response->work_station) {
                $workStationStats[$response->work_station] = ($workStationStats[$response->work_station] ?? 0) + 1;
            }
        }
        arsort($workStationStats);
        
        // Immediate supervisor statistics
        $supervisorStats = [];
        foreach ($allResponses as $response) {
            if ($response->immediate_supervisor_name && $response->job_title) {
                $supervisor = $response->immediate_supervisor_name;
                if (!isset($supervisorStats[$supervisor])) {
                    $supervisorStats[$supervisor] = [
                        'name' => $supervisor,
                        'count' => 0,
                        'titles' => [],
                    ];
                }
                $supervisorStats[$supervisor]['count']++;
                if (!in_array($response->job_title, $supervisorStats[$supervisor]['titles'])) {
                    $supervisorStats[$supervisor]['titles'][] = $response->job_title;
                }
            }
        }
        usort($supervisorStats, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        // Academic qualification statistics
        $qualificationStats = [
            'Certificate' => 0,
            'Diploma' => 0,
            'Bachelor Degree' => 0,
            'Master Degree' => 0,
            'PhD' => 0,
        ];
        foreach ($allResponses as $response) {
            if ($response->qualifications && is_array($response->qualifications)) {
                foreach ($response->qualifications as $qual) {
                    if (isset($qual['type'])) {
                        $type = $qual['type'];
                        if (isset($qualificationStats[$type])) {
                            $qualificationStats[$type]++;
                        }
                    }
                }
            }
        }
        
        // Competencies statistics (Question 4)
        $competencyStats = [];
        $competencyGroupStats = [];
        
        // Define competency groups structure (matching the form)
        $competencyGroups = [
            '4_1_communicates' => [
                'label' => '4.1 Communicates with impact and influence',
                'items' => [
                    'communication' => 'Communication',
                    'diplomacy' => 'Diplomacy',
                    'facilitation' => 'Facilitation',
                    'influence_negotiation' => 'Influence and negotiation',
                    'interviewing' => 'Interviewing',
                    'media_relations' => 'Media relations',
                    'presentation' => 'Presentation',
                    'protocol' => 'Protocol',
                    'report_writing' => 'Report writing',
                    'translation_interpretation' => 'Translation and interpretation',
                ],
            ],
            '4_2_customer_orientation' => [
                'label' => '4.2 Customer Orientation',
                'items' => [
                    'advisory' => 'Advisory',
                    'events_management' => 'Events management',
                    'marketing' => 'Marketing',
                    'organizational_culture' => 'Organizational culture',
                    'relationship_management' => 'Relationship management',
                    'time_management' => 'Time management',
                ],
            ],
            '4_3_standardisation' => [
                'label' => '4.3 Enhances sustainable standardisation',
                'items' => [
                    'best_practices' => 'Best practices frameworks',
                    'international_issues' => 'International issues and trends',
                    'standards_application' => 'Standards application',
                    'tbs_insights' => 'TBS insights',
                ],
            ],
            '4_4_risk_compliance' => [
                'label' => '4.4 Risk, Compliance and Legal Orientation',
                'items' => [
                    'food_safety' => 'Food safety management',
                    'legal_affairs' => 'Legal affairs',
                    'monitoring_evaluation' => 'Monitoring and evaluation',
                    'policies_laws' => 'Policies, laws and regulations',
                    'quality_control' => 'Quality control and assurance',
                    'quality_management' => 'Quality management',
                    'risk_management' => 'Risk management',
                ],
            ],
            '4_5_innovation' => [
                'label' => '4.5 Innovation and Business Improvement',
                'items' => [
                    'analytical_techniques' => 'Analytical techniques',
                    'business_continuity' => 'Business continuity',
                    'creativity_innovation' => 'Creativity and innovation',
                ],
            ],
            '4_6_leadership' => [
                'label' => '4.6 Leadership and Managing Change',
                'items' => [
                    'change_management' => 'Change management',
                    'decision_making' => 'Decision making',
                    'emotional_intelligence' => 'Emotional intelligence',
                    'leadership' => 'Leadership',
                    'motivation' => 'Motivation',
                    'strategic_management' => 'Strategic management',
                ],
            ],
            '4_7_information_mastery' => [
                'label' => '4.7 Information, knowledge and technology mastery',
                'items' => [
                    'computer_application' => 'Computer application',
                    'configuration_management' => 'Configuration management',
                    'data_management' => 'Data management',
                    'forecasting' => 'Forecasting',
                    'graphics_design' => 'Graphics and designing',
                    'ict_networks' => 'ICT networks',
                    'records_management' => 'Records management',
                    'researching' => 'Researching',
                    'technological_trends' => 'Technological trends',
                ],
            ],
            '4_8_people_management' => [
                'label' => '4.8 People Management and Collaboration',
                'items' => [
                    'conflict_management' => 'Conflict management',
                    'counselling' => 'Counselling',
                    'diversity_management' => 'Diversity management',
                    'fraud_management' => 'Fraud management',
                    'mentoring_coaching' => 'Mentoring and coaching',
                    'performance_management' => 'Performance management',
                    'problem_solving' => 'Problem solving',
                    'stress_management' => 'Stress management',
                    'talent_management' => 'Talent management',
                    'teamwork' => 'Teamwork',
                    'welfare_management' => 'Welfare management',
                ],
            ],
        ];
        
        // Initialize group stats
        foreach ($competencyGroups as $groupKey => $group) {
            $competencyGroupStats[$groupKey] = [
                'label' => $group['label'],
                'count' => 0,
                'items' => [],
            ];
            foreach ($group['items'] as $itemKey => $itemLabel) {
                $competencyStats[$itemKey] = [
                    'label' => $itemLabel,
                    'group' => $groupKey,
                    'groupLabel' => $group['label'],
                    'count' => 0,
                ];
                $competencyGroupStats[$groupKey]['items'][$itemKey] = [
                    'label' => $itemLabel,
                    'count' => 0,
                ];
            }
        }
        
        // Count competencies from responses
        foreach ($allResponses as $response) {
            if ($response->competencies && is_array($response->competencies)) {
                foreach ($response->competencies as $groupKey => $items) {
                    if (is_array($items)) {
                        foreach ($items as $itemKey) {
                            if (isset($competencyStats[$itemKey])) {
                                $competencyStats[$itemKey]['count']++;
                                $competencyGroupStats[$groupKey]['count']++;
                                if (isset($competencyGroupStats[$groupKey]['items'][$itemKey])) {
                                    $competencyGroupStats[$groupKey]['items'][$itemKey]['count']++;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Sort competencies by count (descending)
        uasort($competencyStats, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        // Sort group stats by count (descending)
        uasort($competencyGroupStats, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        return view('report', [
            'activeTab' => 'report',
            'responsesCount' => $responsesCount,
            'unreadCount' => $unreadCount,
            'genderPercentages' => $genderPercentages,
            'genderStats' => $genderStats,
            'ageRanges' => $ageRanges,
            'workStationStats' => $workStationStats,
            'supervisorStats' => $supervisorStats,
            'qualificationStats' => $qualificationStats,
            'competencyStats' => $competencyStats,
            'competencyGroupStats' => $competencyGroupStats,
            'competencyGroups' => $competencyGroups,
        ]);
    }

    public function settings()
    {
        $responsesCount = TrainingNeedsAssessment::count();
        
        return view('dashboard', [
            'activeTab' => 'settings',
            'responsesCount' => $responsesCount,
            'responses' => collect(),
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password changed successfully.');
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function showResponse($id)
    {
        $response = TrainingNeedsAssessment::findOrFail($id);
        
        // Mark as read by current user
        if (Auth::check() && (!$response->read_by || $response->read_by !== Auth::id())) {
            $response->read_by = Auth::id();
            $response->read_at = now();
            $response->save();
        }
        
        // Find next unread response
        $nextUnreadResponse = TrainingNeedsAssessment::where('id', '>', $id)
            ->where(function($query) {
                $query->whereNull('read_by')
                      ->orWhere('read_by', '!=', Auth::id());
            })
            ->orderBy('id', 'asc')
            ->first();
        
        // If no unread after current, find first unread overall
        if (!$nextUnreadResponse) {
            $nextUnreadResponse = TrainingNeedsAssessment::where(function($query) {
                $query->whereNull('read_by')
                      ->orWhere('read_by', '!=', Auth::id());
            })
            ->orderBy('id', 'asc')
            ->first();
        }
        
        // Check if all responses are read
        $allRead = TrainingNeedsAssessment::where(function($query) {
            $query->whereNull('read_by')
                  ->orWhere('read_by', '!=', Auth::id());
        })->count() === 0;
        
        // Load the user who read this response
        $response->load('readByUser');
        
        return view('response-details', [
            'response' => $response,
            'nextUnreadResponse' => $nextUnreadResponse,
            'allRead' => $allRead,
        ]);
    }

    public function markAsRead($id)
    {
        $response = TrainingNeedsAssessment::findOrFail($id);
        
        if (Auth::check()) {
            $response->read_by = Auth::id();
            $response->read_at = now();
            $response->save();
        }
        
        return response()->json(['success' => true]);
    }

    public function deleteResponse($id)
    {
        $response = TrainingNeedsAssessment::findOrFail($id);
        $response->delete();
        
        return redirect()->route('dashboard.responses')
            ->with('success', 'Response deleted successfully.');
    }

    public function filterByGender($gender)
    {
        $normalizedGender = ucfirst(strtolower(urldecode($gender)));
        $responses = TrainingNeedsAssessment::where('gender', $normalizedGender)
            ->latest()
            ->paginate(10);
        
        return view('filtervalue.gender', [
            'responses' => $responses,
            'filterValue' => $normalizedGender,
            'filterType' => 'Gender',
        ]);
    }

    public function filterByAge($range)
    {
        $range = urldecode($range);
        list($minAge, $maxAge) = explode('-', $range);
        
        // Ensure we get all ages in the range inclusively
        $responses = TrainingNeedsAssessment::where('age', '>=', (int)$minAge)
            ->where('age', '<=', (int)$maxAge)
            ->latest()
            ->paginate(10);
        
        return view('filtervalue.age', [
            'responses' => $responses,
            'filterValue' => $range,
            'filterType' => 'Age Range',
        ]);
    }

    public function filterByWorkstation($workstation)
    {
        $workstation = urldecode($workstation);
        $responses = TrainingNeedsAssessment::where('work_station', $workstation)
            ->latest()
            ->paginate(10);
        
        return view('filtervalue.workstation', [
            'responses' => $responses,
            'filterValue' => $workstation,
            'filterType' => 'Work Station',
        ]);
    }

    public function filterBySupervisor($supervisor)
    {
        $supervisor = urldecode($supervisor);
        $responses = TrainingNeedsAssessment::where('immediate_supervisor_name', $supervisor)
            ->latest()
            ->paginate(10);
        
        return view('filtervalue.supervisor', [
            'responses' => $responses,
            'filterValue' => $supervisor,
            'filterType' => 'Supervisor',
        ]);
    }

    public function filterByQualification($qualification)
    {
        $qualification = urldecode($qualification);
        
        // Get all responses and filter in PHP for JSON array search
        $allResponses = TrainingNeedsAssessment::all();
        $filteredResponses = $allResponses->filter(function($response) use ($qualification) {
            if ($response->qualifications && is_array($response->qualifications)) {
                foreach ($response->qualifications as $qual) {
                    if (isset($qual['type']) && $qual['type'] === $qualification) {
                        return true;
                    }
                }
            }
            return false;
        });
        
        // Convert to paginated collection
        $page = request()->get('page', 1);
        $perPage = 10;
        $items = $filteredResponses->slice(($page - 1) * $perPage, $perPage)->values();
        $total = $filteredResponses->count();
        
        $responses = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('filtervalue.qualification', [
            'responses' => $responses,
            'filterValue' => $qualification,
            'filterType' => 'Qualification',
        ]);
    }

    public function filterByCompetency($competency)
    {
        $competency = urldecode($competency);
        
        // Get all responses and filter in PHP for JSON array search
        $allResponses = TrainingNeedsAssessment::all();
        $filteredResponses = $allResponses->filter(function($response) use ($competency) {
            if ($response->competencies && is_array($response->competencies)) {
                foreach ($response->competencies as $groupKey => $items) {
                    if (is_array($items) && in_array($competency, $items)) {
                        return true;
                    }
                }
            }
            return false;
        });
        
        // Convert to paginated collection
        $page = request()->get('page', 1);
        $perPage = 10;
        $items = $filteredResponses->slice(($page - 1) * $perPage, $perPage)->values();
        $total = $filteredResponses->count();
        
        $responses = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Get competency label
        $competencyLabel = $competency;
        $competencyGroups = [
            '4_1_communicates' => ['communication' => 'Communication', 'diplomacy' => 'Diplomacy', 'facilitation' => 'Facilitation', 'influence_negotiation' => 'Influence and negotiation', 'interviewing' => 'Interviewing', 'media_relations' => 'Media relations', 'presentation' => 'Presentation', 'protocol' => 'Protocol', 'report_writing' => 'Report writing', 'translation_interpretation' => 'Translation and interpretation'],
            '4_2_customer_orientation' => ['advisory' => 'Advisory', 'events_management' => 'Events management', 'marketing' => 'Marketing', 'organizational_culture' => 'Organizational culture', 'relationship_management' => 'Relationship management', 'time_management' => 'Time management'],
            '4_3_standardisation' => ['best_practices' => 'Best practices frameworks', 'international_issues' => 'International issues and trends', 'standards_application' => 'Standards application', 'tbs_insights' => 'TBS insights'],
            '4_4_risk_compliance' => ['food_safety' => 'Food safety management', 'legal_affairs' => 'Legal affairs', 'monitoring_evaluation' => 'Monitoring and evaluation', 'policies_laws' => 'Policies, laws and regulations', 'quality_control' => 'Quality control and assurance', 'quality_management' => 'Quality management', 'risk_management' => 'Risk management'],
            '4_5_innovation' => ['analytical_techniques' => 'Analytical techniques', 'business_continuity' => 'Business continuity', 'creativity_innovation' => 'Creativity and innovation'],
            '4_6_leadership' => ['change_management' => 'Change management', 'decision_making' => 'Decision making', 'emotional_intelligence' => 'Emotional intelligence', 'leadership' => 'Leadership', 'motivation' => 'Motivation', 'strategic_management' => 'Strategic management'],
            '4_7_information_mastery' => ['computer_application' => 'Computer application', 'configuration_management' => 'Configuration management', 'data_management' => 'Data management', 'forecasting' => 'Forecasting', 'graphics_design' => 'Graphics and designing', 'ict_networks' => 'ICT networks', 'records_management' => 'Records management', 'researching' => 'Researching', 'technological_trends' => 'Technological trends'],
            '4_8_people_management' => ['conflict_management' => 'Conflict management', 'counselling' => 'Counselling', 'diversity_management' => 'Diversity management', 'fraud_management' => 'Fraud management', 'mentoring_coaching' => 'Mentoring and coaching', 'performance_management' => 'Performance management', 'problem_solving' => 'Problem solving', 'stress_management' => 'Stress management', 'talent_management' => 'Talent management', 'teamwork' => 'Teamwork', 'welfare_management' => 'Welfare management'],
        ];
        
        foreach ($competencyGroups as $group) {
            if (isset($group[$competency])) {
                $competencyLabel = $group[$competency];
                break;
            }
        }
        
        return view('filtervalue.competency', [
            'responses' => $responses,
            'filterValue' => $competencyLabel,
            'filterType' => 'Competency',
        ]);
    }

    public function exportReport(Request $request)
    {
        $allResponses = TrainingNeedsAssessment::all();
        
        // Get selected columns from query parameter
        $selectedColumns = $request->query('columns');
        $columns = $selectedColumns ? explode(',', $selectedColumns) : null;
        
        // Define all available columns with their labels
        $allColumns = [
            'id' => 'ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'surname' => 'Surname',
            'gender' => 'Gender',
            'age' => 'Age',
            'job_title' => 'Job Title',
            'work_station' => 'Work Station',
            'immediate_supervisor_name' => 'Immediate Supervisor',
            'qualifications' => 'Qualifications',
            'past_trainings' => 'Past Trainings',
            'competencies' => 'Competencies',
            'desired_trainings' => 'Desired Trainings',
            'training_methods' => 'Training Methods',
            'other_comments' => 'Other Comments',
            'signature_name' => 'Signature Name',
            'signature_date' => 'Signature Date',
            'supervisor_performance_comment' => 'Supervisor Performance Comment',
            'supervisor_training_suggestions' => 'Supervisor Training Suggestions',
            'supervisor_name' => 'Supervisor Name',
            'supervisor_signature' => 'Supervisor Signature',
            'supervisor_date' => 'Supervisor Date',
            'created_at' => 'Created At',
        ];
        
        // If columns are specified, filter to only selected ones
        if ($columns) {
            $selectedColumnsMap = [];
            foreach ($columns as $col) {
                if (isset($allColumns[$col])) {
                    $selectedColumnsMap[$col] = $allColumns[$col];
                }
            }
            $allColumns = $selectedColumnsMap;
        }
        
        $filename = 'training_needs_assessment_report_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($allResponses, $allColumns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers - only selected columns
            fputcsv($file, array_values($allColumns));
            
            // Data rows
            foreach ($allResponses as $response) {
                // Prepare data for each column
                $rowData = [];
                
                foreach (array_keys($allColumns) as $colKey) {
                    switch ($colKey) {
                        case 'id':
                            $rowData[] = $response->id;
                            break;
                        case 'first_name':
                            $rowData[] = $response->first_name ?? '';
                            break;
                        case 'middle_name':
                            $rowData[] = $response->middle_name ?? '';
                            break;
                        case 'surname':
                            $rowData[] = $response->surname ?? '';
                            break;
                        case 'gender':
                            $rowData[] = $response->gender ?? '';
                            break;
                        case 'age':
                            $rowData[] = $response->age ?? '';
                            break;
                        case 'job_title':
                            $rowData[] = $response->job_title ?? '';
                            break;
                        case 'work_station':
                            $rowData[] = $response->work_station ?? '';
                            break;
                        case 'immediate_supervisor_name':
                            $rowData[] = $response->immediate_supervisor_name ?? '';
                            break;
                        case 'qualifications':
                            $qualifications = '';
                            if ($response->qualifications && is_array($response->qualifications)) {
                                $quals = [];
                                foreach ($response->qualifications as $qual) {
                                    if (isset($qual['type'])) {
                                        $quals[] = $qual['type'] . ' - ' . ($qual['award'] ?? '') . ' (' . ($qual['institution'] ?? '') . ')';
                                    }
                                }
                                $qualifications = implode('; ', $quals);
                            }
                            $rowData[] = $qualifications;
                            break;
                        case 'past_trainings':
                            $pastTrainings = '';
                            if ($response->past_trainings && is_array($response->past_trainings)) {
                                $trainings = [];
                                foreach ($response->past_trainings as $training) {
                                    if (isset($training['name'])) {
                                        $trainings[] = $training['name'] . ' (' . ($training['date_attended'] ?? '') . ')';
                                    }
                                }
                                $pastTrainings = implode('; ', $trainings);
                            }
                            $rowData[] = $pastTrainings;
                            break;
                        case 'competencies':
                            $competencies = '';
                            if ($response->competencies && is_array($response->competencies)) {
                                $comps = [];
                                foreach ($response->competencies as $groupKey => $items) {
                                    if (is_array($items)) {
                                        $comps = array_merge($comps, $items);
                                    }
                                }
                                $competencies = implode('; ', $comps);
                            }
                            $rowData[] = $competencies;
                            break;
                        case 'desired_trainings':
                            $desiredTrainings = '';
                            if ($response->desired_trainings && is_array($response->desired_trainings)) {
                                $desiredTrainings = implode('; ', $response->desired_trainings);
                            }
                            $rowData[] = $desiredTrainings;
                            break;
                        case 'training_methods':
                            $trainingMethods = '';
                            if ($response->training_methods && is_array($response->training_methods)) {
                                $methods = [];
                                foreach ($response->training_methods as $method => $rating) {
                                    $methods[] = $method . ': ' . $rating;
                                }
                                $trainingMethods = implode('; ', $methods);
                            }
                            $rowData[] = $trainingMethods;
                            break;
                        case 'other_comments':
                            $rowData[] = $response->other_comments ?? '';
                            break;
                        case 'signature_name':
                            $rowData[] = $response->signature_name ?? '';
                            break;
                        case 'signature_date':
                            $rowData[] = $response->signature_date ? $response->signature_date->format('Y-m-d') : '';
                            break;
                        case 'supervisor_performance_comment':
                            $rowData[] = $response->supervisor_performance_comment ?? '';
                            break;
                        case 'supervisor_training_suggestions':
                            $supervisorSuggestions = '';
                            if ($response->supervisor_training_suggestions && is_array($response->supervisor_training_suggestions)) {
                                $supervisorSuggestions = implode('; ', $response->supervisor_training_suggestions);
                            }
                            $rowData[] = $supervisorSuggestions;
                            break;
                        case 'supervisor_name':
                            $rowData[] = $response->supervisor_name ?? '';
                            break;
                        case 'supervisor_signature':
                            $rowData[] = $response->supervisor_signature ?? '';
                            break;
                        case 'supervisor_date':
                            $rowData[] = $response->supervisor_date ? $response->supervisor_date->format('Y-m-d') : '';
                            break;
                        case 'created_at':
                            $rowData[] = $response->created_at ? $response->created_at->format('Y-m-d H:i:s') : '';
                            break;
                        default:
                            $rowData[] = '';
                    }
                }
                
                fputcsv($file, $rowData);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear all cache
        try {
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('route:clear');
        } catch (\Exception $e) {
            // Continue even if cache clearing fails
        }

        return redirect('/')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}


