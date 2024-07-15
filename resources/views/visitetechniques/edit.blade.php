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
                            <li class="breadcrumb-item active">Modification visite technique</li>
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification assurance</h3>
                            </div>
                                <form method="POST" action="{{ route('visitetechniques.update') }}"  enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="camion_id" value="{{ $visitetechniques->camion->id }}" />
                                    <input type="hidden" name="id" value="{{ $visitetechniques->id }}" />
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Libelle<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2" name="libelle" style="text-transform: capitalize; width:100%;">
                                                    <option value="{{ $visitetechniques->libelle }}" selected>{{ $visitetechniques->libelle }}</option>
                                                    <option value="VISITE TECHNIQUE TRACTEUR" @if (old('libelle') == "VISITE TECHNIQUE TRACTEUR") {{ 'selected' }} @endif>VISITE TECHNIQUE TRACTEUR</option>
                                                    <option value="VISITE TECHNIQUE REMORQUE" @if (old('libelle') == "VISITE TECHNIQUE REMORQUE") {{ 'selected' }} @endif>VISITE TECHNIQUE REMORQUE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Date Début<span class="text-danger">*</span></label>
                                                <input type="date" id="dateDebut" class="form-control form-control-sm @error('dateDebut') is-invalid @enderror" name="dateDebut"  value="{{ @old('dateDebut')? @old('dateDebut'):$visitetechniques->dateDebut }}"  autocomplete="dateDebut" autofocus required>
                                                @error('dateDebut')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Date Fin<span class="text-danger">*</span></label>
                                                <input type="date" id="dateFin" class="form-control form-control-sm @error('dateFin') is-invalid @enderror" name="dateFin"  value="{{ @old('dateFin')? @old('dateFin'):$visitetechniques->dateFin }}"  autocomplete="dateFin" autofocus required>
                                                @error('dateFin')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Document<span class="text-danger">*</span></label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" data-mask value="{{ old('document') }}" >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    @if($visitetechniques->document)
                                        <div class="row mb-2">
                                            <div class="col-6 text-center">
                                                <h4><a href="{{ asset('storage/'.$visitetechniques->document) }}" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf"></i> Afficher le document</a></h4>
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Supprimer document de visIte</label>
                                                    <input id="statut" type="checkbox"  name="remoovdoc" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer badge-secondary">
                                    <div class="row text-center">
                                        <div class="col-md-4"></div>
                                        <div class="col-sm-2">
                                            <a href="{{ route('visitetechniques.index', ['id' => $camions->id]) }}" class="btn btn-sm btn-block btn-primary  text-center">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>

                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-sm btn  btn-block btn-success text-center">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                                {{ __('Modifier') }}
                                            </button>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @endsection
