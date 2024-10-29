@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="pb-3">COMPTE CLIENT</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Banques</a></li>
                        <li class="breadcrumb-item active">Comptes</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card d-flex flex-fill">
                        <div class="card-body">
                            <h1>
                                {{ $client->raisonSociale }}
                            </h1>
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b>
                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-person-dots-from-line"></i></span> Téléphone : {{ $client->telephone }}</li>
                                                </b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail: {{ $client->email }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Adresse: {{ $client->adresse }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut crédit: <span class="badge {{$client->credit == 0 ? 'badge-danger' : 'badge-success'}}">{{ $client->credit == 0 ? 'Non Eligible':'Eligible' }}</span></li>
                                            </div>
                                        </div>
                                    </ul>

                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">CREDIT</span>
                                                    <span class="info-box-number">{{ number_format($client->credit, 0, ',', ' ')  }}</span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-danger"><i class="fas fa-hand-holding-usd"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">DEBIT</span>
                                                    <span class="info-box-number">{{number_format($client->debit, 0, ',', ' ')   }}</span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">SOLDE</span>
                                                    <span class="info-box-number">{{number_format($client->credit-$client->debit, 0, ',', ' ')  }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('newclient.index' )}}" class="btn btn-sm btn-primary float-md-right">
                                        <i class="fa-solid fa-circle-left mr-1"></i>
                                        {{ __('Retour') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
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
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <h2>
                                        SOLDE : {{number_format($client->credit-$client->debit,0,',',' ')}}
                                    </h2>
                                    @if (Auth::user()->roles()->where('libelle', 'GESTION CLIENT')->exists() || Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists())
                                    <a href="{{route('compteClient.appro',['client'=>$client->id])}}" class="float-right btn btn-primary">Approvisionner</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modal-default">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Nouveau Compte</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Réference</th>
                                        <th>Libelle</th>
                                        <th>Crédit</th>
                                        <th>Débit</th>
                                        @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mouvements as $key=>$mouvement)
                                    <tr>
                                        <td class="text-center">MVT-{{str_pad($key+1,6,'0',STR_PAD_LEFT)}}</td>
                                        <td class="text-center">{{date_format(date_create($mouvement['dateMvt']),'d/m/Y H:i')}}</td>
                                        <td class="text-center">{{$mouvement->_Reglement?$mouvement->_Reglement->reference:"--"}}</td>
                                        <td>{{$mouvement['libelleMvt']}}</td>
                                        <td class="text-right">{{$mouvement['sens'] == 0 ? number_format($mouvement['montantMvt'],0,',',' '):''}}</td>
                                        <td class="text-right">{{$mouvement['sens'] == 2 ?  number_format(-$mouvement['montantMvt'],0,',',' ') : ''}}</td>
                                        @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['GESTION CLIENT'])->exists())
                                        <td class="text-right">
                                            @if ($mouvement['destroy'] == false)
                                            <a class="btn btn-danger btn-sm" href="{{ route('compteClient.delete', ['mouvement'=>$mouvement['id'],'client' => $client->id]) }}">
                                                <i class="fa-solid fa-trash-can"></i></a>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Réference</th>
                                        <th>Libelle</th>
                                        <th>Crédit</th>
                                        <th>Débit</th>
                                        @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())
                                        <th>Action</th>
                                        @endif
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
                [0, 'asc']
            ],
            "pageLength": 15,
            // "columnDefs": [{
            //         "targets": 2,
            //         "orderable": false
            //     },
            //     {
            //         "targets": 0,
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