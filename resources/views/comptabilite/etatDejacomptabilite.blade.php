@extends('layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">VENTE DEJA COMPTABILISER</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Etat des comptables</li>
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
                            <form method="post" id="form_bc" action="{{route('ventes.postDejaExport')}}">
                                @csrf
                                <div class="row no-print">
                                    <!-- <div class="col-1"></div> -->
                                    <!-- <div class="col-3">
                                        <div class="form-group mt-3">

                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input custom-control-input-success custom-control-input-outline" type="radio" id="customRadio6" name="filtre" value="creation">
                                                <label for="customRadio6" class="custom-control-label">Filtrer par date de création</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input custom-control-input-success" type="radio" id="customRadio4" name="filtre" checked="" value="traitement">
                                                <label for="customRadio4" class="custom-control-label">Filtrer par date de traitement</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input custom-control-input-success custom-control-input-outline" type="radio" id="customRadio5" name="filtre" value="comptabilisation">
                                                <label for="customRadio5" class="custom-control-label">Filtrer par date de comptabilisation</label>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="custom-control custom-radio" hidden>
                                        <input class="custom-control-input custom-control-input-success" type="radio" id="customRadio4" name="filtre" checked="" value="traitement">
                                        <label for="customRadio4" class="custom-control-label">Filtrer par date de traitement</label>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="">Fournisseur</label>
                                            <select id="client" class="form-control form-control-sm select2" name="fournisseur">
                                                <!-- <option class="" value="" selected>Tous</option> -->
                                                @foreach($fournisseurs as $frs)
                                                <option value="{{$frs->sigle}}" {{old('fournisseur')==$frs->id?'selected':''}}>{{$frs->raisonSociale}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
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
                                        <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                @if(session('resultat'))
                                @if(count(session('resultat')['comptabilisers']) > 0)

                                <div class="col-md-12">
                                    <h4 class="col-12 text-center border border-info p-2 mb-2">
                                        Point des vente Traiter et à comptabiliser de la période du {{date_format(date_create(session('resultat')['debut']),'d/m/Y')}} au {{date_format(date_create(session('resultat')['fin']),'d/m/Y')}}
                                    </h4>

                                    <table id="example1" class="table table-bordered table-striped table-sm mt-2" style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>Date & Heure système</th>
                                                <th>Date Traitement</th>
                                                <th>Code vente</th>
                                                <th>Date vente</th>
                                                <th>Client</th>
                                                <th>IFU</th>
                                                <th>Client Filleul</th>
                                                <th>IFU Filleul</th>
                                                <th>Date achat</th>
                                                <th>Produit</th>
                                                <th>Quantité</th>
                                                <th>PVR</th>
                                                <th>Prix TTC</th>
                                                <th>Prix HT</th>
                                                <th>Prix 1.18</th>
                                                <th>Net HT</th>
                                                <th>TVA</th>
                                                <th>AIB</th>
                                                <th>TTC</th>
                                                <th>FRS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php($qteVente = 0)
                                            @php($montantVente=0)
                                            @foreach(session('resultat')['comptabilisers'] as $key=>$item)
                                            @php($qteVente+=$item->qte)
                                            @php($montantVente+=$item->prixTTC)
                                            <tr>
                                                <td class="text-center text-danger">{{GetVenteTraitedDateViaCode($item->code)?GetVenteTraitedDateViaCode($item->code):"---"}}</td>
                                                <!-- <td>{{date_format(date_create($item->dateSysteme),'d/m/Y')}} {{$item->heureSysteme}}</td> -->
                                                <td>{{date_format(date_create($item->date_traitement),'d/m/Y')}}</td>
                                                <td>{{$item->code}}</td>
                                                <td>{{date_format(date_create($item->dateVente),'d/m/Y')}}</td>
                                                <td>{{$item->clients}}</td>
                                                <td>{{$item->ifu}}</td>
                                                <td>{{$item->clientFilleuls}}</td>
                                                <td>{{$item->clientFilleulsifu}}</td>
                                                <td>{{date_format(date_create($item->dateAchat),'d/m/Y')}}</td>
                                                <td>{{$item->produit}}</td>
                                                <td>{{$item->qte}}</td>
                                                <td>{{$item->pvr}}</td>
                                                <td>{{$item->prixTTC}}</td>
                                                <td>{{$item->PrixHT}}</td>
                                                <td>{{$item->PrixBruite}}</td>
                                                <td>{{$item->NetHT}}</td>
                                                <td>{{$item->TVA}}</td>
                                                <td>{{$item->AIB}}</td>
                                                <td>{{$item->TTC}}</td>
                                                <td>{{$item->FRS}}</td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered table-sm">
                                                <tr>
                                                    <br />
                                                    <td class="" colspan="2"><b>Total Quantité Vendu</b></td>
                                                    <td colspan="6" class="text-right"><b id='qteVente'>{{ number_format($qteVente ?? 0,0,","," ") }} Tonnes</b></td>
                                                </tr>
                                                <tr>
                                                    <br />
                                                    <td class="" colspan="2"><b>Total Montant Vendu</b></td>
                                                    <td colspan="6" class="text-right"><b id='montantVente'>{{ number_format($montantVente ?? 0,0,","," ")  }} FCFA</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-12 text-center border border-info p-2">
                                    Aucune information trouvée pour votre requête.
                                </div>
                                @endif

                                @endif
                            </div>
                            @if(!(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()))
                            <div class="card-footer text-center no-print">
                                @if(session('resultat'))
                                @if(count(session('resultat')['comptabilisers']) > 0)
                                <a href="{{ route('ventes.ReExport',['debut'=>session('resultat')['debut'],'fin'=>session('resultat')['fin'], 'filtre'=>session('resultat')['filtre'] ]) }}" class="btn btn-success"><i class="fa fa-print"></i> Exporter </a>

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
            "buttons": ["pdf", "print", "excel", "csv"],
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
    });

    $("body").on('change', function() {
        const qteVente = new DataTable('#example1').column(10, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        __qteVente = qteVente < 0 ? -qteVente : qteVente

        const montantVente = new DataTable('#example1').column(15, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        __montantVente = montantVente < 0 ? -montantVente : montantVente


        $("#qteVente").html(__qteVente.toLocaleString() + " Tonnes")
        $("#montantVente").html(__montantVente.toLocaleString() + " FCFA")
    })
</script>
@endsection