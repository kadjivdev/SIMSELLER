@extends('layouts.app')

@section('content')

<div class="content-wrapper justify-content-center">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">VISITES TECHNIQUES CAMION</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('visitetechniques.index', ['id' => $camions->id]) }}">Visites techniques</a></li>
                        <li class="breadcrumb-item active">Suppression visite technique</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-12">

                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="card d-flex flex-fill">
                                    <div class="card-body">
                                        <h1 class="pb-3">{{ $camions->marque->libelle }} N° : {{ $camions->immatriculationTracteur }}</h1>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <img class="profile-user-img img-fluid img-circle" style="height: 150px; width: 150px"
                                                    src="@if ($camions->photo)
                                                    {{ asset('images')}}/{{ $camions->photo }}
                                                    @else
                                                    {{asset('dist/img/camion.jpg')}}
                                                    @endif"
                                                    alt="Profile de l'agent">
                                            </div>
                                            <div class="col-sm-10">
                                                <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-regular fa-closed-captioning"></i></span> Immatriculation Tracteur:  {{ $camions->immatriculationTracteur }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-regular fa-closed-captioning"></i></span> Immatriculation Remorque:  {{ $camions->immatriculationRemorque }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-brands fa-42-group"></i></span> Nombre Issieu:  {{ $camions->nombreIssieu }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-weight-hanging"></i></span> Tonnage:  {{ $camions->tonnage }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            @if ($camions->statut == 'Actif')
                                                            <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut:  <span class="badge badge-success">{{ $camions->statut }}</span></li>
                                                            @else
                                                            <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut:  <span class="badge badge-danger">{{ $camions->statut }}</span></li>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <hr/>
                                                    <div class="row text-center">
                                                        <div class="col-sm-2"></div>
                                                        <div class="col-sm-8">
                                                            <div class="row">
                                                                <div class="col-sm-3 mr-3">
                                                                    @if ($statutAssur == 0)
                                                                        <span class="badge badge-danger">Assurance</span>
                                                                    @elseif ($statutAssur == 2)
                                                                        <span class="badge badge-warning">Assurance</span>
                                                                    @elseif ($statutAssur == 1)
                                                                        <span class="badge badge-success">Assurance</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Assurance</span>
                                                                    @endif

                                                                </div>
                                                                <div class="col-sm-3 mr-5">
                                                                    @if ($statutVisiteTracteur == 0)
                                                                        <span class="badge badge-danger">Visite Technique Tracteur</span>
                                                                    @elseif ($statutVisiteTracteur == 1)
                                                                        <span class="badge badge-success">Visite Technique Tracteur</span>
                                                                    @elseif ($statutVisiteTracteur == 2)
                                                                        <span class="badge badge-warning">Visite Technique Tracteur</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Visite Technique Tracteur</span>
                                                                    @endif

                                                                </div>
                                                                <div class="col-sm-3">
                                                                    @if ($statutVisiteRemorque == 0)
                                                                        <span class="badge badge-danger">Visite Technique Remorque</span>
                                                                    @elseif ($statutVisiteRemorque == 1)
                                                                        <span class="badge badge-success">Visite Technique Remorque</span>
                                                                    @elseif ($statutVisiteRemorque == 2)
                                                                        <span class="badge badge-warning">Visite Technique Remorque</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Visite Technique Remorque</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2"></div>
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
    </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card card-warning bg-warning">
                            <div class="card-header">
                                <h3 class="card-title">Suppression visite technique</h3>
                            </div>
                                <div class="card-body">
                                    Êtes vous sûr de vouloir supprimer la: {{ $visitetechniques->libelle }} du {{ date_format(date_create($visitetechniques->dateDebut), 'd/m/Y') }} au {{ date_format(date_create($visitetechniques->dateFin), 'd/m/Y') }}
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <a href="{{ route('visitetechniques.index', ['id' => $visitetechniques->camion->id]) }}" class="btn btn-sm btn-secondary">
                                                {{ __('Retour') }}
                                            </a>

                                            <a class="btn btn-sm btn-success"  href="{{ route('visitetechniques.destroy', ['id'=>$visitetechniques->id]) }}">
                                                {{ __('Supprimer') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4"></div>
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
