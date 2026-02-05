@extends('admin.components.app')
@section('title', 'Add New Tag')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-8">
        <div class="col-md-12">
            <div>
                <h2>Add New Tag</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tag Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.tags.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Tag Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required autofocus
                                   placeholder="e.g., ASIC Miner, Bitcoin, High Performance">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tags help organize and categorize your products.</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Tag</button>
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
