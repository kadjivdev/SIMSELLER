@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">BON DE COMMANDE N° {{ $recu->boncommande->code }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('recus.index', ['boncommandes'=>$recu->boncommande->id]) }}">Reçu</a></li>
                                <li class="breadcrumb-item active">Liste des détails reçu</li>
                            </ol>
                        </div>
                    </div>
                    @include('detailrecus.entete')
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"></h3>
                                        @if ($recu->montant != collect($recu->detailrecus)->sum('montant'))
                                            <a class="btn btn-success btn-sm" href="{{route('detailrecus.create', ['recu'=>$recu->id])}}">
                                                <i class="fas fa-solid fa-plus"></i>
                                                Ajouter
                                            </a>
                                        @endif
                                        <a href="{{ route('recus.index', ['boncommandes'=>$recu->boncommande->id]) }}" class="btn btn-sm btn-primary float-md-right">
                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                            {{ __('Retour') }}
                                        </a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Référence</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Compte</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if ($recu->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($recu->detailrecus as $detailrecu)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td>{{ $detailrecu->code }}</td>
                                                    <td>{{ $detailrecu->reference }}
                                                        @if ($detailrecu->document)
                                                            <a  class="btn btn-success float-md-right text-white btn-sm" href="{{ $detailrecu->document?asset('storage/'.$detailrecu->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>
                                                        @endif
                                                    </td>
                                                        <td class="text-center">{{ $detailrecu->date?date_format(date_create($detailrecu->date), 'd/m/Y'):'' }}</td>
                                                        <td class="text-right">{{ number_format($detailrecu->montant,2,","," ") }}</td>
                                                        <td class="text-center">@if ($detailrecu->compte)
                                                            {{ $detailrecu->compte->banque->sigle }} {{ $detailrecu->compte->numero }}
                                                        @endif</td>
                                                        <td>{{ $detailrecu->typedetailrecu->libelle }}</td>
                                                    <td class="text-center">
                                                        <!--<a class="btn btn-success btn-sm" href=""><i class="fa-regular fa-eye"></i></a>-->
                                                      @if ($recu->boncommande->statut == 'Préparation')                                                         
                                                            <a class="btn btn-warning btn-sm" href="{{ route('detailrecus.edit',  ['recu'=>$recu->id, 'detailrecu'=>$detailrecu->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                            <a class="btn btn-danger btn-sm" href="{{ route('detailrecus.delete',  ['recu'=>$recu->id, 'detailrecu'=>$detailrecu->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                            <!--<a class="btn btn-secondary btn-sm" href=""><i class="fa-solid fa-bed"></i></a>
                                                            <a class="btn btn-primary btn-sm" href=""><i class="fas fa-running"></i></a>-->
                                                      @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Référence</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Compte</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
        </div>
    @endsection
