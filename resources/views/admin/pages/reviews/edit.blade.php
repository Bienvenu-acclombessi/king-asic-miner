@extends('admin.components.app')
@section('title', 'Edit Review')

@section('content')
<div class="container-fluid">
    <div class="row mb-8">
        <div class="col-md-12">
            <h2>Edit Review</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h4 class="mb-0">Review Details</h4></div>
                <div class="card-body">
                    <table class="table table-borderless mb-4">
                        <tr>
                            <th width="150">Product:</th>
                            <td><a href="{{ route('admin.products.show', $review->product) }}">{{ $review->product->name }}</a></td>
                        </tr>
                        <tr>
                            <th>Customer:</th>
                            <td>{{ $review->user->name ?? 'Guest' }}</td>
                        </tr>
                        <tr>
                            <th>Rating:</th>
                            <td>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $review->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>

                    <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Comment</label>
                            <textarea name="comment" rows="4" class="form-control @error('comment') is-invalid @enderror">{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_approved" value="1" {{ old('is_approved', $review->is_approved) ? 'checked' : '' }}>
                            <label class="form-check-label">Approved</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Review</button>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
