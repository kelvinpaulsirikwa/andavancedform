@extends('layouts.app')

@section('title', 'Admin Count | ' . config('advancedforms.appname', config('app.name')))

@section('content')
    <main style="max-width: 960px; margin: 0 auto; padding: 2rem 1.5rem;">
        <div class="card" style="padding: 1.5rem; border-radius: 10px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;">
            <h2 style="margin-bottom: 0.75rem; font-size: 1.2rem; font-weight: 600; color: #0f172a;">Admin Users</h2>
            <p style="font-size: 0.95rem; color: #475569; margin-bottom: 0.5rem;">
                Total admins in the system:
            </p>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; border-radius: 999px; padding: 0.5rem 1rem; font-weight: 700; font-size: 1.1rem;">
                {{ $adminCount }}
            </div>
        </div>
    </main>
@endsection

