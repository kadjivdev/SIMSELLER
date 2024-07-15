@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>REPRESENTANTS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('representants.index') }}">Représentants</a></li>
                        <li class="breadcrumb-item active">Modifier représentant</li>
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
                                <h3 class="card-title">Modifier un representant</h3>
                            </div>
                            <form method="POST" action="{{ route('representants.update', ['representant' => $representant->id]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <img id="previewImg" src="@if ($representant->photo)
                                                    {{ asset('images')}}/{{ $representant->photo }}
                                                @else
                                                    {{asset('dist/img/default.jpg')}}
                                                @endif" style="max-width: 200px;"; />
                                                @error('photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="file">Photo</label>
                                                <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                @error('photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if($representant->photo)
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Supprimer l'image</label>
                                                    <input id="statut" type="checkbox"  name="remoov" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm  @error('matricule') is-invalid @enderror" name="matricule" style="text-transform: capitalize"  value="{{ @old('matricule')? @old('matricule'):$representant->matricule }}"  autocomplete="matricule" autofocus readonly required>
                                                @error('matricule')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Civilité<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="civilite" style="text-transform: capitalize; width:100%;">
                                                    <option value="{{ $representant->civilite }}" selected>{{ $representant->civilite }}</option>
                                                    <option value="Madame" @if (old('civilite') == "Madame") {{ 'selected' }} @endif>Madame</option>
                                                    <option value="Madémoiselle" @if (old('civilite') == "Madémoiselle") {{ 'selected' }} @endif>Madémoiselle</option>
                                                    <option value="Monsieur" @if (old('civilite') == "Monsieur") {{ 'selected' }} @endif>Monsieur</option>
                                                </select>
                                                @error('civilite')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Nom<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm  @error('nom') is-invalid @enderror" name="nom"  value="{{ @old('nom')? @old('nom'):$representant->nom }}"  autocomplete="nom" style="text-transform: uppercase" autofocus required>
                                                @error('nom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Prénom<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm  @error('prenom') is-invalid @enderror" name="prenom" style="text-transform: capitalize"  value="{{ @old('prenom')? @old('prenom'):$representant->prenom }}"  autocomplete="prenom" autofocus required>
                                                @error('prenom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Téléphone<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="number" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror"  value="{{ @old('telephone')? @old('telephone'):$representant->telephone }}" required autocomplete="telephone">
                                                    @error('telephone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Numéro Pro<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="number" name="telephonepro" class="form-control form-control-sm @error('telephonepro') is-invalid @enderror"  value="{{ @old('telephonepro')? @old('telephonepro'):$representant->telephonepro }}" required autocomplete="telephonepro">
                                                    @error('telephonepro')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>E-mail<span class="text-danger">*</span></label>
                                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ @old('email')? @old('email'):$representant->email }}" required autocomplete="email">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-3">
                                            <a href="{{ route('representants.index') }}" class="btn btn-sm btn-block btn-secondary">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>

                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-sm btn-warning btn-block ">
                                                {{ __('Modifier') }}
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </div>
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
