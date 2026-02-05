@extends('client.components.app')
@section('titre')
    Erreur 404
@endsection
@section('page_css')

@endsection
@section('main_content')
    <div role="main" id="maincontent">
        <div class="experience">
            <div class="experience-component experience-man_layouts-1rowGenericCol">
                <div class="s-rich-text ">
                    <div class="container">
                        <div class="row justify-content-center">
                            <article class="col-12 col-lg-12">
                                <h3 style="text-align: center"> Erreur 404
                                </h3>
                                <h6 style="text-align: center">Page introuvable</h6>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
            <div class="experience-component experience-man_layouts-1rowGenericCol">
                <div class="s-rich-text pb-md-6">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-12 col-lg-12">
                                <div style="text-align: center">La page que vous recherchez n'existe pas ; elle a peut-être
                                    été déplacée ou supprimée.</div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
    @section('page_js')

    @endsection