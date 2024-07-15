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
                                        @if(Auth::user()->roles()->where('libelle', ['GESTIONNAIRE'])->exists())
                                        <a class="btn btn-success btn-sm" href="{{route('boncommandes.create')}}">
                                            <i class="fas fa-solid fa-plus"></i>
                                            Créer
                                        </a>
                                        @endif
                                    </div>
                                    <div class="col-sm-9 ">
                                        @if (Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists() == true)
                                        <div class="form-group float-md-right">
                                            <select class="custom-select form-control" id="statuts" name="statuts" onchange="submitStatuts()">
                                                <option value="1" {{ $req == 1 ? 'selected':'' }}>Tout</option>
                                                <option value="2" {{ $req == 2 ? 'selected':'' }}>Livrer</option>
                                                <option value="3" {{ $req == 3 ? 'selected':'' }}>Valider</option>
                                                <option value="4" {{ $req == 4 ? 'selected':'' }}>Programmer</option>
                                                <option value="5" {{ $req == 5 ? 'selected':'' }}>Preparation</option>
                                            </select>
                                        </div>
                                        @endif
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
                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Utilisateur</th>
                                        <th>Actualisation</th>
                                        @if(Auth::user()->roles()->where('libelle', 'COMPTABLE')->exists()== false)
                                        <th>Action</th>

                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    @if ($boncommandes->count() > 0)
                                    <?php $compteur = 1; ?>
                                    <?php $total = 0; ?>
                                    <?php $MontantTotal = 0; ?>
                                    @foreach($boncommandes as $boncommande)
                                    <?php
                                    $new = isset($boncommande->detailboncommandes[0]->qteCommander) ? $boncommande->detailboncommandes[0]->qteCommander : 0;
                                    $total = $total + $new
                                    ?>
                                    <?php $MontantTotal = $MontantTotal + $boncommande->montant; ?>
                                    <tr>
                                        <td>{{ $boncommande->code }}</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($boncommande->dateBon)) }}</td>
                                        <td class="pl-2">{{ $boncommande->fournisseur->sigle }}</td>
                                        <td class="pl-2 qte">{{ isset($boncommande->detailboncommandes[0]->qteCommander) ? $boncommande->detailboncommandes[0]->qteCommander:0}}</td>
                                        <td class="text-right pr-3">{{ isset($boncommande->detailboncommandes[0]->pu) ? $boncommande->detailboncommandes[0]->pu:0}}</td>
                                        <td class="text-right pr-3 montant">{{ number_format($boncommande->montant,2,","," ") }}</td>
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
                                            @if ($boncommande->statut = 'Valider')
                                            <a class="btn btn-danger btn-sm" href="{{ route('boncommandes.delete', ['boncommande'=>$boncommande->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                            @endif
                                            @endif
                                            @elseif ($boncommande->statut == 'Valider' && count($boncommande->recus) == 0)
                                            <a class="btn btn-info btn-sm" href="{{ route('boncommandes.invalider', ['boncommande'=>$boncommande->id]) }}"><i class="fa-regular fa-rectangle-xmark"></i></a>
                                            @endif
                                        </td>
                                        @if(Auth::user()->roles()->where('libelle', 'COMPTABLE')->exists()== false)

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
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Utilisateur</th>
                                        <th>Actualisation</th>
                                        @if(Auth::user()->roles()->where('libelle', 'COMPTABLE')->exists()== false)
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

    <!-- /.content -->

</div>

@endsection

@section('script')

<script>
    $('body').on('change', function() {
        const tableBody = document.querySelector('.table-body');
        let sumQte = 0;
        tableBody.querySelectorAll('tr').forEach(row => {
            const puCells = row.querySelectorAll('.qte');
            let puSum = 0;
            puCells.forEach(puCell => {
                puSum = puSum + parseFloat(puCell.textContent);
            });
            sumQte = sumQte + puSum;
        });
        $('#qte').text(sumQte + ' Tonnes')

        let sumMontant = 0;
        tableBody.querySelectorAll('tr').forEach(row => {
            const puCells = row.querySelectorAll('.montant');
            let puSum = 0;
            puCells.forEach(puCell => {
                puSum = puSum + parseFloat((puCell.textContent.replace(/ /g, "")));
            });
            sumMontant = sumMontant + puSum;
            $('#montant').text(sumMontant + ' FCFA')
        });
    });


    $(function() {

        $("#example1").DataTable({

            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,

            "buttons": ["pdf", "print"],

            "order": [
                [0, 'asc']
            ],

            "pageLength": 15,

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
    function submitStatuts() {
        $('#statutsForm').submit();
    }
</script>

@endsection