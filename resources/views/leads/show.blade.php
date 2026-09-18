@extends('layouts.app')

@section('title', $lead->name)
@section('page-title', 'Lead Detail')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">{{ $lead->name }}</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">{{ $lead->company ?? 'Individual Lead' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('leads.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="content-card mb-3">
            <div class="card-body text-center py-4">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#ef4444);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.5rem;margin:0 auto 1rem;">
                    {{ strtoupper(substr($lead->name, 0, 2)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $lead->name }}</h5>
                @if($lead->company)
                <p class="text-muted mb-2" style="font-size:0.85rem;">{{ $lead->company }}</p>
                @endif
                <span class="badge badge-status bg-{{ $lead->status_color }}-subtle text-{{ $lead->status_color }}">
                    {{ ucfirst($lead->status) }}
                </span>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header"><h6>Details</h6></div>
            <div class="card-body">
                @foreach([
                    ['Email', $lead->email, 'bi-envelope'],
                    ['Phone', $lead->phone, 'bi-telephone'],
                    ['Source', $lead->source, 'bi-link-45deg'],
                    ['Expected Value', $lead->expected_value ? '₹'.number_format($lead->expected_value,0) : null, 'bi-currency-rupee'],
                    ['Follow-up', $lead->follow_up_date?->format('d M Y'), 'bi-calendar'],
                    ['Assigned To', $lead->assignedTo?->name, 'bi-person'],
                ] as [$label, $value, $icon])
                @if($value)
                <div class="mb-3">
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:0.5px;">{{ $label }}</div>
                    <div style="font-size:0.875rem;"><i class="bi {{ $icon }} me-1 text-muted"></i>{{ $value }}</div>
                </div>
                @endif
                @endforeach
                @if($lead->notes)
                <div>
                    <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;font-weight:600;letter-spacing:0.5px;">Notes</div>
                    <div style="font-size:0.875rem;">{{ $lead->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Log Activity --}}
        <div class="content-card mb-4">
            <div class="card-header">
                <h6><i class="bi bi-plus-circle me-2"></i>Log Activity</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('activities.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="subject_type" value="lead">
                    <input type="hidden" name="subject_id" value="{{ $lead->id }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="call">📞 Call</option>
                                <option value="email">📧 Email</option>
                                <option value="meeting">📅 Meeting</option>
                                <option value="follow_up">🔔 Follow-up</option>
                                <option value="note">📝 Note</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="activity_date"
                                   class="form-control form-control-sm"
                                   value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="2"
                                      class="form-control form-control-sm"
                                      placeholder="What happened?"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Log Activity
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Activity History --}}
        <div class="content-card">
            <div class="card-header">
                <h6><i class="bi bi-clock-history me-2"></i>Activity History</h6>
                <span class="badge bg-light text-muted">{{ $lead->activities->count() }}</span>
            </div>
            <div class="card-body">
                @if($lead->activities->count() > 0)
                @foreach($lead->activities as $activity)
                <div class="d-flex gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0">
                        <div style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.9rem;"
                             class="bg-{{ $activity->type_color }}-subtle text-{{ $activity->type_color }}">
                            <i class="bi {{ $activity->type_icon }}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-{{ $activity->type_color }}-subtle text-{{ $activity->type_color }} badge-status me-2">
                                    {{ ucfirst(str_replace('_',' ',$activity->type)) }}
                                </span>
                                <span style="font-size:0.75rem;color:#94a3b8;">by {{ $activity->user->name }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:0.75rem;color:#94a3b8;">
                                    {{ $activity->activity_date->format('d M Y, h:i A') }}
                                </span>
                                <form action="{{ route('activities.destroy', $activity) }}" method="POST"
                                      onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm p-0 text-muted">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p class="mb-0 mt-1" style="font-size:0.85rem;">{{ $activity->description }}</p>
                    </div>
                </div>
                @endforeach
                @else
                <div class="text-center py-4">
                    <i class="bi bi-clock-history" style="font-size:2rem;color:#e2e8f0;"></i>
                    <p class="text-muted mt-2 mb-0" style="font-size:0.85rem;">No activities yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection