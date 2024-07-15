@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">EDITION - POINT DU STOCK NON LIVRE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">Point du stock</li>
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
                                <form method="post" id="form_bc" action="{{route('edition.postPointStockValider')}}">
                                    @csrf
                                    <div class="row no-print" >
                                        <div class="col-1"></div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">Produits</label>
                                                <select  id="client" class="form-control form-control-sm select2"  name="produit">
                                                    <option class="" value="" selected>Tous</option>
                                                    @foreach($produits as $produit)
                                                        <option value="{{$produit->id}}" {{old('produit')==$produit->id?'selected':''}}>{{$produit->libelle}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">Zone</label>
                                                <select  id="zone" class="form-control form-control-sm select2"  name="zone">
                                                    <option class="text-center" value="" selected>Tous</option>
                                                    @foreach($zones as $zon)
                                                        <option value="{{$zon->id}}" {{old('zone')==$zon->id?'selected':''}}>{{$zon->libelle}}</option>
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

                                <div class="row">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['programmations']) > 0)
                                            <div class="col-md-12">
                                                <h4 class="col-12 text-center border border-info p-2 mb-2">
                                                    @if(session('resultat')['produit'] && session('resultat')['zone'])
                                                        Point du stock sur  {{session('resultat')['produit']->libelle}} dans la zone {{session('resultat')['zone']->libelle}} au {{date('d/m/Y')}}
                                                    @endif
                                                    @if(!session('resultat')['produit'] && session('resultat')['zone'])
                                                        Point du stock de {{session('resultat')['zone']->libelle}} au {{date('d/m/Y')}}
                                                    @endif
                                                    @if(session('resultat')['produit'] && !session('resultat')['zone'])
                                                        Point du stock sur  {{session('resultat')['produit']->libelle}} au {{date('d/m/Y')}}
                                                    @endif
                                                    @if(!session('resultat')['produit'] && !session('resultat')['zone'])
                                                        Point du stock au {{date('d/m/Y')}}
                                                    @endif
                                                </h4>
                                                <table id="example1" class="table table-bordered table-striped table-sm mt-2"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Camion</th>
                                                        <th>Chauffeur</th>
                                                        <th>Code Cde</th>
                                                        <th>Date</th>
                                                        <th>Fournisseur</th>
                                                        <th>Produit</th>
                                                        <th>Zone</th>
                                                        <th>Qté Programmer</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="table-body">
                                                    @php($cpt = 0)
                                                    @php($stock = 0)
                                                    @php($livre = 0)
                                                    @foreach(session('resultat')['programmations'] as $key=>$prog)
                                                        <tr>
                                                            @php($cpt++)
                                                            @php($stock = $stock + $prog->qteprogrammer)
                                                           
                                                            <td>{{$cpt}}</td>
                                                            <td>{{$prog->camion->immatriculationTracteur}}</td>
                                                            <td>{{$prog->chauffeur->nom}} {{$prog->chauffeur->prenom}} ({{$prog->telephone}})</td>
                                                            <td>{{$prog->detailboncommande->boncommande->code}}</td>
                                                            <td>{{$prog->detailboncommande->boncommande->dateBon}}</td>
                                                            <td>{{$prog->detailboncommande->boncommande->fournisseur->raisonSociale}}</td>
                                                            <td>{{$prog->detailboncommande->produit->libelle}}</td>
                                                            <td>{{$prog->zone->libelle}}</td>
                                                            <td>{{$prog->qteprogrammer}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="8">Total</td>
                                                            <td class="text-right font-weight-bold">{{number_format($stock,2,',',' ')}}</td>
                                                        </tr>
                                                    </tfoot>
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
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['programmations']) > 0)
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
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["excel","pdf", "print"],
                "order": [
                    [1, 'asc']
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
        });
    </script>
@endsection
