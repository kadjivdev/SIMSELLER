@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">RECUS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('recus.index', ['boncommandes'=>$boncommandes->id]) }}">Bon commande</a></li>
                                <li class="breadcrumb-item active">Liste des reçus</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12">
                    
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12">
                                        <div class="card d-flex flex-fill">
                                            <div class="card-body">
                                                @if(session('messagebc'))
                                                    <div class="alert alert-success alert-dismissible">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                                        {{session('messagebc')}}
                                                    </div>
                                                @endif
                                                <h1 class="pb-3">Bon de commande N° {{ $boncommandes->code }}</h1>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <b><li class=""><span class="fa-li"><i class="fa-solid fa-calendar"></i></span> Date :  {{ date_format(date_create($boncommandes->dateBon), 'd/m/Y') }}</li></b>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <b><li class=""><span class="fa-li"><i class="fa-regular fa-money-bill-1"></i></span> Montant:  {{ number_format($boncommandes->montant,2,","," ") }}</li></b>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut:  {{ $boncommandes->statut }}</li>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class=""><span class="fa-li"><i class="nav-icon fas fa-solid fa-cart-flatbed"></i></span> Type commande:  {{ $boncommandes->typecommande->libelle }}</li>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-user-tie"></i></span> Utilisateur:  {{ $boncommandes->users }}</li>
                                                                </div>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <b><li class=""><span class="fa-li"><i class="fa-regular fa-closed-captioning"></i></span> Sigle :  {{ $boncommandes->fournisseur->sigle }}</li></b>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <b><li class=""><span class="fa-li"><i class="fa-solid fa-book-open"></i></span> Raison Sociale :  {{ $boncommandes->fournisseur->raisonSociale }}</li></b>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone :  {{ $boncommandes->fournisseur->telephone }}</li>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-envelope-circle-check"></i></span> E-mail :  {{ $boncommandes->fournisseur->email }}</li>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-location-dot"></i></span> Adresse :  {{ $boncommandes->fournisseur->adresse }}</li>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-industry"></i></span> Catégorie :  {{ $boncommandes->fournisseur->categoriefournisseur->libelle }}</li>
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
                                @if($message = session('error'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ $message }}
                                    </div>
                                @endif
                                <div class="card-header">
                                    <h3 class="card-title"></h3>
                                    @if ($boncommandes->montant != collect($boncommandes->recus)->sum('montant'))
                                        <a class="btn btn-success btn-sm" href="{{route('recus.create', ['boncommande'=>$boncommandes->id])}}" >
                                            <i class="fas fa-solid fa-plus"></i>
                                            Ajouter
                                        </a>
                                    @endif

                                        <a href="{{ route('boncommandes.index') }}" class="btn btn-sm btn-primary float-md-right">
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
                                            <th>Numéro</th>
                                            <th>Référence</th>
                                            <th>Date</th>
                                            <th>Libellé</th>
                                            <th>Tonnage</th>
                                            <th>Montant</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if ($boncommandes->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($boncommandes->recus as $recu)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td>{{ $recu->numero }}</td>
                                                    <td>{{ $recu->reference }}@if($recu->document)<a  class="btn btn-success text-white btn-sm float-right" href="{{ $recu->document?asset('storage/'.$recu->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>@endif</td>
                                                    <td class="text-center">{{ $recu->date?date_format(date_create($recu->date), 'd/m/Y'):'' }}</td>
                                                        <td>{{ $recu->libelle }}</td>
                                                        <td>{{ number_format($recu->tonnage,2,","," ") }}</td>
                                                        <td class="text-right">{{ number_format($recu->montant,2,","," ") }}</td>
                                                    <td class="text-center">
                                                        <!--<a class="btn btn-success btn-sm" href=""><i class="fa-regular fa-eye"></i></a>-->
                                                        @if ($boncommandes->statut == 'Préparation')
                                                            <a class="btn btn-warning btn-sm" href="{{ route('recus.edit', ['boncommande'=>$boncommandes->id, 'recu'=>$recu->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                            <a class="btn btn-danger btn-sm" href="{{ route('recus.delete', ['boncommande'=>$boncommandes->id, 'recu'=>$recu->id]) }}"><i class="fa-solid fa-trash-can"></i></a>                                                           
                                                        @endif                                                        
                                                        <a class="btn btn-success btn-sm" title="Detail commande" href="{{ route('detailrecus.index', ['recu'=>$recu->id]) }}"><i class="fa-solid fa-list-ol"></i></a>
                                                        <!--<a class="btn btn-primary btn-sm" href=""><i class="fas fa-running"></i></a>-->
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Numéro</th>
                                            <th>Référence</th>
                                            <th>Date</th>
                                            <th>Libellé</th>
                                            <th>Tonnage</th>
                                            <th>Montant</th>
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
