@extends('layouts.app')

@section('title', 'Dashboard – ' . config('advancedforms.appname', config('app.name')))

@push('styles')
    <style>
        main {
            padding: 2rem 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #334155;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.9rem;
            font-family: Tahoma, sans-serif;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .btn {
            padding: 0.6rem 1.25rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            font-family: Tahoma, sans-serif;
        }
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
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
        .responses-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
         .response-item {
             background: #f8fafc;
             border: 1px solid #e2e8f0;
             border-radius: 6px;
             padding: 1rem;
         }
         .response-item:hover {
             background: #f1f5f9;
             border-color: #2563eb;
             box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1);
         }
        .response-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .response-name {
            font-weight: 600;
            color: #0f172a;
        }
        .response-date {
            font-size: 0.85rem;
            color: #64748b;
        }
        .response-details {
            font-size: 0.9rem;
            color: #475569;
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
        .form-iframe {
            width: 100%;
            border: none;
            border-radius: 8px;
            min-height: 2000px;
        }
        .form-container {
            background: transparent;
            padding: 0;
        }
        .form-container .card {
            margin-bottom: 0;
            box-shadow: none;
            padding: 0;
        }
        .pagination-wrapper {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            padding: 0;
            list-style: none;
            flex-wrap: wrap;
            margin: 0;
        }
        .pagination li {
            display: inline-block;
        }
        .pagination a,
        .pagination span,
        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #475569;
            text-decoration: none;
            font-size: 0.9rem;
            font-family: Tahoma, sans-serif;
            transition: all 0.2s;
            background: #ffffff;
        }
        .pagination a:hover,
        .pagination .page-link:hover {
            background: #f1f5f9;
            border-color: #2563eb;
            color: #2563eb;
        }
        .pagination .active span,
        .pagination .active .page-link,
        .pagination .page-item.active .page-link {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            font-weight: 500;
        }
        .pagination .disabled span,
        .pagination .disabled .page-link,
        .pagination .page-item.disabled .page-link {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .pagination .page-item {
            display: inline-block;
            list-style: none;
        }
        /* Arrow styling for Previous/Next links */
        .pagination .pagination-arrow {
            font-weight: 500;
            padding: 0 1rem;
            font-size: 0.9rem;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: auto;
        }
        .pagination a.pagination-arrow {
            transition: all 0.2s;
        }
        .pagination a.pagination-arrow:hover {
            background: #f1f5f9;
            border-color: #2563eb;
            color: #2563eb;
            transform: translateY(-1px);
        }
        .pagination .disabled .pagination-arrow {
            opacity: 0.5;
            cursor: not-allowed;
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
        }
        /* Replace old arrow symbols with proper text - target first and last li */
        .pagination > li:first-child > a:not(.pagination-arrow),
        .pagination > li:first-child > span:not(.pagination-arrow) {
            text-indent: -9999px;
            overflow: hidden;
            position: relative;
        }
        .pagination > li:first-child > a:not(.pagination-arrow):before,
        .pagination > li:first-child > span:not(.pagination-arrow):before {
            content: "← Previous";
            text-indent: 0;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: Tahoma, sans-serif;
        }
        .pagination > li:last-child > a:not(.pagination-arrow),
        .pagination > li:last-child > span:not(.pagination-arrow) {
            text-indent: -9999px;
            overflow: hidden;
            position: relative;
        }
        .pagination > li:last-child > a:not(.pagination-arrow):after,
        .pagination > li:last-child > span:not(.pagination-arrow):after {
            content: "Next →";
            text-indent: 0;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: Tahoma, sans-serif;
        }
        /* Additional styles for any pagination links/buttons */
        .pagination-wrapper a:not(.pagination-info a),
        .pagination-wrapper button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #475569;
            text-decoration: none;
            font-size: 0.9rem;
            font-family: Tahoma, sans-serif;
            transition: all 0.2s;
            background: #ffffff;
        }
        .pagination-wrapper a:not(.pagination-info a):hover,
        .pagination-wrapper button:hover:not(:disabled) {
            background: #f1f5f9;
            border-color: #2563eb;
            color: #2563eb;
        }
        .pagination-info {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 1rem;
            color: #64748b;
            font-size: 0.9rem;
            font-family: Tahoma, sans-serif;
        }
        @media (max-width: 768px) {
            main {
                padding: 1.5rem 1rem;
            }
            .pagination {
                gap: 0.25rem;
            }
            .pagination a,
            .pagination span {
                min-width: 32px;
                height: 32px;
                padding: 0 0.5rem;
                font-size: 0.85rem;
            }
        }
    </style>
@endpush

