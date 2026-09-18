@extends('layouts.app')

@section('title', 'Leads')
@section('page-title', 'Leads')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Leads</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Track and manage your sales pipeline</p>
    </div>
    <a href="{{ route('leads.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Lead
    </a>
</div>

{{-- Pipeline Summary --}}
<div class="row g-3 mb-4">
    @foreach(['new'=>['secondary','New'],'contacted'=>['info','Contacted'],'qualified'=>['primary','Qualified'],'proposal'=>['warning','Proposal'],'won'=>['success','Won'],'lost'=>['danger','Lost']] as $status => $config)
    <div class="col-6 col-md-2">
        <div class="content-card text-center p-3">
            <div class="fw-bold" style="font-size:1.4rem;color:#1e2139;">
                {{ $pipeline[$status] ?? 0 }}
            </div>
            <div>
                <span class="badge bg-{{ $config[0] }}-subtle text-{{ $config[0] }} badge-status">
                    {{ $config[1] }}
                </span>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('leads.index') }}">
            <div class="row g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0"
                               placeholder="Search leads..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['new','contacted','qualified','proposal','won','lost'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('leads.index') }}" class="btn btn-light flex-fill">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-funnel me-2"></i>All Leads <span class="badge bg-primary ms-2">{{ $leads->total() }}</span></h6>
    </div>
    <div class="table-responsive">
        @if($leads->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Contact</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Value</th>
                    <th>Follow-up</th>
                    <th>Assigned</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:0.875rem;">{{ $lead->name }}</div>
                        @if($lead->company)
                        <div style="font-size:0.75rem;color:#94a3b8;">{{ $lead->company }}</div>
                        @endif
                    </td>
                    <td>
                        @if($lead->email)
                        <div style="font-size:0.82rem;"><i class="bi bi-envelope text-muted me-1"></i>{{ $lead->email }}</div>
                        @endif
                        @if($lead->phone)
                        <div style="font-size:0.82rem;"><i class="bi bi-telephone text-muted me-1"></i>{{ $lead->phone }}</div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;">{{ $lead->source ?? '—' }}</td>
                    <td>
                        <span class="badge badge-status bg-{{ $lead->status_color }}-subtle text-{{ $lead->status_color }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td style="font-size:0.85rem;font-weight:600;">
                        {{ $lead->expected_value ? '₹'.number_format($lead->expected_value,0) : '—' }}
                    </td>
                    <td style="font-size:0.82rem;">
                        @if($lead->follow_up_date)
                            <span class="{{ $lead->follow_up_date->isPast() ? 'text-danger' : 'text-muted' }}">
                                {{ $lead->follow_up_date->format('d M Y') }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td style="font-size:0.82rem;">
                        {{ $lead->assignedTo->name ?? '—' }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-light" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                  onsubmit="return confirm('Delete this lead?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
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
            <a href="{{ route('leads.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-lg me-1"></i> Add Lead
            </a>
        </div>
        @endif
    </div>
</div>

@endsection