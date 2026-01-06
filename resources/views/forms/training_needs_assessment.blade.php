<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HESLB Training Needs Assessment – {{ config('advancedforms.appname', config('app.name')) }}</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Tahoma, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #eef2f7;
            padding: 1.5rem 0.75rem;
            font-size: 14px;
        }
        .sheet {
            max-width: 980px;
            width: 100%;
            margin: 0 auto 1.5rem;
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.15);
            padding: 1.75rem 1.75rem 2.25rem;
        }
        .sheet + .sheet {
            margin-top: 1.5rem;
        }
        h1, h2, h3 {
            font-weight: 700;
        }
        .title-main {
            text-align: center;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            text-transform: uppercase;
        }
        .subtitle {
            text-align: center;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            color: #475569;
        }
        .section-heading {
            background: #0f75bc;
            color: #ffffff;
            padding: 0.45rem 0.75rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
        }
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
        }
        .field label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 0.15rem;
            font-weight: 600;
        }
        .field input[type="text"],
        .field input[type="number"],
        .field input[type="date"],
        .field textarea,
        .field select {
            width: 100%;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            padding: 0.4rem 0.45rem;
            font-size: 0.8rem;
        }
        .field textarea {
            min-height: 80px;
            resize: vertical;
        }
        .inline-options {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            font-size: 0.9rem;
        }
        .inline-options label {
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.25rem;
            font-size: 0.85rem;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 0.4rem 0.45rem;
            vertical-align: top;
        }
        th {
            background: #e2e8f0;
            font-weight: 600;
        }
        .intro-block {
            border-radius: 10px;
            border: 2px solid #0f75bc;
            padding: 1.1rem 1.25rem;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
            font-size: 14px;
            line-height: 1.5;
            color: #111827;
        }
        .intro-block p + p {
            margin-top: 0.7rem;
        }
        .note {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 0.4rem;
        }
        .rating-table th {
            text-align: center;
        }
        .rating-table td:first-child {
            font-weight: 500;
        }
        .rating-table input {
            display: block;
            margin: 0 auto;
        }
        .footer-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.25rem;
        }
        .btn-primary {
            background: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 0.55rem 1.4rem;
            font-size: 0.85rem;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #020617;
        }
        .signature-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        .small-label {
            font-size: 0.85rem;
            margin-bottom: 0.15rem;
        }
        @media (max-width: 768px) {
            body {
                padding: 0.75rem 0.5rem 1.5rem;
            }
            .sheet {
                padding: 1.1rem 1rem 1.6rem;
                box-shadow: 0 4px 15px rgba(15, 23, 42, 0.18);
            }
            .title-main {
                font-size: 1rem;
            }
            .subtitle {
                font-size: 0.8rem;
            }
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: 1fr;
            }
            .signature-row {
                grid-template-columns: 1fr;
            }
            table {
                font-size: 0.75rem;
            }
            th, td {
                padding: 0.3rem 0.35rem;
            }
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('training.form.part-a') }}">
        @csrf

        <div class="sheet">
            <div style="text-align:center; margin-bottom:0.75rem;">
                <img src="{{ asset('images/static_files/imageformontop.png') }}"
                     alt="HESLB Training Needs Assessment Header"
                     style="max-width:100%; height:auto;">
            </div>
            <div class="title-main">Higher Education Students' Loans Board</div>
            <div class="subtitle">
                Training Needs Assessment for Higher Education Students' Loans Board Employees 
            </div>

            <div class="intro-block">
            <p>Dear colleague,</p>
                <p>
                    HESLB is currently undertaking a process to improve its Training Needs Assessment (TNA) and staff training programme in order to align with the Treasury Registrar's directives issued in September 2025.
                </p>

                <p>
                    To ensure that, this exercise is conducted objectively and effectively, Management kindly requests all staff to set aside a few minutes to complete the attached Training Needs Assessment questionnaire/form, which is intended to gather essential information from individual staff members. This exercise is a critical component in the preparation of Personal Development Plans (PDPs) and in supporting staff performance in both current and future roles.
                </p>

                <p>
                    The information collected through this process will assist Management in identifying staff training needs and in incorporating them into the institutional training programme to ensure that the programme adequately addresses identified requirements.
                </p>

                <p>
                    Staff are requested to complete the questionnaire honestly, constructively, and thoughtfully, with the understanding that the purpose of this assessment is to enable the Board to gain a better understanding of staff training needs and priorities.
                </p>

                <p>
                    The questionnaire is divided into two (2) parts as follows:
                </p>

                <ul style="margin-left: 1.5rem; margin-bottom: 1rem;">
                    <li>Part A: To be completed by the staff member (subordinate)</li>
                    <li>Part B: To be completed by the immediate supervisor</li>
                </ul>

                <p>
                    All duly completed forms should be submitted no later than 15th January 2026.
                </p>

                <p>
                    Management appreciates your cooperation and commitment to continuous professional development.
                </p>

                <p><strong>FINANCE AND ADMINISTRATION DIRECTORATE</strong></p>
            </div>

            <!-- 1.0 Personal Information -->
            <div class="section-heading">1.0 Personal Information</div>

            <div class="grid-4">
                <div class="field">
                    <label>Gender</label>
                    <div class="inline-options">
                        <label>
                            <input type="radio" name="gender" value="female">
                            Female
                        </label>
                        <label>
                            <input type="radio" name="gender" value="male">
                            Male
                        </label>
                    </div>
                </div>
                <div class="field">
                    <label for="age">Age</label>
                    <input id="age" type="number" name="age" min="18" max="100">
                </div>
                <div class="field">
                    <label for="first_name">First name</label>
                    <input id="first_name" type="text" name="first_name">
                </div>
                <div class="field">
                    <label for="middle_name">Middle name</label>
                    <input id="middle_name" type="text" name="middle_name">
                </div>
            </div>

            <div class="grid-3" style="margin-top:0.75rem;">
                <div class="field">
                    <label for="surname">Surname</label>
                    <input id="surname" type="text" name="surname">
                </div>
                <div class="field">
                    <label for="job_title">Job title / Position</label>
                    <input id="job_title" type="text" name="job_title">
                </div>
                <div class="field">
                    <label for="work_station">Work station</label>
                    <input id="work_station" type="text" name="work_station">
                </div>
            </div>

            <div class="field" style="margin-top:0.75rem;">
                <label for="immediate_supervisor_name">Name of the immediate supervisor and title</label>
                <input id="immediate_supervisor_name" type="text" name="immediate_supervisor_name">
            </div>

            <!-- 2.0 Academic and Professional Qualification -->
            <div class="section-heading">2.0 Academic and Professional Qualification</div>
            <p class="note">Tick whichever is appropriate and indicate award and institution where applicable.</p>

            <table>
                <thead>
                    <tr>
                        <th style="width: 45%;">Qualification</th>
                        <th>Award and Institution</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $qualifications = [
                            'phd' => 'Doctor of Philosophy (PhD)',
                            'masters' => 'Master degree',
                            'bachelor' => 'Bachelor degree',
                            'postgraduate_diploma' => 'Postgraduate Diploma',
                            'certificate' => 'Certificate',
                            'professional' => 'Professional qualification',
                            'other' => 'Other (specify)',
                        ];
                    @endphp
                    @foreach ($qualifications as $key => $label)
                        <tr>
                            <td>
                                <label>
                                    <input type="checkbox" name="qualifications[{{ $key }}][selected]" value="1">
                                    {{ $label }}
                                </label>
                            </td>
                            <td>
                                <input type="text" name="qualifications[{{ $key }}][award]" placeholder="Award / Institution">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 3.0 Other training attended for the past three years -->
            <div class="section-heading">3.0 Other Training Attended for the Past Three Years</div>
            <p class="note">Start with the most recent ones.</p>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">S/No</th>
                        <th>Name of the seminar / course</th>
                        <th style="width: 25%;">Date attended</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 5; $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                <input type="text" name="past_trainings[{{ $i }}][name]">
                            </td>
                            <td>
                                <input type="date" name="past_trainings[{{ $i }}][date_attended]">
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Second sheet: competencies and training -->
        <div class="sheet">
            <div class="section-heading">4.0 Competencies</div>
            <p class="note">
                Please go through the competencies below and tick the most appropriate ones which you think
                are relevant and required for you to perform your duties and responsibilities effectively.
            </p>

            @php
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
            @endphp

            @foreach ($competencyGroups as $groupKey => $group)
                <div class="field" style="margin-bottom:0.8rem;">
                    <label class="small-label">{{ $group['label'] }}</label>
                    <div class="inline-options">
                        @foreach ($group['items'] as $itemKey => $itemLabel)
                            <label>
                                <input type="checkbox" name="competencies[{{ $groupKey }}][]" value="{{ $itemKey }}">
                                {{ $itemLabel }}
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- 5.0 Training / Courses to be attended -->
            <div class="section-heading">5.0 Training / Courses to be Attended</div>
            <p class="note">
                Kindly list any three (3) training / courses in line with the competencies for your role/responsibilities
                which you think will enable you to improve your performance at HESLB.
            </p>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">S/No</th>
                        <th>Training / Course</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 3; $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>
                                <input type="text" name="desired_trainings[{{ $i }}]">
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <!-- 6.0 Training method -->
            <div class="section-heading">6.0 Training Method</div>
            <p class="note">
                Please rate the method of training you feel would be most effective to achieve your training goals.
            </p>

            <table class="rating-table">
                <thead>
                    <tr>
                        <th style="width: 35%;">Method</th>
                        <th>Not Very Effective</th>
                        <th>Somewhat Effective</th>
                        <th>Very Effective</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $methods = [
                            'classroom' => 'Classroom training',
                            'video' => 'Video',
                            'online' => 'Online',
                            'workshop' => 'Workshop',
                            'computer_based' => 'Computer based training',
                            'web_conferencing' => 'Web conferencing',
                            'shadowing' => 'Shadowing',
                            'exchange' => 'Exchange programme',
                            'attachments' => 'Attachments',
                            'other' => 'Other (specify)',
                        ];
                    @endphp
                    @foreach ($methods as $key => $label)
                        <tr>
                            <td>
                                {{ $label }}
                                @if ($key === 'other')
                                    <br>
                                    <input type="text" name="training_methods[other_label]" placeholder="Please specify" style="width:100%;margin-top:0.25rem;">
                                @endif
                            </td>
                            @foreach (['not_very', 'somewhat', 'very'] as $rating)
                                <td>
                                    <input type="radio"
                                           name="training_methods[{{ $key }}]"
                                           value="{{ $rating }}">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 7.0 Other comments -->
            <div class="section-heading">7.0 Other Comments</div>
            <p class="note">
                Please provide any additional comments you would like to be added regarding this assessment.
            </p>

            <div class="field">
                <textarea name="other_comments"></textarea>
            </div>

            <div class="signature-row">
                <div class="field">
                    <label class="small-label" for="signature_name">Signature (name)</label>
                    <input id="signature_name" type="text" name="signature_name">
                </div>
                <div class="field">
                    <label class="small-label" for="signature_date">Date</label>
                    <input id="signature_date" type="date" name="signature_date">
                </div>
            </div>
        </div>

        <!-- Supervisor Email Section -->
        <div class="sheet" style="background: #f0f9ff; border: 2px solid #2563eb;">
            <div class="section-heading" style="color: #1e40af;">Part B: Supervisor Section</div>
            <p class="note" style="color: #1e40af; font-weight: 500;">
                To complete this assessment, please enter your supervisor's email address. They will receive a link to complete Part B.
            </p>
            
            <div class="field">
                <label for="supervisor_email" style="font-weight: 600; color: #1e40af;">Supervisor Email Address *</label>
                <input 
                    id="supervisor_email" 
                    type="email" 
                    name="supervisor_email" 
                    required
                    placeholder="supervisor@example.com"
                    style="width: 100%; padding: 0.75rem; border: 2px solid #2563eb; border-radius: 6px; font-size: 0.95rem;"
                >
                <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.5rem;">
                    Your supervisor will receive an email with a secure link to view Part A and complete Part B.
                </p>
            </div>

            <div class="footer-actions">
                <button type="submit" class="btn-primary" style="background: #2563eb; padding: 0.875rem 2rem; font-size: 1rem;">
                    Submit Part A & Send to Supervisor
                </button>
            </div>
        </div>
    </form>

    <script>
        // Ensure CSRF token is always fresh
        (function() {
            // Update CSRF token before form submission
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Refresh the CSRF token right before submission
                    const tokenInput = form.querySelector('input[name="_token"]');
                    if (tokenInput) {
                        // Get fresh token from meta tag or regenerate
                        const metaToken = document.querySelector('meta[name="csrf-token"]');
                        if (metaToken) {
                            tokenInput.value = metaToken.getAttribute('content');
                        }
                    }
                });
            }

            // If form is in an iframe, ensure cookies are accessible
            if (window.self !== window.top) {
                // We're in an iframe - ensure token is synced
                console.log('Form loaded in iframe - CSRF token initialized');
            }
        })();
    </script>
</body>
</html>



