@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1> AGENTS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('agent.index') }}"> Agent</a></li>
                            <li class="breadcrumb-item active">Modification d'un agent</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification de l'agent <b class="text-warning">{{ $agent->nom }}
                                        {{ $agent->prenom }}</b></h3>
                            </div>
                            <form method="POST" action="{{ route('agent.update', [$agent->id]) }}">
                                @csrf
                                @method('POST')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Nom<span class="text-danger">*</span></label>
                                                <input type="text" id="nom"
                                                    class="form-control form-control-sm @error('nom') is-invalid @enderror"
                                                    name="nom" style="text-transform: uppercase"
                                                    value="{{ @old('nom') ? @old('nom') : $agent->nom }}" autocomplete="off"
                                                    autofocus required>
                                                @error('nom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Prenom<span class="text-danger">*</span></label>
                                                <input type="text" id="prenom"
                                                    class="form-control form-control-sm @error('prenom') is-invalid @enderror"
                                                    name="prenom" style="text-transform:none"
                                                    value="{{ @old('prenom') ? @old('prenom') : $agent->prenom }}"
                                                    autocomplete="off" autofocus required>
                                                @error('prenom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Téléphone<span class="text-danger">*</span></label>
                                                <input type="text" id="telephone"
                                                    class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                    name="telephone" style="text-transform: uppercase"
                                                    value="{{ @old('telephone') ? @old('telephone') : $agent->telephone }}"
                                                    autocomplete="off" autofocus required>
                                                @error('tephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Adresse<span class="text-danger">*</span></label>
                                                <input type="text" id="adresse"
                                                    class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                                    name="adresse" style="text-transform: uppercase"
                                                    value="{{ @old('adresse') ? @old('adresse') : $agent->adresse }}"
                                                    autocomplete="off" autofocus required>
                                                @error('adresse')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <div class="col-sm-4">
                                        <a href="{{ route('agent.index') }}" class="btn btn-sm btn-secondary btn-block">
                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                            {{ __('Retour') }}
                                        </a>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-warning btn-block">Modifier
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>
@endsection
