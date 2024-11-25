@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>LES TOTAUX</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item active">Listes des ventes en instences de comptabilisation.</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-body">
                            <form method="post" id="form_bc" action="{{route('ventes.postVenteTotaux')}}">
                                @csrf
                                <div class="row no-print">
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
                                            <label for="">Date début</label>
                                            <input type="date" class="form-control" name="fin" value="{{old('fin')}}" required>
                                        </div>
                                        @error('fin')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                            </form>

                            <div class="row">
                                @if(session('resultat'))
                                <div class="col-md-12">
                                    <h4 class="col-12 text-center border border-info p-2 mb-2">
                                        Liste des vente traitées de la période du {{date_format(date_create(session('resultat')['debut']),'d/m/Y')}} au {{date_format(date_create(session('resultat')['fin']),'d/m/Y')}}
                                    </h4>
                                    @if(count(session('resultat')['AComptabilisers']) > 0)
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>Envoyé par:</th>

                                                    <th>Modifée</th>

                                                    <th>Date Vente</th>
                                                    <th>Date Validation</th>
                                                    <th>Payeur</th>
                                                    <th>Qté</th>
                                                    <th>PU</th>
                                                    <th>Montant</th>
                                                    <th>Transport</th>
                                                    <th>Total</th>
                                                    <th>Fournisseur</th>
                                                    <th>Statut</th>
                                                    @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                                                    <th>Action</th>
                                                    @endif
                                                    <th>Créer le </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php($qteVente=0)
                                                @php($montantVente=0)

                                                @foreach(session('resultat')['AComptabilisers'] as $key=>$AComptabiliser)

                                                @php($qteVente+=$AComptabiliser->qteTotal)
                                                @php($montantVente+=$AComptabiliser->montant)

                                                <tr class="{{$AComptabiliser->statut == "Vendue" ? 'bg-warning':'' }}">
                                                    <td class="text-center" style="font-weight: bold;">{{ $AComptabiliser->user->representant->nom }} {{ $AComptabiliser->user->representant->prenom }}</td>

                                                    <td class="text-center">
                                                        <!-- <span class="btn btn-sm bg-light" style="font-weight: bold;"> -->
                                                        @if(IsThisVenteModified($AComptabiliser))
                                                        <i class="bi bi-check2-all text-success"></i>
                                                        {{GetVenteUpdatedDate($AComptabiliser)}}
                                                        @else
                                                        <i class="bi bi-x text-danger"></i>
                                                        @endif
                                                        <!-- </span> -->
                                                    </td>

                                                    <td class="text-center">{{ date_format(date_create($AComptabiliser->date),'d/m/Y') }}</td>
                                                    <td class="text-center">{{$AComptabiliser->validated_date? date_format(date_create($AComptabiliser->validated_date),'d/m/Y'):"---" }}</td>
                                                    <!-- <td class="">{{ $AComptabiliser->typeVente->libelle }}</td> -->
                                                    <td class="">{{ count($AComptabiliser->filleuls) > 0 ? $AComptabiliser->filleuls['nomPrenom']." (IFU: ".$AComptabiliser->filleuls['ifu'].")" : $AComptabiliser->commandeclient->client->raisonSociale.' ('.$AComptabiliser->commandeclient->client->ifu.')' }}</td>
                                                    <td class="text-right">{{ number_format($AComptabiliser->qteTotal,0,'',' ') }}</td>
                                                    <td class="text-center"><strong> {{ $AComptabiliser->pu }}</strong></td>
                                                    <td class="text-right">{{ number_format($AComptabiliser->montant,0,'',' ') }}</td>
                                                    <td class="text-right">{{ number_format($AComptabiliser->transport,0,'',' ') }}</td>
                                                    <td class="text-right">{{ number_format($AComptabiliser->montant+$AComptabiliser->transport,0,'',' ') }}</td>
                                                    <td class="text-center"> <strong>{{ $AComptabiliser->vendus[0]->programmation->detailboncommande->boncommande->fournisseur->raisonSociale }} </strong> </td>

                                                    <td class="text-right"><span class="badge badge-success">{{ $AComptabiliser->statut }}</span></td>

                                                    @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                                                    <td>
                                                        @if(!$AComptabiliser->date_traitement)
                                                        <a class="btn btn-success btn-block btn-sm" href="{{route('ventes.ventATraiter',$AComptabiliser->id)}}">Traiter</a>
                                                        @else
                                                        <span class="badge bg-success">Traitée</span>
                                                        @endif
                                                    </td>
                                                    @endif
                                                    <td class="text-center">{{ date_format(date_create($AComptabiliser->created_at),'d/m/Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>Envoyé par:</th>

                                                    <th>Modifée</th>

                                                    <th>Date Vente</th>
                                                    <th>Date Validation</th>
                                                    <th>Fournisseur</th>
                                                    <th>Payeur</th>
                                                    <th>Qté</th>
                                                    <th>PU</th>
                                                    <th>Montant</th>
                                                    <th>Transport</th>
                                                    <th>Total</th>
                                                    <th>Statut</th>
                                                    @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                                                    <th>Action</th>
                                                    @endif

                                                    <th>Créer le </th>
                                                </tr>
                                            </tfoot>
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
                                    @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())

                                    <div class="card-footer text-center no-print">
                                        @if(session('resultat'))
                                        @if(count(session('resultat')['AComptabilisers']) > 0)
                                        <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                                        @endif
                                        @endif
                                    </div>
                                    @endif
                                    @endif

                                </div>

                                @if(count(session('resultat')['AComptabilisersAdjeOla']) > 0)
                                <!-- /.card-header -->
                                <div class="col-12 text-center">
                                    <center>
                                        <h4>VENTE ADJE OLA</h4>
                                    </center>
                                </div>
                                <div class="card-body">
                                    <table id="exampleA" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                            <th>Envoyé par:</th>

                                            <th>Modifée</th>

                                            <th>Date Vente</th>
                                            <th>Date Validation</th>
                                            <th>Payeur</th>
                                            <th>Qté</th>
                                            <th>PU</th>
                                            <th>Montant</th>
                                            <th>Transport</th>
                                            <th>Total</th>
                                            <th>Fournisseur</th>
                                            <th>Statut</th>
                                            @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                                            <th>Action</th>
                                            @endif
                                            <th>Créer le </th>
                                        </thead>
                                        <tbody>
                                            @php($qteVenteAdj=0)
                                            @php($montantVenteAdj=0)

                                            @foreach(session('resultat')['AComptabilisersAdjeOla'] as $key=>$AComptabiliser)
                                            @php($qteVenteAdj+=$AComptabiliser->qteTotal)
                                            @php($montantVenteAdj+=$AComptabiliser->montant)

                                            <tr class="{{$AComptabiliser->statut == "Vendue" ? 'bg-warning':'' }}">
                                                <td class="text-center" style="font-weight: bold;">{{ $AComptabiliser->user->representant->nom }} {{ $AComptabiliser->user->representant->prenom }}</td>

                                                <td class="text-center">
                                                    <!-- <span class="btn btn-sm bg-light" style="font-weight: bold;"> -->
                                                    @if(IsThisVenteModified($AComptabiliser))
                                                    <i class="bi bi-check2-all text-success"></i>
                                                    {{GetVenteUpdatedDate($AComptabiliser)}}
                                                    @else
                                                    <i class="bi bi-x text-danger"></i>
                                                    @endif
                                                    <!-- </span> -->
                                                </td>

                                                <td class="text-center">{{ date_format(date_create($AComptabiliser->date),'d/m/Y') }}</td>
                                                <td class="text-center">{{$AComptabiliser->validated_date? date_format(date_create($AComptabiliser->validated_date),'d/m/Y'):"---" }}</td>
                                                <!-- <td class="">{{ $AComptabiliser->typeVente->libelle }}</td> -->
                                                <td class="">{{ count($AComptabiliser->filleuls) > 0 ? $AComptabiliser->filleuls['nomPrenom']." (IFU: ".$AComptabiliser->filleuls['ifu'].")" : $AComptabiliser->commandeclient->client->raisonSociale.' ('.$AComptabiliser->commandeclient->client->ifu.')' }}</td>
                                                <td class="text-right">{{ number_format($AComptabiliser->qteTotal,0,'',' ') }}</td>
                                                <td class="text-center"><strong> {{ $AComptabiliser->pu }}</strong></td>
                                                <td class="text-right">{{ number_format($AComptabiliser->montant,0,'',' ') }}</td>
                                                <td class="text-right">{{ number_format($AComptabiliser->transport,0,'',' ') }}</td>
                                                <td class="text-right">{{ number_format($AComptabiliser->montant+$AComptabiliser->transport,0,'',' ') }}</td>
                                                <td class="text-center"> <strong>{{ $AComptabiliser->vendus[0]->programmation->detailboncommande->boncommande->fournisseur->raisonSociale }} </strong> </td>

                                                <td class="text-right"><span class="badge badge-success">{{ $AComptabiliser->statut }}</span></td>

                                                @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                                                <td>
                                                    @if(!$AComptabiliser->date_traitement)
                                                    <a class="btn btn-success btn-block btn-sm" href="{{route('ventes.ventATraiter',$AComptabiliser->id)}}">Traiter</a>
                                                    @else
                                                    <span class="badge bg-success">Traitée</span>
                                                    @endif
                                                </td>
                                                @endif
                                                <td class="text-center">{{ date_format(date_create($AComptabiliser->created_at),'d/m/Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>Envoyé par:</th>

                                                <th>Modifée</th>

                                                <th>Date Vente</th>
                                                <th>Date Validation</th>
                                                <th>Payeur</th>
                                                <th>Qté</th>
                                                <th>PU</th>
                                                <th>Montant</th>
                                                <th>Transport</th>
                                                <th>Total</th>
                                                <th>Fournisseur</th>
                                                <th>Statut</th>
                                                @if(Auth::user()->roles()->where('libelle', ['ADMINISTRATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['COMPTABLE'])->exists())
                                                <th>Action</th>
                                                @endif
                                                <th>Créer le </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-bordered table-sm">
                                                <tr>
                                                    <br />
                                                    <td class="" colspan="2"><b>Total Quantité Vendu</b></td>
                                                    <td colspan="6" class="text-right"><b id='qteVenteAdj'>{{ number_format($qteVenteAdj ?? 0,0,","," ") }} Tonnes</b></td>
                                                </tr>
                                                <tr>
                                                    <br />
                                                    <td class="" colspan="2"><b>Total Montant Vendu</b></td>
                                                    <td colspan="6" class="text-right"><b id='montantVenteAdj'>{{ number_format($montantVenteAdj ?? 0,0,","," ")  }} FCFA</b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @else
                                <div class="col-12 text-center border border-info p-2">
                                    Aucune information trouvée pour votre requête.
                                </div>
                                @endif
                            </div>
                        </div>

                        @if(!(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()))
                        <div class="card-footer text-center no-print">
                            @if(session('resultat'))
                            @if(count(session('resultat')['AComptabilisersAdjeOla']) > 0)
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
            "buttons": ["pdf", "print", "csv", "excel"],
            "order": [
                [3, 'desc']
            ],
            "pageLength": 15,
            "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                },
                {
                    "targets": 1,
                    "orderable": false
                },
                {
                    "targets": 9,
                    "orderable": false
                },
                {
                    "targets": 10,
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

    $(function() {
        $("#exampleA").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["pdf", "print", "csv", "excel"],
            "order": [
                [3, 'asc']
            ],
            "pageLength": 15,
            "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                },
                {
                    "targets": 1,
                    "orderable": false
                },
                {
                    "targets": 9,
                    "orderable": false
                },
                {
                    "targets": 10,
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
        }).buttons().container().appendTo('#exampleA_wrapper .col-md-6:eq(0)');
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


    $("body").on('change', function() {
        const qteVente = new DataTable('#example1').column(5, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        __qteVente = qteVente < 0 ? -qteVente : qteVente

        const montantVente = new DataTable('#example1').column(9, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        __montantVente = montantVente < 0 ? -montantVente : montantVente


        $("#qteVente").html(__qteVente.toLocaleString() + " Tonnes")
        $("#montantVente").html(__montantVente.toLocaleString() + " FCFA")


        // ADJEOLA
        const qteVenteAdj = new DataTable('#exampleA').column(5, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        __qteVenteAdj = qteVenteAdj < 0 ? -qteVenteAdj : qteVenteAdj

        const montantVenteAdj = new DataTable('#exampleA').column(9, {
            page: 'all',
            search: 'applied'
        }).data().sum()
        __montantVenteAdj = montantVenteAdj < 0 ? -montantVenteAdj : montantVenteAdj


        $("#qteVenteAdj").html(__qteVenteAdj.toLocaleString() + " Tonnes")
        $("#montantVenteAdj").html(__montantVenteAdj.toLocaleString() + " FCFA")

    })
</script>
@endsection