@extends('client.pages.account.layouts.base')

@section('page_title') Modifier Mot de passe @endsection

@section('content')

    <div class="py-6 p-md-6 p-lg-10">
        <div class="mb-6">
            <!-- heading -->
            <h2 class="mb-0">Modifier son mot de passe</h2>
        </div>
        <div>

            <div class="pe-lg-14">
                <!-- heading -->
                <h5 class="mb-4">Password</h5>
                <form class=" row row-cols-1 row-cols-lg-2">
                    <!-- input -->
                    <div class="mb-3 col">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" placeholder="**********">
                    </div>
                    <!-- input -->
                    <div class="mb-3 col">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" placeholder="**********">
                    </div>
                    <!-- input -->
                    <div class="col-12">
                        <p class="mb-4">Can’t remember your current password?<a href="#"> Reset your password.</a></p>
                        <a href="#" class="btn btn-primary">Save Password</a>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection