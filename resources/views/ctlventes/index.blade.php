@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>APPROVISIONNEMENT A CONTROLLER</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item active">Listes des règlement à contrôler</li>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- FILTRE -->
                            <form method="post" id="form_bc" action="{{route('ctlventes.index')}}">
                                @csrf
                                <div class="row no-print">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Date début</label>
                                            <input type="date" class="form-control" name="debut" value="{{old('debut')}}" required>
                                        </div>
                                        @error('debut')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Date Fin</label>
                                            <input type="date" class="form-control" name="fin" value="{{old('fin')}}" required>
                                        </div>
                                        @error('fin')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Vendeur</label>
                                            <select class="form-control form-select" name="user">
                                                <option class="" value="tout" {{old('user') == 'tout'}}>Tout</option>
                                                @foreach($users as $user)
                                                <option value="{{$user->id}}" {{$user->id == old('user') ? 'selected':''}}>{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('fin')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-3">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                    </div>
                                </div>
                            </form>

                            @if($message = session('message'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                {{ $message }}
                            </div>
                            @endif

                            @if(session("search"))
                            <div class="alert bg-info"> Liste des reglements du <span class="badge bg-warning">{{session("search")["debut"]}} </span> au <span class="badge bg-warning">{{session("search")["fin"]}} </span> </div>
                            @endif

                            <br><br>
                            <!-- <a class="btn btn-success btn-block btn-sm m-3 col-4" href="{{route('ctlventes.reglementSurCompte')}}">Contrôler Règlement Sur Compte Client</a> -->

                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Document</th>
                                        <th>Utilisateur</th>
                                        <th>Dette</th>
                                        <th>Reversement</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reglements as $reglement)
                                    <!-- ICI ON AFFICHE QUE LES APPROVISIONNEMENTS NON VALIDES -->
                                    @if($reglement->statut!=1)
                                    <tr class=" {{ $reglement->observation_validation != NULL ? 'bg-warning':''  }} ">
                                        <td><span class="badge bg-warning">{{$reglement->code}} </span></td>
                                        <td>{{date_format(date_create($reglement->date),'d/m/Y')}}</td>
                                        <td class="text-center"><span class="badge bg-success">{{number_format($reglement->montant,0,',',' ')}}</td>
                                        <td>{{$reglement->reference}}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                @if($reglement->_clt)
                                                {{$reglement->_clt->nom}} {{$reglement->_clt->prenom}}
                                                @else
                                                ---
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if ($reglement->document)
                                            <a class="btn btn-success float-md-right text-white btn-sm" href="{{ $reglement->document?asset('storage/'.$reglement->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>
                                            @endif
                                        </td>
                                        <td>@if($reglement->utilisateur) {{$reglement->utilisateur->name}}@else Utilisateur manquant @endif</td>
                                        <td class="text-center">
                                            @if($reglement->for_dette)
                                            <span class="badge bg-success">Oui</span>
                                            @else
                                            <span class="badge bg-danger">Non</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($reglement->old_solde)
                                            <span class="badge bg-success">Oui</span>
                                            @else
                                            <span class="badge bg-danger">Non</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$reglement->observation_validation)
                                            <a class="btn btn-success btn-block btn-sm" href="{{route('ctlventes.create',$reglement->id)}}">Contrôler</a>
                                            @else

                                            <span class="bg-light px-1">{{$reglement->observation_validation}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Référence</th>
                                        <th>Client</th>
                                        <th>Document</th>
                                        <th>Utilisateur</th>
                                        <th>Dette</th>
                                        <th>Reversement</th>
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