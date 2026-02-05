@extends('admin.components.app')
@section('title', 'Edit Promotion')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div>
                <h2>Edit Promotion</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Promotions</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Promotion Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $promotion->title) }}" required autofocus
                                   placeholder="e.g., Black Friday Sale, Summer Clearance">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Describe the promotion...">{{ old('description', $promotion->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="datetime-local" name="start_at"
                                       class="form-control @error('start_at') is-invalid @enderror"
                                       value="{{ old('start_at', $promotion->start_at ? $promotion->start_at->format('Y-m-d\TH:i') : '') }}">
                                @error('start_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty for immediate start</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="datetime-local" name="end_at"
                                       class="form-control @error('end_at') is-invalid @enderror"
                                       value="{{ old('end_at', $promotion->end_at ? $promotion->end_at->format('Y-m-d\TH:i') : '') }}">
                                @error('end_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty for no expiration</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                       value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">
                                    <strong>Active</strong>
                                    <br><small class="text-muted">Enable this promotion immediately</small>
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <strong>Note:</strong> This promotion is currently linked to <strong>{{ $promotion->products()->count() }}</strong> product(s).
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Promotion</button>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
