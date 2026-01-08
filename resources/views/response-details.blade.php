@extends('layouts.app')

@section('title', 'Response Details – ' . config('advancedforms.appname', config('app.name')))

@push('styles')
    <style>
        .back-link {
            color: #2563eb;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        main {
            padding: 2rem 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        .card h2 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.75rem;
        }
        .card h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            color: #334155;
            background: #f8fafc;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }
        .info-value {
            font-size: 0.9rem;
            color: #1e293b;
            word-wrap: break-word;
            overflow-wrap: break-word;
            word-break: break-all;
        }
        .info-item.signature-item {
            grid-column: 1 / -1;
        }
        .signature-json {
            background: #f8fafc;
            padding: 0.75rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-family: monospace;
            font-size: 0.85rem;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-height: 300px;
            overflow-y: auto;
        }
        .list-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .list-item:last-child {
            border-bottom: none;
        }
        .qualification-item {
            display: flex;
            justify-content: space-between;
            align-items: start;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .qualification-item:last-child {
            border-bottom: none;
        }
        .competency-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .competency-tag {
            background: #e0e7ff;
            color: #3730a3;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        .training-method-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
        }
        .training-method-table th,
        .training-method-table td {
            padding: 0.5rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.9rem;
        }
        .training-method-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #334155;
        }
        .rating-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .rating-very { background: #d1fae5; color: #065f46; }
        .rating-somewhat { background: #fef3c7; color: #92400e; }
        .rating-not-very { background: #fee2e2; color: #991b1b; }
        .text-area {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            margin-top: 0.5rem;
            white-space: pre-wrap;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            main {
                padding: 1.5rem 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div style="padding: 1rem 1.5rem; background: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="{{ route('dashboard.responses') }}" class="back-link">
                ← Back to Responses
            </a>
            @if($response->readByUser)
                <span style="font-size: 0.85rem; color: #64748b; font-family: Tahoma, sans-serif;">
                    Read by: <strong style="color: #334155;">{{ $response->readByUser->email }}</strong>
                    @if($response->read_at)
                        <span style="color: #94a3b8;">({{ $response->read_at->format('M d, Y H:i') }})</span>
                    @endif
                </span>
            @else
                <span style="font-size: 0.85rem; color: #94a3b8; font-family: Tahoma, sans-serif;">
                    Unread
                </span>
            @endif
            <form action="{{ route('dashboard.response.delete', $response->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this response? This action cannot be undone.');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 0.5rem 1rem; background: #dc2626; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.9rem; font-family: Tahoma, sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#b91c1c';" onmouseout="this.style.background='#dc2626';">
                    🗑️ Delete
                </button>
            </form>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center;">
            @if($nextUnreadResponse)
                <a href="{{ route('dashboard.response', $nextUnreadResponse->id) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #2563eb; color: white; text-decoration: none; border-radius: 6px; font-size: 0.9rem; font-weight: 500; transition: background 0.2s; font-family: Tahoma, sans-serif;" onmouseover="this.style.background='#1d4ed8';" onmouseout="this.style.background='#2563eb';">
                    Next Response →
                </a>
            @else
                <span style="padding: 0.5rem 1rem; background: #e2e8f0; color: #64748b; border-radius: 6px; font-size: 0.9rem; font-weight: 500; font-family: Tahoma, sans-serif;">
                    All Read
                </span>
            @endif
        </div>
    </div>
    <main>
        <div class="card">
            <h2>Personal Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <span class="info-value">
                        {{ $response->first_name }} {{ $response->middle_name }} {{ $response->surname }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Gender</span>
                    <span class="info-value">{{ ucfirst($response->gender ?? 'N/A') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Age</span>
                    <span class="info-value">{{ $response->age ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Job Title / Position</span>
                    <span class="info-value">{{ $response->job_title ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Work Station</span>
                    <span class="info-value">{{ $response->work_station ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Immediate Supervisor</span>
                    <span class="info-value">{{ $response->immediate_supervisor_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Submitted Date</span>
                    <span class="info-value">{{ $response->created_at->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Academic and Professional Qualifications</h2>
            @if($response->qualifications)
                @foreach($response->qualifications as $key => $qual)
                    @if(isset($qual['selected']) && $qual['selected'])
                        <div class="qualification-item">
                            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>
                            <span class="info-value">{{ $qual['award'] ?? 'N/A' }}</span>
                        </div>
                    @endif
                @endforeach
            @else
                <p style="color: #64748b;">No qualifications listed.</p>
            @endif
        </div>

        <div class="card">
            <h2>Training Attended (Past 3 Years)</h2>
            @if($response->past_trainings)
                @php $hasTrainings = false; @endphp
                @foreach($response->past_trainings as $training)
                    @if(!empty($training['name']))
                        @php $hasTrainings = true; @endphp
                        <div class="list-item">
                            <strong>{{ $training['name'] }}</strong>
                            @if(!empty($training['date_attended']))
                                <br><span style="color: #64748b; font-size: 0.85rem;">
                                    Date: {{ \Carbon\Carbon::parse($training['date_attended'])->format('F d, Y') }}
                                </span>
                            @endif
                        </div>
                    @endif
                @endforeach
                @if(!$hasTrainings)
                    <p style="color: #64748b;">No training records listed.</p>
                @endif
            @else
                <p style="color: #64748b;">No training records listed.</p>
            @endif
        </div>

        <div class="card">
            <h2>Competencies</h2>
            @if($response->competencies)
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
                @foreach($competencyGroups as $groupKey => $groupLabel)
                    @if(isset($response->competencies[$groupKey]) && count($response->competencies[$groupKey]) > 0)
                        <h3>{{ $groupLabel }}</h3>
                        <div class="competency-list">
                            @foreach($response->competencies[$groupKey] as $comp)
                                <span class="competency-tag">{{ ucfirst(str_replace('_', ' ', $comp)) }}</span>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            @else
                <p style="color: #64748b;">No competencies selected.</p>
            @endif
        </div>

        <div class="card">
            <h2>Training / Courses to be Attended</h2>
            @if($response->desired_trainings)
                @php $hasDesired = false; @endphp
                @foreach($response->desired_trainings as $index => $training)
                    @if(!empty($training))
                        @php $hasDesired = true; @endphp
                        <div class="list-item">
                            <strong>{{ $index }}.</strong> {{ $training }}
                        </div>
                    @endif
                @endforeach
                @if(!$hasDesired)
                    <p style="color: #64748b;">No desired trainings listed.</p>
                @endif
            @else
                <p style="color: #64748b;">No desired trainings listed.</p>
            @endif
        </div>

        <div class="card">
            <h2>Training Methods Rating</h2>
            @if($response->training_methods)
                <table class="training-method-table">
                    <thead>
                        <tr>
                            <th>Method</th>
                            <th>Rating</th>
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
                                'other' => 'Other',
                            ];
                        @endphp
                        @foreach($methods as $key => $label)
                            @if(isset($response->training_methods[$key]) && $response->training_methods[$key] !== null)
                                @php
                                    $rating = $response->training_methods[$key];
                                    $ratingClass = $rating === 'very' ? 'rating-very' : ($rating === 'somewhat' ? 'rating-somewhat' : 'rating-not-very');
                                    $ratingLabel = ucfirst(str_replace('_', ' ', $rating)) . ' Effective';
                                @endphp
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>
                                        <span class="rating-badge {{ $ratingClass }}">{{ $ratingLabel }}</span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #64748b;">No training method ratings provided.</p>
            @endif
        </div>

        @if($response->other_comments)
            <div class="card">
                <h2>Other Comments</h2>
                <div class="text-area">{{ $response->other_comments }}</div>
            </div>
        @endif

        <div class="card">
            <h2>Signature</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Name</span>
                    <span class="info-value">{{ $response->signature_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date</span>
                    <span class="info-value">
                        {{ $response->signature_date ? $response->signature_date->format('F d, Y') : 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        @if($response->supervisor_performance_comment || $response->supervisor_training_suggestions)
            <div class="card">
                <h2>Part B: Supervisor Section</h2>
                
                @if($response->supervisor_performance_comment)
                    <h3>Performance of Subordinate</h3>
                    <div class="text-area">{{ $response->supervisor_performance_comment }}</div>
                @endif

                @if($response->supervisor_training_suggestions)
                    <h3>Training / Courses Suggestion</h3>
                    @foreach($response->supervisor_training_suggestions as $index => $suggestion)
                        @if(!empty($suggestion))
                            <div class="list-item">
                                <strong>{{ $index }}.</strong> {{ $suggestion }}
                            </div>
                        @endif
                    @endforeach
                @endif

                <div class="info-grid" style="margin-top: 1.5rem;">
                    <div class="info-item">
                        <span class="info-label">Supervisor Name</span>
                        <span class="info-value">{{ $response->supervisor_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date</span>
                        <span class="info-value">
                            {{ $response->supervisor_date ? $response->supervisor_date->format('F d, Y') : 'N/A' }}
                        </span>
                    </div>
                    <div class="info-item signature-item">
                        <span class="info-label">Supervisor Signature (Tracking Data)</span>
                        @if($response->supervisor_signature)
                            @php
                                $signatureData = json_decode($response->supervisor_signature, true);
                            @endphp
                            @if($signatureData && is_array($signatureData))
                                <div class="signature-json">
                                    @foreach($signatureData as $key => $value)
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                                    @endforeach
                                </div>
                            @else
                                <div class="signature-json">{{ $response->supervisor_signature }}</div>
                            @endif
                        @else
                            <span class="info-value">N/A</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </main>
@endsection

