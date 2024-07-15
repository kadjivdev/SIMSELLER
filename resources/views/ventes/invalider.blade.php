@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">ANNULATION DE VENTE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ventes.index') }}">Vente</a></li>
                            <li class="breadcrumb-item active">Annulation</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning bg-warning">
                                <div class="card-body text-center">
                                    <h4>Voulez vous Annuler la vente N° {{ $vente->code }}</h4>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4 text-center">
                                            <form method="POST" action="{{ route('ventes.postinvalider', ['vente'=>$vente->id]) }}">
                                                @csrf
                                                <input type="hidden" name="statut" value="Préparation" />
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <a href="{{ route('ventes.index') }}" class="btn btn-sm btn-secondary  btn-block">
                                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                                            {{ __('Retour') }}
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="submit" class="btn btn-sm btn-primary  btn-block">
                                                            {{ __('OUI') }}
                                                            <i class="fa-solid fa-check ml-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-4"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection;
