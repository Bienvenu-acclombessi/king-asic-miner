@extends('admin.components.app')
@section('title', 'Email Campaigns')

@section('content')
<div class="container-fluid">
    <div class="row mb-8">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2>Email Campaigns</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Email Campaigns</li>
                        </ol>
                    </nav>
                </div>
                <div><a href="{{ route('admin.email-campaigns.create') }}" class="btn btn-primary">Create Campaign</a></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Title</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Sent At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $campaign)
                                    <tr>
                                        <td><strong>{{ $campaign->title }}</strong></td>
                                        <td>{{ $campaign->subject }}</td>
                                        <td>
                                            @if($campaign->status === 'sent')
                                                <span class="badge bg-light-success text-dark-success">Sent</span>
                                            @elseif($campaign->status === 'draft')
                                                <span class="badge bg-light-secondary text-dark-secondary">Draft</span>
                                            @else
                                                <span class="badge bg-light-warning text-dark-warning">{{ ucfirst($campaign->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y H:i') : 'Not sent' }}</td>
                                        <td>
                                            <a href="{{ route('admin.email-campaigns.edit', $campaign) }}" class="btn btn-sm btn-ghost-secondary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if($campaign->status !== 'sent')
                                                <form action="{{ route('admin.email-campaigns.send', $campaign) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Send this campaign?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-ghost-primary">
                                                        <i class="bi bi-send"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.email-campaigns.destroy', $campaign) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-ghost-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-inbox fs-1 text-muted"></i>
                                            <h5 class="mt-3">No campaigns found</h5>
                                            <a href="{{ route('admin.email-campaigns.create') }}" class="btn btn-primary mt-2">Create Campaign</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($campaigns->hasPages())
                        <div class="mt-4">{{ $campaigns->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
