<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Needs Assessment</title>
    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }
        .button:hover {
            background: #1d4ed8;
        }
        .info {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #2563eb;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.85rem;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0;">Training Needs Assessment</h2>
    </div>
    <div class="content">
        <p>Dear Supervisor,</p>
        
        <p>A subordinate has completed Part A of the Training Needs Assessment form and has requested that you complete Part B.</p>
        
        <div class="info">
            <strong>Subordinate Details:</strong><br>
            Name: {{ $assessment->first_name }} {{ $assessment->middle_name }} {{ $assessment->surname }}<br>
            Position: {{ $assessment->job_title ?? 'N/A' }}<br>
            Work Station: {{ $assessment->work_station ?? 'N/A' }}
        </div>
        
        <p>Please click the button below to review Part A and complete Part B of the assessment:</p>
        
        <div style="text-align: center;">
            <a href="{{ route('training.supervisor.form', $token) }}" class="button">
                Complete Part B Assessment
            </a>
        </div>
        
        <p style="font-size: 0.9rem; color: #64748b;">
            Or copy and paste this link into your browser:<br>
            <a href="{{ route('training.supervisor.form', $token) }}" style="color: #2563eb; word-break: break-all;">
                {{ route('training.supervisor.form', $token) }}
            </a>
        </p>
        
        <div class="footer">
            <p><strong>Note:</strong> This link is unique and secure. Please do not share it with others.</p>
            <p>If you did not expect this email, please ignore it.</p>
        </div>
    </div>
</body>
</html>

