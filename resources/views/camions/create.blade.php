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
                        <li class="breadcrumb-item active">Nouveau camion</li>
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
                                <h3 class="card-title">Ajouter un nouveau camion</h3>
                            </div>
                            <form method="POST" action="{{ route('camions.store') }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Marque<span class="text-danger"></span></label>
                                                <select class="select2 form-control form-control-sm @error('marque_id') is-invalid @enderror" name="marque_id" style="width: 100%;">
                                                    <option selected disabled>** choisissez une marque **</option>
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
                                                <select name="nombreIssieu" class="form-control form-control-sm" id="">
                                                    <option value=""></option>
                                                    <option value="3" @if(old('nombreIssieu')){{old('nombreIssieu') == 3 ? 'selected':''}}@endif>3</option>
                                                    <option value="4" @if(old('nombreIssieu')){{old('nombreIssieu') == 4 ? 'selected':''}}@endif>4</option>
                                                    <option value="5" @if(old('nombreIssieu')){{old('nombreIssieu') == 5 ? 'selected':''}}@endif>5</option>
                                                    <option value="6" @if(old('nombreIssieu')){{old('nombreIssieu') == 6 ? 'selected':''}}@endif>6</option>
                                                    <option value="7" @if(old('nombreIssieu')){{old('nombreIssieu') == 7 ? 'selected':''}}@endif>7</option>
                                                    <option value="8" @if(old('nombreIssieu')){{old('nombreIssieu') == 8 ? 'selected':''}}@endif>8</option>
                                                    <option value="9" @if(old('nombreIssieu')){{old('nombreIssieu') == 9 ? 'selected':''}}@endif>9</option>
                                                    <option value="10" @if(old('nombreIssieu')){{old('nombreIssieu') == 10 ? 'selected':''}}@endif>10</option>

                                                </select>
                                                @error('nombreIssieu')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Tonnage<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="tonnage" style="text-transform: uppercase"  value="{{ old('tonnage') }}"  autocomplete="tonnage" min="1" autofocus required>
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
                                                <input type="text" class="form-control form-control-sm" name="immatriculationTracteur" style="text-transform: uppercase"  value="{{ old('immatriculationTracteur') }}"  autocomplete="immatriculationTracteur" autofocus required>
                                                @error('immatriculationTracteur')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Immatriculation Remorque<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="immatriculationRemorque" style="text-transform: uppercase"  value="{{ old('immatriculationRemorque') }}"  autocomplete="immatriculationRemorque" autofocus required>
                                                @error('immatriculationRemorque')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <input type="hidden" name="statut" value="Actif">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Avaliseurs</label><span class="text-danger">*</span>
                                                <select class="select2 form-control form-control-sm @error('avaliseur_id') is-invalid @enderror" name="avaliseur_id" style="width: 100%;">
                                                    <option selected disabled>** choisissez un avaliseur **</option>
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
                                                        <option value="{{ $chauffeur->id }}" {{ old('chauffeur_id') == $chauffeur->id ? 'selected' : '' }}>{{ $chauffeur->nom }} {{ $chauffeur->prenom }}</option>
                                                    @endforeach
                                                </select>
                                                @error('chauffeur_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="file">Photo</label>
                                                <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                <img id="previewImg" style="max-width: 130px; margin-top:20px"; />
                                                @error('photo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('camions.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-success btn-block">
                                                {{ __('Ajouter') }}
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

@endsection;
