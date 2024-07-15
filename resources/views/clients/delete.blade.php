@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>CLIENTS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Liste des clients</a></li>
                            <li class="breadcrumb-item active">Suppression client</li>
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
                                <h3 class="card-title">Suppression Client</h3>
                            </div>
                                <div class="card-body">
                                    Êtes vous sûr de vouloir supprimer le client @if ($client->typeclient->libelle == env('TYPE_CLIENT_S'))
                                        {{ $client->raisonSociale }} ({{ $client->sigle }})
                                    @elseif ($client->typeclient->libelle == env('TYPE_CLIENT_P'))
                                        {{ $client->prenom }} {{ $client->nom }}
                                    @endif CODE N° : {{ $client->code }}
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('clients.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            
                                            <a class="btn btn-sm btn-success btn-block"  href="{{ route('clients.destroy', ['client'=>$client->id]) }}">
                                                {{ __('Supprimer') }}
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;
