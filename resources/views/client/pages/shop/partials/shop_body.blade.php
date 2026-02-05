
  <!-- section-->
  <div class="mt-4">
    <div class="container">
      <!-- row -->
      <div class="row ">
        <!-- col -->
        <div class="col-12">
          <!-- breadcrumb -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="/">Home</a></li>
              <li class="breadcrumb-item active"><a href="#!">Shop</a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <!-- section -->
  <div class=" mt-8 mb-lg-14 mb-8">
    <!-- container -->
    <div class="container">
        @include('client.pages.shop.partials.category_list')
      <!-- row -->
      <div class="row gx-10">
        <!-- col -->
        @include('client.pages.shop.partials.filter')
        <section class="col-lg-9 col-md-12">
        @include('client.pages.shop.partials.product_list')
        </section>
      </div>
    </div>
  </div>