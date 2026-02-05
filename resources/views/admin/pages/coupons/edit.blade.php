@extends('admin.components.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="container-fluid">
    <div class="row mb-8">
        <div class="col-md-12">
            <h2>Edit Coupon</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
                    <li class="breadcrumb-item active">Edit: {{ $coupon->code }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header"><h4 class="mb-0">Coupon Details</h4></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $coupon->code) }}" required style="text-transform: uppercase;">
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="discount" class="form-control @error('discount') is-invalid @enderror"
                                       value="{{ old('discount', $coupon->discount) }}" required>
                                @error('discount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Min Order Amount</label>
                                <input type="number" step="0.01" name="min_order_amount" class="form-control @error('min_order_amount') is-invalid @enderror"
                                       value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                                @error('min_order_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max Discount Amount</label>
                                <input type="number" step="0.01" name="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror"
                                       value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                                @error('max_discount_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valid From</label>
                                <input type="datetime-local" name="valid_from" class="form-control @error('valid_from') is-invalid @enderror"
                                       value="{{ old('valid_from', $coupon->valid_from?->format('Y-m-d\TH:i')) }}">
                                @error('valid_from')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valid To</label>
                                <input type="datetime-local" name="valid_to" class="form-control @error('valid_to') is-invalid @enderror"
                                       value="{{ old('valid_to', $coupon->valid_to?->format('Y-m-d\TH:i')) }}">
                                @error('valid_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Usage Limit</label>
                            <input type="number" name="usage_limit" class="form-control @error('usage_limit') is-invalid @enderror"
                                   value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Leave empty for unlimited">
                            @error('usage_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">Used: {{ $coupon->used_count ?? 0 }} times</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header"><h4 class="mb-0">Status</h4></div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Coupon</button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">Delete Coupon</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
