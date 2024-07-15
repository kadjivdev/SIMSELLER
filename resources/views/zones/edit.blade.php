@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>ZONES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('zones.index') }}">Zones</a></li>
                            <li class="breadcrumb-item active">Modification zone</li>
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
                                <h3 class="card-title">Modification zone</h3>
                            </div>
                            <form method="POST" action="{{ route('zones.update', ['zone'=>$zone->id]) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" id="libelle" class="form-control form-control-sm @error('libelle') is-invalid @enderror" name="libelle" style="text-transform: uppercase"  value="{{ @old('libelle')?@old('libelle'):$zone->libelle }}"  autocomplete="libelle" autofocus required>
                                                @error('libelle')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Représentants<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('representant_id') is-invalid @enderror" name="representant_id" style="width: 100%;">
                                                    <option value="{{ $zone->representant->id }}" selected>{{ $zone->representant->nom }} {{ $zone->representant->prenom }}</option>
                                                    @foreach($representants as $representant)
                                                        <option value="{{ $representant->id }}" {{ old('representant_id') == $representant->id ? 'selected' : '' }}>{{ $representant->nom }} {{ $representant->prenom }}</option>
                                                    @endforeach
                                                </select>
                                                @error('representant_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Départements<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('departement_id') is-invalid @enderror" name="departement_id" style="width: 100%;">
                                                    <option value="{{ $zone->departement->id }}" selected>{{ $zone->departement->libelle }}</option>
                                                    @foreach($departements as $departement)
                                                        <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>{{ $departement->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('departement_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('zones.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-success btn-block">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                {{ __('Modifier') }}
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
