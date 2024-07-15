@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>PRODUITS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
                                <li class="breadcrumb-item active">Détail</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

                <!-- Main content -->
                <section class="content">
                    <div class="card card-solid">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-12 col-sm-3">
                            <h3 class="d-inline-block d-sm-none">{{ $produits->libelle }}</h3>
                            <div class="col-12">
                            <img src="@if ($produits->photo) {{ asset('images')}}/{{ $produits->photo }} @else {{asset('dist/img/ciment.jpg')}} @endif" class="product-image" style="max-width: 200px;"; alt="Product Image">
                            </div>
                            <!--<div class="col-12 product-image-thumbs">
                            <div class="product-image-thumb active"><img src="../../dist/img/prod-1.jpg" alt="Product Image"></div>
                            <div class="product-image-thumb" ><img src="../../dist/img/prod-2.jpg" alt="Product Image"></div>
                            <div class="product-image-thumb" ><img src="../../dist/img/prod-3.jpg" alt="Product Image"></div>
                            <div class="product-image-thumb" ><img src="../../dist/img/prod-4.jpg" alt="Product Image"></div>
                            <div class="product-image-thumb" ><img src="../../dist/img/prod-5.jpg" alt="Product Image"></div>
                            </div>-->
                        </div>
                            <div class="col-12 col-sm-9">
                                <h3 class="my-3">{{ $produits->libelle }}</h3>
                                <hr>
                                <h6>TYPE PRODUIT: <span class="h4">{{ $produits->typeproduit->libelle }}</span></h6>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <!--<label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_a2" autocomplete="off">
                                        Blue
                                        <br>
                                        <i class="fas fa-circle fa-2x text-blue"></i>
                                    </label>
                                    <label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_a3" autocomplete="off">
                                        Purple
                                        <br>
                                        <i class="fas fa-circle fa-2x text-purple"></i>
                                    </label>
                                    <label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_a4" autocomplete="off">
                                        Red
                                        <br>
                                        <i class="fas fa-circle fa-2x text-red"></i>
                                    </label>
                                    <label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_a5" autocomplete="off">
                                        Orange
                                        <br>
                                        <i class="fas fa-circle fa-2x text-orange"></i>
                                    </label>
                                    </div>

                                    <h4 class="mt-3">Size <small>Please select one</small></h4>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_b1" autocomplete="off">
                                        <span class="text-xl">S</span>
                                        <br>
                                        Small
                                    </label>
                                    <label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_b2" autocomplete="off">
                                        <span class="text-xl">M</span>
                                        <br>
                                        Medium
                                    </label>
                                    <label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_b3" autocomplete="off">
                                        <span class="text-xl">L</span>
                                        <br>
                                        Large
                                    </label>
                                    <label class="btn btn-default text-center">
                                        <input type="radio" name="color_option" id="color_option_b4" autocomplete="off">
                                        <span class="text-xl">XL</span>
                                        <br>
                                        Xtra-Large
                                    </label>
                                    </div>

                                    <div class="bg-gray py-2 px-3 mt-4">
                                    <h2 class="mb-0">
                                        $80.00
                                    </h2>
                                    <h4 class="mt-0">
                                        <small>Ex Tax: $80.00 </small>
                                    </h4>-->
                                </div>
                                <p>{{ $produits->description }}</p>

                                <div>
                                <a href="{{ route('produits.create') }}" class="btn btn-success btn-lg btn-flat">
                                    <i class="fas fa-solid fa-plus fa-lg mr-2"></i>
                                    Nouveau Produit
                                </a>

                                <a href="{{ route('produits.index') }}" class="btn btn-primary btn-lg btn-flat">
                                    <i class="nav-icon fa-brands fa-product-hunt fa-lg mr-2"></i>
                                    Liste des Produits
                                </a>
                                </div>

                                <!--<div class="mt-4 product-share">
                                <a href="#" class="text-gray">
                                    <i class="fab fa-facebook-square fa-2x"></i>
                                </a>
                                <a href="#" class="text-gray">
                                    <i class="fab fa-twitter-square fa-2x"></i>
                                </a>
                                <a href="#" class="text-gray">
                                    <i class="fas fa-envelope-square fa-2x"></i>
                                </a>
                                <a href="#" class="text-gray">
                                    <i class="fas fa-rss-square fa-2x"></i>
                                </a>
                                </div>-->

                            </div>
                        </div>
                        <div class="row mt-4">
                            <nav class="w-100">
                                <div class="nav nav-tabs" id="product-tab" role="tablist">
                                <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Fournisseurs</a>
                                <!--<a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Comments</a>
                                <a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">Rating</a>-->
                                </div>
                            </nav>
                            <div class="tab-content p-3 w-100" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                    <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Logo</th>
                                                <th>Sigle</th>
                                                <th>Raison Sociale</th>
                                                <th>Téléphone</th>
                                                <th>E-mail</th>
                                                <th>Catégorie</th>
                                                <th>Adresse</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($produits->count() > 0)
                                                <?php $compteur=1; ?>
                                                @foreach($produits->fournisseurs as $fournisseur)
                                                    <tr>
                                                        <td>{{ $compteur++ }}</td>
                                                        <td><img class="profile-user-img img-fluid img-circle" style="height: 50px; width: 50px"
                                                            src="@if ($fournisseur->logo)
                                                            {{ asset('images')}}/{{ $fournisseur->logo }}
                                                        @else
                                                            {{asset('dist/img/logo.png')}}
                                                        @endif"
                                                            alt="Profile Fournisseur"></td>
                                                        <td>{{ $fournisseur->sigle }}</td>
                                                        <td>{{ $fournisseur->raisonSociale }}</td>
                                                        <td>{{ $fournisseur->telephone }}</td>
                                                        <td>{{ $fournisseur->email }}</td>
                                                        <td>{{ $fournisseur->categoriefournisseur->libelle }}</td>
                                                        <td>{{ $fournisseur->adresse }}</td>
                                                        <td class="text-center">
                                                            <a class="btn btn-success btn-sm" href="{{ route('fournisseurs.show', ['id'=>$fournisseur->id]) }}"><i class="fa-regular fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Logo</th>
                                                <th>Sigle</th>
                                                <th>Raison Sociale</th>
                                                <th>Téléphone</th>
                                                <th>E-mail</th>
                                                <th>Catégorie</th>
                                                <th>Adresse</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab"> Vivamus rhoncus nisl sed venenatis luctus. Sed condimentum risus ut tortor feugiat laoreet. Suspendisse potenti. Donec et finibus sem, ut commodo lectus. Cras eget neque dignissim, placerat orci interdum, venenatis odio. Nulla turpis elit, consequat eu eros ac, consectetur fringilla urna. Duis gravida ex pulvinar mauris ornare, eget porttitor enim vulputate. Mauris hendrerit, massa nec aliquam cursus, ex elit euismod lorem, vehicula rhoncus nisl dui sit amet eros. Nulla turpis lorem, dignissim a sapien eget, ultrices venenatis dolor. Curabitur vel turpis at magna elementum hendrerit vel id dui. Curabitur a ex ullamcorper, ornare velit vel, tincidunt ipsum. </div>
                                <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"> Cras ut ipsum ornare, aliquam ipsum non, posuere elit. In hac habitasse platea dictumst. Aenean elementum leo augue, id fermentum risus efficitur vel. Nulla iaculis malesuada scelerisque. Praesent vel ipsum felis. Ut molestie, purus aliquam placerat sollicitudin, mi ligula euismod neque, non bibendum nibh neque et erat. Etiam dignissim aliquam ligula, aliquet feugiat nibh rhoncus ut. Aliquam efficitur lacinia lacinia. Morbi ac molestie lectus, vitae hendrerit nisl. Nullam metus odio, malesuada in vehicula at, consectetur nec justo. Quisque suscipit odio velit, at accumsan urna vestibulum a. Proin dictum, urna ut varius consectetur, sapien justo porta lectus, at mollis nisi orci et nulla. Donec pellentesque tortor vel nisl commodo ullamcorper. Donec varius massa at semper posuere. Integer finibus orci vitae vehicula placerat. </div>-->
                            </div>
                        </div>
                    </div>
                    </div>
                </section>
        </div>
    @endsection
