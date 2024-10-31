@extends('layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">ETAT DE REGLEMENT SUR VENTES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Etat des Général</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-body">
                            <form method="post" id="form_bc" action="{{route('edition.etatReglementperiode')}}">
                                @csrf
                                <div class="row no-print">
                                    <div class="col-1"></div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Date début</label>
                                            <input type="date" required class="form-control" name="debut" value="{{old('debut')}}">
                                        </div>
                                        @error('debut')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Date début</label>
                                            <input type="date" required class="form-control" name="fin" value="{{old('fin')}}">
                                        </div>
                                        @error('fin')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                            </form>

                            <div class="row">

                                <div class="col-md-12">
                                    @if(session('result'))
                                    <h4 class="col-12 text-center border border-info p-2 mb-2">
                                        Point des règlements de compte de la période du {{date_format(date_create($startDate),'d/m/Y')}} au {{date_format(date_create($endDate),'d/m/Y')}}
                                    </h4>
                                    @endif

                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reference</th>
                                                    <th>Client</th>
                                                    <th>Date</th>
                                                    <th>Montant</th>
                                                    <th>Par</th>
                                                    <th>Code</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($total = 0)

                                                @foreach($reglements as $key=>$reglement)
                                                @php($total += $reglement->montant)
                                                <tr>
                                                    <td class="text-center">{{$loop->index +1}}</td>
                                                    <td class="text-center"><span class="badge bg-warning">{{$reglement->reference}} </span></td>
                                                    
                                                    <td class="text-center">
                                                        <b>
                                                            {{$reglement->client->raisonSociale}}
                                                        </b>
                                                    </td>
                                                    <td class="text-center">{{date_format(date_create($reglement->created_at),'d/m/Y H:i')}}</td>
                                                    <td class="text-center"><span class="badge bg-success">{{number_format($reglement->montant,0,',',' ')}} </span> </td>
                                                    <td class="text-center"> <span class="badge bg-danger">{{$reglement->utilisateur?$reglement->utilisateur->name:"---"}} </span> </td>
                                                    <td class="text-center">
                                                        @if($reglement->vente)
                                                        <span class="badge bg-warning">{{$reglement->vente->code}} </span>
                                                        @else
                                                        ---
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach

                                                
                                            </tbody>
                                            <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reference</th>
                                                    <th>Client</th>
                                                    <th>Date</th>
                                                    <th>Montant</th>
                                                    <th>Par</th>
                                                    <th>Code</th>
                                                </tr>
                                            </tfoot>
                                        </table>

                                        <div class="row">
                                            <div class="col-12">
                                                <table class="table table-bordered table-sm">
                                                    <tr>
                                                        <br />
                                                        <td class="" colspan="3"><b>Total approvisionné: </b></td>
                                                        <td class="text-right"><b id="montant">{{ number_format($total,0,","," ")  }} </b></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(count($reglements)==0)
                                <div class="col-12 text-center border border-info p-2">
                                    Aucune information trouvée pour votre requête.
                                </div>
                                @endif
                            </div>

                            <div class="card-footer text-center no-print">
                                <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"],
            "order": [
                [0, 'asc']
            ],
            "pageLength": 15,
            // "columnDefs": [{
            //         "targets": 0,
            //         "orderable": false
            //     },
            //     {
            //         "targets": 1,
            //         "orderable": false
            //     },
            //     {
            //         "targets": 9,
            //         "orderable": false
            //     },
            //     {
            //         "targets": 10,
            //         "orderable": false
            //     }
            // ],
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
        const amount = new DataTable('#example1').column(4, {
            page: 'all',
            search: 'applied'
        }).data().sum()

        __V = amount < 0 ? -amount : amount
        $("#montant").html(__V.toLocaleString())
    })
</script>
@endsection