@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'Users & Roles')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Users & Roles</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">
            Manage system users and their access levels
        </p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add User
    </a>
</div>

<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-people me-2"></i>All Users
            <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
        </h6>
    </div>
    <div class="table-responsive">
        @if($users->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.8rem;flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:0.875rem;">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                    <span class="badge bg-primary-subtle text-primary ms-1" style="font-size:0.65rem;">You</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:0.85rem;">{{ $user->email }}</td>
                    <td>
                        @php
                            $roleColors = [
                                'super-admin' => 'danger',
                                'admin'       => 'primary',
                                'manager'     => 'warning',
                                'employee'    => 'secondary',
                            ];
                            $roleColor = $roleColors[$user->role?->slug] ?? 'secondary';
                        @endphp
                        <span class="badge badge-status bg-{{ $roleColor }}-subtle text-{{ $roleColor }}">
                            {{ $user->role?->name ?? 'No Role' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-status {{ $user->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:#94a3b8;">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('users.edit', $user) }}"
                               class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($users->hasPages())
        <div class="px-4 py-3 border-top">{{ $users->links() }}</div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-people" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No users found</h6>
        </div>
        @endif
    </div>
</div>

@endsection