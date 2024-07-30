@extends('layouts.app')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">EDITION - POINT DU SOLDE</h1>
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
                            <form method="post" id="form_bc" action="{{ route('edition.postPointSolde') }}">
                                @csrf
                                <div class="row no-print">
                                    <div class="col-1"></div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Clients</label>
                                            <select id="client" class="form-control form-control-sm select2" name="client">
                                                <option class="" value="" selected>Tous</option>
                                                @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" {{ old('client') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->raisonSociale }}
                                                </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
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
                           
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">AVOIR EN COMPTE</span>
                                            <span class="info-box-number">{{(session('resultat')) ? number_format(session('resultat')['SommeCompte'], '0', '', ' '): number_format($SommeCompte, '0', '', ' ')  }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-danger"><i class="fas fa-hand-holding-usd"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">DEBIT</span>
                                            <span class="info-box-number">{{(session('resultat')) ? number_format(session('resultat')['debit'], '0', '', ' '): number_format($debit, '0', '', ' ')  }}</span>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-hand-holding-usd"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">RESTE A PAYER VENTE</span>
                                            <span class="info-box-number" id='reste'>{{ number_format($sommeVentes - $reglements, '0', '', ' ') }}</span>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">CREDIT</span>
                                            <span class="info-box-number">{{(session('resultat')) ? number_format(session('resultat')['credit'], '0', '', ' '):  number_format($credit, '0', '', ' ') }}</span>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                @if (session('resultat'))
                                @if (count(session('resultat')['ventes']) > 0)
                                <div class="col-md-12">
                                    <h4 class="col-12 text-center border border-info p-2 mb-2">
                                        @if (session('resultat')['client'] != null)
                                        Point du solde de {{ session('resultat')['client']->raisonSociale }}
                                        @else
                                        Point du solde de la zone : {{ session('resultat')['zone']->libelle }}

                                        @endif

                                    </h4>
                                    @php($cpt = 0)
                                    @php($qte = 0)
                                    @php($montant = 0)
                                    @php($regle = 0)
                                    <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 11px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>date</th>
                                                <th>Client/Destination</th>
                                                <th>Type</th>
                                                <th>Zones</th>
                                                <th>Quantite</th>
                                                <th>Montant</th>
                                                <th>Réglé</th>
                                                <th>Reste</th>
                                                <th>Echéance</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-body">
                                            @foreach (session('resultat')['ventes'] as $key => $item)
                                            @php($cpt++)
                                            @php($qte = $qte + $item->qteTotal)
                                            @php($montant = $montant + $item->montant)
                                            @php($regle = $regle + $item->reglements()->sum('montant'))
                                            <tr>
                                                <td>{{ $cpt }}</td>
                                                <td>{{ $item->code }}</td>
                                                <td>{{ date_format(date_create($item->date), 'd/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ $item->raisonSociale }} ({{ $item->telephone }})
                                                    @if (substr($item->code, 0, 2) == 'VI')
                                                    {{ $item->commandeclient->code }}
                                                    @endif
                                                    /{{ $item->destination }}
                                                </td>
                                                <td>{{ $item->typeVente->libelle }}</td>
                                                <td>{{ $item->Zlibelle }}</td>
                                                <td>{{ number_format($item->qteTotal, '0', '', ' ') }}</td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($item->montant, '0', '', ' ') }}
                                                </td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($item->reglements()->sum('montant'), '0', '', ' ') }}
                                                </td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($item->montant - $item->reglements()->sum('montant'), '0', '', ' ') }}
                                                </td>
                                                <td class="text-center font-weight-bold">
                                                    @if ($item->type_vente_id == 2)
                                                    @if ($item->montant - $item->reglements()->sum('montant') == 0)
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Soldé</span>
                                                    @elseif($item->echeances()->where('statut', 0)->first())
                                                    {{ date_format(date_create($item->echeances()->where('statut', 0)->first()->date),'d/m/Y') }}
                                                    @else
                                                    <span class="badge bg-danger"><i class="fa fa-times"></i> Non
                                                        défini</span> @endif
                                                    @elseif($item->montant - $item->reglements()->sum('montant') == 0)
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Soldé</span>
                                                    @else
                                                    <span class="badge bg-danger"><i class="fa fa-times"></i> Anomalie</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="font-weight-bold">Total</td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($qte, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($montant, 0, ',', ' ') }}
                                                </td>
                                                <td class="text-right font-weight-bold">
                                                    {{ number_format($regle, 0, ',', ' ') }}
                                                </td>
                                                <td id="Tr" colspan="2" class="text-left font-weight-bold">
                                                    {{ number_format($montant - $regle, 0, ',', ' ') }}
                                                </td>
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
                            <div class="card-footer text-center no-print">
                                @if (session('resultat'))
                                @if (count(session('resultat')['ventes']) > 0)
                                <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection


@section('script')

@if (session('resultat'))
<script>
    $(function() {
        // Récupérer les éléments
        var tdReste = $('#Tr');
        var spanReste = $('#reste');

        // Mettre la valeur initiale dans l'élément
        spanReste.html(tdReste.text());

        // Déclencher la mise à jour de la valeur si besoin
        // (si la valeur de tdReste ne change pas dynamiquement)
        if (!tdReste.is(':input')) {
            tdReste.trigger('change');
        }

        // Attacher l'événement change à l'élément
        tdReste.on('change', function() {
            // Mettre la nouvelle valeur dans l'élément
            spanReste.html($(this).text());
        });
    });

    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"],
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
@endif




@endsection