@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>FOURNISSEURS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseurs</a></li>
                            <li class="breadcrumb-item active">Nouveau fournisseur</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau fournisseur</h3>
                            </div>
                            <form method="POST" action="{{ route('fournisseurs.store') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="file">Logo</label>
                                                <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                <img id="previewImg" style="max-width: 130px; margin-top:20px"; />
                                                @error('photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Sigle<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm @error('sigle') is-invalid @enderror" name="sigle"  value="{{ old('sigle') }}"  autocomplete="sigle" style="text-transform: uppercase" autofocus required>
                                                @error('sigle')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Raison Sociale<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="raisonSociale" style="text-transform: uppercase"  value="{{ old('raisonSociale') }}"  autocomplete="raisonSocial" autofocus required>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Catégorie<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('categorie_fournisseur_id') is-invalid @enderror" name="categorie_fournisseur_id" style="width: 100%;">
                                                    <option selected disabled>** choisir catégorie fournisseur **</option>
                                                    @foreach($categoriefournisseurs as $categoriefournisseur)
                                                        <option value="{{ $categoriefournisseur->id }}" {{ old('categorie_fournisseur_id') == $categoriefournisseur->id ? 'selected' : '' }}>{{ $categoriefournisseur->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('categorie_fournisseur_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Téléphone<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}" required autocomplete="telephone">
                                                    @error('telephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>E-mail<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                                                    </div>
                                                    <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                                    @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Adresse<span class="text-danger">*</span></label>
                                                <textarea class="form-control form-control-sm  @error('adresse') is-invalid @enderror" name="adresse" id="exampleFormControlTextarea1" style="text-transform: capitalize"  autocomplete="adresse" autofocus rows="3">{{ old('adresse') }}</textarea>
                                                @error('adresse')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <a href="{{ route('fournisseurs.index') }}" class="btn btn-sm btn-secondary">
                                                {{ __('Retour') }}
                                            </a>

                                            <button type="submit" class="btn btn-sm btn-success">
                                                {{ __('Enregistrer') }}
                                            </button>
                                        </div>
                                        <div class="col-sm-4"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;
