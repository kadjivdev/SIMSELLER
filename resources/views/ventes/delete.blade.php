@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">SUPPRESSION DE LA VENTE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ventes.index') }}">Vente</a></li>
                            <li class="breadcrumb-item active">Suppression</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning bg-warning">
                            <div class="card-header">
                                <h3 class="card-title">SUPPRESSION DE VENTE</h3>
                            </div>
                                <div class="card-body">
                                    Êtes vous sûr de vouloir supprimer la vente N° : {{ $vente->code }}
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3">
                                            <a href="{{ route('ventes.index') }}" class="btn btn-sm btn-block btn-secondary">
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-3">
                                            <a class="btn btn-sm btn-success btn-block"  href="{{ route('ventes.destroy', ['vente'=>$vente->id]) }}">
                                                {{ __('Supprimer') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection;
