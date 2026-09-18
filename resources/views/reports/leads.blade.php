@extends('layouts.app')
@section('title', 'Lead Report')
@section('page-title', 'Lead Report')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Lead Report</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Sales pipeline analysis</p>
    </div>
    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success">
        <i class="bi bi-download me-1"></i> Export CSV
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['Total Leads',    $summary['total'],    '#3b82f6', 'bi-funnel'],
        ['Won',            $summary['won'],       '#22c55e', 'bi-trophy'],
        ['Lost',           $summary['lost'],      '#ef4444', 'bi-x-circle'],
        ['Pipeline Value', '₹'.number_format($summary['pipeline'],0), '#a855f7', 'bi-currency-rupee'],
    ] as [$label, $val, $color, $icon])
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $color }}20;">
                <i class="bi {{ $icon }}" style="color:{{ $color }};"></i>
            </div>
            <div class="stat-value">{{ $val }}</div>
            <div class="stat-label">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['new','contacted','qualified','proposal','won','lost'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="source" class="form-select">
                        <option value="">All Sources</option>
                        @foreach(['Website','Referral','Cold Call','Social Media','Email Campaign','Trade Show','Other'] as $src)
                        <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('reports.leads') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-funnel me-2"></i>Lead Pipeline</h6>
    </div>
    <div class="table-responsive">
        @if($leads->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Value</th>
                    <th>Assigned To</th>
                    <th>Follow-up</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                @php
                    $colors = ['new'=>'secondary','contacted'=>'info','qualified'=>'primary','proposal'=>'warning','won'=>'success','lost'=>'danger'];
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:0.875rem;">{{ $lead->name }}</div>
                        @if($lead->company)
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ $lead->company }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;">{{ $lead->source ?? '—' }}</td>
                    <td>
                        <span class="badge badge-status bg-{{ $colors[$lead->status] ?? 'secondary' }}-subtle text-{{ $colors[$lead->status] ?? 'secondary' }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td style="font-weight:600;font-size:0.875rem;">
                        {{ $lead->expected_value ? '₹'.number_format($lead->expected_value, 0) : '—' }}
                    </td>
                    <td style="font-size:0.82rem;">{{ $lead->assignedTo->name ?? '—' }}</td>
                    <td style="font-size:0.82rem;">
                        @if($lead->follow_up_date)
                        <span class="{{ $lead->follow_up_date->isPast() ? 'text-danger' : 'text-muted' }}">
                            {{ $lead->follow_up_date->format('d M Y') }}
                        </span>
                        @else —
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($leads->hasPages())
        <div class="px-4 py-3 border-top">{{ $leads->links() }}</div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-funnel" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No leads found</h6>
        </div>
        @endif
    </div>
</div>

@endsection