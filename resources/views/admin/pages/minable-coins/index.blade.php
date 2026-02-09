@extends('admin.components.app')
@section('title', 'Minable Coins Management')

@push('styles')
<style>
/* Fix modal and backdrop z-index to appear above sidebar */
.modal-backdrop.show {
    z-index: 9998 !important;
}
.modal.show {
    z-index: 9999 !important;
}
.modal-dialog {
    z-index: 9999 !important;
}
/* Ensure modal body can scroll */
.modal-dialog-scrollable .modal-body {
    overflow-y: auto !important;
    max-height: calc(100vh - 200px) !important;
}
.coin-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 4px;
}
.color-preview {
    width: 30px;
    height: 30px;
    border-radius: 4px;
    border: 1px solid #ddd;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-coin me-2"></i>Minable Coins</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Minable Coins</li>
                        </ol>
                    </nav>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMinableCoinModal">
                        <i class="bi bi-plus-circle me-1"></i>Add New Coin
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Oops! There were some problems:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.minable-coins.index') }}" method="GET" class="row g-3">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" placeholder="Search by name, symbol or algorithm..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Coins</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-secondary flex-fill">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ route('admin.minable-coins.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Coins Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list me-2"></i>All Minable Coins ({{ $minableCoins->total() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">Logo</th>
                                    <th>Name</th>
                                    <th>Symbol</th>
                                    <th>Algorithm</th>
                                    <th>Block Time</th>
                                    <th>Default Price</th>
                                    <th>Color</th>
                                    <th>Status</th>
                                    <th width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($minableCoins as $coin)
                                    <tr>
                                        <td>
                                            @if($coin->logo_url)
                                                <img src="{{ $coin->logo_url }}" alt="{{ $coin->name }}" class="coin-logo">
                                            @else
                                                <div class="coin-logo bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-coin text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td><strong>{{ $coin->name }}</strong></td>
                                        <td><span class="badge bg-secondary">{{ $coin->symbol }}</span></td>
                                        <td>{{ $coin->algorithm }}</td>
                                        <td>{{ $coin->block_time }}s</td>
                                        <td>${{ number_format($coin->default_price, 2) }}</td>
                                        <td>
                                            @if($coin->color)
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="color-preview" style="background-color: {{ $coin->color }}"></div>
                                                    <small>{{ $coin->color }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coin->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning edit-coin-btn" data-id="{{ $coin->id }}">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-coin-btn" data-id="{{ $coin->id }}" data-name="{{ $coin->name }}">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                            <p class="mb-0">No minable coins found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($minableCoins->hasPages())
                    <div class="card-footer bg-white">
                        {{ $minableCoins->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Modals -->
@include('admin.pages.minable-coins.partials.create-modal')
@include('admin.pages.minable-coins.partials.edit-modal')
@include('admin.pages.minable-coins.partials.delete-modal')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle edit button click
    $('.edit-coin-btn').click(function() {
        const coinId = $(this).data('id');

        $.ajax({
            url: `/king-admin/minable-coins/${coinId}/edit`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const coin = response.minableCoin;

                    $('#edit-coin-id').val(coin.id);
                    $('#edit-name').val(coin.name);
                    $('#edit-symbol').val(coin.symbol);
                    $('#edit-algorithm').val(coin.algorithm);
                    $('#edit-difficulty').val(coin.difficulty);
                    $('#edit-block_time').val(coin.block_time);
                    $('#edit-block_reward').val(coin.block_reward);
                    $('#edit-default_price').val(coin.default_price);
                    $('#edit-color').val(coin.color);
                    $('#edit-is_active').prop('checked', coin.is_active);

                    if (coin.logo_url) {
                        $('#current-logo').attr('src', coin.logo_url).show();
                        $('#remove-logo-wrapper').show();
                    } else {
                        $('#current-logo').hide();
                        $('#remove-logo-wrapper').hide();
                    }

                    $('#editMinableCoinModal').modal('show');
                }
            },
            error: function() {
                alert('Error loading coin data');
            }
        });
    });

    // Handle delete button click
    $('.delete-coin-btn').click(function() {
        const coinId = $(this).data('id');
        const coinName = $(this).data('name');

        $('#delete-coin-name').text(coinName);
        $('#delete-form').attr('action', `/king-admin/minable-coins/${coinId}`);
        $('#deleteMinableCoinModal').modal('show');
    });

    // Preview logo on file select
    $('#create-logo, #edit-logo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            const previewId = this.id === 'create-logo' ? 'create-logo-preview' : 'edit-logo-preview';

            reader.onload = function(e) {
                $(`#${previewId}`).attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush
