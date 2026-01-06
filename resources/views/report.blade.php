@extends('layouts.app')

@section('title', 'Report – ' . config('advancedforms.appname', config('app.name')))

@push('styles')
    <style>
        main {
            padding: 2rem 1.5rem;
            width: 100%;
            margin: 0;
            max-width: none;
        }
        .card {
            background: #ffffff;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            width: 100%;
            max-width: none;
        }
        .card h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.75rem;
        }
        .card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #334155;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #2563eb;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 1rem;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        thead {
            background: #f1f5f9;
        }
        th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            color: #334155;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }
        tr:hover {
            background: #f8fafc;
        }
        tr[onclick]:hover {
            background: #f1f5f9;
            cursor: pointer;
        }
        .gender-item:hover {
            background: #f8fafc;
            border-radius: 6px;
            padding: 0.5rem;
            margin: -0.5rem;
        }
        .percentage-bar {
            background: #e2e8f0;
            height: 28px;
            border-radius: 14px;
            overflow: hidden;
            margin-top: 0.5rem;
            position: relative;
            display: flex;
            align-items: center;
        }
        .percentage-fill {
            height: 100%;
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            transition: width 0.3s ease;
            position: absolute;
            left: 0;
            top: 0;
        }
        .percentage-text {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            color: #1e293b;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 10;
            white-space: nowrap;
        }
        .percentage-bar .percentage-text {
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }
        .gender-stats {
            display: flex;
            gap: 5rem;
            margin-top: 1rem;
            width: 100%;
            max-width: none;
            padding: 0;
        }
        .gender-item {
            flex: 1 1 0;
            min-width: 0;
            width: auto;
            max-width: none;
        }
        .gender-item a {
            display: block;
            width: 100%;
        }
        .percentage-bar {
            width: 100%;
            max-width: none;
            min-width: 200px;
        }
        .gender-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 1rem;
            color: #475569;
            font-weight: 500;
        }
        .gender-label strong {
            font-size: 1.1rem;
            color: #0f172a;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #64748b;
        }
        .empty-state p {
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        .supervisor-title {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.25rem;
        }
        @media (max-width: 768px) {
            main {
                padding: 1.5rem 1rem;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .gender-stats {
                flex-direction: column;
                gap: 1rem;
            }
            .chart-container {
                height: 250px;
            }
        }
    </style>
@endpush

@section('content')
    <main>
        <div class="card" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="margin: 0;">Training Needs Assessment Report Summary</h2>
            <button onclick="openExportModal()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 500; font-size: 0.9rem; cursor: pointer; transition: background 0.2s; font-family: Tahoma, sans-serif;" onmouseover="this.style.background='#1d4ed8';" onmouseout="this.style.background='#2563eb';">
                <span>📊</span>
                <span>Export to Excel</span>
            </button>
        </div>

        <!-- Export Modal -->
        <div id="exportModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
            <div style="background: white; border-radius: 8px; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                <h3 style="margin: 0 0 1.5rem 0; font-size: 1.5rem; color: #0f172a; font-family: Tahoma, sans-serif;">Export to Excel</h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                    <button onclick="exportAllData()" style="padding: 0.75rem 1.5rem; background: #2563eb; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.95rem; font-family: Tahoma, sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#1d4ed8';" onmouseout="this.style.background='#2563eb';">
                        Export All Data
                    </button>
                    <button onclick="showColumnSelection()" style="padding: 0.75rem 1.5rem; background: #64748b; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.95rem; font-family: Tahoma, sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#475569';" onmouseout="this.style.background='#64748b';">
                        Specify Columns
                    </button>
                </div>

                <div id="columnSelection" style="display: none;">
                    <h4 style="margin: 0 0 1rem 0; font-size: 1.1rem; color: #334155; font-family: Tahoma, sans-serif;">Select Columns to Export</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto; padding: 1rem; background: #f8fafc; border-radius: 6px; margin-bottom: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="id" checked>
                            <span>ID</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="first_name" checked>
                            <span>First Name</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="middle_name" checked>
                            <span>Middle Name</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="surname" checked>
                            <span>Surname</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="gender" checked>
                            <span>Gender</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="age" checked>
                            <span>Age</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="job_title" checked>
                            <span>Job Title</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="work_station" checked>
                            <span>Work Station</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="immediate_supervisor_name" checked>
                            <span>Immediate Supervisor</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="qualifications" checked>
                            <span>Qualifications</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="past_trainings" checked>
                            <span>Past Trainings</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="competencies" checked>
                            <span>Competencies</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="desired_trainings" checked>
                            <span>Desired Trainings</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="training_methods" checked>
                            <span>Training Methods</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="other_comments" checked>
                            <span>Other Comments</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="signature_name" checked>
                            <span>Signature Name</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="signature_date" checked>
                            <span>Signature Date</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="supervisor_performance_comment" checked>
                            <span>Supervisor Performance Comment</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="supervisor_training_suggestions" checked>
                            <span>Supervisor Training Suggestions</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="supervisor_name" checked>
                            <span>Supervisor Name</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="supervisor_signature" checked>
                            <span>Supervisor Signature</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="supervisor_date" checked>
                            <span>Supervisor Date</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-family: Tahoma, sans-serif;">
                            <input type="checkbox" class="column-checkbox" value="created_at" checked>
                            <span>Created At</span>
                        </label>
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <button onclick="exportSelectedColumns()" style="flex: 1; padding: 0.75rem 1.5rem; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.95rem; font-family: Tahoma, sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#059669';" onmouseout="this.style.background='#10b981';">
                            Export Selected
                        </button>
                        <button onclick="selectAllColumns()" style="padding: 0.75rem 1rem; background: #64748b; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.85rem; font-family: Tahoma, sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#475569';" onmouseout="this.style.background='#64748b';">
                            Select All
                        </button>
                        <button onclick="deselectAllColumns()" style="padding: 0.75rem 1rem; background: #64748b; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.85rem; font-family: Tahoma, sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#475569';" onmouseout="this.style.background='#64748b';">
                            Deselect All
                        </button>
                    </div>
                </div>

                <button onclick="closeExportModal()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: #e2e8f0; color: #475569; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.9rem; font-family: Tahoma, sans-serif; transition: background 0.2s;" onmouseover="this.style.background='#cbd5e1';" onmouseout="this.style.background='#e2e8f0';">
                    Cancel
                </button>
            </div>
        </div>
        <div class="card">
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Unread</div>
                    <div class="stat-value">{{ $unreadCount ?? 0 }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Total Responses</div>
                    <div class="stat-value">{{ $responsesCount }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Work Stations</div>
                    <div class="stat-value">{{ count($workStationStats) }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Supervisors</div>
                    <div class="stat-value">{{ count($supervisorStats) }}</div>
                </div>
            </div>
        </div>

        <!-- Gender Statistics -->
        <div class="card">
            <h3>Gender Distribution</h3>
            @if(count($genderStats) > 0)
                <div style="display: flex; justify-content: center; align-items: center; flex-direction: column; margin: 2rem 0;">
                    <div class="chart-container" style="height: 400px; width: 100%; max-width: 500px; margin: 0 auto;">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div style="margin-top: 1.5rem; display: flex; gap: 2rem; flex-wrap: wrap; justify-content: center;">
                        @foreach($genderStats as $gender => $count)
                            <a href="{{ route('dashboard.filter.gender', urlencode($gender)) }}" style="text-decoration: none; color: inherit;">
                                <div style="cursor: pointer; padding: 0.75rem 1.5rem; background: #f8fafc; border-radius: 6px; border: 2px solid transparent; transition: all 0.2s;" onmouseover="this.style.borderColor='#2563eb'; this.style.background='#f1f5f9';" onmouseout="this.style.borderColor='transparent'; this.style.background='#f8fafc';">
                                    <div style="font-weight: 600; color: #0f172a; font-size: 1rem;">{{ $gender }}</div>
                                    <div style="font-size: 0.9rem; color: #64748b; margin-top: 0.25rem;">{{ $genderPercentages[$gender] ?? 0 }}% ({{ $count }})</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <p>No gender data available</p>
                </div>
            @endif
        </div>

        <!-- Age Range Statistics -->
        <div class="card">
            <h3>Age Distribution (3-Year Ranges)</h3>
            @if(count($ageRanges) > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Age Range</th>
                                <th>Number of People</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ageRanges as $range => $count)
                                @php
                                    $percentage = $responsesCount > 0 ? round(($count / $responsesCount) * 100, 1) : 0;
                                @endphp
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('dashboard.filter.age', urlencode($range)) }}'">
                                    <td><strong>{{ $range }} years</strong></td>
                                    <td>{{ $count }}</td>
                                    <td>
                                        <div class="percentage-bar" style="max-width: 200px;">
                                            <div class="percentage-fill" style="width: {{ $percentage }}%"></div>
                                            <span class="percentage-text">{{ $percentage }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>No age data available</p>
                </div>
            @endif
        </div>

        <!-- Work Station Statistics -->
        <div class="card">
            <h3>Work Station Distribution</h3>
            @if(count($workStationStats) > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Work Station</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workStationStats as $station => $count)
                                @php
                                    $percentage = $responsesCount > 0 ? round(($count / $responsesCount) * 100, 1) : 0;
                                @endphp
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('dashboard.filter.workstation', urlencode($station)) }}'">
                                    <td><strong>{{ $station }}</strong></td>
                                    <td>{{ $count }}</td>
                                    <td>
                                        <div class="percentage-bar" style="max-width: 200px;">
                                            <div class="percentage-fill" style="width: {{ $percentage }}%"></div>
                                            <span class="percentage-text">{{ $percentage }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>No work station data available</p>
                </div>
            @endif
        </div>

        <!-- Immediate Supervisor Statistics -->
        <div class="card">
            <h3>Immediate Supervisors</h3>
            @if(count($supervisorStats) > 0)
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Supervisor Name</th>
                                <th>Number of Staff</th>
                                <th>Staff Titles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supervisorStats as $supervisor)
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('dashboard.filter.supervisor', urlencode($supervisor['name'])) }}'">
                                    <td><strong>{{ $supervisor['name'] }}</strong></td>
                                    <td>{{ $supervisor['count'] }}</td>
                                    <td>
                                        @foreach($supervisor['titles'] as $title)
                                            <div class="supervisor-title">• {{ $title }}</div>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>No supervisor data available</p>
                </div>
            @endif
        </div>

        <!-- Academic Qualification Statistics -->
        <div class="card">
            <h3>Academic Qualification Distribution</h3>
            @if(array_sum($qualificationStats) > 0)
                <div class="chart-container">
                    <canvas id="qualificationChart"></canvas>
                </div>
                <div class="table-container" style="margin-top: 1.5rem;">
                    <table>
                        <thead>
                            <tr>
                                <th>Qualification</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQualifications = array_sum($qualificationStats);
                            @endphp
                            @foreach($qualificationStats as $qual => $count)
                                @if($count > 0)
                                    @php
                                        $percentage = $totalQualifications > 0 ? round(($count / $totalQualifications) * 100, 1) : 0;
                                    @endphp
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('dashboard.filter.qualification', urlencode($qual)) }}'">
                                        <td><strong>{{ $qual }}</strong></td>
                                        <td>{{ $count }}</td>
                                        <td>
                                            <div class="percentage-bar" style="max-width: 200px;">
                                                <div class="percentage-fill" style="width: {{ $percentage }}%"></div>
                                                <span class="percentage-text">{{ $percentage }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>No qualification data available</p>
                </div>
            @endif
        </div>

        <!-- Competencies Statistics (Question 4) -->
        <div class="card">
            <h3>Competencies Distribution (Question 4)</h3>
            @if(count($competencyGroupStats) > 0 && array_sum(array_column($competencyGroupStats, 'count')) > 0)
                <!-- Competency Groups Summary -->
                <h4 style="font-size: 1rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 1rem; color: #334155;">By Competency Groups</h4>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Competency Group</th>
                                <th>Total Selections</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCompetencySelections = array_sum(array_column($competencyGroupStats, 'count'));
                            @endphp
                            @foreach($competencyGroupStats as $groupKey => $group)
                                @if($group['count'] > 0)
                                    @php
                                        $percentage = $totalCompetencySelections > 0 ? round(($group['count'] / $totalCompetencySelections) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $group['label'] }}</strong></td>
                                        <td>{{ $group['count'] }}</td>
                                        <td>
                                            <div class="percentage-bar" style="max-width: 200px;">
                                                <div class="percentage-fill" style="width: {{ $percentage }}%"></div>
                                                <span class="percentage-text">{{ $percentage }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Top Individual Competencies -->
                <h4 style="font-size: 1rem; font-weight: 600; margin-top: 2rem; margin-bottom: 1rem; color: #334155;">Top Individual Competencies</h4>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Competency</th>
                                <th>Group</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $topCompetencies = array_slice($competencyStats, 0, 20, true); // Top 20
                                $totalResponsesWithCompetencies = $responsesCount;
                            @endphp
                            @foreach($topCompetencies as $compKey => $comp)
                                @if($comp['count'] > 0)
                                    @php
                                        $percentage = $totalResponsesWithCompetencies > 0 ? round(($comp['count'] / $totalResponsesWithCompetencies) * 100, 1) : 0;
                                    @endphp
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('dashboard.filter.competency', urlencode($compKey)) }}'">
                                        <td><strong>{{ $comp['label'] }}</strong></td>
                                        <td style="font-size: 0.85rem; color: #64748b;">{{ $comp['groupLabel'] }}</td>
                                        <td>{{ $comp['count'] }}</td>
                                        <td>
                                            <div class="percentage-bar" style="max-width: 200px;">
                                                <div class="percentage-fill" style="width: {{ $percentage }}%"></div>
                                                <span class="percentage-text">{{ $percentage }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <p>No competency data available</p>
                </div>
            @endif
        </div>
    </main>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            @if(count($genderStats) > 0 && array_sum($genderStats) > 0)
            const genderCtx = document.getElementById('genderChart');
            if (genderCtx) {
                const genderChart = new Chart(genderCtx, {
                    type: 'pie',
                    data: {
                        labels: [
                            @foreach($genderStats as $gender => $count)
                                @if($count > 0)
                                    '{{ $gender }}',
                                @endif
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Gender Distribution',
                            data: [
                                @foreach($genderStats as $gender => $count)
                                    @if($count > 0)
                                        {{ $count }},
                                    @endif
                                @endforeach
                            ],
                            backgroundColor: [
                                'rgba(37, 99, 235, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(96, 165, 250, 0.8)',
                                'rgba(147, 197, 253, 0.8)',
                            ],
                            borderColor: [
                                'rgba(37, 99, 235, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(96, 165, 250, 1)',
                                'rgba(147, 197, 253, 1)',
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        aspectRatio: 1,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    family: 'Tahoma'
                                },
                                bodyFont: {
                                    size: 13,
                                    family: 'Tahoma'
                                },
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        label += context.parsed + ' (' + percentage + '%)';
                                        return label;
                                    }
                                }
                            }
                        },
                        onClick: function(event, elements) {
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                const labels = this.data.labels;
                                if (labels[index]) {
                                    const gender = labels[index];
                                    window.location.href = '{{ url("/dashboard/filter/gender") }}/' + encodeURIComponent(gender);
                                }
                            }
                        },
                        onHover: function(event, elements) {
                            event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                        }
                    }
                });
            }
            @endif

            @if(array_sum($qualificationStats) > 0)
            const ctx = document.getElementById('qualificationChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach($qualificationStats as $qual => $count)
                                @if($count > 0)
                                    '{{ $qual }}',
                                @endif
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Number of Staff',
                            data: [
                                @foreach($qualificationStats as $qual => $count)
                                    @if($count > 0)
                                        {{ $count }},
                                    @endif
                                @endforeach
                            ],
                            backgroundColor: [
                                'rgba(37, 99, 235, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(96, 165, 250, 0.8)',
                                'rgba(147, 197, 253, 0.8)',
                                'rgba(191, 219, 254, 0.8)',
                            ],
                            borderColor: [
                                'rgba(37, 99, 235, 1)',
                                'rgba(59, 130, 246, 1)',
                                'rgba(96, 165, 250, 1)',
                                'rgba(147, 197, 253, 1)',
                                'rgba(191, 219, 254, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    family: 'Tahoma'
                                },
                                bodyFont: {
                                    size: 13,
                                    family: 'Tahoma'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        family: 'Tahoma',
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        family: 'Tahoma',
                                        size: 12
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
            @endif

            function openExportModal() {
                document.getElementById('exportModal').style.display = 'flex';
            }

            function closeExportModal() {
                document.getElementById('exportModal').style.display = 'none';
                document.getElementById('columnSelection').style.display = 'none';
            }

            function exportAllData() {
                window.location.href = '{{ route("dashboard.report.export") }}';
            }

            function showColumnSelection() {
                document.getElementById('columnSelection').style.display = 'block';
            }

            function selectAllColumns() {
                document.querySelectorAll('.column-checkbox').forEach(checkbox => {
                    checkbox.checked = true;
                });
            }

            function deselectAllColumns() {
                document.querySelectorAll('.column-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }

            function exportSelectedColumns() {
                const selectedColumns = [];
                document.querySelectorAll('.column-checkbox:checked').forEach(checkbox => {
                    selectedColumns.push(checkbox.value);
                });

                if (selectedColumns.length === 0) {
                    alert('Please select at least one column to export.');
                    return;
                }

                const columnsParam = selectedColumns.join(',');
                window.location.href = '{{ route("dashboard.report.export") }}?columns=' + encodeURIComponent(columnsParam);
            }

            // Close modal when clicking outside
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('exportModal');
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            closeExportModal();
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
