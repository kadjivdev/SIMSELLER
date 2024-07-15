@extends('layouts.app')

    @section('content')
        <div class="content-wrapper justify-content-center">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h1 class="pb-3">Agents SST: ({{ $agent->matricule }})  {{ $agent->nom.' '.$agent->prenom }}  </h1>
                                <div class="row">
                                    <div class="col-6 col-sm-6 col-md-6">
                                        <div class="card d-flex flex-fill">
                                            <div class="card-body">
                                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                                    <li class="big"><span class="fa-li"><i class="fa-solid fa-baby"></i></span> Age:  {{ $years }}ans</li>
                                                    <li class="big"><span class="fa-li"><i class="fa-solid fa-venus-mars"></i></span> Sexe:  {{ $agent->sexe  }}</li>
                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone:  {{ $agent->telephone }}</li>
                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $agent->email }}</li>
                                                    <li class=""><span class="fa-li"><i class="fa-regular fa-calendar-check"></i></span> Début SCB-LAFARGE:  {{ date('d/m/Y', strtotime($agent->dateDebutSCB)) }}</li>
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
                                <li class="breadcrumb-item active">Mise à jour de Badge</li>
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
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Mise à jour du Badge N° {{ $badge->code }}</h3>
                                </div>
                                <form method="POST" action="{{ route('utilisers.update') }}" >
                                    @csrf
                                    <input type="hidden" name="agent_id" value="{{ $agent->id }}">
                                    <input type="hidden" name="badge_id" value="{{ $badge->id }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>BADGE</label>
                                                    <input type="text" name="badge_id" class="form-control form-control-sm @error('dateFin') is-invalid @enderror" value="{{ $badge->code }}" readonly>
                                                    @error('badge_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>DATE DE RETRAIT</label>
                                                    <input type="date" name="dateFin" class="form-control form-control-sm @error('dateFin') is-invalid @enderror" value="{{ $date }}">

                                                    @error('dateFin')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-2 mr-2">
                                                <a href="{{ route('utilisers.show', ['id'=>$agent->id]) }}" class="btn btn-sm btn-secondary">
                                                    {{ __('Retour') }}
                                                </a>
                                            </div>

                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    {{ __('Valider') }}
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
