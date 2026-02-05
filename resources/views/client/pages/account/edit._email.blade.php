
@extends('client.pages.account.layouts.base')

@section('page_title') Editer Email @endsection

@section('content')

<div class="py-6 p-md-6 p-lg-10">
            <div class="mb-6">
              <!-- heading -->
              <h2 class="mb-0">Paramètre de email</h2>
            </div>
            <div>
              <!-- heading -->
              <h5 class="mb-4">Modifier votre email</h5>
              <div class="row">
                <div class="col-lg-5">
                  <!-- form -->
                  <form>
                    <!-- input -->
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" readonly class="form-control" placeholder="example@gmail.com">
                    </div>

                    <div class="alert alert-info">Vousallez recevoir un mail pour la confirmation de votre mail.</div>
                    
                    <!-- button -->
                    <div class="mb-3">
                      <button class="btn btn-primary">Save Details</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>


@endsection


