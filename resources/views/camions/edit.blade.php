@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>CAMIONS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('camions.index') }}">Camions</a></li>
                        <li class="breadcrumb-item active">Modification camion</li>
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
                                <h3 class="card-title">Modification de la photo</h3>
                            </div>
                            <form method="POST" action="{{ route('camions.photo') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" name="id" value="{{ $camions->id }}" />
                                    <div class="row text-center">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <img id="previewImg" class="" style="height: 150px; width: 150px"
                                                     src="@if ($camions->photo)
                                                     {{ asset('images')}}/{{ $camions->photo }}
                                                     @else
                                                     {{asset('dist/img/camion.jpg')}}
                                                     @endif"
                                                     alt="Photo camion"  onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror"value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                @error('photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($camions->photo)
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <div class="form-group">
                                                <label for="statut" style="display: block">Supprimer l'image</label>
                                                <input id="statut" type="checkbox"  name="remoov" style="width: 20px; height: 20px"  />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('camions.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
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
                                <h3 class="card-title">Modification camion</h3>
                            </div>
                            <form method="POST" action="{{ route('camions.update') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" name="id" value="{{ $camions->id }}" />
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Marque<span class="text-danger"></span></label>
                                                <select class="select2 form-control form-control-sm @error('marque_id') is-invalid @enderror" name="marque_id" style="width: 100%;">
                                                    <option value="{{ $camions->marque->id }}" selected>{{ $camions->marque->libelle }}</option>
                                                    @foreach($marques as $marque)
                                                        <option value="{{ $marque->id }}" {{ old('marque_id') == $marque->id ? 'selected' : '' }}>{{ $marque->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('marque_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Nombre Issieu<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="nombreIssieu" style="text-transform: uppercase"  value="{{ @old('nombreIssieu')? @old('nombreIssieu'):$camions->nombreIssieu }}"  autocomplete="nombreIssieu" min="1" autofocus required>
                                                @error('nombreIssieu')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Tonnage<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="tonnage" style="text-transform: uppercase"  value="{{ @old('tonnage')? @old('nombreIssieu'):$camions->tonnage }}"  autocomplete="tonnage" min="1" autofocus required>
                                                @error('tonnage')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Immatriculation Tracteur<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="immatriculationTracteur" style="text-transform: uppercase"  value="{{ @old('immatriculationTracteur')? @old('immatriculationTracteur'):$camions->immatriculationTracteur }}"  autocomplete="immatriculationTracteur" autofocus required>
                                                @error('immatriculationTracteur')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Immatriculation Remorque<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="immatriculationRemorque" style="text-transform: uppercase"  value="{{ @old('immatriculationRemorque')? @old('immatriculationRemorque'):$camions->immatriculationRemorque }}"  autocomplete="immatriculationRemorque" autofocus required>
                                                @error('immatriculationRemorque')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Avaliseurs</label>
                                                <select class="select2 form-control form-control-sm @error('avaliseur_id') is-invalid @enderror" name="avaliseur_id" style="width: 100%;">
                                                    <option value="{{ $camions->avaliseur->id }}" selected>{{ $camions->avaliseur->nom }} {{  $camions->avaliseur->prenom  }}</option>
                                                    @foreach($avaliseurs as $avaliseur)
                                                        <option value="{{ $avaliseur->id }}" {{ old('avaliseur_id') == $avaliseur->id ? 'selected' : '' }}>{{ $avaliseur->nom }} {{ $avaliseur->prenom }}</option>
                                                    @endforeach
                                                </select>
                                                @error('avaliseur_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Chauffeurs</label><span class="text-danger">*</span>
                                                <select class="select2 form-control form-control-sm @error('chauffeur_id') is-invalid @enderror" name="chauffeur_id" style="width: 100%;">
                                                    <option selected disabled>** choisissez un chauffeur **</option>
                                                    @foreach($chauffeurs as $chauffeur)
                                                        <option value="{{ $chauffeur->id }}" @if(old('chauffeur_id')) {{ old('chauffeur_id') == $chauffeur->id ? 'selected' : '' }} @else {{ $camions->chauffeur_id == $chauffeur->id ? 'selected' : '' }} @endif>{{ $chauffeur->nom }} {{ $chauffeur->prenom }}</option>
                                                    @endforeach
                                                </select>
                                                @error('chauffeur_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <div class="form-group">
                                                <label for="statut" style="display: block">Activer</label>
                                                <input id="statut" type="checkbox" @if(old('statut')) checked @else {{$camions->statut == 'Actif' ? 'checked' : ''}} @endif name="statut" style="width: 20px; height: 20px"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer mb-5">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('camions.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
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
                    <div class="col-md-1"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;

