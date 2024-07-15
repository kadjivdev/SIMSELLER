@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">EDITION PROGRAMME</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Programmations</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <form action="{{route('programmations.postEdition')}}" method="post" id="formProgramme">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-1 ">
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label for="">Jour Début</label>
                                                        <input type="date" value="{{old('debut')}}" name="debut" class="form-control" required >
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="">Jour Fin</label>
                                                        <input type="date" value="{{old('fin')}}" name="fin" class="form-control" required >
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">Fournisseur</label>
                                                        <select name="fournisseur" class="form-control"  id="" required>
                                                            <option value="">--Sélection le fournisseur--</option>
                                                            @foreach($fournisseurs as $forunisseur)*
                                                                <option value="{{$forunisseur->id}}" {{old('fournisseur') == $forunisseur->id ? 'selected' : ''}}>{{$forunisseur->raisonSociale}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-primary" style="margin-top: 2.2em">Afficher</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-1 ">
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    @if(is_array(session('programmes')) && count(session('programmes'))>0):
                                        <div class="row">
                                            <div class="col-md-2">
                                            </div>
                                            <div class="col-md-8 border border-primary mb-2 text-center">
                                                <h5>PROGRAMMATION DE LA JOURNEE DU {{date_format(date_create(session('dateProgramme')),'d/m/Y')}}</h5>
                                                <b>Fournisseur: </b>{{session('fournisseur')->raisonSociale}}
                                            </div>
                                            <div class="col-md-2">
                                            </div>
                                        </div>

                                            <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                                <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Camion</th>
                                                    <th>Chauffeur</th>
                                                    <th>Produit</th>
                                                    <th>Quantité</th>
                                                    <th>Zone</th>
                                                    <th>Avaliseur</th>
                                                    <th>Imprimer</th>
                                                </tr>
                                                </thead>
                                                <tbody class="card-body">
                                                    @foreach(session('programmes') as $key=> $programme)
                                                        <tr>
                                                            <td>{{$key + 1}}</td>
                                                            <td>{{$programme->immatriculationTracteur}}</td>
                                                            <td>{{$programme->chauffeur}}</td>
                                                            <td>{{$programme->produit}}</td>
                                                            <td class="text-right">{{number_format($programme->qteprogrammer,2,',',' ')}}</td>
                                                            <td>{{$programme->zone}}</td>
                                                            <td>{{$programme->avaliseur}}</td>
                                                            <td class="text-center">@if($programme->imprimer) <i class="fa fa-print"></i> @endif</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        <div class="row" id="btn" hidden>
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8 mt-3 text-center">
                                                <a href="" class="btn btn-default">Retour</a>

                                                @if(session('imprimer'))
                                                    <a target="_blank" rel="noopener"  href="{{route('programmations.impression',['jour'=>session('dateProgramme'),'fournisseur'=>session('fournisseur')])}}" class="btn btn-success"><i class="fa fa-print"></i> Imprimer</a>
                                                @else
                                                    <button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                                                       Valider & Imprimer
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    @elseif(is_array(session('programmes')))
                                        <div class="row">
                                            <div class="col-md-12 alert alert-info">
                                                <i class="fa fa-info-circle"></i> Aucun programme de chargement n'est prévu pour le {{date_format(date_create(session('dateProgramme')),'d/m/Y')}}.
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8 mt-3 text-center">
                                                <a href="" class="btn btn-default">Retour</a>
                                            </div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    @endif
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
                @if(session('fournisseur'))
                <div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-center">Confirmation impression</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="fa fa-warning"></i>L'impression des ligne de programme est une opération irreversible. Elle valide
                                    les lignes comme imprimer et elles ne pourront plus être modifiées ni supprimées. Êtes vous suir de cette opération ?
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between" >
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button class="btn btn-primary" onclick="imprimEtat()">Oui je confirme </button>
                            </div>
                        </div>

                    </div>

                </div>
                    @endif
            </section>
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function(){
                $('#btn').removeAttr('hidden');
            })
            function imprimEtat()
            {
                window.open("{{ session('lien') }}", '_blank');
                window.location.reload();
            }

            $(function () {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["excel","pdf", "print"],
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
