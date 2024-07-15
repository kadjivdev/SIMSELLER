@extends('layouts.app')

    @section('content')

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h1 class="pb-3">Agents SST: ({{ $agent->matricule }})  {{ $agent->nom.' '.$agent->prenom }}  </h1>
                            <div class="row">
                                <div class="col-6 col-sm-12 col-md-12">
                                    <div class="card d-flex flex-fill">
                                        <div class="card-body">
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <li class="big"><span class="fa-li"><i class="fa-solid fa-baby"></i></span> Age:  {{ $years }}ans</li>
                                                        <li class="big"><span class="fa-li"><i class="fa-solid fa-venus-mars"></i></span> Sexe:  {{ $agent->sexe  }}</li>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone:  {{ $agent->telephone }}</li>
                                                        <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $agent->email }}</li>
                                                        <li class=""><span class="fa-li"><i class="fa-regular fa-calendar-check"></i></span> Début SCB-LAFARGE:  {{ date_format(date_create($agent->dateDebutSCB), 'd/m/Y') }}</li>
                                                    </div>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('utilisers.show', ['id' => $agent->id]) }}">Badges</a></li>
                                <li class="breadcrumb-item active">Suppression</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                        <section class="content">
                                <div class="card alert-danger">
                                    <div class="card-header">
                                        <h3 class="card-title">Suppression de {{  $badges->libelle.' ('.$badges->code.')'  }}  </h3>
                                    </div>
                                    <div class="card-body">
                                        Êtes vous vraiment sûr de vouloir supprimer l'utilisation du badge: {{  $badges->code }}
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-2  mr-2">
                                                <a href="{{ route('utilisers.show', ['id'=>$agent->id]) }}" class="btn btn-sm btn-secondary">
                                                    {{ __('Retour') }}
                                                </a>
                                            </div>

                                            <a class="btn btn-warning btn-sm" href="{{ route('utilisers.destroy', ['badge_id'=>$badges->id, 'agent_id'=>$agent->id, 'dateDebut'=>$badges->pivot->dateDebut]) }}">
                                                {{ __('Supprimer') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        </section>
                </div>
                <div class="col-sm-3"></div>
            </div>

        </div>

    @endsection
