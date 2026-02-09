@extends('admin.components.app')

@section('title', 'Edit Product - Wizard')

@push('styles')
<link rel="stylesheet" href="{{ asset('admin/css/product-wizard.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Edit Product: {{ $product->name }}</h2>
                    <p class="text-muted mb-0">Follow the steps to update your product</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Products
                </a>
            </div>

            <!-- Progress Bar -->
            @include('admin.pages.products.wizard.components.progress-bar')

            <!-- Wizard Form -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form id="wizardForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <!-- Step 1: Basics -->
                        <div class="wizard-step" data-step="1">
                            @include('admin.pages.products.edit.step1-basics')
                        </div>

                        <!-- Step 2: Options -->
                        <div class="wizard-step" data-step="2" style="display: none;">
                            @include('admin.pages.products.edit.step2-options')
                        </div>

                        <!-- Step 3: Variants -->
                        <div class="wizard-step" data-step="3" style="display: none;">
                            @include('admin.pages.products.edit.step3-variants')
                        </div>

                        <!-- Step 4: Pricing -->
                        <div class="wizard-step" data-step="4" style="display: none;">
                            @include('admin.pages.products.edit.step4-pricing')
                        </div>

                        <!-- Step 5: Media -->
                        <div class="wizard-step" data-step="5" style="display: none;">
                            @include('admin.pages.products.edit.step5-media')
                        </div>

                        <!-- Step 6: Finalize -->
                        <div class="wizard-step" data-step="6" style="display: none;">
                            @include('admin.pages.products.edit.step6-finalize')
                        </div>

                        <!-- Navigation -->
                        @include('admin.pages.products.wizard.components.step-navigation')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast for auto-save notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="autoSaveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Changes Saved</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Your changes have been saved automatically.
        </div>
    </div>
</div>

<!-- Create Product Option Modal (For Wizard) -->
@include('admin.pages.products.wizard.modals.create-product-option')
@endsection

@push('scripts')
<script>
    // Pass data from Laravel to JavaScript
    window.wizardData = {
        editMode: true,
        productId: {{ $product->id }},
        productData: @json($productData),
        productTypes: @json($productTypes),
        brands: @json($brands),
        collections: @json($collections),
        tags: @json($tags),
        customerGroups: @json($customerGroups),
        productOptions: @json($productOptions),
        updateUrl: "{{ route('admin.products.update', $product) }}",
        csrfToken: "{{ csrf_token() }}"
    };
</script>
<script src="{{ asset('admin/js/wizard/step-manager.js') }}"></script>
<script src="{{ asset('admin/js/wizard/variant-generator.js') }}"></script>
<script src="{{ asset('admin/js/wizard/price-calculator.js') }}"></script>
<script src="{{ asset('admin/js/wizard/form-persistence.js') }}"></script>
<script src="{{ asset('admin/js/product-wizard-edit.js') }}"></script>
@endpush
