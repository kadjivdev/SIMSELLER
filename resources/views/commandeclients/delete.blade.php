@extends('layouts.app')

@section('content')
<div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">COMMANDE CLIENTS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('commandeclients.index') }}">Commande client</a></li>
                            <li class="breadcrumb-item active">Suppression</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card card-warning bg-warning">
                            <div class="card-header">
                                <h3 class="card-title">SUPPRESSION DE COMMANDE CLIENT</h3>
                            </div>
                                <div class="card-body">
                                    Êtes vous sûr de vouloir supprimer la commande client N° : {{ $commandeclient->code }} du client {{ $commandeclient->client->typeclient->libelle == env('TYPE_CLIENT_P')?$commandeclient->client->nom.' '.$commandeclient->client->prenom:$commandeclient->client->sigle }}
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3">
                                            <a href="{{ route('commandeclients.index') }}" class="btn btn-sm btn-block btn-secondary">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-3">
                                            <a class="btn btn-sm btn-success btn-block"  href="{{ route('commandeclients.destroy', ['commandeclient'=>$commandeclient->id]) }}">
                                                {{ __('Supprimer') }}
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>

@endsection

