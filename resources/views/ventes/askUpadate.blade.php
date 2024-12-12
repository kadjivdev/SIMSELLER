@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>VENTES EN ATTENTES DE MODIFICATION </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item active">Listes des ventes en attente de modification</li>
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
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ $message }}
                        </div>
                        @endif

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Code Commande</th>
                                        <th>Code Pro</th>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>PU</th>
                                        <th>Qté</th>
                                        <th>Montant</th>
                                        @if(Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() == true)
                                        <th>Zone</th>
                                        @endif
                                        <th>Statut</th>
                                        <th>Utilisateur</th>
                                        <th>Actualisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($ventes->count() > 0)
                                    <?php $compteur = 1; ?>
                                    @foreach($ventes as $vente)
                                    <tr>
                                        <td>{{ $vente->code }}</td>
                                        <td class="text-center"> <span class="badge bg-warning">{{ $vente->vendus->first()?$vente->vendus->first()->programmation->code:"---" }} </span> </td>
                                        <td>{{!$vente->commandeclient->byvente ?  $vente->commandeclient->code : '' }}</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($vente->date)) }}</td>
                                        <td class="pl-2">
                                            {{ $vente->commandeclient->client->raisonSociale }}
                                        </td>
                                        <td class="text-right pr-3">{{ number_format($vente->pu,2,","," ") }}</td>
                                        <td class="text-right pr-3">{{ number_format($vente->qteTotal,2,","," ") }}</td>
                                        <td class="text-right pr-3">{{ number_format($vente->montant,2,","," ") }}</td>
                                        @if(Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() == true)
                                        <td class="pl-2">{{-- {{ $vente->commandeclient->zone->libelle }} --}} </td>
                                        @endif
                                        @if ($vente->statut == 'Vendue')
                                        @if(($vente->montant-$vente->remise) - $vente->reglements->sum('montant') == 0)
                                        <td class="text-center"><span class="badge badge-success">Soldé</span></td>
                                        @elseif( $vente->reglements->sum('montant') > 0)
                                        <td class="text-center"><span class="badge badge-warning">Solde en cours</span></td>
                                        @else
                                        <td class="text-center"><span class="badge badge-success">{{ $vente->statut }}</span></td>
                                        @endif
                                        @elseif ($vente->statut == 'Annulée')
                                        <td class="text-center"><span class="badge badge-danger">{{ $vente->statut }}</span></td>
                                        @else
                                        <td class="text-center"><span class="badge badge-info">{{ $vente->statut }}</span></td>
                                        @endif
                                        <td>{{ $vente->user?$vente->user->name:"---" }}</td>
                                        <td class="text-center">
                                            @if($vente->users == auth()->user()->id)
                                            @if ($vente->statut == 'Vendue')
                                            <a class="btn btn-primary btn-sm" href="{{ route('ventes.show', ['vente'=>$vente->id]) }}"><i class="fa fa-print"></i></a>
                                            @if(false)
                                            <a class="btn btn-info btn-sm" href="{{ route('ventes.invalider', ['vente'=>$vente->id]) }}"><i class="fa-regular fa-rectangle-xmark"></i></a>
                                            @endif
                                            @elseif($vente->statut == 'Préparation' || $vente->statut == 'En attente de modification')
                                            @if($vente->vendus->count() > 0)
                                            <a class="btn btn-success btn-sm" href="{{ route('vendus.create', ['vente'=>$vente->id]) }}" title="Valider"><i class="fa-solid fa-check"></i></a>
                                            @endif
                                            <a class="btn btn-secondary btn-sm" href="{{ route('vendus.create', ['vente'=>$vente->id]) }}" title="Ajouter Détails Vente"><i class="fa-solid fa-circle-plus"></i></a>
                                            <!--<a class="btn btn-success btn-sm" href=""><i class="fa-solid fa-circle-check"></i></a>-->
                                            <!--<a class="btn btn-warning btn-sm" href="{{ route('ventes.edit', ['vente'=>$vente->id, 'statuts'=>$vente->commandeclient->type_commande_id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>-->
                                            {{-- <a class="btn btn-danger btn-sm" href="{{ route('ventes.delete', ['vente'=>$vente->id]) }}"><i class="fa-solid fa-trash-can"></i></a> --}}
                                            @else
                                            @endif
                                            @else
                                            ---
                                            @endif
                                        </td>

                                        {{-- <td class="text-center">
                                                        @if ($vente->statut == 'Vendue')
                                                            <div class="dropdown">
                                                                <button type="button" class="dropdown-toggle btn btn-success btn-sm" href="#" role="button" data-toggle="dropdown">
                                                                    Actions<i class="dw dw-more"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-md-right dropdown-menu-icon-list drop text-sm">
                                                                    <a class="dropdown-item" href="{{route('reglements.index',['vente'=>$vente])}}"><i class="fa-solid fa-file-invoice-dollar"></i> Règlement <span class="badge badge-info">{{$vente->reglements ? count($vente->reglements):0}}</span></a>
                                        @if($vente->type_vente_id == 2)
                                        <a class="dropdown-item" href="{{route('echeances.index',['vente'=>$vente])}}"><i class="fa-solid fa-file-invoice-dollar"></i> Échéancier <span class="badge badge-info">{{$vente->echeances ? count($vente->echeances):0}}</span></a>
                                        @endif
                                        <!--<a class="dropdown-item" href=""><i class="fa-solid fa-industry"></i> Chantiers</a> -->
                        </div>
                    </div>
                    @endif
                    </td> --}}
                    </tr>
                    @endforeach
                    @endif
                    </tbody>
                    <tfoot class="text-white text-center bg-gradient-gray-dark">
                        <tr>
                            <th>Code</th>
                            <th>Code Commande</th>
                            <th>Code Pro</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>PU</th>
                            <th>Qté</th>
                            <th>Montant</th>
                            @if(Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() == true)
                            <th>Zone</th>
                            @endif
                            <th>Statut</th>
                            <th>Utilisateur</th>
                            <th>Actualisation</th>
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

@section('script')
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["pdf", "print"],
            "order": [
                [0, 'desc']
            ],
            "pageLength": 15,
            "columnDefs": [{
                    "targets": 8,
                    "orderable": false
                },
                {
                    "targets": 9,
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
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
@endsection