@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">DETAIL CAMION</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('camions.index') }}">Camions</a></li>
                                <li class="breadcrumb-item active">Détail camion</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">

                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12">
                                        <div class="card d-flex flex-fill">
                                            <div class="card-body">
                                                <h1 class="pb-3">{{ $camions->marque }} N° : {{ $camions->immatriculationTracteur }}</h1>
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <img class="profile-user-img img-fluid img-circle" style="height: 150px; width: 150px"
                                                            src="{{ asset('images')}}/{{ $camions->photo }}"
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
                                                                <div class="col-sm-4">
                                                                    @if ($assurance == NULL)
                                                                        <span class="badge badge-danger">Assurance</span>
                                                                    @elseif ($assurance->dateFin == date('Y-m-d'))
                                                                        <span class="badge badge-warning">Assurance</span>
                                                                    @elseif ($assurance->dateFin > date('Y-m-d'))
                                                                        <span class="badge badge-success">Assurance</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Assurance</span>
                                                                    @endif

                                                                </div>
                                                                <div class="col-sm-4">
                                                                    @if ($visitetracteur == NULL)
                                                                        <span class="badge badge-danger">Visite Technique Tracteur</span>
                                                                    @elseif ($visitetracteur->dateFin > date('Y-m-d'))
                                                                        <span class="badge badge-success">Visite Technique Tracteur</span>
                                                                    @elseif ($visitetracteur->dateFin == date('Y-m-d'))
                                                                        <span class="badge badge-warning">Visite Technique Tracteur</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Visite Technique Tracteur</span>
                                                                    @endif

                                                                </div>
                                                                <div class="col-sm-4">
                                                                    @if ($visiteremorque == NULL)
                                                                        <span class="badge badge-danger">Visite Technique Remorque</span>
                                                                    @elseif ($visiteremorque->dateFin > date('Y-m-d'))
                                                                        <span class="badge badge-success">Visite Technique Remorque</span>
                                                                    @elseif ($visiteremorque->dateFin == date('Y-m-d'))
                                                                        <span class="badge badge-warning">Visite Technique Remorque</span>
                                                                    @else
                                                                        <span class="badge badge-danger">Visite Technique Remorque</span>
                                                                    @endif
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
            </section>
            <section class="content">
                <div class="card card-solid">
                <div class="card-body">
                    <div class="row mt-4">
                        <nav class="w-100">
                            <div class="nav nav-tabs" id="product-tab" role="tablist">
                            <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Programmation</a>
                            <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Assurance</a>
                            <a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">Visite Technique</a>
                            </div>
                        </nav>
                        <div class="tab-content p-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                <table id="example" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Date Programmer</th>
                                        <th>Qté Programmer</th>
                                        <th>Date Livrer</th>
                                        <th>Qté Livrer</th>
                                        <th>BL</th>
                                        <th>Chauffeur</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                    </tbody>
                                    <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Date Programmer</th>
                                        <th>Qté Programmer</th>
                                        <th>Date Livrer</th>
                                        <th>Qté Livrer</th>
                                        <th>BL</th>
                                        <th>Chauffeur</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-sm table-bordered table-striped" style="font-size: 12px">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Police</th>
                                                    <th>Date Début</th>
                                                    <th>Date Fin</th>
                                                    <th>Compagnie</th>
                                                    <th>Document</th>
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
                                                            <td><a  class="btn btn-success btn-block text-white btn-sm" href="{{ asset('storage/'.$assurance->document) }}" target="_blank"><i class="fa-solid fa-file-pdf mr-2"></i><b>Ouvrir Assurance</b></a></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        </div>
                                    </div> 
                                </div>
                            </div><!--
                            <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"> Cras ut ipsum ornare, aliquam ipsum non, posuere elit. In hac habitasse platea dictumst. Aenean elementum leo augue, id fermentum risus efficitur vel. Nulla iaculis malesuada scelerisque. Praesent vel ipsum felis. Ut molestie, purus aliquam placerat sollicitudin, mi ligula euismod neque, non bibendum nibh neque et erat. Etiam dignissim aliquam ligula, aliquet feugiat nibh rhoncus ut. Aliquam efficitur lacinia lacinia. Morbi ac molestie lectus, vitae hendrerit nisl. Nullam metus odio, malesuada in vehicula at, consectetur nec justo. Quisque suscipit odio velit, at accumsan urna vestibulum a. Proin dictum, urna ut varius consectetur, sapien justo porta lectus, at mollis nisi orci et nulla. Donec pellentesque tortor vel nisl commodo ullamcorper. Donec varius massa at semper posuere. Integer finibus orci vitae vehicula placerat. </div>
                        </div>
                        </div> -->
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>            
        </div>
    @endsection
