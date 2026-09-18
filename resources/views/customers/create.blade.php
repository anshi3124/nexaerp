@extends('layouts.app')

@section('title', 'Add Customer')
@section('page-title', 'Add Customer')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Add New Customer</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Fill in the details below</p>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
</div>

<form action="{{ route('customers.store') }}" method="POST">
    @csrf

    <div class="row g-4">

        {{-- Left Column --}}
        <div class="col-md-8">
            <div class="content-card">
                <div class="card-header">
                    <h6><i class="bi bi-person me-2"></i>Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-500">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="John Doe">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company"
                                   class="form-control @error('company') is-invalid @enderror"
                                   value="{{ old('company') }}" placeholder="Acme Corp">
                            @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="john@example.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" placeholder="+91 98765 43210">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" rows="2"
                                      class="form-control @error('address') is-invalid @enderror"
                                      placeholder="Street address">{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input type="text" name="city"
                                   class="form-control"
                                   value="{{ old('city') }}" placeholder="Mumbai">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input type="text" name="state"
                                   class="form-control"
                                   value="{{ old('state') }}" placeholder="Maharashtra">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input type="text" name="country"
                                   class="form-control"
                                   value="{{ old('country') }}" placeholder="India">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-md-4">
            <div class="content-card mb-3">
                <div class="card-header">
                    <h6><i class="bi bi-gear me-2"></i>Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-500">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active"   {{ old('status','active') == 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="4"
                                  class="form-control"
                                  placeholder="Any additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Customer
                </button>
                <a href="{{ route('customers.index') }}" class="btn btn-light">
                    Cancel
                </a>
            </div>
        </div>

    </div>
</form>

@endsection