@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">PROGRAMMATIONS</h1>
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
                            <form id="statutsForm" action="" method="get">
                                <div class="row">
                                    <div class="col-sm-3">
                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DES PROGRAMMATIONS'])->exists())
                                        <a href="{{route('programmations.edition')}}" class="btn btn-primary">Imprimer un programme</a>
                                        @endif
                                    </div>
                                    <div class="col-sm-9 ">
                                        <div class="form-group float-md-right">
                                            <select class="custom-select form-control" id="statuts" name="statuts" onchange="submitStatuts()">
                                                <option value="1" {{ $req == 1 ? 'selected':'' }}>Tout</option>
                                                <option value="2" {{ $req == 2 ? 'selected':'' }}>Non Programmé</option>
                                                <option value="3" {{ $req == 3 ? 'selected':'' }}>En cours</option>
                                                <option value="4" {{ $req == 4 ? 'selected':'' }}>Programmé</option>
                                                <option value="5" {{ $req == 5 ? 'selected':'' }}>Livré</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Produit</th>
                                        <th>Qté commander</th>
                                        <th>Qté programmer</th>
                                        <th>Qté Reste</th>
                                        <th>Statut</th>
                                        <th>Pourcentage</th>
                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DES PROGRAMMATIONS'])->exists())
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody class="table-body">
                                    @if ($detailboncommandes->count() > 0)
                                    <?php $compteur = 1; ?>

                                    @foreach($detailboncommandes as $detailboncommande)
                                    <tr>
                                        <td>{{ $compteur++ }}</td>
                                        <td>{{ $detailboncommande->boncommande->code }}</td>
                                        <td class="text-center">{{ $detailboncommande->boncommande->dateBon?date_format(date_create($detailboncommande->boncommande->dateBon), 'd/m/Y'):'' }}</td>
                                        <td>{{ $detailboncommande->boncommande->fournisseur->sigle }}</td>
                                        <td>{{ $detailboncommande->produit->libelle }}</td>
                                        <td class="text-right qteCommande">{{ number_format($detailboncommande->qteCommander,2,","," ") }}</td>
                                        <td class="text-right qtePro">{{ number_format(collect($detailboncommande->programmations()->whereIn('statut', ['Valider', 'Livrer'])->get())->sum('qteprogrammer'),2,","," ") }}</td>
                                        <td class="text-right qteReste">{{ number_format(($detailboncommande->qteCommander - collect($detailboncommande->programmations->whereIn('statut', ['Valider', 'Livrer']))->sum('qteprogrammer')),2,","," ") }}</td>

                                        <td class="text-center">
                                            @if ( (collect($detailboncommande->programmations->whereIn('statut', ['Valider', 'Livrer']))->sum('qteprogrammer')) == 0)
                                            <span class="badge badge-danger">Non programmé</span>
                                            @elseif (($detailboncommande->boncommande->statut =='Livrer') && floatval($detailboncommande->qteCommander) == floatval((collect($detailboncommande->programmations->whereIn('statut', ['Valider', 'Livrer']))->sum('qteprogrammer'))))
                                            <span class="badge badge-secondary">Livrer</span>
                                            @elseif (floatval($detailboncommande->qteCommander) == floatval((collect($detailboncommande->programmations->whereIn('statut', ['Valider', 'Livrer']))->sum('qteprogrammer'))))
                                            <span class="badge badge-success">Programmé</span>
                                            @else
                                            <span class="badge badge-warning">En cours</span>
                                            @endif
                                        </td>

                                        <td class="text-right text-lg"><b>{{ number_format((intval(collect($detailboncommande->programmations()->whereIn('statut', ['Valider', 'Livrer'])->get())->sum('qteprogrammer'))*100)/intval($detailboncommande->qteCommander),2,',',' ') }}%</b></td>

                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DES PROGRAMMATIONS'])->exists())
                                        <td class="text-center">
                                            <div class="row">
                                                <div class="col-sm-12 ">
                                                    <a target="_blank" class="btn btn-success btn-sm" href="{{ route('programmations.create', ['detailboncommande'=>$detailboncommande->id]) }}" @if (($detailboncommande->boncommande->statut == 'Programmer') && (floatval($detailboncommande->qteCommander) == floatval((collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qteprogrammer')))))disabled="disabled" @endif id="programmer"><i class="fa-solid fa-p"></i> Programmer</a>
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
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Produit</th>
                                        <th>Qté commander</th>
                                        <th>Qté programmer</th>
                                        <th>Qté Reste</th>
                                        <th>Statut</th>
                                        <th>Pourcentage</th>
                                        @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR DES PROGRAMMATIONS'])->exists())
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
                                            <td class="" colspan="2"><b>Quantité Totale Commandée</b></td>
                                            <td colspan="6" class="text-right"><b id='qteCommande'>0 Tonnes</b></td>
                                        </tr>
                                        <tr>
                                            <br />
                                            <td class="" colspan="2"><b>Quantité Totale Programmée</b></td>
                                            <td colspan="6" class="text-right"><b id='qtePro'>0 Tonnes</b></td>
                                        </tr>
                                        <tr>
                                            <br />
                                            <td class="" colspan="2"><b>Quantité Totale Reste</b></td>
                                            <td colspan="6" class="text-right"><b id='qteReste'>0 Tonnes</b></td>
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
    $('body').on('change', function() {
        console.log('OK');
        const tableBody = document.querySelector('.table-body');
        let sumQte = 0;
        tableBody.querySelectorAll('tr').forEach(row => {
            const qteCells = row.querySelectorAll('.qteCommande');
            let qteSum = 0;
            qteCells.forEach(qteCell => {
                qteSum = qteSum + parseFloat(qteCell.textContent);
            });
            sumQte = sumQte + qteSum;
        });
        $('#qteCommande').text(sumQte + ' Tonnes')

        let sumProgramme = 0;
        tableBody.querySelectorAll('tr').forEach(row => {
            const puCells = row.querySelectorAll('.qtePro');
            let puSum = 0;
            puCells.forEach(puCell => {
                puSum = puSum + parseFloat((puCell.textContent));
            });
            sumProgramme = sumProgramme + puSum;
            $('#qtePro').text(sumProgramme + ' Tonnes')
        });

        let sumReste = 0;
        tableBody.querySelectorAll('tr').forEach(row => {
            const puCells = row.querySelectorAll('.qteReste');
            let puSum = 0;
            puCells.forEach(puCell => {
                puSum = puSum + parseFloat((puCell.textContent));
            });
            sumReste = sumReste + puSum;
            $('#qteReste').text(sumReste + ' Tonnes')
        });

    });

    function submitStatuts() {
        $('#statutsForm').submit();
    }

    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": "['excel', 'pdf', 'print']",
            "order": [
                [0, 'desc']
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
<script>
    $('#programmer').removeAttr('disabled');
</script>
@endsection