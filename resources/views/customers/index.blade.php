@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Customers</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">
            Manage your customer relationships
        </p>
    </div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Customer
    </a>
</div>

{{-- Filters --}}
<div class="content-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('customers.index') }}">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            class="form-control border-start-0"
                            placeholder="Search by name, email, company..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-light flex-fill">
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="content-card">
    <div class="card-header">
        <h6>
            <i class="bi bi-people me-2"></i>
            All Customers
            <span class="badge bg-primary ms-2">{{ $customers->total() }}</span>
        </h6>
    </div>

    <div class="table-responsive">
        @if($customers->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td style="color:#94a3b8; font-size:0.8rem;">{{ $customer->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.8rem;flex-shrink:0;">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:0.875rem;">{{ $customer->name }}</div>
                                @if($customer->company)
                                <div style="font-size:0.75rem;color:#94a3b8;">{{ $customer->company }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($customer->email)
                        <div style="font-size:0.82rem;">
                            <i class="bi bi-envelope text-muted me-1"></i>{{ $customer->email }}
                        </div>
                        @endif
                        @if($customer->phone)
                        <div style="font-size:0.82rem;">
                            <i class="bi bi-telephone text-muted me-1"></i>{{ $customer->phone }}
                        </div>
                        @endif
                    </td>
                    <td style="font-size:0.82rem;">
                        {{ collect([$customer->city, $customer->state, $customer->country])->filter()->implode(', ') ?: '—' }}
                    </td>
                    <td>
                        <span class="badge badge-status {{ $customer->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:#94a3b8;">
                        {{ $customer->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('customers.show', $customer) }}"
                               class="btn btn-sm btn-light" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this customer?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($customers->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $customers->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <i class="bi bi-people" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No customers found</h6>
            <p class="text-muted" style="font-size:0.85rem;">
                {{ request()->hasAny(['search','status']) ? 'Try adjusting your filters.' : 'Add your first customer to get started.' }}
            </p>
            @if(!request()->hasAny(['search','status']))
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Customer
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

@endsection