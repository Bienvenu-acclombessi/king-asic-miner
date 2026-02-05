@extends('client.components.app')

@section('page_title', 'Accueil - Apexto - Fournisseur d\'Or de Mineurs ASIC')

@section('content')

@include('client.pages.accueil.partials.banner')
<!-- Liste de produit-->
@include('client.pages.accueil.partials.product_list')
<!-- Liste de produit -->

<!-- Liste de product slide-->
@include('client.pages.accueil.partials.products_slide')
<!-- Liste de produit -->

<!-- Liste de product par marque -->
@include('client.pages.accueil.partials.product_per_mark')
<!-- Liste de marque -->

<!-- Pourquoinous choisis -->
@include('client.pages.accueil.partials.why_choose')
<!-- Pourquoinous choisis  -->

<!-- Ressources minière en 4 -->
@include('client.pages.accueil.partials.ressources_mining')

<!-- Ressources minière en 4  -->

<!-- Equipe -->
@include('client.pages.accueil.partials.team')
<!-- Fin Equipe  -->


<!-- Partners youtube -->
@include('client.pages.accueil.partials.youtube_partners')
<!-- Fin Partner youtube  -->


<!-- Partners world -->
@include('client.pages.accueil.partials.world_partners')
<!-- Fin Partner world  -->

<!-- Blog Apexto -->
@include('client.pages.accueil.partials.blog_list')
<!-- Blog Apexto -->

@endsection