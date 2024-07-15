@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>COMMANDE CLIENTS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                                <li class="breadcrumb-item active">Listes des commandes clients</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                @if($message = session('message'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ $message }}
                                    </div>
                                @endif
                                <div class="card-header">
                                    <h3 class="card-title"></h3>
                                    <a class="btn btn-success btn-sm" href="{{route('commandeclients.create')}}">
                                        <i class="fas fa-solid fa-plus"></i>
                                        Créer
                                    </a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>Code</th>
                                            <th>Date</th>
                                            <th>Client</th>
                                            <th>Montant</th>
                                            <th>Type</th>
                                            <th>Zone</th>
                                            <th>Statut</th>
                                            <th>Utilisateur</th>
                                            <th>Actualisation</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($commandeclients->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($commandeclients as $commandeclient)
                                                <tr>
                                                    <td>{{ $commandeclient->code }}</td>
                                                    <td  class="text-center">{{ date('d/m/Y', strtotime($commandeclient->dateBon)) }}</td>
                                                    <td class="pl-2">
                                                       {{$commandeclient->client->raisonSociale}}
                                                    </td>
                                                    <td  class="text-right pr-3">{{ number_format($commandeclient->montant,2,","," ") }}</td>
                                                    <td class="pl-2">{{ $commandeclient->typecommande->libelle }}</td>
                                                    <td class="pl-2">{{ $commandeclient->zone->libelle }}</td>
                                                    @if ($commandeclient->statut == 'Livrée')
                                                        <td class="text-center"><span class="badge badge-success">{{ $commandeclient->statut }}</span></td>
                                                    @elseif ($commandeclient->statut == 'Préparation')
                                                        <td class="text-center"><span class="badge badge-info">{{ $commandeclient->statut }}</span></td>
                                                    @elseif ($commandeclient->statut == 'Livraison partielle')
                                                        <td class="text-center"><span class="badge badge-primary">{{ $commandeclient->statut }}</span></td>
                                                    @elseif ($commandeclient->statut == 'Annulée')
                                                        <td class="text-center"><span class="badge badge-danger">{{ $commandeclient->statut }}</span></td>
                                                    @else
                                                        <td class="text-center"><span class="badge badge-success">{{ $commandeclient->statut }}</span></td>
                                                    @endif
                                                    <td>{{ $commandeclient->user ? $commandeclient->user->name : ''  }}</td>
                                                    <td class="text-center">
                                                        @if($commandeclient->statut != 'Préparation')
                                                            @if($commandeclient->statut == 'Validée')
                                                                <a class="btn btn-primary btn-sm" href="{{ route('commandeclients.show', ['commandeclient'=>$commandeclient->id]) }}"><i class="fa-regular fa-eye"></i></a>
                                                                <a class="btn btn-secondary btn-sm" title="Annuler de commande client" href="{{ route('commandeclients.annulation', ['commandeclient'=>$commandeclient->id]) }}"><i class="fa-regular fa-rectangle-xmark"></i></a>
                                                            @else
                                                                <a class="btn btn-primary btn-sm" href="{{ route('commandeclients.show', ['commandeclient'=>$commandeclient->id]) }}"><i class="fa-regular fa-eye"></i></a>
                                                            @endif
                                                        @else
                                                            @if($commandeclient->commanders->count() > 0)
                                                                <a class="btn btn-success btn-sm" href="{{ route('commandeclients.edit', ['commandeclient'=>$commandeclient->id]) }}"><i class="fa-solid fa-check" title="Validation"></i></a>
                                                            @endif
                                                            <a class="btn btn-secondary btn-sm" href="{{ route('commandeclients.edit', ['commandeclient'=>$commandeclient->id]) }}"><i class="fa-solid fa-circle-plus" title="Ajouter des produits au commande clients"></i></a>
                                                            <a class="btn btn-warning btn-sm" href="{{ route('commandeclients.create', ['commandeclient'=>$commandeclient->id]) }}"><i class="fa-solid fa-pen-to-square" title="Modifier les informations de commande client"></i></a>
                                                            <!--<a class="btn btn-success btn-sm" href=""><i class="fa-solid fa-circle-check"></i></a> -->
                                                            <a class="btn btn-danger btn-sm" href="{{ route('commandeclients.delete', ['commandeclient'=>$commandeclient->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button type="button" class="dropdown-toggle btn btn-success btn-sm" href="#" role="button" data-toggle="dropdown" @if($commandeclient->statut == 'Non livrée' || $commandeclient->statut == 'Annulée') disabled @endif>
                                                                Actions<i class="dw dw-more"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-md-right dropdown-menu-icon-list drop text-sm">
                                                                <a class="dropdown-item" href=""><i class="fa-solid fa-file-invoice"></i> Accusé document</a>
                                                                <!--<a class="dropdown-item" href=""><i class="fa-solid fa-industry"></i> Chantiers</a> -->
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>Code</th>
                                            <th>Date</th>
                                            <th>Client</th>
                                            <th>Montant</th>
                                            <th>Type</th>
                                            <th>Zone</th>
                                            <th>Statut</th>
                                            <th>Utilisateur</th>
                                            <th>Actualisation</th>
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
            <!-- /.content -->
        </div>
    @endsection
