<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', config('advancedforms.appname', config('app.name')))</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Tahoma, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            min-height: 100vh;
            background: #f5f7fa;
            color: #1e293b;
        }
        header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        body {
            font-family: Tahoma, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            min-height: 100vh;
            background: #f5f7fa;
            color: #1e293b;
            padding-top: 70px;
        }
        .brand {
            font-weight: 700;
            font-size: 1rem;
            color: #0f172a;
        }
        .header-brand {
            display: flex;
            align-items: center;
            font-family: Tahoma, sans-serif;
        }
        .brand-content {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .app-name {
            font-weight: 600;
            font-size: 1rem;
            color: #0f172a;
        }
        .app-subtitle {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 0.1rem;
        }
        .header-nav {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .header-nav-tab {
            padding: 0.5rem 1rem;
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            font-family: Tahoma, sans-serif;
            display: inline-flex;
            align-items: center;
        }
        .header-nav-tab:hover {
            color: #334155;
            background: #f8fafc;
        }
        .header-nav-tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }
        .header-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            background: #1e293b;
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .logout-form button {
            background: transparent;
            border: 1px solid #cbd5e1;
            color: #475569;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
        }
        .logout-form button:hover {
            background: #f1f5f9;
        }
        .login-link {
            color: #2563eb;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .login-link:hover {
            background: #f1f5f9;
        }
        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            flex-direction: column;
            gap: 4px;
            font-family: Tahoma, sans-serif;
        }
        .mobile-menu-toggle span {
            display: block;
            width: 24px;
            height: 2px;
            background: #1e293b;
            transition: all 0.3s ease;
        }
        .mobile-menu-toggle.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        .mobile-menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }
        .mobile-menu-toggle.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        .mobile-menu {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            flex-direction: column;
            gap: 0.5rem;
            max-height: calc(100vh - 70px);
            overflow-y: auto;
            z-index: 999;
        }
        .mobile-menu.active {
            display: flex;
        }
        .mobile-nav-tab {
            padding: 0.75rem 1rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 6px;
            font-family: Tahoma, sans-serif;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
        }
        .mobile-nav-tab:hover {
            background: #f8fafc;
            color: #334155;
        }
        .mobile-nav-tab.active {
            color: #2563eb;
            background: #eff6ff;
        }
        .mobile-user-section {
            display: none;
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            margin-top: 0.5rem;
            flex-direction: column;
            gap: 0.75rem;
        }
        .mobile-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            header {
                flex-wrap: nowrap;
                position: fixed;
                justify-content: space-between;
                height: 70px;
            }
            body {
                padding-top: 70px;
            }
            .header-brand {
                display: flex;
                order: 1;
                flex: 1;
            }
            .app-name {
                font-size: 0.9rem;
                font-weight: 600;
            }
            .app-subtitle {
                font-size: 0.7rem;
            }
            .header-nav {
                display: none;
            }
            .user-info {
                display: none;
            }
            .mobile-menu-toggle {
                display: flex;
                order: 2;
                margin-left: auto;
            }
            .mobile-user-section {
                display: flex;
            }
            .mobile-menu {
                display: none;
            }
            .mobile-menu.active {
                display: flex;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <header>
        <div class="header-brand">
            <div class="brand-content">
                <span class="app-name">{{ config('advancedforms.appname', config('app.name')) }}</span>
                <span class="app-subtitle">TNA</span>
            </div>
        </div>
        <div class="header-nav">
            @auth
                <a href="{{ route('dashboard.questions') }}" class="header-nav-tab {{ $activeTab === 'questions' ? 'active' : '' }}">
                    Questions
                </a>
                <a href="{{ route('dashboard.responses') }}" class="header-nav-tab {{ $activeTab === 'responses' ? 'active' : '' }}">
                    Responses
                    @if($responsesCount > 0)
                        <span class="header-badge">{{ $responsesCount }}</span>
                    @endif
                </a>
                <a href="{{ route('dashboard.report') }}" class="header-nav-tab {{ $activeTab === 'report' ? 'active' : '' }}">
                    Report
                </a>
                <a href="{{ route('dashboard.settings') }}" class="header-nav-tab {{ $activeTab === 'settings' ? 'active' : '' }}">
                    Settings
                </a>
            @endauth
        </div>
        <div class="user-info">
            @auth
                <div class="avatar">
                    {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
                </div>
                <span>{{ auth()->user()->email ?? 'User' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="login-link">Login</a>
            @endauth
        </div>
        @auth
        <button class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="mobile-menu" id="mobileMenu">
            <a href="{{ route('dashboard.questions') }}" class="mobile-nav-tab {{ $activeTab === 'questions' ? 'active' : '' }}" onclick="closeMobileMenu()">
                <span>Questions</span>
            </a>
            <a href="{{ route('dashboard.responses') }}" class="mobile-nav-tab {{ $activeTab === 'responses' ? 'active' : '' }}" onclick="closeMobileMenu()">
                <span>Responses</span>
                @if($responsesCount > 0)
                    <span class="header-badge">{{ $responsesCount }}</span>
                @endif
            </a>
            <a href="{{ route('dashboard.report') }}" class="mobile-nav-tab {{ $activeTab === 'report' ? 'active' : '' }}" onclick="closeMobileMenu()">
                <span>Report</span>
            </a>
            <a href="{{ route('dashboard.settings') }}" class="mobile-nav-tab {{ $activeTab === 'settings' ? 'active' : '' }}" onclick="closeMobileMenu()">
                <span>Settings</span>
            </a>
            <div class="mobile-user-section">
                <div class="mobile-user-info">
                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->email ?? 'U', 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->email ?? 'User' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="logout-form" style="width: 100%;">
                    @csrf
                    <button type="submit" style="width: 100%;">Logout</button>
                </form>
            </div>
        </div>
        @endauth
    </header>

        @yield('content')

        @stack('scripts')
        
        <script>
            function toggleMobileMenu() {
                const menu = document.getElementById('mobileMenu');
                const toggle = document.getElementById('mobileMenuToggle');
                menu.classList.toggle('active');
                toggle.classList.toggle('active');
            }
            
            function closeMobileMenu() {
                const menu = document.getElementById('mobileMenu');
                const toggle = document.getElementById('mobileMenuToggle');
                menu.classList.remove('active');
                toggle.classList.remove('active');
            }
            
            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                const menu = document.getElementById('mobileMenu');
                const toggle = document.getElementById('mobileMenuToggle');
                if (menu && toggle && !menu.contains(event.target) && !toggle.contains(event.target)) {
                    menu.classList.remove('active');
                    toggle.classList.remove('active');
                }
            });
        </script>
        
        @auth
        <script>
            // Prevent back button navigation when logged in
            history.pushState(null, null, location.href);
            window.onpopstate = function(event) {
                history.pushState(null, null, location.href);
                // Redirect to dashboard if trying to go back
                if (window.location.pathname === '/login' || window.location.pathname === '/') {
                    window.location.href = '{{ route("dashboard.questions") }}';
                }
            };
        </script>
        @else
        <script>
            // Prevent back button navigation when logged out
            history.pushState(null, null, location.href);
            window.onpopstate = function(event) {
                history.pushState(null, null, location.href);
                // Redirect to login if trying to access protected pages
                const protectedPaths = ['/dashboard', '/dashboard/questions', '/dashboard/responses', '/dashboard/report', '/dashboard/settings'];
                if (protectedPaths.some(path => window.location.pathname.startsWith(path))) {
                    window.location.href = '{{ route("login") }}';
                }
            };
        </script>
        @endauth
    </body>
    </html>

