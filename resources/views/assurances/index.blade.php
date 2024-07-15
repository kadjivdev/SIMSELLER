@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">ASSURANCE CAMION</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('camions.index') }}">Camions</a></li>
                                <li class="breadcrumb-item active">Assurance</li>
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
                                                            alt="Camion">
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div>
                                    @if($message = session('message'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                            {{ $message }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="card-head pt-3 pl-4 pr-4 row">
                                        <div class="col-sm-6">
                                            <a class="btn btn-success btn-sm" href="{{route('assurances.create', ['id' => $camions->id])}}">
                                                <i class="fas fa-solid fa-plus"></i>
                                                Ajouter
                                            </a>
                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <a href="{{ route('camions.index') }}" class="btn btn-sm btn-primary right"><i class="fa-solid fa-circle-left"></i> Retour</a>
                                        </div>
                                    </div>
                                    <h4 class="text-center">Listes des assurances</h4>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Police</th>
                                                            <th>Date Début</th>
                                                            <th>Date Fin</th>
                                                            <th>Compagnie</th>
                                                            <th>Document</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($camions->count() > 0)
                                                            <?php $compteur=1; ?>
                                                            @foreach($camions->assurances as $assurance)
                                                                <tr>
                                                                    <td>{{ $compteur++ }}</td>
                                                                    <td>{{ $assurance->police }}</td>
                                                                    <td class="text-center">{{ date_format(date_create($assurance->dateDebut), 'd/m/Y') }}</td>
                                                                    <td class="text-center">{{ date_format(date_create($assurance->dateFin), 'd/m/Y') }}</td>
                                                                    <td>{{ $assurance->compagnie }}</td>
                                                                    <td>
                                                                        @if ($assurance->document != NULL)
                                                                        <a  class="btn btn-success btn-block text-white btn-sm" href="{{ asset('storage/'.$assurance->document) }}" target="_blank"><i class="fa-solid fa-file-pdf mr-2"></i><b>Ouvrir Assurance</b></a>
                                                                        @else

                                                                        @endif
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <a class="btn btn-warning btn-sm" href="{{ route('assurances.edit', ['camion_id'=>$camions->id, 'assurance_id'=>$assurance->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                                        <a class="btn btn-danger btn-sm" href="{{ route('assurances.delete', ['camion_id'=>$camions->id, 'assurance_id'=>$assurance->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Police</th>
                                                            <th>Date Début</th>
                                                            <th>Date Fin</th>
                                                            <th>Compagnie</th>
                                                            <th>Document</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    @endsection
