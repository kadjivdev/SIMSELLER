@extends('layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">ETAT DE LIVRAISON DES COMMANDES</h1>
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
                            <form method="post" id="form_bc" action="{{route('edition.postetatlivraisoncde')}}">
                                @csrf
                                <div class="row no-print">

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Date début</label>
                                            <input type="date" class="form-control" name="debut" value="{{old('debut')}}">
                                        </div>
                                        @error('debut')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Date début</label>
                                            <input type="date" class="form-control" name="fin" value="{{old('fin')}}">
                                        </div>
                                        @error('fin')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                            </form>

                            <div class="row">
                                @if(session('resultat'))
                                @if(count(session('resultat')['bcs']) > 0)

                                <div class="col-md-12">
                                    <h4 class="col-12 text-center border border-info p-2 mb-2">
                                        @if(session('resultat')['fournisseur'])
                                        Situation des bons de commandes au {{date('d/m/Y')}} du fournissseur {{session('resultat')['bcs'][0]->bc->fournisseur->raisonSociale}}
                                        @else
                                        Situation des bons de commandes au {{date('d/m/Y')}}
                                        @endif
                                    </h4>
                                    <table id="example1" class="table table-bordered table-striped table-sm mt-2" style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>date</th>
                                                <th>Produit</th>
                                                <th>Fournisseur</th>
                                                <th>Qte cde</th>
                                                <th>Qte Prog</th>
                                                <th>Qte Liv</th>
                                                <th>Qte vendu</th>
                                                <th>Qte non vendu</th>
                                                <th>Reste à Liv</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($cpt = 0)
                                            @php($stock = 0)
                                            @php($prog = 0)
                                            @php($livre = 0)
                                            @php($Vendu = 0)
                                            @php($totalNonVendu = 0)
                                            @php($cder = 0)

                                            @php($Montstock = 0)
                                            @php($Montprog = 0)
                                            @php($Montlivre = 0)
                                            @php($MontVendu = 0)
                                            @php($Montcder = 0)
                                            @foreach(session('resultat')['bcs'] as $key=>$item)
                                            <tr>
                                                @php($cpt++)
                                                @php($stock = $stock + ($item->qteBc - $item->qteLiv) )
                                                @php($prog = $prog + $item->qteprog)
                                                @php($livre = $livre + $item->qteLiv)
                                                @php($Vendu = $Vendu + $item->qteVendu)
                                                @php($restNonVendu = $item->qteLiv-$item->qteVendu)
                                                @php($cder = $cder + $item->qteBc)

                                                @php($Montstock = $Montstock + ($item->montBc - $item->MontLiv) )
                                                @php($Montprog = $Montprog + $item->Montprog)
                                                @php($Montlivre = $Montlivre + $item->MontLiv)
                                                @php($MontVendu = $MontVendu + $item->montVendu)
                                                @php($Montcder = $Montcder + $item->montBc)
                                                @php($totalNonVendu = $totalNonVendu + $restNonVendu)

                                                <td>{{$cpt}}</td>
                                                <td>{{$item->bc->code}}</td>
                                                <td>{{date_format(date_create($item->bc->dateBon),'d/m/Y')}}</td>
                                                <td>{{$item->bc->detailboncommandes[0]->produit->libelle}}</td>
                                                <td>{{$item->bc->fournisseur->raisonSociale}}</td>
                                                <td class="text-right font-weight-bold">{{$item->qteBc}}</td>
                                                <td class="text-right font-weight-bold"> {{$item->qteprog}} </td>
                                                <td class="text-right font-weight-bold">{{$item->qteLiv}}</td>
                                                <td class="text-right font-weight-bold"> {{$item->qteVendu}}</td>
                                                <td class="text-right font-weight-bold text-danger"> {{$restNonVendu}}</td>
                                                <td class="text-right font-weight-bold">{{$item->qteBc - $item->qteLiv}} @if($item->qteBc < $item->qteLiv) diff @endif </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered table-sm">
                                                <thead class="">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Code</th>
                                                        <th>date</th>
                                                        <th>Produit</th>
                                                        <th>Fournisseur</th>
                                                        <th>Qte cde</th>
                                                        <th>Qte Prog</th>
                                                        <th>Qte Liv</th>
                                                        <th>Qte vendu</th>
                                                        <th>Qte non vendu</th>
                                                        <th>Reste à Liv</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td colspan="5" class="font-weight-bold">Total</td>
                                                        <td class="text-right font-weight-bold" id="cder">{{number_format($cder,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold" id="prog"> {{number_format($prog,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold" id="livre">{{number_format($livre,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold" id="Vendu"> {{number_format($Vendu,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold text-danger" id="totalNonVendu"> {{number_format($totalNonVendu,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold" id="resteALivrer">{{number_format($stock,2,',',' ')}}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="5" class="font-weight-bold text-success">Montant</td>
                                                        <td class="text-right font-weight-bold text-success" id="Montcder">{{number_format($Montcder,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold text-success" id="Monprog"> {{number_format($Montprog,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold text-success" id="Monlivre">{{number_format($Montlivre,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold text-success" id="MontVendu"> {{number_format($MontVendu,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold text-success"> --- </td>
                                                        <td class="text-right font-weight-bold text-success" id="Montstock">{{number_format($Montstock,2,',',' ')}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
                                @if(count(session('resultat')['bcs']) > 0)
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

@section("script")
<script>
    $("#example1").DataTable({

        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,

        "buttons": ["pdf", "print","excel"],

        "order": [
            [0, 'asc']
        ],

        "pageLength": 15,

        // "columnDefs": [

        //     {

        //         "targets": 7,

        //         "orderable": false

        //     },

        //     {

        //         "targets": 8,

        //         "orderable": false

        //     }

        // ],

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


    $("body").on('change', function() {
        const cder = new DataTable('#example1').column(5, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        $("#cder").html(cder)
        // 
        const prog = new DataTable('#example1').column(6, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        $("#prog").html(prog)
        // 
        const livre = new DataTable('#example1').column(7, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        $("#livre").html(livre)
        // 
        const Vendu = new DataTable('#example1').column(8, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        $("#Vendu").html(Vendu)
        // 
        const totalNonVendu = new DataTable('#example1').column(9, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        $("#totalNonVendu").html(totalNonVendu)
        // 
        const resteALivrer = new DataTable('#example1').column(10, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        $("#resteALivrer").html(resteALivrer)
        
    })
</script>

@endsection