@extends('admin.components.app')
@section('title', 'Create Email Campaign')

@section('content')
<div class="container-fluid">
    <div class="row mb-8">
        <div class="col-md-12">
            <h2>Create Email Campaign</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.email-campaigns.index') }}">Email Campaigns</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.email-campaigns.store') }}" method="POST">
        @csrf
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header"><h4 class="mb-0">Campaign Details</h4></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Campaign Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject') }}" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Content <span class="text-danger">*</span></label>
                            <textarea name="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Campaign</button>
                            <a href="{{ route('admin.email-campaigns.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
