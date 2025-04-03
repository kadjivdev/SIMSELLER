@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <section class="content-header">

        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>BON DE COMMANDES</h1>
                </div>

                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>

                        <li class="breadcrumb-item active">Listes des bons de commande</li>

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
                        @if($message = session('error'))
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ $message }}
                        </div>
                        @endif

                        <div class="card-header">
                            <form id="statutsForm" action="" method="get">
                                <h3 class="card-title"></h3>
                                <div class="row">
                                    <div class="col-sm-3">
                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DE BON DE COMMANDE'])->exists())
                                        <a class="btn btn-success btn-sm" href="{{route('boncommandes.create')}}">
                                            <i class="fas fa-solid fa-plus"></i>
                                            Créer
                                        </a>
                                        @endif
                                    </div>
                                    <div class="col-sm-9 ">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1"></div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Date début</label>
                                            <input type="date" class="form-control" name="debut" value="{{old('debut')}}" required>
                                        </div>
                                        @error('debut')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Date Fin</label>
                                            <input type="date" class="form-control" name="fin" value="{{old('fin')}}" required>
                                        </div>
                                        @error('fin')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- bon non en préparation -->
                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Code/Reference</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Qte com</th>
                                        <th>Qte programmée</th>
                                        <th>Qte vendus</th>
                                        <th>Stock</th>
                                        <th>Agent</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Utilisateur</th>
                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DE BON DE COMMANDE'])->exists())
                                        <th>Actualisation</th>
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="table-body">

                                    @if ($boncommandes->count() > 0)
                                    <?php $compteur = 1; ?>
                                    <?php $total = 0; ?>
                                    <?php $MontantTotal = 0; ?>

                                    @foreach($pre_boncommandes as $boncommande)
                                    <?php
                                    $new = isset($boncommande->detailboncommandes[0]->qteCommander) ? $boncommande->detailboncommandes[0]->qteCommander : 0;
                                    $total = $total + $new;

                                    $MontantTotal = $MontantTotal + $boncommande->montant;

                                    // Qte programmée
                                    $QteProgrammee = isset($boncommande->detailboncommandes[0]) ? $boncommande->detailboncommandes[0]->programmations->whereIn('statut', ['Valider', 'Livrer'])->sum("qteprogrammer") : 0;
                                    // Qte vendus
                                    $QteVendue =  isset($boncommande->detailboncommandes[0]) ? $boncommande->detailboncommandes[0]->programmations->sum(function ($programmation) {
                                        return $programmation->vendus->sum("qteVendu");
                                    }) : 0;
                                    ?>

                                    <tr class="bg-info">
                                        <td>1 {{ $boncommande->code }} / @foreach($boncommande->recus as $recu)
                                            <span class="badge bg-dark">REF_{{$recu->reference}} </span>
                                            @endforeach
                                        </td>

                                        <td class="text-center">
                                            @foreach($boncommande->recus as $recu)
                                            <span class="badge bg-dark"> {{$recu->reference}} </span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($boncommande->dateBon)) }}</td>
                                        <td class="pl-2">{{ $boncommande->fournisseur->sigle }}</td>
                                        <td class="pl-2 qte">{{ number_format(isset($boncommande->detailboncommandes[0]->qteCommander) ? $boncommande->detailboncommandes[0]->qteCommander:0,2," "," ") }}</td>
                                        <td class="pl-2 "><span class="badge bg-success"> {{ number_format($QteProgrammee,2,"."," ") }}</span> </td>
                                        <td class="pl-2 "><span class="badge bg-success"> {{number_format($QteVendue,2,'.',' ')}}</span> </td>
                                        <td class="pl-2 "><span class="badge bg-danger"> {{number_format($QteProgrammee-$QteVendue,2,'.',' ')}}</span> </td>
                                        <td class="" style="width:auto;">
                                            <div style="width:auto;height:100px!important;overflow-y: scroll">
                                                @if(isset($boncommande->detailboncommandes[0]))
                                                @foreach($boncommande->detailboncommandes[0]->programmations as $programmation)
                                                @if($programmation->qteprogrammer>$programmation->vendus->sum("qteVendu"))
                                                @if($programmation->zone)
                                                <span class="badge d-block bg-dark">{{$programmation->zone->_user?->name}} (blguest/bl : {{$programmation->bl_gest}}/{{$programmation->bl}} ; Reste : {{$programmation->qteprogrammer-$programmation->vendus->sum("qteVendu")}})</span>
                                                <span class="badge d-block bg-dark">Qte Prog: {{$programmation->qteprogrammer}} ; QteVendue : {{$programmation->vendus->sum("qteVendu")}}</span>
                                                <hr>
                                                @endif
                                                @endif
                                                @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-right pr-3">{{ isset($boncommande->detailboncommandes[0]->pu) ? $boncommande->detailboncommandes[0]->pu:0}}</td>
                                        <td class="text-right pr-3 montant">{{$boncommande->montant }}</td>
                                        <td class="pl-2">{{ $boncommande->typecommande->libelle }}</td>
                                        @if ($boncommande->statut == 'Valider')
                                        <td class="text-center"><span class="badge badge-success">{{ $boncommande->statut }}</span></td>
                                        @elseif ($boncommande->statut == 'Préparation')
                                        <td class="text-center"><span class="badge badge-info">{{ $boncommande->statut }}</span></td>
                                        @elseif ($boncommande->statut == 'Livrer')
                                        <td class="text-center"><span class="badge badge-secondary">{{ $boncommande->statut }}</span></td>
                                        @elseif ($boncommande->statut == 'Programmer')
                                        <td class="text-center"><span class="badge badge-primary">{{ $boncommande->statut }}</span></td>
                                        @elseif($boncommande->statut == 'Envoyé')
                                        <td class="text-center"><span class="badge badge-primary">En attente de validation</span></td>
                                        @else
                                        <td class="text-center"><span class="badge badge-dark">{{ $boncommande->statut }}</span></td>
                                        @endif
                                        <td>{{ $boncommande->users }}</td>

                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DE BON DE COMMANDE'])->exists())
                                        <td class="text-center">
                                            @if(count($boncommande->detailboncommandes) > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('boncommandes.show', ['boncommande'=>$boncommande->id]) }}" title="Voir détail et imprimer"><i class="fa-regular fa-eye"></i></a>
                                            @endif

                                            @if(Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists())
                                            @if(count($boncommande->recus) > 0 && $boncommande->statut == 'Envoyé')
                                            <a class="btn btn-success btn-sm" href="{{ route('boncommandes.valider', ['boncommande'=>$boncommande->id]) }}" title="Valider la commande "><i class="fa-solid fa-circle-check"></i></a>
                                            @endif
                                            @endif

                                            @if ($boncommande->statut == 'Préparation')
                                            <a class="btn btn-secondary btn-sm" href="{{ route('boncommandes.edit', ['boncommande'=>$boncommande->id]) }}"><i class="fa-solid fa-circle-plus"></i></a>
                                            @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists())
                                            <a class="btn btn-warning btn-sm" href="{{ route('boncommandes.create', ['boncommandes'=>$boncommande->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>

                                            @endif
                                            @elseif ($boncommande->statut == 'Valider' && count($boncommande->recus) == 0)
                                            <a class="btn btn-info btn-sm" href="{{ route('boncommandes.invalider', ['boncommande'=>$boncommande->id]) }}"><i class="fa-regular fa-rectangle-xmark"></i></a>
                                            @endif

                                            @if ($boncommande->statut == 'Valider')
                                            <a class="btn btn-danger btn-sm" href="{{ route('boncommandes.delete', ['boncommande'=>$boncommande->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="dropdown-toggle btn btn-success btn-sm" href="#" role="button" data-toggle="dropdown" @if(count($boncommande->detailboncommandes) == 0 ) disabled @endif>
                                                    Actions<i class="dw dw-more"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-md-right dropdown-menu-icon-list drop text-sm">
                                                    <a class="dropdown-item" href="{{ route('recus.index', ['boncommandes'=>$boncommande->id]) }}" title="Enregistrer reçus"><i class="fa-solid fa-file-invoice-dollar"></i> Reçu <span class="badge badge-info">{{count($boncommande->recus)}}</span></a>
                                                    <a class="dropdown-item" href="{{ route('accusedocuments.index', ['boncommandes'=>$boncommande->id]) }}" target="_blank" title="Enregistrer document"><i class="fa-solid fa-file-invoice"></i> Accusé <span class="badge badge-info">{{count($boncommande->accusedocuments)}}</span></a>
                                                    @if(Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists()== true)
                                                    @if( (count($boncommande->recus) > 0) && ($boncommande->statut == 'Envoyé') )
                                                    <a class="dropdown-item" href="{{ route('boncommandes.retourner', ['boncommandes'=>$boncommande->id]) }}"><i class="fa-solid fa-undo"></i> Retourner </a>
                                                    @endif
                                                    @endif
                                                    @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()== true)
                                                    @if( (count($boncommande->recus) > 0) && ($boncommande->statut == 'Préparation') )
                                                    <a class="dropdown-item" href="{{ route('boncommandes.envoyer', ['boncommandes'=>$boncommande->id]) }}"><i class="fa-solid fa-paper-plane"></i> Envoyer </a>
                                                    @endif
                                                    @endif
                                                    <!--<a class="dropdown-item" href=""><i class="fa-solid fa-industry"></i> Chantiers</a> -->
                                                </div>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach

                                    @foreach($boncommandes as $boncommande)
                                    <?php
                                    $new = isset($boncommande->detailboncommandes[0]->qteCommander) ? $boncommande->detailboncommandes[0]->qteCommander : 0;
                                    $total = $total + $new;

                                    $MontantTotal = $MontantTotal + $boncommande->montant;

                                    // Qte programmée
                                    $QteProgrammee = isset($boncommande->detailboncommandes[0]) ? $boncommande->detailboncommandes[0]->programmations->whereIn('statut', ['Valider', 'Livrer'])->sum("qteprogrammer") : 0;
                                    // Qte vendus
                                    $QteVendue =  isset($boncommande->detailboncommandes[0]) ? $boncommande->detailboncommandes[0]->programmations->sum(function ($programmation) {
                                        return $programmation->vendus->sum("qteVendu");
                                    }) : 0;
                                    ?>

                                    <tr class="">
                                        <td>{{ $boncommande->code }} / @foreach($boncommande->recus as $recu)
                                            <span class="badge bg-dark">REF_{{$recu->reference}} </span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($boncommande->dateBon)) }}</td>
                                        <td class=""><span class="badge bg-light"> {{ $boncommande->fournisseur->sigle }}</span></td>
                                        <td class="qte"><span class="badge bg-warning"> {{number_format(isset($boncommande->detailboncommandes[0]->qteCommander) ? $boncommande->detailboncommandes[0]->qteCommander:0,2," "," ") }} </span></td>
                                        <td class=""><span class="badge bg-success"> {{ number_format($QteProgrammee,2,"."," ") }}</span> </td>
                                        <td class=""><span class="badge bg-success"> {{number_format($QteVendue,2,'.',' ')}}</span> </td>
                                        <td class=""><span class="badge bg-danger"> {{number_format($QteProgrammee-$QteVendue,2,'.',' ')}}</span> </td>
                                        <td class="" style="width:auto;">
                                            <div style="width:auto;height:100px!important;overflow-y: scroll">
                                                @if(isset($boncommande->detailboncommandes[0]))
                                                @foreach($boncommande->detailboncommandes[0]->programmations as $programmation)
                                                @if($programmation->qteprogrammer>$programmation->vendus->sum("qteVendu"))
                                                @if($programmation->zone)
                                                <span class="badge d-block bg-dark">{{$programmation->zone->_user?->name}} (blguest/bl : {{$programmation->bl_gest}}/{{$programmation->bl}} ; Reste : {{$programmation->qteprogrammer-$programmation->vendus->sum("qteVendu")}})</span>
                                                <span class="badge d-block bg-dark">Qte Prog: {{$programmation->qteprogrammer}} ; QteVendue : {{$programmation->vendus->sum("qteVendu")}}</span>
                                                <hr>
                                                @endif
                                                @endif
                                                @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-right pr-3">{{ isset($boncommande->detailboncommandes[0]->pu) ? $boncommande->detailboncommandes[0]->pu:0}}</td>
                                        <td class="text-right pr-3 montant">{{$boncommande->montant }}</td>
                                        <td class="pl-2">{{ $boncommande->typecommande->libelle }}</td>
                                        @if ($boncommande->statut == 'Valider')
                                        <td class="text-center"><span class="badge badge-success">{{ $boncommande->statut }}</span></td>
                                        @elseif ($boncommande->statut == 'Préparation')
                                        <td class="text-center"><span class="badge badge-info">{{ $boncommande->statut }}</span></td>
                                        @elseif ($boncommande->statut == 'Livrer')
                                        <td class="text-center"><span class="badge badge-secondary">{{ $boncommande->statut }}</span></td>
                                        @elseif ($boncommande->statut == 'Programmer')
                                        <td class="text-center"><span class="badge badge-primary">{{ $boncommande->statut }}</span></td>
                                        @elseif($boncommande->statut == 'Envoyé')
                                        <td class="text-center"><span class="badge badge-primary">En attente de validation</span></td>
                                        @else
                                        <td class="text-center"><span class="badge badge-dark">{{ $boncommande->statut }}</span></td>
                                        @endif
                                        <td>{{ $boncommande->users }}</td>

                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DE BON DE COMMANDE'])->exists())
                                        <td class="text-center">
                                            @if(count($boncommande->detailboncommandes) > 0)
                                            <a class="btn btn-primary btn-sm" href="{{ route('boncommandes.show', ['boncommande'=>$boncommande->id]) }}" title="Voir détail et imprimer"><i class="fa-regular fa-eye"></i></a>
                                            @endif

                                            @if(Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists())
                                            @if(count($boncommande->recus) > 0 && $boncommande->statut == 'Envoyé')
                                            <a class="btn btn-success btn-sm" href="{{ route('boncommandes.valider', ['boncommande'=>$boncommande->id]) }}" title="Valider la commande "><i class="fa-solid fa-circle-check"></i></a>
                                            @endif
                                            @endif

                                            @if ($boncommande->statut == 'Préparation')
                                            <a class="btn btn-secondary btn-sm" href="{{ route('boncommandes.edit', ['boncommande'=>$boncommande->id]) }}"><i class="fa-solid fa-circle-plus"></i></a>
                                            @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists())
                                            <a class="btn btn-warning btn-sm" href="{{ route('boncommandes.create', ['boncommandes'=>$boncommande->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>

                                            @endif
                                            @elseif ($boncommande->statut == 'Valider' && count($boncommande->recus) == 0)
                                            <a class="btn btn-info btn-sm" href="{{ route('boncommandes.invalider', ['boncommande'=>$boncommande->id]) }}"><i class="fa-regular fa-rectangle-xmark"></i></a>
                                            @endif

                                            @if ($boncommande->statut == 'Valider')
                                            <a class="btn btn-danger btn-sm" href="{{ route('boncommandes.delete', ['boncommande'=>$boncommande->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button type="button" class="dropdown-toggle btn btn-success btn-sm" href="#" role="button" data-toggle="dropdown" @if(count($boncommande->detailboncommandes) == 0 ) disabled @endif>
                                                    Actions<i class="dw dw-more"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-md-right dropdown-menu-icon-list drop text-sm">
                                                    <a class="dropdown-item" href="{{ route('recus.index', ['boncommandes'=>$boncommande->id]) }}" title="Enregistrer reçus"><i class="fa-solid fa-file-invoice-dollar"></i> Reçu <span class="badge badge-info">{{count($boncommande->recus)}}</span></a>
                                                    <a class="dropdown-item" href="{{ route('accusedocuments.index', ['boncommandes'=>$boncommande->id]) }}" target="_blank" title="Enregistrer document"><i class="fa-solid fa-file-invoice"></i> Accusé <span class="badge badge-info">{{count($boncommande->accusedocuments)}}</span></a>
                                                    @if(Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists()== true)
                                                    @if( (count($boncommande->recus) > 0) && ($boncommande->statut == 'Envoyé') )
                                                    <a class="dropdown-item" href="{{ route('boncommandes.retourner', ['boncommandes'=>$boncommande->id]) }}"><i class="fa-solid fa-undo"></i> Retourner </a>
                                                    @endif
                                                    @endif
                                                    @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()== true)
                                                    @if( (count($boncommande->recus) > 0) && ($boncommande->statut == 'Préparation') )
                                                    <a class="dropdown-item" href="{{ route('boncommandes.envoyer', ['boncommandes'=>$boncommande->id]) }}"><i class="fa-solid fa-paper-plane"></i> Envoyer </a>
                                                    @endif
                                                    @endif
                                                    <!--<a class="dropdown-item" href=""><i class="fa-solid fa-industry"></i> Chantiers</a> -->
                                                </div>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                                <tfoot class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Code/Reference</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Qte com</th>
                                        <th>Qte programmée</th>
                                        <th>Qte vendus</th>
                                        <th>Stock</th>
                                        <th>Agent</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Utilisateur</th>
                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DE BON DE COMMANDE'])->exists())
                                        <th>Actualisation</th>
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <br />
                                            <td class="" colspan="2"><b>Total Bon de Commande</b></td>
                                            <td colspan="6" class="text-right"><b id="qte">{{ number_format($total ?? 0,0,","," ")  }} Tonne(s)</b></td>
                                        </tr>
                                        <tr>
                                            <br />
                                            <td class="" colspan="2"><b>Total Montant Bon de Commande</b></td>
                                            <td colspan="6" class="text-right"><b id="montant">{{ number_format($MontantTotal ?? 0,0,","," ") }} FCFA</b></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
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

