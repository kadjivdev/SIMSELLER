@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">REGLEMENT VENTE</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('ventes.index') }}">Vente</a></li>
                                <li class="breadcrumb-item active">Liste règlément vente</li>
                            </ol>
                        </div>
                    </div>
                    @include('reglements.entete')
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"></h3>
                                        @if (($vente->montant-$vente->remise) > collect($vente->reglements)->sum('montant'))
                                            <a class="btn btn-success btn-sm" href="{{route('reglements.create', ['vente'=>$vente->id])}}">
                                                <i class="fas fa-solid fa-plus"></i>
                                                Ajouter
                                            </a>
                                        @endif
                                        @if(($vente->montant-$vente->remise) > collect($vente->reglements)->sum('montant') && count($vente->echeances) > 0 && $vente->type_vente_id == 2)
                                            <button type="button" class="btn btn-primary btn-sm float-md-right" data-toggle="modal" data-target="#modal-default">
                                                Retour
                                            </button>
                                        @else
                                            <a href="{{ route('ventes.index' )}}" class="btn btn-sm btn-primary float-md-right">
                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        @endif
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
                                            @if ($vente->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($vente->reglements as $reglement)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td>{{ $reglement->code }}</td>
                                                    <td>{{ $reglement->reference?:'-' }}
                                                        @if ($reglement->document)
                                                            <a  class="btn btn-success float-md-right text-white btn-sm" href="{{ $reglement->document?asset('storage/'.$reglement->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>
                                                        @endif
                                                    </td>
                                                        <td class="text-center">{{ $reglement->date?date_format(date_create($reglement->date), 'd/m/Y'):'' }}</td>
                                                        <td class="text-right">{{ number_format($reglement->montant,2,","," ") }}</td>
                                                        <td class="text-center">
                                                        @if ($reglement->compte)
                                                            {{ $reglement->compte->banque->sigle }} {{ $reglement->compte->numero }}
                                                        @else
                                                            -
                                                        @endif</td>
                                                        <td>{{ $reglement->typeReglement ? $reglement->typeReglement->libelle : '-' }}</td>
                                                    <td class="text-center">
                                                        <!--<a class="btn btn-success btn-sm" href=""><i class="fa-regular fa-eye"></i></a>-->
                                                        @if(!$vente->statut_reglement)
                                                            <a class="btn btn-danger btn-sm" href="{{ route('reglements.delete',  ['vente'=>$vente->id, 'reglement'=>$reglement->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                        @elseif($reglement->statut == 2)
                                                            <span class="badge badge-warning"><i class="fa fa-check-circle fa-2x"></i></span>
                                                        @else
                                                            <span class="badge badge-warning"><i class="fa fa-check-circle fa-2x"></i></span>
                                                        @endif

                                                        <!--<a class="btn btn-secondary btn-sm" href=""><i class="fa-solid fa-bed"></i></a>
                                                        <a class="btn btn-primary btn-sm" href=""><i class="fas fa-running"></i></a>-->
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

            <div class="modal fade" id="modal-default">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Définition échéance</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                            <div class="modal-body">
                                <div class="col-md-12 alert alert-info">
                                   Voulez vous mettre à jour l'échéance de règlement ?
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <a href="{{ route('ventes.index') }}" class="btn btn-sm btn-secondary">
                                    {{ __('Nom') }}
                                </a>
                                <a href="{{ route('echeances.index',['vente'=>$vente->id]) }}" class="btn btn-sm btn-primary">
                                    {{ __('Oui') }}
                                </a>
                            </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade" id="validation_reglement">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h4 class="modal-title">ATTENTION!</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{route('reglements.valider',['vente'=>$vente->id])}}" id="validation_reglement">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12 alert alert-warning " >
                                        <h3 class="text-danger font-italic">Lorsque vous validez le(s) règlement(s) de cette vente, vous ne pouvez plus apporter des modifications. Êtes vous sûr de cette opération ?</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <div class="col-sm-4">
                                    <button data-dismiss="modal" class="btn btn-sm btn-secondary btn-block">
                                        <i class="fa-solid fa-times"></i>
                                        {{ __('Non') }}
                                    </button>
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-success btn-block">Oui
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
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
                    [1, 'asc']
                ],
                "pageLength": 100,
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
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');})
    </script>
@endsection
