@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">ECHEANCE REGLEMENT</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('ventes.index') }}">Vente</a></li>
                                <li class="breadcrumb-item active">Liste règlément vente</li>
                            </ol>
                        </div>
                    </div>
                    @include('echeances.entete')
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"></h3>
                                    <a class="btn btn-success btn-sm" href="{{route('echeances.create', ['vente'=>$vente->id])}}">
                                        <i class="fas fa-solid fa-plus"></i>
                                        Ajouter
                                    </a>
                                    <a href="{{ route('ventes.index' )}}" class="btn btn-sm btn-primary float-md-right">
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
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if ($vente->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($vente->echeances as $echeance)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td>{{ date_format(date_create($echeance->date),'d/m/Y') }}</td>
                                                    <td class="text-center">
                                                        @if($echeance->statut == 1)
                                                            <span class="badge bg-success"><i class="fa fa-check"></i> Clôturer</span>
                                                        @elseif($echeance->date < date('Y-m-d'))
                                                            <span class="badge bg-danger">Expirer</span>
                                                        @else
                                                            <span class="badge bg-warning">En cours</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @if($echeance->statut == 0 && $echeance->date > date('Y-m-d'))
                                                            <a class="btn btn-warning btn-sm" href="{{ route('echeances.edit',  ['vente'=>$vente->id, 'echeance'=>$echeance->id]) }}"><i class="fa-solid fa-close"></i></a>
                                                            <a class="btn btn-danger btn-sm" href="{{ route('echeances.delete',  ['vente'=>$vente->id, 'echeance'=>$echeance->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Statut</th>
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
