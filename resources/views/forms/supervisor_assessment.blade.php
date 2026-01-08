<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Supervisor Assessment – {{ config('advancedforms.appname', config('app.name')) }}</title>

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
        .read-only {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 1rem;
            border-radius: 6px;
            color: #64748b;
        }
        .read-only .section-heading {
            color: #475569;
        }
        .read-only input,
        .read-only textarea,
        .read-only select {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            cursor: not-allowed;
        }
        h1, h2, h3 {
            font-weight: 700;
        }
        .intro-section {
            background: #f0f9ff;
            border-left: 4px solid #2563eb;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.7;
        }
        .section-heading {
            background: #2563eb;
            color: white;
            padding: 0.6rem 1rem;
            margin: 1.5rem 0 0.75rem;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
        }
        .section-heading:first-child {
            margin-top: 0;
        }
        .note {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.75rem;
            font-style: italic;
        }
        .field {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: #334155;
            font-size: 0.9rem;
        }
        .small-label {
            font-size: 0.85rem;
            font-weight: 500;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 0.9rem;
            font-family: Tahoma, sans-serif;
            background: white;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
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
            background: #f1f5f9;
            font-weight: 600;
            text-align: left;
        }
        .signature-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        .footer-actions {
            margin-top: 2rem;
            text-align: center;
        }
        .btn-primary {
            background: #2563eb;
            color: white;
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
        }
        .alert-info {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }
            .sheet {
                padding: 1.25rem 1rem;
            }
            .signature-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="sheet">
        <h1 style="text-align: center; color: #1e40af; margin-bottom: 1rem;">Training Needs Assessment</h1>
        <div class="alert alert-info">
            <strong>Part A (Read-Only):</strong> Below is the information submitted by the subordinate. Please review it before completing Part B.
        </div>
    </div>

    <!-- Part A - Read Only -->
    <div class="sheet read-only">
        <div class="section-heading">Part A: Personal Information</div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="field">
                <label class="small-label">Gender</label>
                <input type="text" value="{{ $assessment->gender ?? 'N/A' }}" readonly>
            </div>
            <div class="field">
                <label class="small-label">Age</label>
                <input type="text" value="{{ $assessment->age ?? 'N/A' }}" readonly>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="field">
                <label class="small-label">First Name</label>
                <input type="text" value="{{ $assessment->first_name ?? 'N/A' }}" readonly>
            </div>
            <div class="field">
                <label class="small-label">Middle Name</label>
                <input type="text" value="{{ $assessment->middle_name ?? 'N/A' }}" readonly>
            </div>
            <div class="field">
                <label class="small-label">Surname</label>
                <input type="text" value="{{ $assessment->surname ?? 'N/A' }}" readonly>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
            <div class="field">
                <label class="small-label">Job Title</label>
                <input type="text" value="{{ $assessment->job_title ?? 'N/A' }}" readonly>
            </div>
            <div class="field">
                <label class="small-label">Work Station</label>
                <input type="text" value="{{ $assessment->work_station ?? 'N/A' }}" readonly>
            </div>
            <div class="field">
                <label class="small-label">Immediate Supervisor Name</label>
                <input type="text" value="{{ $assessment->immediate_supervisor_name ?? 'N/A' }}" readonly>
            </div>
        </div>

        @if($assessment->qualifications && count($assessment->qualifications) > 0)
            <div class="section-heading" style="margin-top: 1.5rem;">Academic Qualifications</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 45%;">Qualification</th>
                        <th>Award and Institution</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $qualificationLabels = [
                            'phd' => 'Doctor of Philosophy (PhD)',
                            'masters' => 'Master degree',
                            'bachelor' => 'Bachelor degree',
                            'postgraduate_diploma' => 'Postgraduate Diploma',
                            'certificate' => 'Certificate',
                            'professional' => 'Professional qualification',
                            'other' => 'Other (specify)',
                        ];
                    @endphp
                    @foreach($assessment->qualifications as $key => $qual)
                        @if(isset($qual['selected']) && $qual['selected'] == 1)
                            <tr>
                                <td>
                                    {{ $qualificationLabels[$key] ?? (str_starts_with($key, 'other_') ? 'Other (specify)' : ucfirst(str_replace('_', ' ', $key))) }}
                                    @if($key === 'other' || str_starts_with($key, 'other_'))
                                        @if(!empty($qual['specify']))
                                            <div style="margin-top: 0.5rem; font-size: 0.85rem; color: #64748b; font-style: italic;">
                                                ({{ $qual['specify'] }})
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $qual['award'] ?? 'N/A' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($assessment->past_trainings && count($assessment->past_trainings) > 0)
            <div class="section-heading" style="margin-top: 1.5rem;">Past Trainings</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">S/No</th>
                        <th style="width: 50%;">Name of the seminar / course</th>
                        <th style="width: 25%;">Date attended</th>
                    </tr>
                </thead>
                <tbody>
                    @php $index = 1; @endphp
                    @foreach($assessment->past_trainings as $training)
                        @if(!empty($training['name']) || !empty($training['date_attended']))
                            <tr>
                                <td>{{ $index++ }}</td>
                                <td>{{ $training['name'] ?? 'N/A' }}</td>
                                <td>{{ $training['date_attended'] ? \Carbon\Carbon::parse($training['date_attended'])->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($assessment->competencies)
            <div class="section-heading" style="margin-top: 1.5rem;">Competencies</div>
            <div style="padding: 1rem; background: #f1f5f9; border-radius: 4px;">
                @php
                    $competencyGroups = [
                        '4_1_communicates' => '4.1 Communicates with impact and influence',
                        '4_2_customer_orientation' => '4.2 Customer Orientation',
                        '4_3_standardisation' => '4.3 Enhances sustainable standardisation',
                        '4_4_risk_compliance' => '4.4 Risk, Compliance and Legal Orientation',
                        '4_5_innovation' => '4.5 Innovation and Business Improvement',
                        '4_6_leadership' => '4.6 Leadership and Managing Change',
                        '4_7_information_mastery' => '4.7 Information, knowledge and technology mastery',
                        '4_8_people_management' => '4.8 People Management and Collaboration',
                    ];
                @endphp
                @foreach($assessment->competencies as $groupKey => $items)
                    @if(is_array($items) && count($items) > 0)
                        <div style="margin-bottom: 1rem;">
                            <strong>{{ $competencyGroups[$groupKey] ?? $groupKey }}:</strong>
                            <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                                @foreach($items as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        @if($assessment->desired_trainings && count($assessment->desired_trainings) > 0)
            <div class="section-heading" style="margin-top: 1.5rem;">Desired Trainings</div>
            <ul style="margin-left: 1.5rem;">
                @foreach($assessment->desired_trainings as $training)
                    <li>{{ $training }}</li>
                @endforeach
            </ul>
        @endif

        @if($assessment->training_methods && count($assessment->training_methods) > 0)
            <div class="section-heading" style="margin-top: 1.5rem;">Preferred Training Methods</div>
            <ul style="margin-left: 1.5rem;">
                @foreach($assessment->training_methods as $method)
                    <li>{{ $method }}</li>
                @endforeach
            </ul>
        @endif

        @if($assessment->other_comments)
            <div class="section-heading" style="margin-top: 1.5rem;">Other Comments</div>
            <div class="field">
                <textarea readonly>{{ $assessment->other_comments }}</textarea>
            </div>
        @endif

        <div class="signature-row" style="margin-top: 1.5rem;">
            <div class="field">
                <label class="small-label">Signature (name)</label>
                <input type="text" value="{{ $assessment->signature_name ?? 'N/A' }}" readonly>
            </div>
            <div class="field">
                <label class="small-label">Date</label>
                <input type="text" value="{{ $assessment->signature_date ? \Carbon\Carbon::parse($assessment->signature_date)->format('Y-m-d') : 'N/A' }}" readonly>
            </div>
        </div>
    </div>

    <!-- Part B - Editable -->
    <form method="POST" action="{{ route('training.supervisor.part-b', $token) }}">
        @csrf
        <div class="sheet">
            <div class="section-heading">Part B: To be filled by the supervising officer</div>

            <div class="section-heading" style="margin-top:0.75rem;">1.0 Performance of Subordinate</div>
            <p class="note">
                Is your subordinate performing to the required standards? If no, what prevents him/her from achieving the required standards?
            </p>
            <div class="field">
                <textarea name="supervisor_performance_comment" required>{{ old('supervisor_performance_comment', $assessment->supervisor_performance_comment ?? '') }}</textarea>
            </div>

            <div class="section-heading">2.0 Training / Courses Suggestion</div>
            <p class="note">
                Please refer to the competencies in Part A (4) and (5) above, then suggest three (3) training / courses
                which will improve your subordinate's performance.
            </p>

            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">S/No</th>
                        <th>Training / Course</th>
                        <th style="width: 10%;"></th>
                    </tr>
                </thead>
                <tbody id="training-suggestions-tbody">
                    @php
                        $existingSuggestions = old('supervisor_training_suggestions', $assessment->supervisor_training_suggestions ?? []);
                        $suggestionCount = max(3, count($existingSuggestions));
                    @endphp
                    @for ($i = 1; $i <= $suggestionCount; $i++)
                        <tr class="training-suggestion-row">
                            <td class="row-number">{{ $i }}</td>
                            <td>
                                <input type="text" name="supervisor_training_suggestions[{{ $i }}]" value="{{ $existingSuggestions[$i] ?? '' }}" required>
                            </td>
                            <td>
                                @if($i > 3)
                                    <button type="button" class="btn-remove-training" onclick="removeTrainingSuggestionRow(this)" style="padding: 0.4rem 0.75rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-family: Tahoma, sans-serif;">Remove</button>
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
            <div style="margin-top: 0.75rem;">
                <button type="button" id="add-training-suggestion" onclick="addTrainingSuggestionRow()" style="padding: 0.6rem 1.25rem; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem; font-weight: 500; font-family: Tahoma, sans-serif;">+ Add Another</button>
            </div>

            <div class="signature-row" style="margin-top:0.9rem;">
                <div class="field">
                    <label class="small-label" for="supervisor_name">Name</label>
                    <input id="supervisor_name" type="text" name="supervisor_name" value="{{ old('supervisor_name', $assessment->supervisor_name ?? '') }}" required>
                </div>
                <div class="field">
                    <label class="small-label" for="supervisor_date">Date</label>
                    <input id="supervisor_date" type="text" value="{{ date('d/m/Y') }}" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                    <input type="hidden" name="supervisor_date" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="footer-actions">
                <button type="submit" class="btn-primary">Submit Part B Assessment</button>
            </div>
        </div>
    </form>

    <script>
        // Training Suggestions - Add Another functionality
        let trainingSuggestionCounter = {{ $suggestionCount }};

        // Function to update row numbers
        function updateTrainingSuggestionRowNumbers() {
            const rows = document.querySelectorAll('#training-suggestions-tbody .training-suggestion-row');
            rows.forEach((row, index) => {
                const rowNumberCell = row.querySelector('.row-number');
                if (rowNumberCell) {
                    rowNumberCell.textContent = index + 1;
                }
            });
        }

        // Function to add a new training suggestion row
        function addTrainingSuggestionRow() {
            trainingSuggestionCounter++;
            const tbody = document.getElementById('training-suggestions-tbody');
            const newRow = document.createElement('tr');
            newRow.className = 'training-suggestion-row';
            
            newRow.innerHTML = `
                <td class="row-number">${trainingSuggestionCounter}</td>
                <td>
                    <input type="text" name="supervisor_training_suggestions[${trainingSuggestionCounter}]" required>
                </td>
                <td>
                    <button type="button" class="btn-remove-training" onclick="removeTrainingSuggestionRow(this)" style="padding: 0.4rem 0.75rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-family: Tahoma, sans-serif;">Remove</button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            updateTrainingSuggestionRowNumbers();
        }

        // Function to remove a training suggestion row
        function removeTrainingSuggestionRow(btn) {
            const row = btn.closest('tr');
            if (row) {
                const rows = document.querySelectorAll('#training-suggestions-tbody .training-suggestion-row');
                // Don't allow removing if only 3 rows remain (minimum required)
                if (rows.length > 3) {
                    row.remove();
                    updateTrainingSuggestionRowNumbers();
                } else {
                    alert('At least three (3) training suggestions are required.');
                }
            }
        }

        // Initialize row numbers on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateTrainingSuggestionRowNumbers();
        });
    </script>
</body>
</html>

