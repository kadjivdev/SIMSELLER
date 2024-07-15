@extends('layouts.app')

    @section('content')
        <div class="content-wrapper justify-content-center">
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
                                                        <li class=""><span class="fa-li"><i class="fa-regular fa-calendar-check"></i></span> Début SCB-LAFARGE:  {{ date_format(date_create($agent->dateDebutSCB), 'd/m/Y H:m:s') }}</li>
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
                                <li class="breadcrumb-item active">Nouvelle</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Nouvelle Badge</h3>
                                </div>
                                <form method="POST" action="{{ route('utilisers.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>BADGES</label>
                                                    <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                                                    <select class="select2 form-control form-control-sm @error('badge_id') is-invalid @enderror" name="badge_id" style="width: 100%;">
                                                        <option selected disabled>Choisissez un badge</option>
                                                        @foreach($badges as $badge)
                                                            <option value="{{ $badge->id }}" {{ old('badge_id') == $badge->id ? 'selected' : '' }}>{{ $badge->code }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('badge_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>DATE RECEPTION</label>
                                                    <input type="date" name="dateDebut" class="form-control form-control-sm @error('dateDebut') is-invalid @enderror" value="{{ $date }}">

                                                    @error('dateDebut')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>DATE FIN</label>
                                                    <input type="date" name="dateFin" class="form-control form-control-sm @error('dateFin') is-invalid @enderror" value="{{ old('dateFin') }}">

                                                    @error('dateFin')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-4 mr-2">
                                                <a href="{{ route('utilisers.show', ['id'=>$agent->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                                    {{ __('Retour') }}
                                                </a>
                                            </div>

                                            <div class="col-sm-4">
                                                <button type="submit" class="btn btn-sm btn-success btn-block">
                                                    {{ __('Ajouter') }}
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
