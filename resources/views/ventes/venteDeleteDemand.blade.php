@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>DEMANDE DE SUPPRESSION DE VENTES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item active">Listes des ventes</li>
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
                                        <th>N°</th>
                                        <th>Code Vente</th>
                                        <th>Demandeur</th>
                                        <th>Statut</th>
                                        <th>Raison</th>
                                        <th>Preuve</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    @if (count($venteDeleteDemands) > 0)
                                    @foreach($venteDeleteDemands as $demand)
                                    @if(!$demand->deleted)
                                    <tr style="align-items: center;">
                                        <td>{{ $demand->deleted }}</td>
                                        <td>
                                            @if($demand->_Vente)
                                            {{$demand->_Vente->code}}
                                            @else
                                            <span class="text-danger">supprimé</span>
                                            @endif
                                        </td>
                                        <td>{{$demand->_Demandeur->name}}</td>
                                        <td>
                                            @if($demand->valide)
                                            <span class="bg-success text-white p-1">Validé</span> &nbsp;
                                            @if($demand->deleted)
                                            <span class="bg-success text-white p-1">Déjà Supprimée</span>
                                            @endif
                                            @else
                                            <span class="bg-warning text-white p-1">En attente</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <textarea name="" class="form-control" rows="1" id="">{{$demand->raison}}
                                            </textarea>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{$demand->prouve_file}}" target="_blank" rel="noopener noreferrer">
                                                <img src="{{$demand->prouve_file}}" title="Visualiser" alt="" style="width: 50px;border-radius:10px;cursor:pointer">
                                            </a>
                                        </td>
                                        <td class="d-flex">
                                            <form id="update_form" action="{{route('ventes.deleteValidation')}}" method="post">
                                                @csrf
                                                <input type="text" name="demand" value="{{$demand->id}}" hidden>
                                                <button type="submit" class="btn btn-sm btn-success mr-2" @if($demand->valide || $demand->deleted) disabled @endif ><i class="bi bi-check-circle"></i> </button>
                                            </form>

                                            @if(!$demand->valide)
                                            <form id="update_form" action="{{route('ventes.DeleteDemandVenteDelete',$demand->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mt-1"><i class="bi bi-x-square-fill"></i></button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @foreach($venteDeleteDemands as $demand)
                                    @if($demand->deleted)
                                    <tr class="bg-secondary" style="align-items: center;">
                                        <td>{{ $demand->deleted }}</td>
                                        <td>
                                            @if($demand->_Vente)
                                            {{$demand->_Vente->code}}
                                            @else
                                            <span class="text-danger">supprimé</span>
                                            @endif
                                        </td>
                                        <td>{{$demand->_Demandeur->name}}</td>
                                        <td>
                                            <span class="bg-success text-white p-1">Déjà Supprimée</span>
                                        </td>
                                        <td class="text-center">
                                            <textarea name="" class="form-control" rows="1" id="">{{$demand->raison}}
                                            </textarea>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{$demand->prouve_file}}" target="_blank" rel="noopener noreferrer">
                                                <img src="{{$demand->prouve_file}}" title="Visualiser" alt="" style="width: 50px;border-radius:10px;cursor:pointer">
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            ---
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @else
                                    <p class="text-center">Aucune demande en attente!</p>
                                    @endif
                                </tbody>
                                <tfoot class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>N°</th>
                                        <th>Code Vente</th>
                                        <th>Demandeur</th>
                                        <th>Statut</th>
                                        <th>Raison</th>
                                        <th>Preuve</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if(!(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()))
                        <div class="card-footer text-center no-print">
                            <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                        </div>
                        @endif
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
            "buttons": ["excel", "pdf", "print"],
            "pageLength": 15,
            "order": [
                [0, 'asc']
            ],
            "columnDefs": [{
                    "targets": 5,
                    "orderable": false
                },
                {
                    "targets": 6,
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
</script>

@endsection