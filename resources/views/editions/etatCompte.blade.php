@extends('layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">EDITION - ETAT DES COMPTES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Point du Solde</li>
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
                            <form method="post" id="form_bc" action="{{ route('edition.postEtatCompte') }}">
                                @csrf
                                <div class="row no-print">
                                    <div class="col-1"></div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Zone</label>
                                            <select id="zone" class="form-control form-control-sm select2" name="zone">
                                                <option class="text-center" value="" selected>Tous</option>
                                                @foreach ($zones as $zone)
                                                <option value="{{ $zone->id }}" {{ old('zone') == $zone->id ? 'selected' : '' }}>{{ $zone->libelle }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                            </form>

                            <!--  -->
                            <div class="row text-center">
                                <!-- CREDIT -->
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">CREDIT</span>
                                            <span class="info-box-number">{{(session('resultat')) ? number_format(session('resultat')['credit'], '0', '', ' '):  number_format($credit, '0', '', ' ') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- DEBIT -->
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="fas fa-hand-holding-usd"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">DEBIT</span>
                                            <span class="info-box-number">{{(session('resultat')) ? number_format(session('resultat')['debit'], '0', '', ' '): number_format($debit, '0', '', ' ')  }}</span>
                                        </div>

                                    </div>
                                </div>

                                <!-- SOLDE -->
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">SOLDE</span>
                                            <span class="info-box-number">{{(session('resultat')) ? number_format(session('resultat')['SommeCompte'], '0', '', ' '): number_format($SommeCompte, '0', '', ' ')  }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--  -->

                            <div class="row">
                                @if (session('resultat'))
                                @if (count(session('resultat')['clients']) > 0)
                                <div class="col-md-12">
                                    <h4 class="col-12 text-center border border-info p-2 mb-2">
                                        @if (isset(session('resultat')['zone']))
                                        {{-- Point du solde de {{ session('resultat')['client']->raisonSociale }} --}}
                                        {{-- @else --}}
                                        Point du solde de la zone : {{ session('resultat')['zone']->libelle }}

                                        @endif

                                    </h4>
                                    <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Clients</th>
                                                <th>Crédit</th>
                                                <th>Débit</th>
                                                <th>Solde</th>

                                            </tr>
                                        </thead>
                                        <tbody class="table-body">
                                            @php($solde = 0)
                                            @php($credit = 0)
                                            @php($debit = 0)
                                            @foreach (session('resultat')['clients'] as $key => $item)
                                            <tr>
                                                @php($solde = $solde + ((count($item->compteClients) > 0)?$item->compteClients[0]->solde:0))
                                                @php($credit = $credit + $item->credit)
                                                @php($debit = $debit + $item->debit)
                                                <td>{{ ++$key }}</td>

                                                <td>{{ $item->raisonSociale }} ({{ $item->telephone }})</td>

                                                <td class="text-right font-weight-bold bg-success">{{ number_format($item->credit, '0', '', ' ') }}</td>
                                                <td class="text-right font-weight-bold bg-danger">
                                                    {{ number_format($item->debit, '0', '', ' ') }}
                                                </td>
                                                <td class="text-right font-weight-bold bg-primary">
                                                    {{ number_format((count($item->compteClients) > 0)?$item->compteClients[0]->solde:0, '0', '', ' ') }}
                                                </td>


                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="col-12 text-center border border-info p-2">
                                    Aucun stock trouvé pour votre requête.
                                </div>
                                @endif

                                @endif
                            </div>

                            @if(!(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()))
                            <div class="card-footer text-center no-print">
                                @if (session('resultat'))
                                @if (count(session('resultat')['clients']) > 0)
                                <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                                @endif
                                @endif
                            </div>
                            @endif
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
    // $(function() {
    //     $("#example1").DataTable({
    //         "responsive": true,
    //         "lengthChange": false,
    //         "autoWidth": false,
    //         "buttons": ["excel", "pdf", "print"],
    //         "order": [
    //             [3, 'asc']
    //         ],
    //         "pageLength": 100,
    //         "columnDefs": [{
    //                 "targets": 0,
    //                 "orderable": false
    //             },
    //             {
    //                 "targets": 1,
    //                 "orderable": false
    //             },
    //             {
    //                 "targets": 9,
    //                 "orderable": false
    //             },
    //             {
    //                 "targets": 10,
    //                 "orderable": false
    //             }
    //         ],
    //         language: {
    //             "emptyTable": "Aucune donnée disponible dans le tableau",
    //             "lengthMenu": "Afficher _MENU_ éléments",
    //             "loadingRecords": "Chargement...",
    //             "processing": "Traitement...",
    //             "zeroRecords": "Aucun élément correspondant trouvé",
    //             "paginate": {
    //                 "first": "Premier",
    //                 "last": "Dernier",
    //                 "previous": "Précédent",
    //                 "next": "Suiv"
    //             },
    //             "aria": {
    //                 "sortAscending": ": activer pour trier la colonne par ordre croissant",
    //                 "sortDescending": ": activer pour trier la colonne par ordre décroissant"
    //             },
    //             "select": {
    //                 "rows": {
    //                     "_": "%d lignes sélectionnées",
    //                     "1": "1 ligne sélectionnée"
    //                 },
    //                 "cells": {
    //                     "1": "1 cellule sélectionnée",
    //                     "_": "%d cellules sélectionnées"
    //                 },
    //                 "columns": {
    //                     "1": "1 colonne sélectionnée",
    //                     "_": "%d colonnes sélectionnées"
    //                 }
    //             },
    //             "autoFill": {
    //                 "cancel": "Annuler",
    //                 "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
    //                 "fillHorizontal": "Remplir les cellules horizontalement",
    //                 "fillVertical": "Remplir les cellules verticalement"
    //             },
    //             "searchBuilder": {
    //                 "conditions": {
    //                     "date": {
    //                         "after": "Après le",
    //                         "before": "Avant le",
    //                         "between": "Entre",
    //                         "empty": "Vide",
    //                         "equals": "Egal à",
    //                         "not": "Différent de",
    //                         "notBetween": "Pas entre",
    //                         "notEmpty": "Non vide"
    //                     },
    //                     "number": {
    //                         "between": "Entre",
    //                         "empty": "Vide",
    //                         "equals": "Egal à",
    //                         "gt": "Supérieur à",
    //                         "gte": "Supérieur ou égal à",
    //                         "lt": "Inférieur à",
    //                         "lte": "Inférieur ou égal à",
    //                         "not": "Différent de",
    //                         "notBetween": "Pas entre",
    //                         "notEmpty": "Non vide"
    //                     },
    //                     "string": {
    //                         "contains": "Contient",
    //                         "empty": "Vide",
    //                         "endsWith": "Se termine par",
    //                         "equals": "Egal à",
    //                         "not": "Différent de",
    //                         "notEmpty": "Non vide",
    //                         "startsWith": "Commence par"
    //                     },
    //                     "array": {
    //                         "equals": "Egal à",
    //                         "empty": "Vide",
    //                         "contains": "Contient",
    //                         "not": "Différent de",
    //                         "notEmpty": "Non vide",
    //                         "without": "Sans"
    //                     }
    //                 },
    //                 "add": "Ajouter une condition",
    //                 "button": {
    //                     "0": "Recherche avancée",
    //                     "_": "Recherche avancée (%d)"
    //                 },
    //                 "clearAll": "Effacer tout",
    //                 "condition": "Condition",
    //                 "data": "Donnée",
    //                 "deleteTitle": "Supprimer la règle de filtrage",
    //                 "logicAnd": "Et",
    //                 "logicOr": "Ou",
    //                 "title": {
    //                     "0": "Recherche avancée",
    //                     "_": "Recherche avancée (%d)"
    //                 },
    //                 "value": "Valeur"
    //             },
    //             "searchPanes": {
    //                 "clearMessage": "Effacer tout",
    //                 "count": "{total}",
    //                 "title": "Filtres actifs - %d",
    //                 "collapse": {
    //                     "0": "Volet de recherche",
    //                     "_": "Volet de recherche (%d)"
    //                 },
    //                 "countFiltered": "{shown} ({total})",
    //                 "emptyPanes": "Pas de volet de recherche",
    //                 "loadMessage": "Chargement du volet de recherche..."
    //             },
    //             "buttons": {
    //                 "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
    //                 "collection": "Collection",
    //                 "colvis": "Visibilité colonnes",
    //                 "colvisRestore": "Rétablir visibilité",
    //                 "copy": "Copier",
    //                 "copySuccess": {
    //                     "1": "1 ligne copiée dans le presse-papier",
    //                     "_": "%ds lignes copiées dans le presse-papier"
    //                 },
    //                 "copyTitle": "Copier dans le presse-papier",
    //                 "csv": "CSV",
    //                 "excel": "Excel",
    //                 "pageLength": {
    //                     "-1": "Afficher toutes les lignes",
    //                     "_": "Afficher %d lignes"
    //                 },
    //                 "pdf": "PDF",
    //                 "print": "Imprimer"
    //             },
    //             "decimal": ",",
    //             "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
    //             "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
    //             "infoThousands": ".",
    //             "search": "Rechercher:",
    //             "thousands": ".",
    //             "infoFiltered": "(filtrés depuis un total de _MAX_ éléments)",
    //             "datetime": {
    //                 "previous": "Précédent",
    //                 "next": "Suivant",
    //                 "hours": "Heures",
    //                 "minutes": "Minutes",
    //                 "seconds": "Secondes",
    //                 "unknown": "-",
    //                 "amPm": [
    //                     "am",
    //                     "pm"
    //                 ],
    //                 "months": [
    //                     "Janvier",
    //                     "Fevrier",
    //                     "Mars",
    //                     "Avril",
    //                     "Mai",
    //                     "Juin",
    //                     "Juillet",
    //                     "Aout",
    //                     "Septembre",
    //                     "Octobre",
    //                     "Novembre",
    //                     "Decembre"
    //                 ],
    //                 "weekdays": [
    //                     "Dim",
    //                     "Lun",
    //                     "Mar",
    //                     "Mer",
    //                     "Jeu",
    //                     "Ven",
    //                     "Sam"
    //                 ]
    //             },
    //             "editor": {
    //                 "close": "Fermer",
    //                 "create": {
    //                     "button": "Nouveaux",
    //                     "title": "Créer une nouvelle entrée",
    //                     "submit": "Envoyer"
    //                 },
    //                 "edit": {
    //                     "button": "Editer",
    //                     "title": "Editer Entrée",
    //                     "submit": "Modifier"
    //                 },
    //                 "remove": {
    //                     "button": "Supprimer",
    //                     "title": "Supprimer",
    //                     "submit": "Supprimer",
    //                     "confirm": {
    //                         "1": "etes-vous sure de vouloir supprimer 1 ligne?",
    //                         "_": "etes-vous sure de vouloir supprimer %d lignes?"
    //                     }
    //                 },
    //                 "error": {
    //                     "system": "Une erreur système s'est produite"
    //                 },
    //                 "multi": {
    //                     "title": "Valeurs Multiples",
    //                     "restore": "Rétablir Modification",
    //                     "noMulti": "Ce champ peut être édité individuellement, mais ne fait pas partie d'un groupe. ",
    //                     "info": "Les éléments sélectionnés contiennent différentes valeurs pour ce champ. Pour  modifier et "
    //                 }
    //             }
    //         },
    //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    //     $('#example2').DataTable({
    //         "paging": true,
    //         "lengthChange": false,
    //         "searching": false,
    //         "ordering": true,
    //         "info": true,
    //         "autoWidth": false,
    //         "responsive": true,
    //     });
    // });

    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print",'csv'],
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
    });
</script>
@endsection