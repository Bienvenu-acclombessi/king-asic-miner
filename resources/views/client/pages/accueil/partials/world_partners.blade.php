<section class="my-lg-14 my-8">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-8">
                <h2 class="mb-4">Global Partners</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div id="partnersCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                    <!-- Carousel Inner -->
                    <div class="carousel-inner">
                        <!-- Slide 1 -->
                        <div class="carousel-item active">
                            <div class="row g-4 justify-content-center">
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/antminer.webp') }}" alt="Antminer" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/bitdeer.webp') }}" alt="Bitdeer" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/bombax.webp') }}" alt="Bombax" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/canaan.webp') }}" alt="Canaan" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/dragonball-miner.webp') }}" alt="Dragonball Miner" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="carousel-item">
                            <div class="row g-4 justify-content-center">
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/elphapex.webp') }}" alt="Elphapex" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/fog-hashing.webp') }}" alt="Fog Hashing" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/goldshell.webp') }}" alt="Goldshell" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/ibelink.webp') }}" alt="Ibelink" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/iceriver.webp') }}" alt="IceRiver" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="carousel-item">
                            <div class="row g-4 justify-content-center">
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/ipollo.webp') }}" alt="Ipollo" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/kryptex.webp') }}" alt="Kryptex" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/whatsminer.webp') }}" alt="Whatsminer" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="partner-card">
                                        <img src="{{ asset('assets/kingshop/assets/images/world-partners/woolypooly.webp') }}" alt="Woolypooly" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Indicators -->
                    <div class="carousel-indicators position-relative mt-5">
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.partner-card {
    transition: all 0.3s ease;
}

.partner-card:hover {
    transform: translateY(-5px);
}

.partner-card img {
    width: 100%;
    height: auto;
    display: block;
}

.carousel-indicators {
    bottom: auto !important;
    margin-bottom: 0;
}

.carousel-indicators [data-bs-target] {
    width: 40px;
    height: 8px;
    border-radius: 10px;
    background-color: transparent;
    border: 2px solid #ddd;
    opacity: 1;
    margin: 0 5px;
}

.carousel-indicators .active {
    background-color: #000;
    border-color: #000;
}
</style>