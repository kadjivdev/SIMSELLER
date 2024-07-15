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
                                <li class="breadcrumb-item"><a href="{{ route('agents.show') }}">Agents SST</a></li>
                                <li class="breadcrumb-item active">Aptitudes</li>
                            </ol>
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
                                    @if ($message = session('message'))
                                        <div class="alert alert-success text-center">
                                            {{ $message }}
                                        </div>
                                    @endif
                                        @if ($message = session('error_message'))
                                            <div class="alert alert-danger text-center">
                                                {{ $message }}
                                            </div>
                                        @endif
                                </div>
                                <div>
                                    <div class="card-head pt-3 pl-4 pr-4 row">
                                        <div class="col-sm-6">
                                            <a href="{{ route('utilisers.create', ['id'=>$agent->id]) }}" class="btn btn-sm btn-success">
                                                {{ __('Nouvelle Badge') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <a href="{{ route('agents.show', (['id'=>$agent->id])) }}" class="btn btn-sm btn-primary right">Retour</a>
                                        </div>
                                    </div>
                                    <h2 class="text-center">Listes des badges</h2>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Code</th>
                                                            <th>Date Début</th>
                                                            <th>Date Fin</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($agent->count() > 0)
                                                            <?php $compteur=1; ?>
                                                            @foreach($agent->badges as $badge)
                                                                <tr>
                                                                    <td>{{ $compteur++ }}</td>
                                                                    <td>{{ $badge->code }}</td>
                                                                    <td class="text-center">{{ date_format(date_create($badge->pivot->dateDebut), 'd/m/Y') }}</td>
                                                                    @if($badge->pivot->dateFin == NULL)
                                                                        <td class="text-center"><i class="fa-solid fa-hourglass-end"></i></td>
                                                                    @else
                                                                        <td class="text-center">{{ date_format(date_create($badge->pivot->dateFin), 'd/m/Y') }}</td>
                                                                    @endif

                                                                    <td class="text-center">
                                                                        @if($badge->pivot->dateFin == NULL)
                                                                            <a class="btn btn-info btn-xs" href="{{route('utilisers.edit', ['badge_id'=>$badge->id, 'agent_id'=>$agent->id])}}"><i class="fa-solid fa-clipboard-check"></i></a>
                                                                            <a class="btn btn-danger btn-xs" href="{{ route('utilisers.delete', ['badge_id'=>$badge->id, 'agent_id'=>$agent->id, 'dateDebut'=>$badge->pivot->dateDebut]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                                        @else
                                                                            <a class="btn btn-danger btn-xs" href="{{ route('utilisers.delete', ['badge_id'=>$badge->id, 'agent_id'=>$agent->id, 'dateDebut'=>$badge->pivot->dateDebut]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Code</th>
                                                            <th>Date Début</th>
                                                            <th>Date Fin</th>
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
