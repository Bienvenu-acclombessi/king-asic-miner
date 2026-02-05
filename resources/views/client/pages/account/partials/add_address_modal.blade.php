
  <!-- Modal -->
  <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <!-- modal content -->
      <div class="modal-content">
<!-- modal body -->
        <div class="modal-body p-6">
          <div class="d-flex justify-content-between mb-5">
            <div>
              <!-- heading -->
              <h5 class="mb-1" id="addAddressModalLabel">New Shipping Address</h5>
              <p class="small mb-0">Add new shipping address for your order delivery.</p>
            </div>
            <div>
              <!-- button -->
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          </div>
          <!-- row -->
          <div class="row g-3">
            <!-- col -->
            <div class="col-12">
               <!-- input -->
              <input type="text" class="form-control" placeholder="First name" aria-label="First name" required="">
            </div>
             <!-- col -->
            <div class="col-12">
               <!-- input -->
              <input type="text" class="form-control" placeholder="Last name" aria-label="Last name" required="">
            </div>
 <!-- col -->
            <div class="col-12">
 <!-- input -->
              <input type="text" class="form-control" placeholder="Address Line 1">
            </div>
             <!-- col -->
            <div class="col-12">
 <!-- input -->
              <input type="text" class="form-control" placeholder="Address Line 2">
            </div>
             <!-- col -->
            <div class="col-12">
 <!-- input -->
              <input type="text" class="form-control" placeholder="City">
            </div>
             <!-- col -->
            <div class="col-12">
 <!-- form select -->
              <select class="form-select">
                <option selected=""> India</option>
                <option value="1">UK</option>
                <option value="2">USA</option>
                <option value="3">UAE</option>
              </select>
            </div>
             <!-- col -->
            <div class="col-12">
 <!-- form select -->
              <select class="form-select">
                <option selected="">Gujarat</option>
                <option value="1">Northern Ireland</option>
                <option value="2"> Alaska</option>
                <option value="3">Abu Dhabi</option>
              </select>
            </div>
             <!-- col -->
            <div class="col-12">
 <!-- input -->
              <input type="text" class="form-control" placeholder="Zip Code">
            </div>
             <!-- col -->
            <div class="col-12">
 <!-- input -->
              <input type="text" class="form-control" placeholder="Business Name">
            </div>
             <!-- col -->
            <div class="col-12">
               <!-- form check -->
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  Set as Default
                </label>
              </div>
            </div>
             <!-- col -->
            <div class="col-12 text-end">
              <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-primary" type="button">Save Address</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>