@section('script')
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": true,
            "buttons": ["excel", "pdf", "print"],
            "order": [
                [0, 'asc']
            ],
            "pageLength": 15,
            "columnDefs": [{
                    "targets": 2,
                    "orderable": false
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
            language: {
                "emptyTable": "Aucune donnée disponible dans le tableau",
                "lengthMenu": "Afficher _MENU_ éléments",
                "loadingRecords": "Chargement...",
                "processing": "Traitement...",
                "zeroRecords": "Aucun élément correspondant trouvé",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "previous": "Précédent",
                    "next": "Suiv"
                },
                "aria": {
                    "sortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                },
                "select": {
                    "rows": {
                        "_": "%d lignes sélectionnées",
                        "1": "1 ligne sélectionnée"
                    },
                    "cells": {
                        "1": "1 cellule sélectionnée",
                        "_": "%d cellules sélectionnées"
                    },
                    "columns": {
                        "1": "1 colonne sélectionnée",
                        "_": "%d colonnes sélectionnées"
                    }
                },
                "autoFill": {
                    "cancel": "Annuler",
                    "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                    "fillHorizontal": "Remplir les cellules horizontalement",
                    "fillVertical": "Remplir les cellules verticalement"
                },
                "searchBuilder": {
                    "conditions": {
                        "date": {
                            "after": "Après le",
                            "before": "Avant le",
                            "between": "Entre",
                            "empty": "Vide",
                            "equals": "Egal à",
                            "not": "Différent de",
                            "notBetween": "Pas entre",
                            "notEmpty": "Non vide"
                        },
                        "number": {
                            "between": "Entre",
                            "empty": "Vide",
                            "equals": "Egal à",
                            "gt": "Supérieur à",
                            "gte": "Supérieur ou égal à",
                            "lt": "Inférieur à",
                            "lte": "Inférieur ou égal à",
                            "not": "Différent de",
                            "notBetween": "Pas entre",
                            "notEmpty": "Non vide"
                        },
                        "string": {
                            "contains": "Contient",
                            "empty": "Vide",
                            "endsWith": "Se termine par",
                            "equals": "Egal à",
                            "not": "Différent de",
                            "notEmpty": "Non vide",
                            "startsWith": "Commence par"
                        },
                        "array": {
                            "equals": "Egal à",
                            "empty": "Vide",
                            "contains": "Contient",
                            "not": "Différent de",
                            "notEmpty": "Non vide",
                            "without": "Sans"
                        }
                    },
                    "add": "Ajouter une condition",
                    "button": {
                        "0": "Recherche avancée",
                        "_": "Recherche avancée (%d)"
                    },
                    "clearAll": "Effacer tout",
                    "condition": "Condition",
                    "data": "Donnée",
                    "deleteTitle": "Supprimer la règle de filtrage",
                    "logicAnd": "Et",
                    "logicOr": "Ou",
                    "title": {
                        "0": "Recherche avancée",
                        "_": "Recherche avancée (%d)"
                    },
                    "value": "Valeur"
                },
                "searchPanes": {
                    "clearMessage": "Effacer tout",
                    "count": "{total}",
                    "title": "Filtres actifs - %d",
                    "collapse": {
                        "0": "Volet de recherche",
                        "_": "Volet de recherche (%d)"
                    },
                    "countFiltered": "{shown} ({total})",
                    "emptyPanes": "Pas de volet de recherche",
                    "loadMessage": "Chargement du volet de recherche..."
                },
                "buttons": {
                    "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                    "collection": "Collection",
                    "colvis": "Visibilité colonnes",
                    "colvisRestore": "Rétablir visibilité",
                    "copy": "Copier",
                    "copySuccess": {
                        "1": "1 ligne copiée dans le presse-papier",
                        "_": "%ds lignes copiées dans le presse-papier"
                    },
                    "copyTitle": "Copier dans le presse-papier",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pageLength": {
                        "-1": "Afficher toutes les lignes",
                        "_": "Afficher %d lignes"
                    },
                    "pdf": "PDF",
                    "print": "Imprimer"
                },
                "decimal": ",",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                "infoThousands": ".",
                "search": "Rechercher:",
                "thousands": ".",
                "infoFiltered": "(filtrés depuis un total de _MAX_ éléments)",
                "datetime": {
                    "previous": "Précédent",
                    "next": "Suivant",
                    "hours": "Heures",
                    "minutes": "Minutes",
                    "seconds": "Secondes",
                    "unknown": "-",
                    "amPm": [
                        "am",
                        "pm"
                    ],
                    "months": [
                        "Janvier",
                        "Fevrier",
                        "Mars",
                        "Avril",
                        "Mai",
                        "Juin",
                        "Juillet",
                        "Aout",
                        "Septembre",
                        "Octobre",
                        "Novembre",
                        "Decembre"
                    ],
                    "weekdays": [
                        "Dim",
                        "Lun",
                        "Mar",
                        "Mer",
                        "Jeu",
                        "Ven",
                        "Sam"
                    ]
                },
                "editor": {
                    "close": "Fermer",
                    "create": {
                        "button": "Nouveaux",
                        "title": "Créer une nouvelle entrée",
                        "submit": "Envoyer"
                    },
                    "edit": {
                        "button": "Editer",
                        "title": "Editer Entrée",
                        "submit": "Modifier"
                    },
                    "remove": {
                        "button": "Supprimer",
                        "title": "Supprimer",
                        "submit": "Supprimer",
                        "confirm": {
                            "1": "etes-vous sure de vouloir supprimer 1 ligne?",
                            "_": "etes-vous sure de vouloir supprimer %d lignes?"
                        }
                    },
                    "error": {
                        "system": "Une erreur système s'est produite"
                    },
                    "multi": {
                        "title": "Valeurs Multiples",
                        "restore": "Rétablir Modification",
                        "noMulti": "Ce champ peut être édité individuellement, mais ne fait pas partie d'un groupe. ",
                        "info": "Les éléments sélectionnés contiennent différentes valeurs pour ce champ. Pour  modifier et "
                    }
                }
            },
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });

    $("body").on('change', function() {
        var table = $('#example1').DataTable();

        const newQte = new DataTable('#example1').column(3, {
            page: 'all',
            search: 'applied'
        }).data().sum()

        const montant = new DataTable('#example1').column(9, {
            page: 'all',
            search: 'applied'
        }).data().sum()

        const _newQte = newQte>0?newQte:-newQte
        const _montant = montant>0?montant:-montant

        $("#qte").html(new Intl.NumberFormat().format(_newQte) + " Tonnes ")
        $("#montant").html(new Intl.NumberFormat().format(_montant) + " FCFA ")
    })
</script>

<script>
    function submitStatuts() {
        $('#statutsForm').submit();
    }
</script>
@endsection