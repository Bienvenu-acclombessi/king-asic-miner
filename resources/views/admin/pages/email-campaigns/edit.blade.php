@extends('admin.components.app')
@section('title', 'Edit Email Campaign')

@section('content')
<div class="container-fluid">
    <div class="row mb-8">
        <div class="col-md-12">
            <h2>Edit Email Campaign</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.email-campaigns.index') }}">Email Campaigns</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.email-campaigns.update', $emailCampaign) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header"><h4 class="mb-0">Campaign Details</h4></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Campaign Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $emailCampaign->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject', $emailCampaign->subject) }}" required>
                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Content <span class="text-danger">*</span></label>
                            <textarea name="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $emailCampaign->content) }}</textarea>
                            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Campaign</button>
                            <a href="{{ route('admin.email-campaigns.index') }}" class="btn btn-secondary">Cancel</a>
                            @if($emailCampaign->status !== 'sent')
                                <form action="{{ route('admin.email-campaigns.send', $emailCampaign) }}" method="POST"
                                      onsubmit="return confirm('Send this campaign to all subscribers?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Send Now</button>
                                </form>
                            @endif
                            <form action="{{ route('admin.email-campaigns.destroy', $emailCampaign) }}" method="POST" class="ms-auto"
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
