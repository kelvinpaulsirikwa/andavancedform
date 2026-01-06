@extends('layouts.app')

@section('title', 'Filtered by Supervisor – ' . config('advancedforms.appname', config('app.name')))

@push('styles')
    <style>
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
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #0f172a;
        }
        .filter-info {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #2563eb;
        }
        .filter-info strong {
            color: #334155;
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
            transition: all 0.2s;
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
            font-size: 1rem;
        }
        .response-date {
            font-size: 0.85rem;
            color: #64748b;
        }
        .response-details {
            font-size: 0.9rem;
            color: #475569;
        }
        .response-details strong {
            color: #334155;
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
        .back-link {
            color: #2563eb;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .back-link:hover {
            text-decoration: underline;
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
            .response-item-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
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
        <div class="card">
            <a href="{{ route('dashboard.report') }}" class="back-link">← Back to Report</a>
            <h2>Filtered Responses: {{ $filterType }} - {{ $filterValue }}</h2>
            
            <div class="filter-info">
                <strong>Filter:</strong> {{ $filterType }} = {{ $filterValue }} | 
                <strong>Total Results:</strong> {{ $responses->total() }}
            </div>
            
            @if($responses->count() > 0)
                <div class="responses-list">
                    @foreach($responses as $response)
                        <a href="{{ route('dashboard.response', $response->id) }}" style="text-decoration: none; color: inherit;">
                            <div class="response-item" style="cursor: pointer;">
                                <div class="response-item-header">
                                    <div class="response-name">
                                        {{ $response->first_name }} {{ $response->middle_name }} {{ $response->surname }}
                                    </div>
                                    <div class="response-date">
                                        {{ $response->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="response-details">
                                    <strong>Position:</strong> {{ $response->job_title ?? 'N/A' }}<br>
                                    <strong>Work Station:</strong> {{ $response->work_station ?? 'N/A' }}<br>
                                    <strong>Supervisor:</strong> {{ $response->immediate_supervisor_name ?? 'N/A' }}
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
                    <p>No responses found for this filter.</p>
                </div>
            @endif
        </div>
    </main>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pagination = document.querySelector('.pagination');
            if (pagination) {
                const firstLi = pagination.querySelector('li:first-child');
                const lastLi = pagination.querySelector('li:last-child');
                
                if (firstLi) {
                    const firstLink = firstLi.querySelector('a, span');
                    if (firstLink) {
                        const text = firstLink.textContent.trim();
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

