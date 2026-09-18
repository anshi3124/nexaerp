@extends('layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e2139;">Categories</h4>
        <p class="text-muted mb-0" style="font-size:0.875rem;">Manage product categories</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Category
    </a>
</div>

<div class="content-card">
    <div class="card-header">
        <h6><i class="bi bi-tags me-2"></i>All Categories
            <span class="badge bg-primary ms-2">{{ $categories->total() }}</span>
        </h6>
    </div>
    <div class="table-responsive">
        @if($categories->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Products</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td style="color:#94a3b8;font-size:0.8rem;">{{ $category->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:36px;height:36px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-tag text-primary"></i>
                            </div>
                            <span style="font-weight:600;font-size:0.875rem;">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:0.85rem;color:#64748b;">
                        {{ $category->description ?? '—' }}
                    </td>
                    <td>
                        <span class="badge bg-primary-subtle text-primary badge-status">
                            {{ $category->products_count }} products
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:#94a3b8;">
                        {{ $category->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('categories.edit', $category) }}"
                               class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this category?')">
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
        @if($categories->hasPages())
        <div class="px-4 py-3 border-top">{{ $categories->links() }}</div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-tags" style="font-size:3rem;color:#e2e8f0;"></i>
            <h6 class="mt-3 text-muted">No categories yet</h6>
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </a>
        </div>
        @endif
    </div>
</div>

@endsection