@use('Illuminate\Support\Facades\Storage')

@extends('client.components.app')

@section('page_title', $product->seo_title)
@section('styles')
  <link href="/assets/kingshop/assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet" />

@endsection
@section('content')
    @include('client.pages.product.partials.product_body')
@endsection
@section('scripts')
    <script src="/assets/kingshop/assets/libs/rater-js/index.js"></script>
    <script src="/assets/kingshop/assets/libs/dropzone/dist/min/dropzone.min.js"></script>
    <script src="/client/js/product-detail-cart.js"></script>
@endsection