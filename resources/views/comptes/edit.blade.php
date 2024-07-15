@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('comptes.index', ['banque'=>$banque->id]) }}">Comptes</a></li>
                            <li class="breadcrumb-item active">Modification compte</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="pb-3">COMPTE BANCAIRE N° : {{ $compte->numero }}</h1>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="card d-flex flex-fill">
                                        <div class="card-body">
                                            <h1>{{ $banque->raisonSociale }}  ({{ $banque->sigle }})</h1>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-person-dots-from-line"></i></span> Interlocuteur:  {{ $banque->interlocuteur }}</li></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone:  {{ $banque->telephone }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $banque->email }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Adresse:  {{ $banque->adresse }}</li>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                <h3 class="card-title">Modification Type Document</h3>
                            </div>
                            <form method="POST" action="{{ route('comptes.update', ['banque'=>$banque->id, 'compte'=>$compte->id]) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Numéro<span class="text-danger">*</span></label>
                                                <input type="text" id="numero" class="form-control form-control-sm @error('numero') is-invalid @enderror" name="numero" style="text-transform: uppercase"  value="{{ @old('numero')?@old('numero'):$compte->numero }}"  autocomplete="numero" autofocus required>
                                                @error('numero')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <div class="form-group">
                                                    <label>Intitulé<span class="text-danger">*</span></label>
                                                    <input type="text" id="numero" class="form-control form-control-sm @error('numero') is-invalid @enderror" name="intitule" style="text-transform: uppercase"  value="{{ @old('intitule')?@old('intitule'):$compte->intitule }}"  autocomplete="numero" autofocus required>
                                                    @error('intitule')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('comptes.index', ['banque'=>$banque->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
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
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;