@section('content')
    <main>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Questions Tab -->
        <div id="questions-tab" class="tab-content {{ $activeTab === 'questions' ? 'active' : '' }}">
            
            <div class="form-container">
                <iframe 
                    src="{{ route('training.form') }}" 
                    class="form-iframe"
                    title="Training Needs Assessment Form">
                </iframe>
            </div>
        </div>

        <!-- Responses Tab -->
        <div id="responses-tab" class="tab-content {{ $activeTab === 'responses' ? 'active' : '' }}">
            <div class="card">
                <h2>Responses ({{ $responsesCount }})</h2>
                
                @if(isset($responses) && $responses->count() > 0)
                    <div class="responses-list">
                        @foreach($responses as $response)
                            @php
                                $isUnread = !$response->read_by || $response->read_by != auth()->id();
                            @endphp
                            <a href="{{ route('dashboard.response', $response->id) }}" style="text-decoration: none; color: inherit;">
                                <div class="response-item" style="cursor: pointer; transition: all 0.2s; {{ $isUnread ? 'border-left: 4px solid #2563eb; background: #f0f9ff;' : '' }}">
                                    <div class="response-item-header">
                                        <div class="response-name" style="display: flex; align-items: center; gap: 0.5rem;">
                                            {{ $response->first_name }} {{ $response->middle_name }} {{ $response->surname }}
                                            @if($isUnread)
                                                <span style="display: inline-block; padding: 0.15rem 0.5rem; background: #2563eb; color: white; border-radius: 12px; font-size: 0.7rem; font-weight: 600; font-family: Tahoma, sans-serif;">NEW</span>
                                            @endif
                                        </div>
                                        <div class="response-date">
                                            {{ $response->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="response-details">
                                        <strong>Position:</strong> {{ $response->job_title ?? 'N/A' }}<br>
                                        <strong>Work Station:</strong> {{ $response->work_station ?? 'N/A' }}<br>
                                        <strong>Supervisor:</strong> {{ $response->immediate_supervisor_name ?? 'N/A' }}
                                        @if(!$isUnread && $response->readByUser)
                                            <br><span style="font-size: 0.85rem; color: #64748b; margin-top: 0.5rem; display: inline-block;">
                                                <strong>Read by:</strong> {{ $response->readByUser->email }}
                                                @if($response->read_at)
                                                    <span style="color: #94a3b8;">({{ $response->read_at->format('M d, Y H:i') }})</span>
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="pagination-wrapper">
                        {{ $responses->links('vendor.pagination.default') }}
                        <div class="pagination-info">
                            Showing {{ $responses->firstItem() }} to {{ $responses->lastItem() }} of {{ $responses->total() }} results
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <p>No responses yet. Responses will appear here once forms are submitted.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Settings Tab -->
        <div id="settings-tab" class="tab-content {{ $activeTab === 'settings' ? 'active' : '' }}">
            <!-- Change Password -->
            <div class="card">
                <h2>Change Password</h2>
                <form method="POST" action="{{ route('dashboard.change-password') }}">
                    @csrf
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
            </div>

            <!-- Add New User -->
            <div class="card">
                <h2>Add New User</h2>
                <form method="POST" action="{{ route('dashboard.add-user') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input type="password" id="user_password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation_user">Confirm Password</label>
                        <input type="password" id="password_confirmation_user" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </main>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Replace old arrow symbols with proper text arrows
            const pagination = document.querySelector('.pagination');
            if (pagination) {
                // Find first and last list items
                const firstLi = pagination.querySelector('li:first-child');
                const lastLi = pagination.querySelector('li:last-child');
                
                if (firstLi) {
                    const firstLink = firstLi.querySelector('a, span');
                    if (firstLink) {
                        const text = firstLink.textContent.trim();
                        // Check if it's an old arrow symbol (‹ or similar)
                        if (text === '‹' || text === '›' || text === '\u2039' || text === '\u203A' || 
                            text === '&lsaquo;' || text === '&rsaquo;' || 
                            (text.length === 1 && !text.match(/[0-9a-zA-Z]/))) {
                            firstLink.textContent = '← Previous';
                            firstLink.classList.add('pagination-arrow');
                        }
                    }
                }
                
                if (lastLi) {
                    const lastLink = lastLi.querySelector('a, span');
                    if (lastLink) {
                        const text = lastLink.textContent.trim();
                        // Check if it's an old arrow symbol (› or similar)
                        if (text === '‹' || text === '›' || text === '\u2039' || text === '\u203A' || 
                            text === '&lsaquo;' || text === '&rsaquo;' || 
                            (text.length === 1 && !text.match(/[0-9a-zA-Z]/))) {
                            lastLink.textContent = 'Next →';
                            lastLink.classList.add('pagination-arrow');
                        }
                    }
                }
            }
        });
    </script>
@endpush

@endsection
