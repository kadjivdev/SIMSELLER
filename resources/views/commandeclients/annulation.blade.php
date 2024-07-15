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
                                    <h4>Voulez vous annuler la commande client N° {{ $commandeclient->code }} du client {{ $commandeclient->client->sigle?$commandeclient->client->sigle:$commandeclient->client->nom.' '.$commandeclient->client->prenom }} ?</h4>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4 text-center">
                                            <div class="row text-center">
                                                <div class="col-md-6">
                                                    <a href="{{ route('commandeclients.index') }}" class="btn btn-sm btn-secondary  btn-block">
                                                        <i class="fa-solid fa-circle-left mr-1"></i>
                                                        {{ __('Retour') }}
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a class="btn btn-sm btn-primary  btn-block"  href="{{ route('commandeclients.annuler', ['commandeclient'=>$commandeclient->id]) }}">
                                                        {{ __('Ok') }}
                                                        <i class="fa-solid fa-check ml-1"></i>
                                                    </a>
                                                </div>
                                            </div>
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
