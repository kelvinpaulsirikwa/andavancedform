<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HESLB Training Needs Assessment – {{ config('advancedforms.appname', config('app.name')) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/static_files/heslblogo.png') }}">

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
        .field-error {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            font-family: Tahoma, sans-serif;
        }
        .error-highlight {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }
        .btn-add-other {
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
            transition: background 0.2s;
        }
        .btn-add-other:hover {
            background: #1d4ed8;
        }
        .btn-remove-other {
            background: #dc2626;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
            margin-top: 0.25rem;
            transition: background 0.2s;
        }
        .btn-remove-other:hover {
            background: #b91c1c;
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
            /* Make past trainings table responsive */
            #past-trainings-table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            #past-trainings-table thead {
                display: none;
            }
            #past-trainings-table tbody {
                display: block;
            }
            #past-trainings-table tr {
                display: block;
                border: 1px solid #cbd5e1;
                border-radius: 6px;
                margin-bottom: 0.75rem;
                padding: 0.75rem;
                background: #f8fafc;
            }
            #past-trainings-table td {
                display: block;
                border: none;
                padding: 0.5rem 0;
                text-align: left;
            }
            #past-trainings-table td:before {
                content: attr(data-label);
                font-weight: 600;
                display: block;
                margin-bottom: 0.25rem;
                color: #475569;
                font-size: 0.8rem;
            }
            #past-trainings-table input[type="text"],
            #past-trainings-table input[type="date"] {
                width: 100%;
                font-size: 0.85rem;
                padding: 0.5rem;
            }
            #past-trainings-table .btn-remove-training {
                width: 100%;
                padding: 0.5rem;
                font-size: 0.85rem;
                margin-top: 0.25rem;
            }
            /* Make training methods table responsive */
            #training-methods-table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            #training-methods-table thead {
                display: none;
            }
            #training-methods-table tbody {
                display: block;
            }
            #training-methods-table tr {
                display: block;
                border: 1px solid #cbd5e1;
                border-radius: 6px;
                margin-bottom: 0.75rem;
                padding: 0.75rem;
                background: #f8fafc;
            }
            #training-methods-table td {
                display: block;
                border: none;
                padding: 0.5rem 0;
                text-align: left;
            }
            #training-methods-table td:first-child {
                font-weight: 600;
                margin-bottom: 0.5rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e2e8f0;
            }
            #training-methods-table td:before {
                content: attr(data-label);
                font-weight: 600;
                display: block;
                margin-bottom: 0.25rem;
                color: #475569;
                font-size: 0.8rem;
            }
            #training-methods-table td:first-child:before {
                content: "Method: ";
            }
            #training-methods-table input[type="radio"] {
                margin-right: 0.5rem;
            }
            #training-methods-table .training-method-other-input {
                width: 100%;
                font-size: 0.85rem;
                padding: 0.5rem;
            }
            #training-methods-table .btn-remove-training {
                width: 100%;
                padding: 0.5rem;
                font-size: 0.85rem;
                margin-top: 0.5rem;
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
                    <label>Gender *</label>
                    <div class="inline-options">
                        <label>
                            <input type="radio" name="gender" value="Female" required>
                            Female
                        </label>
                        <label>
                            <input type="radio" name="gender" value="Male" required>
                            Male
                        </label>
                    </div>
                    @error('gender')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="age">Age *</label>
                    <input id="age" type="number" name="age" min="18" max="100" required>
                    @error('age')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="first_name">First name *</label>
                    <input id="first_name" type="text" name="first_name" required>
                    @error('first_name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="middle_name">Middle name *</label>
                    <input id="middle_name" type="text" name="middle_name" required>
                    @error('middle_name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid-3" style="margin-top:0.75rem;">
                <div class="field">
                    <label for="surname">Surname *</label>
                    <input id="surname" type="text" name="surname" required>
                    @error('surname')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="job_title">Job title / Position *</label>
                    <input id="job_title" type="text" name="job_title" required>
                    @error('job_title')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="work_station">Work station *</label>
                    <input id="work_station" type="text" name="work_station" required>
                    @error('work_station')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="field" style="margin-top:0.75rem;">
                <label for="immediate_supervisor_name">Name of the immediate supervisor and title *</label>
                <input id="immediate_supervisor_name" type="text" name="immediate_supervisor_name" required>
                @error('immediate_supervisor_name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2.0 Academic and Professional Qualification -->
            <div class="section-heading">2.0 Academic and Professional Qualification</div>
            <p class="note">Tick whichever is appropriate and indicate award and institution where applicable. * At least one qualification must be selected.</p>
            @error('qualifications')
                <div class="field-error" style="margin-bottom: 0.75rem;">{{ $message }}</div>
            @enderror

            <table>
                <thead>
                    <tr>
                        <th style="width: 45%;">Qualification</th>
                        <th>Award and Institution *</th>
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
                        <tr class="qualification-row" data-qual-key="{{ $key }}">
                            <td>
                                <label>
                                    <input type="checkbox" name="qualifications[{{ $key }}][selected]" value="1" class="qualification-checkbox" data-qual-key="{{ $key }}">
                                    {{ $label }}
                                </label>
                                @if($key === 'other')
                                    <div style="margin-top: 0.5rem;">
                                        <input type="text" name="qualifications[{{ $key }}][specify]" placeholder="Specify qualification" class="qualification-specify" style="width: 100%; padding: 0.4rem 0.45rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8rem;">
                                    </div>
                                @endif
                            </td>
                            <td>
                                <input type="text" name="qualifications[{{ $key }}][award]" placeholder="Award / Institution" class="qualification-award">
                            </td>
                        </tr>
                    @endforeach
                    <!-- Dynamic "Other" rows will be added here -->
                </tbody>
            </table>
            
            <!-- Add Another "Other" Qualification Button -->
            <div id="add-other-btn-container" style="margin-top: 0.75rem; display: none;">
                <button type="button" id="add-other-qualification" class="btn-add-other">
                    + Add Another "Other" Qualification
                </button>
            </div>

            <!-- 3.0 Other training attended for the past three years -->
            <div class="section-heading">3.0 Other Training Attended for the Past Three Years</div>
            <p class="note">Start with the most recent ones.</p>

            <table id="past-trainings-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">S/No</th>
                        <th>Name of the seminar / course</th>
                        <th style="width: 25%;">Date attended</th>
                        <th style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody id="past-trainings-tbody">
                    @for ($i = 1; $i <= 5; $i++)
                        <tr class="past-training-row">
                            <td class="row-number" data-label="S/No">{{ $i }}</td>
                            <td data-label="Name of the seminar / course">
                                <input type="text" name="past_trainings[{{ $i }}][name]" class="training-name-input">
                            </td>
                            <td data-label="Date attended">
                                <input type="date" name="past_trainings[{{ $i }}][date_attended]" class="training-date-input">
                            </td>
                            <td data-label="Action">
                                @if($i > 1)
                                    <button type="button" class="btn-remove-training" onclick="removeTrainingRow(this)">Remove</button>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.75rem;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            
            <!-- Add Another Training Button -->
            <div style="margin-top: 0.75rem;">
                <button type="button" id="add-training-btn" class="btn-add-other">
                    + Add Another Training
                </button>
            </div>
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

            <table id="desired-trainings-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">S/No</th>
                        <th>Training / Course</th>
                        <th style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody id="desired-trainings-tbody">
                    @for ($i = 1; $i <= 3; $i++)
                        <tr class="desired-training-row">
                            <td class="row-number">{{ $i }}</td>
                            <td>
                                <input type="text" name="desired_trainings[{{ $i }}]" class="desired-training-input">
                            </td>
                            <td>
                                @if($i > 1)
                                    <button type="button" class="btn-remove-training" onclick="removeDesiredTrainingRow(this)">Remove</button>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.75rem;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            
            <!-- Add Another Desired Training Button -->
            <div style="margin-top: 0.75rem;">
                <button type="button" id="add-desired-training-btn" class="btn-add-other">
                    + Add Another Training / Course
                </button>
            </div>

            <!-- 6.0 Training method -->
            <div class="section-heading">6.0 Training Method</div>
            <p class="note">
                Please rate the method of training you feel would be most effective to achieve your training goals.
            </p>

            <table class="rating-table" id="training-methods-table">
                <thead>
                    <tr>
                        <th style="width: 35%;">Method</th>
                        <th>Not Very Effective</th>
                        <th>Somewhat Effective</th>
                        <th>Very Effective</th>
                    </tr>
                </thead>
                <tbody id="training-methods-tbody">
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
                        <tr class="training-method-row" data-method-key="{{ $key }}">
                            <td data-label="Method">
                                {{ $label }}
                                @if ($key === 'other')
                                    <br>
                                    <input type="text" name="training_methods[other_label]" placeholder="Please specify" class="training-method-other-input" style="width:100%;margin-top:0.25rem;">
                                    <button type="button" class="btn-remove-training" onclick="removeTrainingMethodRow(this)" style="margin-top:0.5rem; width:100%;">Remove</button>
                                @endif
                            </td>
                            @foreach (['not_very', 'somewhat', 'very'] as $rating)
                                <td data-label="{{ ucfirst(str_replace('_', ' ', $rating)) }} Effective">
                                    <input type="radio"
                                           name="training_methods[{{ $key }}]"
                                           value="{{ $rating }}">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Add Another "Other" Training Method Button -->
            <div style="margin-top: 0.75rem;">
                <button type="button" id="add-other-method-btn" class="btn-add-other">
                    + Add Another "Other" Training Method
                </button>
            </div>

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
                    <label class="small-label" for="signature_name">Signature (name) *</label>
                    <input id="signature_name" type="text" name="signature_name" required readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                    @error('signature_name')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label class="small-label" for="signature_date">Date *</label>
                    <input id="signature_date" type="text" value="{{ date('d/m/Y') }}" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                    <input type="hidden" name="signature_date" value="{{ date('Y-m-d') }}">
                    @error('signature_date')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
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
                @error('supervisor_email')
                    <div class="field-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
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
        // Form validation and CSRF token handling
        (function() {
            const form = document.querySelector('form');
            if (!form) return;

            // Get initial token from meta tag
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            const tokenInput = form.querySelector('input[name="_token"]');
            
            if (metaToken && tokenInput) {
                tokenInput.value = metaToken.getAttribute('content');
            }

            // Auto-populate signature name from first name, middle name, and surname
            function updateSignatureName() {
                const firstName = document.getElementById('first_name');
                const middleName = document.getElementById('middle_name');
                const surname = document.getElementById('surname');
                const signatureName = document.getElementById('signature_name');
                
                if (firstName && middleName && surname && signatureName) {
                    const autoGenerated = [firstName.value.trim(), middleName.value.trim(), surname.value.trim()]
                        .filter(part => part.length > 0)
                        .join(' ');
                    
                    signatureName.value = autoGenerated;
                }
            }
            
            // Initialize signature name on page load
            updateSignatureName();
            
            // Update signature name when name fields change
            const nameFields = ['first_name', 'middle_name', 'surname'];
            nameFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', updateSignatureName);
                    field.addEventListener('change', updateSignatureName);
                }
            });

            // Function to show error message
            function showError(fieldName, message) {
                let field, input;
                
                if (fieldName === 'gender') {
                    const genderContainer = document.querySelector('.inline-options');
                    if (genderContainer) {
                        field = genderContainer.closest('.field');
                    }
                } else if (fieldName.startsWith('qualifications[')) {
                    input = document.querySelector(`input[name="${fieldName}"]`);
                    if (input) {
                        field = input.closest('td') || input.closest('tr');
                    }
                } else {
                    input = document.querySelector(`input[name="${fieldName}"], textarea[name="${fieldName}"]`);
                    if (input) {
                        field = input.closest('.field') || input.parentElement;
                    }
                }

                if (field) {
                    // Remove existing error
                    const existingError = field.querySelector('.field-error');
                    if (existingError) {
                        existingError.remove();
                    }

                    // Add error class to input
                    if (input) {
                        input.classList.add('error-highlight');
                    }

                    // Create error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'field-error';
                    errorDiv.textContent = message;
                    
                    if (input) {
                        input.parentElement.appendChild(errorDiv);
                    } else {
                        field.appendChild(errorDiv);
                    }
                }
            }

            // Counter for dynamic "Other" qualifications
            let otherQualCounter = 0;

            // Function to add a new "Other" qualification row
            function addOtherQualificationRow() {
                otherQualCounter++;
                const table = document.querySelector('table tbody');
                const newRow = document.createElement('tr');
                newRow.className = 'qualification-row other-qual-row';
                newRow.setAttribute('data-qual-key', `other_${otherQualCounter}`);
                
                newRow.innerHTML = `
                    <td>
                        <label>
                            <input type="checkbox" name="qualifications[other_${otherQualCounter}][selected]" value="1" class="qualification-checkbox" data-qual-key="other_${otherQualCounter}" checked>
                            Other (specify)
                        </label>
                        <div style="margin-top: 0.5rem;">
                            <input type="text" name="qualifications[other_${otherQualCounter}][specify]" placeholder="Specify qualification" class="qualification-specify" required style="width: 100%; padding: 0.4rem 0.45rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.8rem;">
                        </div>
                    </td>
                    <td style="position: relative;">
                        <input type="text" name="qualifications[other_${otherQualCounter}][award]" placeholder="Award / Institution" class="qualification-award" required>
                        <button type="button" class="btn-remove-other" onclick="removeOtherQualification(this)">Remove</button>
                    </td>
                `;
                
                // Insert before the closing tbody tag (or at the end)
                table.appendChild(newRow);
                
                // Attach validation to the new checkbox
                const newCheckbox = newRow.querySelector('.qualification-checkbox');
                attachQualificationValidation(newCheckbox);
            }

            // Function to remove an "Other" qualification row
            window.removeOtherQualification = function(btn) {
                const row = btn.closest('tr');
                if (row) {
                    row.remove();
                    // If no more "Other" rows exist, hide the add button if "other" checkbox is unchecked
                    checkOtherQualificationState();
                }
            };

            // Function to check if "other" checkbox is checked and show/hide add button
            function checkOtherQualificationState() {
                const otherCheckbox = document.querySelector('input[name="qualifications[other][selected]"]');
                const addBtnContainer = document.getElementById('add-other-btn-container');
                const otherRows = document.querySelectorAll('.other-qual-row');
                
                if (otherCheckbox && otherCheckbox.checked) {
                    addBtnContainer.style.display = 'block';
                } else if (otherRows.length === 0) {
                    // Only hide if no dynamic rows exist
                    addBtnContainer.style.display = 'none';
                }
            }

            // Function to attach validation to qualification checkbox
            function attachQualificationValidation(checkbox) {
                checkbox.addEventListener('change', function() {
                    const nameAttr = this.getAttribute('name');
                    const key = nameAttr.match(/qualifications\[([^\]]+)\]/)[1];
                    const awardInput = document.querySelector(`input[name="qualifications[${key}][award]"]`);
                    const specifyInput = document.querySelector(`input[name="qualifications[${key}][specify]"]`);
                    
                    if (this.checked) {
                        if (awardInput) {
                            awardInput.setAttribute('required', 'required');
                            awardInput.classList.remove('error-highlight');
                            const error = awardInput.parentElement.querySelector('.field-error');
                            if (error) error.remove();
                        }
                        
                        // If it's an "other" qualification, require specify field
                        if (key === 'other' || key.startsWith('other_')) {
                            if (specifyInput) {
                                specifyInput.setAttribute('required', 'required');
                                specifyInput.classList.remove('error-highlight');
                                const error = specifyInput.parentElement.querySelector('.field-error');
                                if (error) error.remove();
                            }
                        }
                        
                        // If it's the main "other" checkbox, show add button
                        if (key === 'other') {
                            checkOtherQualificationState();
                        }
                    } else {
                        if (awardInput) {
                            awardInput.removeAttribute('required');
                            awardInput.classList.remove('error-highlight');
                            const error = awardInput.parentElement.querySelector('.field-error');
                            if (error) error.remove();
                        }
                        
                        if (specifyInput) {
                            specifyInput.removeAttribute('required');
                            specifyInput.classList.remove('error-highlight');
                            const error = specifyInput.parentElement.querySelector('.field-error');
                            if (error) error.remove();
                        }
                        
                        // If it's the main "other" checkbox, hide add button if no dynamic rows
                        if (key === 'other') {
                            const otherRows = document.querySelectorAll('.other-qual-row');
                            if (otherRows.length === 0) {
                                document.getElementById('add-other-btn-container').style.display = 'none';
                            }
                        }
                    }
                });
            }

            // Attach validation to all existing qualification checkboxes
            document.querySelectorAll('.qualification-checkbox').forEach(function(checkbox) {
                attachQualificationValidation(checkbox);
            });

            // Add button click handler
            const addOtherBtn = document.getElementById('add-other-qualification');
            if (addOtherBtn) {
                addOtherBtn.addEventListener('click', function() {
                    addOtherQualificationRow();
                });
            }

            // Initialize button visibility on page load
            checkOtherQualificationState();

            // Past Trainings - Add Another functionality
            // Initialize counter based on existing rows
            const existingTrainingRows = document.querySelectorAll('#past-trainings-tbody .past-training-row');
            let trainingCounter = existingTrainingRows.length;

            // Function to update row numbers
            function updateTrainingRowNumbers() {
                const rows = document.querySelectorAll('#past-trainings-tbody .past-training-row');
                rows.forEach((row, index) => {
                    const rowNumberCell = row.querySelector('.row-number');
                    if (rowNumberCell) {
                        rowNumberCell.setAttribute('data-label', 'S/No');
                        rowNumberCell.textContent = index + 1;
                    }
                });
            }

            // Function to add a new training row
            window.addTrainingRow = function() {
                trainingCounter++;
                const tbody = document.getElementById('past-trainings-tbody');
                const newRow = document.createElement('tr');
                newRow.className = 'past-training-row';
                
                newRow.innerHTML = `
                    <td class="row-number" data-label="S/No">${trainingCounter}</td>
                    <td data-label="Name of the seminar / course">
                        <input type="text" name="past_trainings[${trainingCounter}][name]" class="training-name-input">
                    </td>
                    <td data-label="Date attended">
                        <input type="date" name="past_trainings[${trainingCounter}][date_attended]" class="training-date-input">
                    </td>
                    <td data-label="Action">
                        <button type="button" class="btn-remove-training" onclick="removeTrainingRow(this)">Remove</button>
                    </td>
                `;
                
                tbody.appendChild(newRow);
                updateTrainingRowNumbers();
            };

            // Function to remove a training row
            window.removeTrainingRow = function(btn) {
                const row = btn.closest('tr');
                if (row) {
                    const rows = document.querySelectorAll('#past-trainings-tbody .past-training-row');
                    // Don't allow removing if only one row remains
                    if (rows.length > 1) {
                        row.remove();
                        updateTrainingRowNumbers();
                    } else {
                        alert('At least one training entry is required.');
                    }
                }
            };

            // Add button click handler for training
            const addTrainingBtn = document.getElementById('add-training-btn');
            if (addTrainingBtn) {
                addTrainingBtn.addEventListener('click', function() {
                    addTrainingRow();
                });
            }

            // Desired Trainings - Add Another functionality
            // Initialize counter based on existing rows
            const existingDesiredTrainingRows = document.querySelectorAll('#desired-trainings-tbody .desired-training-row');
            let desiredTrainingCounter = existingDesiredTrainingRows.length;

            // Function to update row numbers for desired trainings
            function updateDesiredTrainingRowNumbers() {
                const rows = document.querySelectorAll('#desired-trainings-tbody .desired-training-row');
                rows.forEach((row, index) => {
                    const rowNumberCell = row.querySelector('.row-number');
                    if (rowNumberCell) {
                        rowNumberCell.textContent = index + 1;
                    }
                });
            }

            // Function to add a new desired training row
            window.addDesiredTrainingRow = function() {
                desiredTrainingCounter++;
                const tbody = document.getElementById('desired-trainings-tbody');
                const newRow = document.createElement('tr');
                newRow.className = 'desired-training-row';
                
                newRow.innerHTML = `
                    <td class="row-number">${desiredTrainingCounter}</td>
                    <td>
                        <input type="text" name="desired_trainings[${desiredTrainingCounter}]" class="desired-training-input">
                    </td>
                    <td>
                        <button type="button" class="btn-remove-training" onclick="removeDesiredTrainingRow(this)">Remove</button>
                    </td>
                `;
                
                tbody.appendChild(newRow);
                updateDesiredTrainingRowNumbers();
            };

            // Function to remove a desired training row
            window.removeDesiredTrainingRow = function(btn) {
                const row = btn.closest('tr');
                if (row) {
                    const rows = document.querySelectorAll('#desired-trainings-tbody .desired-training-row');
                    // Don't allow removing if only one row remains
                    if (rows.length > 1) {
                        row.remove();
                        updateDesiredTrainingRowNumbers();
                    } else {
                        alert('At least one training / course entry is required.');
                    }
                }
            };

            // Add button click handler for desired training
            const addDesiredTrainingBtn = document.getElementById('add-desired-training-btn');
            if (addDesiredTrainingBtn) {
                addDesiredTrainingBtn.addEventListener('click', function() {
                    addDesiredTrainingRow();
                });
            }

            // Training Methods - Add Another "Other" functionality
            let otherMethodCounter = 0;

            // Function to add a new "Other" training method row
            window.addOtherTrainingMethodRow = function() {
                otherMethodCounter++;
                const tbody = document.getElementById('training-methods-tbody');
                const newRow = document.createElement('tr');
                newRow.className = 'training-method-row other-method-row';
                newRow.setAttribute('data-method-key', `other_${otherMethodCounter}`);
                
                newRow.innerHTML = `
                    <td data-label="Method">
                        Other (specify)
                        <br>
                        <input type="text" name="training_methods[other_${otherMethodCounter}_label]" placeholder="Please specify" class="training-method-other-input" style="width:100%;margin-top:0.25rem;">
                        <button type="button" class="btn-remove-training" onclick="removeTrainingMethodRow(this)" style="margin-top:0.5rem; width:100%;">Remove</button>
                    </td>
                    <td data-label="Not Very Effective">
                        <input type="radio" name="training_methods[other_${otherMethodCounter}]" value="not_very">
                    </td>
                    <td data-label="Somewhat Effective">
                        <input type="radio" name="training_methods[other_${otherMethodCounter}]" value="somewhat">
                    </td>
                    <td data-label="Very Effective">
                        <input type="radio" name="training_methods[other_${otherMethodCounter}]" value="very">
                    </td>
                `;
                
                tbody.appendChild(newRow);
            };

            // Function to remove an "Other" training method row
            window.removeTrainingMethodRow = function(btn) {
                const row = btn.closest('tr');
                if (row) {
                    // Check if it's a dynamic row (other_*) or the original "other" row
                    const methodKey = row.getAttribute('data-method-key');
                    if (methodKey && methodKey.startsWith('other_')) {
                        // It's a dynamic row, safe to remove
                        row.remove();
                    } else if (methodKey === 'other') {
                        // It's the original "other" row - check if there are other "other" rows
                        const allOtherRows = document.querySelectorAll('.other-method-row, tr[data-method-key="other"]');
                        if (allOtherRows.length > 1) {
                            row.remove();
                        } else {
                            alert('At least one "Other" training method entry is required.');
                        }
                    }
                }
            };

            // Add button click handler for other training method
            const addOtherMethodBtn = document.getElementById('add-other-method-btn');
            if (addOtherMethodBtn) {
                addOtherMethodBtn.addEventListener('click', function() {
                    addOtherTrainingMethodRow();
                });
            }

            // Form submission validation
            form.addEventListener('submit', function(e) {
                // Refresh CSRF token
                if (metaToken && tokenInput) {
                    tokenInput.value = metaToken.getAttribute('content');
                }

                // Clear previous errors
                document.querySelectorAll('.field-error').forEach(el => el.remove());
                document.querySelectorAll('.error-highlight').forEach(el => el.classList.remove('error-highlight'));

                let isValid = true;

                // Validate Gender
                const gender = document.querySelector('input[name="gender"]:checked');
                if (!gender) {
                    showError('gender', 'Gender is required.');
                    isValid = false;
                }

                // Validate Age
                const age = document.querySelector('input[name="age"]');
                if (!age || !age.value || age.value.trim() === '') {
                    showError('age', 'Age is required.');
                    isValid = false;
                } else if (parseInt(age.value) < 18 || parseInt(age.value) > 100) {
                    showError('age', 'Age must be between 18 and 100.');
                    isValid = false;
                }

                // Validate required text fields
                const requiredFields = [
                    { name: 'first_name', label: 'First name' },
                    { name: 'middle_name', label: 'Middle name' },
                    { name: 'surname', label: 'Surname' },
                    { name: 'job_title', label: 'Job title' },
                    { name: 'work_station', label: 'Work station' },
                    { name: 'immediate_supervisor_name', label: 'Immediate supervisor name' },
                    { name: 'signature_name', label: 'Signature name' },
                    { name: 'signature_date', label: 'Signature date' },
                ];

                requiredFields.forEach(function(field) {
                    const input = document.querySelector(`input[name="${field.name}"]`);
                    if (!input || !input.value || input.value.trim() === '') {
                        showError(field.name, `${field.label} is required.`);
                        isValid = false;
                    }
                });

                // Validate Qualifications - At least one must be selected
                const qualificationCheckboxes = document.querySelectorAll('.qualification-checkbox');
                let hasSelectedQualification = false;
                let qualificationErrors = [];

                qualificationCheckboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        hasSelectedQualification = true;
                        const nameAttr = checkbox.getAttribute('name');
                        const key = nameAttr.match(/qualifications\[([^\]]+)\]/)[1];
                        const awardInput = document.querySelector(`input[name="qualifications[${key}][award]"]`);
                        const specifyInput = document.querySelector(`input[name="qualifications[${key}][specify]"]`);
                        
                        // Validate award/institution (required for all)
                        if (!awardInput || !awardInput.value || awardInput.value.trim() === '' || awardInput.value.trim() === 'Award / Institution') {
                            showError(`qualifications[${key}][award]`, 'Award and Institution is required for selected qualification.');
                            isValid = false;
                        }
                        
                        // Validate specify field (required only for "other" qualifications)
                        if ((key === 'other' || key.startsWith('other_')) && (!specifyInput || !specifyInput.value || specifyInput.value.trim() === '')) {
                            showError(`qualifications[${key}][specify]`, 'Please specify the qualification name.');
                            isValid = false;
                        }
                    }
                });

                if (!hasSelectedQualification) {
                    showError('qualifications', 'Please select at least one qualification.');
                    isValid = false;
                }
                
                // Check if "other" checkbox is checked to show add button
                checkOtherQualificationState();

                // Validate Supervisor Email
                const supervisorEmail = document.querySelector('input[name="supervisor_email"]');
                if (!supervisorEmail || !supervisorEmail.value || supervisorEmail.value.trim() === '') {
                    showError('supervisor_email', 'Supervisor email is required.');
                    isValid = false;
                } else {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(supervisorEmail.value)) {
                        showError('supervisor_email', 'Please enter a valid email address.');
                        isValid = false;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    const firstError = document.querySelector('.field-error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return false;
                }
            });

            // If form is in an iframe
            if (window.self !== window.top) {
                console.log('Form loaded in iframe - CSRF token initialized');
            }
        })();
    </script>
</body>
</html>



