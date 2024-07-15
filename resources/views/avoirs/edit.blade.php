@extends('layouts.app')

    @section('content')
    <div class="content-wrapper justify-content-center">
        <section class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h1 class="pb-3">Chantiers: {{ $chantier->libelle }} ({{ $chantier->nature->code }})</h1>
                            <div class="row">
                                <div class="col-8 col-sm-8 col-md-8">
                                    <div class="card d-flex flex-fill">
                                        <div class="card-body">
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Description:  {{ $chantier->description }}</li>
                                                <li class=""><span class="fa-li"><i class="fa-regular fa-calendar"></i></span> Date Début:  {{ date('d/m/Y', strtotime($chantier->dateDebut)) }}</li>
                                                <li class=""><span class="fa-li"><i class="fa-regular fa-calendar-check"></i></span> Date Fin:  {{ date('d/m/Y', strtotime($chantier->dateFin)) }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('intervenirs.show', (['id'=>$chantier->id])) }}">Chantiers</a></li>
                            <li class="breadcrumb-item active">Société Modification</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Modifier les informations de la société</h3>
                            </div>
                            <form method="POST" action="{{ route('intervenirs.update') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Sociétés</label>
                                                <input type="hidden" name="chantier_id" value="{{ $chantier->id }}">
                                                <select class="select2 form-control form-control-sm @error('societe_id') is-invalid @enderror" name="societe_id" style="width: 100%;">
                                                    <option selected selected="selected" value="{{ $societe->id }}" {{ old('societe_id') == $societe->id ? 'selected' : '' }}>{{ $societe->code.' '.$societe->raisonSociale.' ('.$societe->sigle.')' }}</option>
                                                </select>
                                                @error('societe_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Observation</label>
                                                <textarea class="form-control form-control-sm  @error('observation') is-invalid @enderror" name="observation" id="exampleFormControlTextarea1" value="{{ @old('observation')? @old('observation'):$societe->pivot->observation }}" style="text-transform: capitalize"  autocomplete="observation" autofocus rows="3">{{ $societe->pivot->observation }}</textarea>
                                                @error('observation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-2">
                                            <a href="{{ route('intervenirs.show', ['id'=>$chantier->id]) }}" class="btn btn-block btn-sm btn-secondary">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-sm btn-success btn-block">
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
@endsection
