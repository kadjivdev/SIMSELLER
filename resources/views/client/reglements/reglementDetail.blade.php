@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">REGLEMENT DE DETTE</h1>
                    <strong class="">Client: </strong> <em>{{$client->nom}} {{$client->prenom}} --- {{$client->raisonSociale}} </em> <br>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="text-center">
                        @if(session()->has("message"))
                        <div class="text-center alert alert-success"> {{session()->get("message")}} </div>
                        @endif

                        @if(session()->has("error"))
                        <div class="text-center alert alert-danger"> {{session()->get("error")}} </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-12">

                    <!-- TABLE -->
                    <h3 class="text-center">Liste des règlements de dettes</h3>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                            <thead class="text-white text-center bg-gradient-gray-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Compte</th>
                                    <th>Type</th>
                                    <th>Preuve</th>
                                    <th>Opérateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->_detteReglements as $rglment)
                                <tr>
                                    <td>{{ $loop->index +1 }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->reference }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->date }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->montant }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->_Compte->numero }} ({{ $rglment->_Compte->intitule }}) </td>
                                    <td class="ml-5 pr-5">{{ $rglment->_TypeDetailRecu->libelle }} </td>
                                    <td class="ml-5 pr-5 text-center">
                                        <a target="_blank" href="{{$rglment->document}}" class="btn btn-success btn-sm">
                                            <!-- <img src="{{$rglment->_document}}" alt="" srcset=""> -->
                                            <i class="bi bi-filetype-pdf"></i>
                                        </a>
                                    </td>
                                    <td class="ml-5 pr-5">{{ $rglment->_Operator->name }} </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="text-white text-center bg-gradient-gray-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Compte</th>
                                    <th>Type</th>
                                    <th>Preuve</th>
                                    <th>Operateur</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

@endsection
@section('script')
<script>
    // $('document').ready(function() {

    //     $('#reference').attr('disabled', true);
    //     $('#compte_id').attr('disabled', true);
    //     $('#document').attr('disabled', false);
    //     $('#type').attr('disabled', true);

    //     selectReglement();
    // })

    function selectReglement() {
        if ($('#srcReg').val() == "indirect") {
            $('#reference').removeAttr('disabled');
            $('#compte_id').removeAttr('disabled');
            $('#document').removeAttr('disabled');
            $('#type').removeAttr('disabled');
            //$('#confirmation').attr('required','required');
        } else {
            $('#reference').attr('disabled', true);
            $('#compte_id').attr('disabled', true);
            $('#document').attr('disabled', true);
            $('#type').attr('disabled', true);
        }
    }
    $('#reglement').submit(function() {
        $('#action').attr('hidden', 'hidden');
        $('#spin').removeAttr('hidden');
    })
    $('#reglement').submit(function() {
        $('#action').attr('hidden', 'hidden');
        $('#chargement').removeAttr('hidden');
    })
</script>

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