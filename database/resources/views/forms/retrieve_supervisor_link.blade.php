<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Retrieve Supervisor Link – {{ config('advancedforms.appname', config('app.name')) }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Tahoma, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #eef2f7;
            padding: 2rem 1rem;
            font-size: 14px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.15);
            padding: 2rem;
        }
        .card-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        .card-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }
        h1 {
            text-align: center;
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            text-align: center;
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #334155;
            font-size: 0.9rem;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: Tahoma, sans-serif;
            transition: border-color 0.2s;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .btn-primary {
            width: 100%;
            background: #2563eb;
            color: white;
            padding: 0.875rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .link-display {
            background: #f8fafc;
            border: 2px solid #2563eb;
            border-radius: 8px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }
        .link-display-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.75rem;
        }
        .link-value {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 0.75rem;
            font-size: 0.9rem;
            color: #2563eb;
            word-break: break-all;
            font-family: monospace;
            margin-bottom: 1rem;
        }
        .copy-btn {
            width: 100%;
            background: #10b981;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
            transition: background 0.2s;
        }
        .copy-btn:hover {
            background: #059669;
        }
        .match-info {
            text-align: center;
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.5rem;
        }
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            .container {
                padding: 1.5rem;
            }
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        
        <h1>Supervisor Link</h1>
        <p class="subtitle">Get your supervisor access link</p>

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($success) && $success)
            <div class="alert alert-success">
                <strong>Success!</strong> Your information has been verified ({{ $match_percentage }}% match). Your supervisor link is below:
            </div>
            
            <div class="link-display">
                <div class="link-display-label">Your Supervisor Link:</div>
                <div class="link-value" id="supervisorLink">{{ $supervisor_link }}</div>
                <button type="button" class="copy-btn" onclick="copyLink()">Copy Link</button>
                <div class="match-info">Match: {{ $match_percentage }}%</div>
            </div>
        @else
            <div class="alert alert-info">
                Please answer the following security questions to retrieve your supervisor link. You need at least 50% match to get the link.
            </div>

            <form method="POST" action="{{ route('training.retrieve.link.submit') }}">
                @csrf
                
                <div class="form-group">
                    <label for="first_name">First Name *</label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        value="{{ old('first_name') }}"
                        required
                        placeholder="Enter your first name"
                    >
                </div>

                <div class="form-group">
                    <label for="surname">Surname *</label>
                    <input 
                        type="text" 
                        id="surname" 
                        name="surname" 
                        value="{{ old('surname') }}"
                        required
                        placeholder="Enter your surname"
                    >
                </div>

                <div class="form-group">
                    <label for="job_title">Job Title / Position *</label>
                    <input 
                        type="text" 
                        id="job_title" 
                        name="job_title" 
                        value="{{ old('job_title') }}"
                        required
                        placeholder="Enter your job title"
                    >
                </div>

                <div class="form-group">
                    <label for="work_station">Work Station *</label>
                    <input 
                        type="text" 
                        id="work_station" 
                        name="work_station" 
                        value="{{ old('work_station') }}"
                        required
                        placeholder="Enter your work station"
                    >
                </div>

                <div class="form-group">
                    <label for="immediate_supervisor_name">Name of Immediate Supervisor *</label>
                    <input 
                        type="text" 
                        id="immediate_supervisor_name" 
                        name="immediate_supervisor_name" 
                        value="{{ old('immediate_supervisor_name') }}"
                        required
                        placeholder="Enter your supervisor's name"
                    >
                </div>

                <button type="submit" class="btn-primary">Retrieve Supervisor Link</button>
            </form>
        @endif
    </div>

    <script>
        function copyLink() {
            const linkElement = document.getElementById('supervisorLink');
            const link = linkElement.textContent;
            
            navigator.clipboard.writeText(link).then(function() {
                const btn = document.querySelector('.copy-btn');
                const originalText = btn.textContent;
                btn.textContent = 'Copied!';
                btn.style.background = '#059669';
                
                setTimeout(function() {
                    btn.textContent = originalText;
                    btn.style.background = '#10b981';
                }, 2000);
            }).catch(function(err) {
                alert('Failed to copy link. Please copy manually.');
            });
        }
    </script>
</body>
</html>

