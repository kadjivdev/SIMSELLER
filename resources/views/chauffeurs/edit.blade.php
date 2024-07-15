@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>CHAUFFEURS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('chauffeurs.index') }}">Chauffeurs</a></li>
                        <li class="breadcrumb-item active">Modification chauffeur</li>
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
                            <form method="POST" action="{{ route('chauffeurs.photo') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" name="id" value="{{ $chauffeurs->id }}" />
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <img id="previewImg" src="@if ($chauffeurs->photo)
                                                    {{ asset('images')}}/{{ $chauffeurs->photo }}
                                                @else
                                                    {{asset('dist/img/default.jpg')}}
                                                @endif" style="max-width: 200px;"; />
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
                                    @if($chauffeurs->photo)
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
                                            <a href="{{ route('chauffeurs.index') }}" class="btn btn-sm btn-secondary btn-block">
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
                                <h3 class="card-title">Modification chauffeur</h3>
                            </div>
                            <form method="POST" action="{{ route('chauffeurs.update') }}"  enctype="multipart/form-data">
                                @csrf
                                    <div class="card-body">
                                        <input type="hidden" name="id" value="{{ $chauffeurs->id }}" />
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label>Nom<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm" name="nom" style="text-transform: uppercase"  value="{{ @old('nom')? @old('nom'):$chauffeurs->nom }}"  autocomplete="nom" autofocus required>
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="form-group">
                                                    <label>Prénom<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm" name="prenom" style="text-transform: capitalize"  value="{{ @old('prenom')? @old('prenom'):$chauffeurs->prenom }}"  autocomplete="prenom" autofocus required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date Naissance<span class="text-danger"></span></label>
                                                    <input type="date" id="dateNaissance" class="form-control form-control-sm @error('dateNaissance') is-invalid @enderror" name="dateNaissance"  value="{{ @old('dateNaissance')? @old('dateNaissance'): $chauffeurs->dateNaissance }}"  autocomplete="dateNaissance" autofocus >
                                                    @error('dateNaissance')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Téléphone<span class="text-danger"></span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                            </div>
                                                            <input type="text" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror" data-mask value="{{ @old('telephone')? @old('telephone'):$chauffeurs->telephone }}"  autocomplete="telephone" min="8">
                                                            @error('telephone')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Numero Permis<span class="text-danger"></span></label>
                                                    <input type="text" class="form-control form-control-sm" name="numero" style="text-transform: uppercase"  value="{{ @old('numero')? @old('numero'):$chauffeurs->numero }}"  autocomplete="numero" autofocus >
                                                    @error('numero')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="@if($chauffeurs->permis) col-sm-5 @else col-sm-8 @endif">
                                                <div class="form-group">
                                                    <label for="file">Document Permis<span class="text-danger"></span></label>
                                                    <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" data-mask value="{{ old('document') }}" >
                                                    @error('document')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            @if($chauffeurs->permis)
                                            <div class="col-sm-3" style="margin-top: 2em">
                                                <a  class="btn btn-success btn-block text-white btn-sm" href="{{ $chauffeurs->permis?asset('storage/'.$chauffeurs->permis):'' }}" target="_blank"><i class="fa-solid fa-file-pdf mr-2"></i><b>{{ $chauffeurs->numero }}</b></a>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="@if($chauffeurs->permis)col-sm-6 @else col-sm-12 @endif text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Activer</label>
                                                    <input id="statut" type="checkbox" @if(old('statut')) checked @else {{$chauffeurs->statut == 'Actif' ? 'checked' : ''}} @endif name="statut" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                            @if($chauffeurs->permis)
                                            <div class="col-sm-6 text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Supprimer document permis</label>
                                                    <input id="statut" type="checkbox"  name="remoovdoc" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('chauffeurs.index') }}" class="btn btn-sm btn-secondary btn-block">
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

@endsection
