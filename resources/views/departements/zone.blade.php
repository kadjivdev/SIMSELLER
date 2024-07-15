@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>DEPARTEMENT : {{ $departement->libelle }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                                <li class="breadcrumb-item active">Listes zones</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header row">
                                    <div class="col-sm-6">
                                        <h2 class="card-title">Liste des zones</h2>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="{{ route('departements.index') }}" class="btn btn-sm btn-primary  float-right">
                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                            {{ __('Retour') }}
                                        </a>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Libelle</th>
                                            <th>Représentant</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($departement->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($departement->zones as $zone)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td>{{ $zone->libelle }}</td>
                                                    <td>{{ $zone->representant->civilite }} {{ $zone->representant->nom }} {{ $zone->representant->prenom }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Libelle</th>
                                            <th>Représentant</th>
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
            <!-- /.content -->
        </div>
    @endsection
