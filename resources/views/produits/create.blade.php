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
                            <li class="breadcrumb-item active">Nouveau produis</li>
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
                                <h3 class="card-title">Nouveau produits</h3>
                            </div>
                            <form method="POST" action="{{ route('produits.store') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="file">Photo</label>
                                                <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                <img id="previewImg" style="max-width: 130px; margin-top:20px"; />
                                                @error('photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Prix Fournisseur<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm @error('prix_fourniture') is-invalid @enderror" name="prix_fourniture"  value="{{ old('prix_fourniture') }}"  autocomplete="off" style="text-transform: uppercase" autofocus required>
                                                @error('prix_fourniture')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm @error('libelle') is-invalid @enderror" name="libelle"  value="{{ old('libelle') }}"  autocomplete="off" style="text-transform: uppercase" autofocus required>
                                                @error('libelle')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('typeproduit') is-invalid @enderror" name="typeproduit" style="width: 100%;">
                                                    <option selected disabled>** choisir type produit **</option>
                                                    @foreach($typeproduits as $typeproduit)
                                                        <option value="{{ $typeproduit->id }}" {{ old('typeproduit') == $typeproduit->id ? 'selected' : '' }}>{{ $typeproduit->libelle }}</option>
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
                                                <label>Description</label>
                                                <textarea class="form-control form-control-sm  @error('description') is-invalid @enderror" name="description" id="exampleFormControlTextarea1" style="text-transform: capitalize"  autocomplete="off" autofocus rows="3">{{ old('description') }}</textarea>
                                                @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-3">
                                            <a href="{{ route('produits.index') }}" class="btn btn-sm btn-block btn-secondary">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-sm btn-block btn-success">
                                                {{ __('Enregistrer') }}
                                                <i class="fa-solid fa-floppy-disk"></i>
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

@endsection
