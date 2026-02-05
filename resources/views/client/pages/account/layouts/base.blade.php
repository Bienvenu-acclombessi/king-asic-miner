@extends('client.components.app')

@section('page_title')
    @yield('page_title', 'Paramètres de compte - King ASIC Miner')
@endsection

@section('content')

    <section>
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- col -->
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center d-md-none py-4">
                        <!-- heading -->
                        <h3 class="fs-5 mb-0">Account Setting</h3>
                        <!-- button -->
                        <button class="btn btn-outline-gray-400 text-muted d-md-none btn-icon btn-sm ms-3 " type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasAccount" aria-controls="offcanvasAccount">
                            <i class="bi bi-text-indent-left fs-3"></i>
                        </button>
                    </div>
                </div>
                <!-- col -->
                <div class="col-lg-3 col-md-4 col-12 border-end  d-none d-md-block">
                    @include('client.pages.account.layouts.side')
                </div>
                <div class="col-lg-9 col-md-8 col-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>
    @include('client.pages.account.layouts.offcanvas')


@endsection