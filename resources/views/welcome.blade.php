<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Font -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

            <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .hero-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            object-fit: cover;
            object-position: center;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.85) 100%);
            z-index: 1;
        }

        main {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .content-wrapper {
            width: 100%;
            max-width: 80rem;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        p {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 3.5rem;
            line-height: 1.6;
            max-width: 42rem;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .icon-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            max-width: 56rem;
            margin: 0 auto;
        }

        .icon-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.25rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
        }

        .icon-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.25);
        }

        .icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .icon-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .icon-card p {
            font-size: 0.9375rem;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
            text-shadow: none;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.25rem;
            }

            p {
                font-size: 1rem;
                margin-bottom: 2.5rem;
            }

            .icon-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .icon-card {
                padding: 2rem 1.5rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1023px) {
            .icon-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            h1 {
                font-size: 4.5rem;
            }

            p {
                font-size: 1.375rem;
            }
        }
            </style>
    </head>

<body>

<!-- BACKGROUND IMAGE -->
<img
    src="{{ asset('images/static_files/hero.png') }}"
    alt="Background"
    class="hero-background"
>

<!-- COLOR OVERLAY -->
<div class="overlay"></div>

<!-- MAIN CONTENT -->
<main>
    <div class="content-wrapper">

        <h1>
            Welcome to {{ config('advancedforms.appname', config('app.name')) }}
        </h1>

        <p>
            A simple, advanced form experience designed to streamline your workflow and boost productivity.
        </p>

        @if(session('status'))
            <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; max-width: 800px; margin-left: auto; margin-right: auto; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);">
                <div style="color: #065f46; font-weight: 600; margin-bottom: 0.75rem; font-size: 1rem;">✓ Success!</div>
                <div style="color: #1e293b; font-size: 0.95rem; margin-bottom: 1rem;">{{ session('status') }}</div>
                @if(session('supervisor_link'))
                    <div style="background: #f8fafc; border: 2px solid #2563eb; border-radius: 8px; padding: 1rem; margin-top: 1rem;">
                        <div style="font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">Your Supervisor Link:</div>
                        <div id="supervisorLinkDisplay" style="background: white; border: 1px solid #cbd5e1; border-radius: 6px; padding: 0.75rem; font-size: 0.85rem; color: #2563eb; word-break: break-all; font-family: monospace; margin-bottom: 0.75rem;">{{ session('supervisor_link') }}</div>
                        <button onclick="copySupervisorLink(this)" data-link="{{ session('supervisor_link') }}" style="width: 100%; background: #10b981; color: white; padding: 0.75rem; border: none; border-radius: 6px; font-size: 0.9rem; font-weight: 600; cursor: pointer; font-family: Tahoma, sans-serif;">Copy Link</button>
                    </div>
                @endif
            </div>
                        @endif

        @if(session('error'))
            <div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; max-width: 800px; margin-left: auto; margin-right: auto; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);">
                <div style="color: #991b1b; font-weight: 600; margin-bottom: 0.75rem; font-size: 1rem;">✗ Error</div>
                <div style="color: #1e293b; font-size: 0.95rem;">{{ session('error') }}</div>
            </div>
            @endif

        <!-- ICON CARDS IN ROW -->
        <div class="icon-grid">
            
            <a href="{{ route('training.form') }}" class="icon-card">
                <div class="icon">📝</div>
                <h3>Fill Form</h3>
                <p>Create and complete forms with ease</p>
            </a>

            <a href="{{ route('training.retrieve.link') }}" class="icon-card">
                <div class="icon">🔗</div>
                <h3>Supervisor Link</h3>
                <p>Get your supervisor access link</p>
            </a>

            <a href="{{ route('login') }}" class="icon-card">
                <div class="icon">📊</div>
                <h3>View Responses</h3>
                <p>Analyze and review all submissions</p>
            </a>

        </div>

    </div>
</main>

    <script>
        function copySupervisorLink(btn) {
            const link = btn.getAttribute('data-link');
            navigator.clipboard.writeText(link).then(function() {
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