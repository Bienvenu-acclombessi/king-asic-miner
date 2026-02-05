<style>
    .youtube-video-container {
        position: relative;
        cursor: pointer;
        overflow: hidden;
    }
    
    .youtube-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .youtube-video-container:hover .youtube-thumbnail {
        transform: scale(1.05);
    }
    
    .play-button-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70px;
        height: 70px;
        border: 3px solid white;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 2;
    }
    
    .play-button-overlay::after {
        content: '';
        width: 0;
        height: 0;
        border-left: 20px solid white;
        border-top: 12px solid transparent;
        border-bottom: 12px solid transparent;
        margin-left: 5px;
    }
    
    .youtube-video-container:hover .play-button-overlay {
        transform: translate(-50%, -50%) scale(1.15);
        background: rgba(0, 0, 0, 0.6);
    }
    
    .youtube-video-container iframe {
        display: none;
    }
    
    .youtube-video-container.playing iframe {
        display: block;
    }
    
    .youtube-video-container.playing .youtube-thumbnail,
    .youtube-video-container.playing .play-button-overlay {
        display: none;
    }
</style>

<section class="my-lg-14 my-8">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-8">
                <h2 class="mb-4">YouTube Partners</h2>
            </div>
        </div>
        
        <!-- YouTube Partners Grid -->
        <div class="row g-2 mb-8">
            <!-- Large Video (2/5 width) -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="ratio ratio-16x9 h-100 w-100">
                    <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Red Panda Mining" src="https://www.youtube.com/embed/UZ_vGR_Pwzo?controls=1&rel=0" loading="lazy"></iframe>
                </div>
            </div>
            
            <!-- Small Videos Grid (3/5 width) -->
            <div class="col-lg-8 col-md-6 col-12">
                <div class="row g-2">
                    <!-- Row 1 -->
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="ratio ratio-16x9 w-100">
                            <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="VoskCoin - Bitmain Antminer S21" src="https://www.youtube.com/embed/jkjTy9XI7CU?controls=1&rel=0" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="ratio ratio-16x9 w-100">
                            <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Hobbyist Miner" src="https://www.youtube.com/embed/QjoG9X25hPg?controls=1&rel=0" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="ratio ratio-16x9 w-100">
                            <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Rabid Mining AL0" src="https://www.youtube.com/embed/cXM2g8BnpWU?controls=1&rel=0" loading="lazy"></iframe>
                        </div>
                    </div>
                    
                    <!-- Row 2 -->
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="ratio ratio-16x9 w-100">
                            <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Kaspa Mining" src="https://www.youtube.com/embed/smICwMX0v6s?controls=1&rel=0" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="ratio ratio-16x9 w-100">
                            <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="ViaBTC" src="https://www.youtube.com/embed/UtwuMPBXGDA?controls=1&rel=0" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-6">
                        <div class="ratio ratio-16x9 w-100">
                            <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Handshake Mining" src="https://www.youtube.com/embed/tHtKRJ2-tz0?controls=1&rel=0" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apexto Daily Updates Section -->
        <div class="row">
            <div class="col-12 mb-2">
                <h4>Apexto Daily Updates</h4>
            </div>
        </div>
        
        <!-- Updates Videos (5 videos in one row) -->
        <div class="row g-2">
            <div class="col-lg col-md-4 col-6">
                <div class="ratio ratio-16x9 w-100">
                    <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Bitaxe NerdOCTaxe BTC Solo Mining" src="https://www.youtube.com/embed/8bvqKtZWmKs?controls=1&rel=0" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-lg col-md-4 col-6">
                <div class="ratio ratio-16x9 w-100">
                    <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Avalon Q" src="https://www.youtube.com/embed/-4gHLGTi8XQ?controls=1&rel=0" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-lg col-md-4 col-6">
                <div class="ratio ratio-16x9 w-100">
                    <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="VolcMiner D1 Hydro" src="https://www.youtube.com/embed/_pEVCiBNDIs?controls=1&rel=0" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-lg col-md-4 col-6">
                <div class="ratio ratio-16x9 w-100">
                    <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Antminer L9" src="https://www.youtube.com/embed/Hhfi9Ksd2LE?controls=1&rel=0" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-lg col-md-4 col-6">
                <div class="ratio ratio-16x9 w-100">
                    <iframe class="rounded w-100" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="Apexto Hydro-Cooling Products" src="https://www.youtube.com/embed/ORoH4XoNoYc?controls=1&rel=0" loading="lazy"></iframe>
                </div>
            </div>
        </div>
        
    </div>
</section>