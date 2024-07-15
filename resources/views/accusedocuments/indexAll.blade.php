@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">ACCUSE DOCUMENTS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Liste des accusés de doment</li>
                            </ol>
                        </div>
                    </div>
                    
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                               <div class="card-heard">
                                <form action="{{ route('accusedocuments.indexAll') }}" method="get">
                                    
                                    <div class="row ">
                                        <div class="col-2"></div>
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
                                    <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Commande</th>
                                            <th>Code</th>
                                            <th>Référence</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Montant</th>
                                            <th>Observation</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="table-body">
                                            <?php $compteur=1; ?>
                                            @foreach($accuseDocuments as $accusedocument)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td>{{ $accusedocument->boncommande->code }}</td>
                                                    <td>{{ $accusedocument->code }}</td>
                                                    <td>{{ $accusedocument->reference }}@if($accusedocument->document)<a  class="btn btn-success text-white btn-sm float-right" href="{{ $accusedocument->document?asset('storage/'.$accusedocument->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>@endif</td>
                                                    <td class="text-center">{{ $accusedocument->date?date_format(date_create($accusedocument->date), 'd/m/Y'):'' }}</td>
                                                        <td>{{ $accusedocument->typedocument->libelle }}</td>
                                                        <td class="text-right">{{ number_format($accusedocument->montant,2,","," ") }}</td>
                                                        <td>{{ $accusedocument->observation }}</td>
                                                    <td class="text-center">
                                                        <!--<a class="btn btn-success btn-sm" href=""><i class="fa-regular fa-eye"></i></a>-->
                                                        <a class="btn btn-warning btn-sm" href="{{ route('accusedocuments.edit', ['boncommande'=>$accusedocument->boncommande->id, 'accusedocument'=>$accusedocument->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                        <a class="btn btn-danger btn-sm" href="{{ route('accusedocuments.delete', ['boncommande'=>$accusedocument->boncommande->id, 'accusedocument'=>$accusedocument->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                        <!--<a class="btn btn-secondary btn-sm" href=""><i class="fa-solid fa-bed"></i></a>
                                                        <a class="btn btn-primary btn-sm" href=""><i class="fas fa-running"></i></a>-->
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Commande</th>
                                            <th>Code</th>
                                            <th>Référence</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Montant</th>
                                            <th>Observation</th>
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
    @section('script')
    <script>
        $(function () {

            $("#example1").DataTable({

                "responsive": true, "lengthChange": false, "autoWidth": false,

                "buttons": ["excel","pdf", "print"],

                "order": [[0, 'desc']],

                "pageLength": 100,

                "columnDefs": [

                    {

                        "targets": 7,

                        "orderable": false

                    },

                    {

                        "targets": 8,

                        "orderable": false

                    }

                ],

                language:{

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
