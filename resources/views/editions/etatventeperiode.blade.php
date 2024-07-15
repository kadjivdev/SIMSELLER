@extends('layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">ETAT DE VENTE DUNE PERIODE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Etat des commandes</li>
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
                            <form method="post" id="form_bc" action="{{route('edition.postetatventepeirode')}}">
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

                            <div class="row">
                                @if(session('resultat'))
                                @if(count(session('resultat')['ventes']) > 0)

                                <div class="col-md-12">
                                    <h4 class="col-12 text-center border border-info p-2 mb-2">
                                        Point des vente de la période du {{date_format(date_create(session('resultat')['debut']),'d/m/Y')}} au {{date_format(date_create(session('resultat')['fin']),'d/m/Y')}}
                                    </h4>
                                    @if(session('resultat')['user'])
                                    <h4 class="text-center">
                                        Utilisateur : {{session('resultat')['user']->name}}
                                    </h4>
                                    @endif
                                    <table id="example1" class="table table-bordered table-striped table-sm mt-2" style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>date</th>
                                                <th> Client</th>
                                                <th>Type</th>
                                                <th>Pu ciment</th>
                                                <th>Qte</th>
                                                <th>Mont. Ciment</th>
                                                <th>PU. Transport</th>
                                                <th>Mont. Transport</th>
                                                <th>Réglé</th>
                                                <th>Reste</th>
                                                <th class="no-print">Echéance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($cpt = 0)
                                            @php($montant = 0)
                                            @php($regle = 0)
                                            @php($totalTrans = 0)
                                            @php($totalQte = 0)
                                            @foreach(session('resultat')['ventes'] as $key=>$item)
                                            <tr>
                                                @php($cpt++)
                                                @php($montant = $montant + ($item->montant) )
                                                @php($regle = $regle + $item->reglements()->sum('montant'))
                                                @php($totalQte = $totalQte + $item->qteTotal)
                                                @php($totalTrans = $totalTrans + ($item->transport*$item->qteTotal))
                                                <td>{{$cpt}}</td>
                                                <td>{{$item->code}}</td>
                                                <td>{{date_format(date_create($item->date),'d/m/Y')}}</td>
                                                <td>
                                                    {{$item->raisonSociale}} ({{$item->telephone}})
                                                    @if(substr($item->code,0,2) == 'VI')
                                                    {{$item->commandeclient->code}}
                                                    @endif
                                                </td>
                                                <td>{{$item->typeVente->libelle}}</td>
                                                <td class="text-right font-weight-bold">{{number_format($item->pu,'0','',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format($item->qteTotal,'0','',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format(($item->pu*$item->qteTotal),'0','',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format(($item->transport),'0','',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format(($item->transport*$item->qteTotal),'0','',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format($item->reglements()->sum('montant'),'0','',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format(($item->montant - $item->reglements()->sum('montant')),'0','',' ')}}</td>
                                                <td class="text-center font-weight-bold no-print">
                                                    @if($item->type_vente_id == 2)
                                                    @if(($item->montant - $item->reglements()->sum('montant')) == 0)
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Soldé</span>
                                                    @elseif($item->echeances()->where('statut',0)->first())
                                                    {{date_format(date_create($item->echeances()->where('statut',0)->first()->date),'d/m/Y')}}
                                                    @else
                                                    <span class="badge bg-danger"><i class="fa fa-times"></i> Non défini</span>
                                                    @endif
                                                    @elseif($item->montant - $item->reglements()->sum('montant') == 0)
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Soldé</span>
                                                    @else
                                                    <span class="badge bg-danger"><i class="fa fa-times"></i> Anomalie</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr class="bg-info">
                                                <td colspan="5" class="font-weight-bold">Total</td>
                                                <td class="text-center font-weight-bold">-</td>
                                                <td class="text-right font-weight-bold">{{number_format($totalQte,0,',',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format($montant,0,',',' ')}}</td>
                                                <td class="text-right font-weight-bold">-</td>
                                                <td class="text-right font-weight-bold">{{number_format($totalTrans,0,',',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format($regle,0,',',' ')}}</td>
                                                <td class="text-right font-weight-bold">{{number_format(($montant+$totalTrans) - $regle,0,',',' ')}}</td>
                                                <td class="text-right font-weight-bold">-</td>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">Code</th>
                                                <th class="text-center">date</th>
                                                <th class="text-center"> Client</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Pu ciment</th>
                                                <th class="text-center">Qte</th>
                                                <th class="text-center">Mont. Ciment</th>
                                                <th class="text-center">PU. Transport</th>
                                                <th class="text-center">Mont. Transport</th>
                                                <th class="text-center">Réglé</th>
                                                <th class="text-center">Reste</th>
                                                <th class="no-print">Echéance</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="col-12 text-center border border-info p-2">
                                    Aucun information trouvée pour votre requête.
                                </div>
                                @endif

                                @endif
                            </div>
                            @if(!(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()))
                            <div class="card-footer text-center no-print">
                                @if(session('resultat'))
                                @if(count(session('resultat')['ventes']) > 0)
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
    });
</script>
@endsection