@extends('layouts.app')

@section('title', 'Edit Lead')
@section('page-title', 'Edit Lead')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Edit Lead</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">{{ $lead->name }}</p>
    </div>
    <a href="{{ route('leads.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('leads.update', $lead) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-md-8">
            <div class="content-card">
                <div class="card-header">
                    <h6><i class="bi bi-funnel me-2"></i>Lead Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-500">Lead Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $lead->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-control"
                                   value="{{ old('company', $lead->company) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $lead->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $lead->phone) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                <option value="">Select Source</option>
                                @foreach(['Website','Referral','Cold Call','Social Media','Email Campaign','Trade Show','Other'] as $src)
                                <option value="{{ $src }}" {{ old('source', $lead->source) == $src ? 'selected' : '' }}>{{ $src }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Expected Value (₹)</label>
                            <input type="number" name="expected_value" class="form-control"
                                   value="{{ old('expected_value', $lead->expected_value) }}" step="0.01">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="3" class="form-control">{{ old('notes', $lead->notes) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="content-card mb-3">
                <div class="card-header">
                    <h6><i class="bi bi-gear me-2"></i>Lead Settings</h6>
                </div>
                <div class="card-body">

                    <div class="mb-3">
                        <label class="form-label fw-500">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['new'=>'New','contacted'=>'Contacted','qualified'=>'Qualified','proposal'=>'Proposal','won'=>'Won','lost'=>'Lost'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $lead->status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Follow-up Date</label>
                        <input type="date" name="follow_up_date" class="form-control"
                               value="{{ old('follow_up_date', $lead->follow_up_date?->format('Y-m-d')) }}">
                    </div>

                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Update Lead
                </button>
                <a href="{{ route('leads.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>
    </div>
</form>

@endsection