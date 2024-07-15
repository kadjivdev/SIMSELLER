@extends('layouts.app')

    @section('content')

        <div class="content-wrapper">
            <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-9">
                        <h1 class="pb-3">Société: {{ $societe->raisonSociale }}  ({{ $societe->sigle }})</h1>
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-6">
                                    <div class="card d-flex flex-fill">
                                        <div class="card-body">
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Adresse:  {{ $societe->adresse }}</li>
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone:  {{ $societe->telephone }}</li>
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $societe->email }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-sm-3">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('societes.show') }}">Société</a></li>
                            <li class="breadcrumb-item active">Dissocier agent SST</li>
                        </ol>
                    </div>
                </div>
            </div>
            </section>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                        <section class="content">
                                <div class="card alert-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Clôture de contrat de l'agent  SST {{  $agent->matricule.' '.$agent->nom.' '.$agent->prenom  }} </h3>
                                    </div>
                                    <form  method="POST" action="{{ route('appartenirs.done') }}">
                                        @csrf
                                        <div class="card-body">
                                            Êtes vous vraiment sûr de vouloir mettre fin au contrat de l'agent  SST de la société: {{  $societe->sigle.' '.$societe->raisonSociale  }}
                                                <input type="hidden" name="societe_id"  value="{{  $societe->id }}">
                                                <input type="hidden" name="agent_id"  value="{{ $agent->id }}">
                                                <input type="hidden" name="dateDebut"  value="{{ $agent->pivot->dateDebut }}">
                                        </div>
                                        <div class="card-footer">
                                            <div class="row justify-content-center">
                                                <div class="col-sm-2">
                                                    <a href="{{ route('appartenirs.show', ['id'=>$societe->id]) }}" class="btn btn-sm btn-secondary">
                                                        {{ __('Retour') }}
                                                    </a>
                                                </div>

                                                <button type="submit" class="btn btn-success btn-sm">
                                                    {{ __('Confirmer') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        </section>
                </div>
                <div class="col-sm-3"></div>
            </div>

        </div>

    @endsection
