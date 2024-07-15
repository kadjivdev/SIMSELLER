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
                            <li class="breadcrumb-item active">Modification produis</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification de la Photo</h3>
                            </div>
                            <form method="POST" action="{{ route('produits.photo') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" name="id" value="{{ $produits->id }}" />
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <img id="previewImg"  src="@if ($produits->photo) {{ asset('images')}}/{{ $produits->photo }} @else {{asset('dist/img/ciment.jpg')}} @endif" alt="logo du fournisseur" style="max-width: 200px;"; />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                @error('photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                      
                                    </div>
                                    @if($produits->photo)
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Supprimer l'image</label>
                                                    <input id="statut" type="checkbox"  name="remoov" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('produits.index') }}" class="btn btn-sm btn-block btn-secondary">
                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-warning btn-block">
                                                {{ __('Modifier') }}
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification produit</h3>
                            </div>
                            <form method="POST" action="{{ route('produits.update') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" name="id" value="{{ $produits->id }}" />
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm @error('libelle') is-invalid @enderror" name="libelle"  value="{{ @old('libelle')? @old('libelle'):$produits->libelle }}"  autocomplete="off" style="text-transform: uppercase" autofocus required>
                                                @error('libelle')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2 @error('type_produit_id') is-invalid @enderror" style="width: 100%;" name="type_produit_id">
                                                    <option selected="selected" value="{{ @old('type_produit_id')? @old('type_produit_id'):$produits->typeproduit->id }}">{{ $produits->typeproduit->libelle }}</option>
                                                    @foreach($typeproduits as $typeproduit)
                                                        <option value="{{ $typeproduit->id }}" {{ old('type_produit_id') == $typeproduit->id ? 'selected' : '' }}>{{ $typeproduit->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('categorie_fournisseur_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                           
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Prix Fournisseur<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm @error('prix_fourniture') is-invalid @enderror" name="prix_fourniture"  value="{{ $produits->prix_fourniture }}"  autocomplete="off" style="text-transform: uppercase" autofocus required>
                                                @error('prix_fourniture')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control form-control-sm  @error('description') is-invalid @enderror" name="description" id="exampleFormControlTextarea1" style="text-transform: capitalize"  autocomplete="off" autofocus rows="3">{{ @old('description')? @old('adresse'):$produits->description }}</textarea>
                                                @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('produits.index') }}" class="btn btn-sm btn-block btn-secondary">
                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-warning btn-block">
                                                {{ __('Modifier') }}
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;
