@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">REGLEMENT DE DETTE</h1>
                    <strong class="">Client: </strong> <em>{{$client->nom}} {{$client->prenom}} --- {{$client->raisonSociale}} </em> <br>
                    <strong class="">Dette actuelle: </strong> <em class="bg-danger p-1">{{$client->debit_old?number_format($client->debit_old, '0', '', ' '):number_format(0, '0', '', ' ')}} </em>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="text-center">
                        @if(session()->has("message"))
                        <div class="text-center alert alert-success"> {{session()->get("message")}} </div>
                        @endif

                        @if(session()->has("error"))
                        <div class="text-center alert alert-danger"> {{session()->get("error")}} </div>
                        @endif
                    </div>
                    @if(IsClientHasADebt($client->id))
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Nouveau Règlement </h3>
                        </div>
                        <form method="POST" id="reglement" action="{{route('newclient.reglement',$client->id)}}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    
                                    <div class="col-8 text-center" id="solde" hidden>
                                        <span class="h2">2000</span>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Référence<span class="text-danger">*</span></label>
                                            <input id="reference" type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase" value="{{ old('reference') }}" autocomplete="off" autofocus>
                                            @error('reference')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Date<span class="text-danger">*</span></label>
                                            <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date" value="{{ old('date')?old('date'):date('Y-m-d') }}" autocomplete="off" autofocus required>
                                            @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Comptes<span class="text-danger">*</span></label>
                                            <select id="compte_id" class="select2 form-control form-control-sm @error('compte') is-invalid @enderror" name="compte" style="width: 100%;">
                                                <option value="{{ NULL }}" selected>** choisir un compte **</option>
                                                @foreach($comptes as $compte)
                                                <option value="{{ $compte->id }}" {{ old('compte') == $compte->id ? 'selected' : '' }}>{{ $compte->banque->sigle }} | <strong>{{ $compte->intitule }}</strong> : {{ $compte->numero }}</option>
                                                @endforeach
                                            </select>
                                            @error('compte')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Type<span class="text-danger">*</span></label>
                                            <select id="type" class="select2 form-control form-control-sm @error('type_detail_recu') is-invalid @enderror" name="type_detail_recu" required style="width: 100%;">
                                                <option selected>** choisir type detail reçu **</option>
                                                @foreach($typedetailrecus as $typedetailrecu)
                                                <option value="{{ $typedetailrecu->id }}" {{ old('type_detail_recu') == $typedetailrecu->id ? 'selected' : '' }}>{{ $typedetailrecu->libelle }}</option>
                                                @endforeach
                                            </select>
                                            @error('type_detail_recu')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Montant<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase" value="{{ old('montant') }}" autocomplete="off" min="1" autofocus required>
                                            @error('montant')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="file">Document<span class="text-danger">*</span></label>
                                            <input id="document" type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" value="{{ old('document') }}" required>
                                            @error('document')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div id="spin" hidden>
                                    <i class="fa fa-spin fa-spinner fa-2x"></i>
                                </div>
                                <div class="row justify-content-center" id="action">
                                    <div class="col-sm-4">
                                        <a href="{{route('newclient.index')}}" class="btn btn-sm btn-secondary btn-block">
                                            <i class="fa-solid fa-circle-left"></i>
                                            {{ __('Retour') }}
                                        </a>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-success btn-block">
                                            {{ __('Enregistrer') }}
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row justify-content-center" id="chargement" hidden>
                                    <div class="col-sm-4">
                                        <a href="#" class="btn btn-sm btn-secondary btn-block" disabled>
                                            <i class="fa-solid fa-circle-left"></i>
                                            {{ __('Retour') }}
                                        </a>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-success btn-block" disabled>
                                            {{ __('Enregistrement encours...') }}
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
                <div class="col-md-2"></div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-12">

                    <!-- TABLE -->
                    <h3 class="text-center">Liste des règlements de dettes</h3>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                            <thead class="text-white text-center bg-gradient-gray-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Compte</th>
                                    <th>Type</th>
                                    <th>Preuve</th>
                                    <th>Opérateur</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($client->_detteReglements) > 0)
                                @foreach ($client->_detteReglements as $rglment)
                                <tr>
                                    <td>{{ $loop->index +1 }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->reference }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->date }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->montant }}</td>
                                    <td class="ml-5 pr-5">{{ $rglment->_Compte->numero }} ({{ $rglment->_Compte->intitule }}) </td>
                                    <td class="ml-5 pr-5">{{ $rglment->_TypeDetailRecu->libelle }} </td>
                                    <td class="ml-5 pr-5 text-center">
                                        <a target="_blank" href="{{$rglment->document}}" class="btn btn-success btn-sm">
                                            <!-- <img src="{{$rglment->_document}}" alt="" srcset=""> -->
                                            <i class="bi bi-filetype-pdf"></i>
                                        </a>
                                    </td>
                                    <td class="ml-5 pr-5">{{ $rglment->_Operator->name }} </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                            <tfoot class="text-white text-center bg-gradient-gray-dark">
                                <tr>
                                    <th>N°</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Compte</th>
                                    <th>Type</th>
                                    <th>Preuve</th>
                                    <th>Operateur</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

@endsection
@section('script')
<script>
    // $('document').ready(function() {

    //     $('#reference').attr('disabled', true);
    //     $('#compte_id').attr('disabled', true);
    //     $('#document').attr('disabled', false);
    //     $('#type').attr('disabled', true);

    //     selectReglement();
    // })

    function selectReglement() {
        if ($('#srcReg').val() == "indirect") {
            $('#reference').removeAttr('disabled');
            $('#compte_id').removeAttr('disabled');
            $('#document').removeAttr('disabled');
            $('#type').removeAttr('disabled');
            //$('#confirmation').attr('required','required');
        } else {
            $('#reference').attr('disabled', true);
            $('#compte_id').attr('disabled', true);
            $('#document').attr('disabled', true);
            $('#type').attr('disabled', true);
        }
    }
    $('#reglement').submit(function() {
        $('#action').attr('hidden', 'hidden');
        $('#spin').removeAttr('hidden');
    })
    $('#reglement').submit(function() {
        $('#action').attr('hidden', 'hidden');
        $('#chargement').removeAttr('hidden');
    })
</script>

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
@endsection