{{-- Categories List Section --}}
@php
$categories = [
    [
        'name' => 'Solo Miner',
        'image' => 'https://apextomining.com/apexto/uploads/2025/05/solo-miner-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'solo-miner'])
    ],
    [
        'name' => 'Bitmain Antminer',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/8-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'bitmain-antminer'])
    ],
    [
        'name' => 'IceRiver',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/1-2-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'iceriver'])
    ],
    [
        'name' => 'Whatsminer',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/2-2-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'whatsminer'])
    ],
    [
        'name' => 'Elphapex',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/4564-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'elphapex'])
    ],
    [
        'name' => 'Goldshell',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/4-2-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'goldshell'])
    ],
    [
        'name' => 'Jasminer',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/3-1-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'jasminer'])
    ],
    [
        'name' => 'Canaan Avalon',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/canaan-Avalon-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'canaan-avalon'])
    ],
    [
        'name' => 'Volcminer',
        'image' => 'https://apextomining.com/apexto/uploads/2024/11/VOLCMINER-1-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'volcminer'])
    ],
    [
        'name' => 'BitDeer',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/BitDeer-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'bitdeer'])
    ],
    [
        'name' => 'Fluminer',
        'image' => 'https://apextomining.com/apexto/uploads/2024/11/Fluminer-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'fluminer'])
    ],
    [
        'name' => 'iPollo',
        'image' => 'https://apextomining.com/apexto/uploads/2024/06/5-1-70x70.png',
        'link' => route('client.shop.by_category', ['category' => 'ipollo'])
    ],
];
@endphp

<section class="categories-section">
    <div class="categories-grid">
        @foreach($categories as $category)
            @include('client.pages.shop.partials.category_card', [
                'name' => $category['name'],
                'image' => $category['image'],
                'link' => $category['link']
            ])
        @endforeach
    </div>
</section>

<style>
.categories-section {
    padding: 20px 0;
}

.categories-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-start;
}

.category-card {
    display: inline-flex;
    align-items: center;
    border: 1px solid #d1d1d1;
    background: #fff;
    padding: 0 10px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-card:hover {
    border-color: #f7941d;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.category-card-image {
    width: 70px;
    height: 70px;
    margin: 0 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-card-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.category-card-name {
    color: #000;
    margin: 0 10px;
    font-weight: 500;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .categories-grid {
        display: none;
    }
}
</style>
