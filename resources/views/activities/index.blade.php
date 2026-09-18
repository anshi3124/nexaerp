@extends('layouts.app')

@section('title', 'Activities')
@section('page-title', 'Activities')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Activities</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">
            All logged calls, emails, meetings and follow-ups
        </p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff;">
                <i class="bi bi-activity" style="color:#3b82f6;"></i>
            </div>
            <div class="stat-value">{{ $summary['total'] }}</div>
            <div class="stat-label">Total Activities</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4;">
                <i class="bi bi-telephone" style="color:#22c55e;"></i>
            </div>
            <div class="stat-value">{{ $summary['calls'] }}</div>
            <div class="stat-label">Calls</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff;">
                <i class="bi bi-calendar-event" style="color:#a855f7;"></i>
            </div>
            <div class="stat-value">{{ $summary['meetings'] }}</div>
            <div class="stat-label">Meetings</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fffbeb;">
                <i class="bi bi-envelope" style="color:#f59e0b;"></i>
            </div>
            <div class="stat-value">{{ $summary['emails'] }}</div>
            <div class="stat-label">Emails</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('activities.index') }}">
            <div class="row g-2">
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        @foreach(['call'=>'📞 Call','email'=>'📧 Email','meeting'=>'📅 Meeting','follow_up'=>'🔔 Follow-up','note'=>'📝 Note'] as $val => $label)
                        <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="user_id" class="form-select">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('activities.index') }}" class="btn btn-light flex-fill">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Activities Table --}}
<div class="content-card">
    <div class="card-header">
        <h6>
            <i class="bi bi-clock-history me-2"></i>
            All Activities
            <span class="badge bg-primary ms-2">{{ $activities->total() }}</span>
        </h6>
    </div>

    @if($activities->count() > 0)
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Related To</th>
                    <th>Description</th>
                    <th>Logged By</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                <tr>
                    <td>
                        <div style="font-size:0.82rem;font-weight:500;">
                            {{ $activity->activity_date->format('d M Y') }}
                        </div>
                        <div style="font-size:0.75rem;color:#94a3b8;">
                            {{ $activity->activity_date->format('h:i A') }}
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-status
                            bg-{{ $activity->type_color }}-subtle
                            text-{{ $activity->type_color }}">
                            <i class="bi {{ $activity->type_icon }} me-1"></i>
                            {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                        </span>
                    </td>
                    <td>
                        @if($activity->subject)
                            @php
                                $isCustomer = $activity->subject_type === \App\Models\Customer::class;
                            @endphp
                            <div style="font-size:0.875rem;font-weight:600;">
                                {{ $activity->subject->name }}
                            </div>
                            <span class="badge badge-status {{ $isCustomer ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning' }}"
                                  style="font-size:0.65rem;">
                                {{ $isCustomer ? 'Customer' : 'Lead' }}
                            </span>
                        @else
                            <span class="text-muted" style="font-size:0.82rem;">Deleted</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:0.85rem;max-width:300px;">
                            {{ Str::limit($activity->description, 80) }}
                        </div>
                    </td>
                    <td style="font-size:0.82rem;">
                        {{ $activity->user->name }}
                    </td>
                    <td>
                        <form action="{{ route('activities.destroy', $activity) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this activity?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-light text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($activities->hasPages())
    <div class="px-4 py-3 border-top">
        {{ $activities->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-5">
        <i class="bi bi-clock-history" style="font-size:3rem;color:#e2e8f0;"></i>
        <h6 class="mt-3 text-muted">No activities found</h6>
        <p class="text-muted" style="font-size:0.85rem;">
            Log activities from customer or lead detail pages.
        </p>
    </div>
    @endif
</div>

@endsection