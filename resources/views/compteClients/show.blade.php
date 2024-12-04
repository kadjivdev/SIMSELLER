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

                                    @php($credit = $client->reglements->where("for_dette",false)->whereNull("vente_id")->sum("montant"))
                                    @php($debit = $client->reglements->whereNotNull("vente_id")->sum("montant"))

                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">CREDIT</span>
                                                    <span class="info-box-number">{{ number_format($credit, 0, ',', ' ')  }}</span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-danger"><i class="fas fa-hand-holding-usd"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">DEBIT</span>
                                                    <span class="info-box-number">{{number_format($debit, 0, ',', ' ')   }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">SOLDE</span>
                                                    <span class="info-box-number">{{number_format($credit-$debit, 0, ',', ' ')  }}</span>
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
                                    @if (Auth::user()->roles()->where('libelle', 'GESTION CLIENT')->exists() || Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', 'VENDEUR')->exists())
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
                                        <th>Pour dette</th>
                                        <th>Reversement</th>
                                        <th>Preuve</th>
                                        @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($total = 0)
                                    @foreach($mouvements as $key=>$mouvement)
                                    @php($total += !$mouvement->_Reglement->for_dette?$mouvement['montantMvt']:0)
                                    <tr>
                                        <td class="text-center">MVT-{{str_pad($key+1,6,'0',STR_PAD_LEFT)}}</td>
                                        <td class="text-center">{{date_format(date_create($mouvement['created_at']),'d/m/Y H:i')}}</td>
                                        <td class="text-center">{{$mouvement->_Reglement?$mouvement->_Reglement->reference:"--"}}</td>
                                        <td>{{$mouvement['libelleMvt']}}</td>
                                        <td class="text-right">{{($mouvement['sens'] == 0 && !$mouvement->_Reglement->for_dette) ? number_format($mouvement['montantMvt'],0,',',' '):''}}</td>
                                        <td class="text-right">{{$mouvement['sens'] == 2 ?  number_format(-$mouvement['montantMvt'],0,',',' ') : ''}}</td>
                                        <td class="text-center">
                                            @if($mouvement->_Reglement->for_dette)
                                            <span class="badge bg-success">Oui</span>
                                            <span class="badge bg-info d-block">{{number_format($mouvement['montantMvt'],0,',',' ')}}</span>
                                            @else
                                            <span class="badge bg-danger">Non</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($mouvement->_Reglement->old_solde)
                                            <span class="badge bg-success">Oui</span>
                                            @else
                                            <span class="badge bg-danger">Non</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-success float-md-right text-white btn-sm" href="{{ $mouvement->_Reglement->document?asset('storage/'.$mouvement->_Reglement->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>
                                        </td>
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
                                        <th>Pour dette</th>
                                        <th>Reversement</th>
                                        <th>Preuve</th>
                                        @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>

                            <table id="" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <tr>
                                    <td class="text-left" colspan="2">Total :</td>
                                    <td class="text-right" colspan="3"> <span class="badge bg-success" id="montant">{{number_format($total,0,',',' ')}}</span> FCFA </td>
                                </tr>
                            </table>


                            @if(count($rejet_reglements)>0)

                            <br><br><br><br><br>

                            <!-- LES APPROVISIONNEMENTS REJETES -->
                            <h6 class="text-center bg-warning p-1" style="display: inline!important;">LES APPROVISIONNEMENTS REJETES</h6>
                            <table id="" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Réference</th>
                                        <th>Libelle</th>
                                        <th>Montant</th>
                                        <th>Pour dette</th>
                                        <th>Reversement</th>
                                        <th>Preuve</th>
                                        <!-- @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists()) -->
                                        <th>Action</th>
                                        <!-- @endif -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($total = 0)
                                    @foreach($rejet_reglements as $reglement)
                                    <tr>
                                        <td class="text-center">{{$reglement->reference}}</td>
                                        <td class="text-center">{{$reglement->date}}</td>
                                        <td class="text-center">{{$reglement->reference}}</td>
                                        <td> <textarea name="" class="form-control" rows="1" placeholder="{{$reglement->observation_validation}}" disabled></textarea> </td>
                                        <td class="text-right">{{number_format($reglement->montant,0,',',' ')}}</td>
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
                                            <a class="btn btn-success float-md-right text-white btn-sm" href="{{ $reglement->document}}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>
                                        </td>
                                        <!-- @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['GESTION CLIENT'])->exists()) -->
                                        <td class="text-right">
                                            <a class="btn btn-warning btn-sm" href="#" data-bs-toggle="modal" onclick="showUpdateModal({{$reglement->id,$reglement->document}})" data-bs-target="#updateAppro">
                                                <i class="bi bi-pencil"></i></a>
                                        </td>
                                        <!-- @endif -->
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Réference</th>
                                        <th>Libelle</th>
                                        <th>Montant</th>
                                        <th>Pour dette</th>
                                        <th>Reversement</th>
                                        <th>Preuve</th>
                                        <!-- @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists()) -->
                                        <th>Action</th>
                                        <!-- @endif -->
                                    </tr>
                                </tfoot>
                            </table>
                            @endif
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

    <!--  MODIFICATION D4APPROVISIONNEMENT -->
    <div class="modal fade" id="updateAppro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fs-5" id="exampleModalLabel">Approvisionnement : <span class="badge bg-success" id="appro_amont_to_update"></span> </h6>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('compteClient.updateAppro')}}" enctype="multipart/form-data">
                        @csrf
                        @method("PATCH")
                        <input type="hidden" name="approId" id="approId">
                        <div class="mb-3">
                            <label for="reference" class="form-label">Reference</label>
                            <input type="text" class="form-control" name="reference" id="reference">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" id="date">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Montant</label>
                            <input type="montant" name="montant" class="form-control" id="appro_montant">
                        </div>
                        <div class="mb-3">
                            <label for="document" class="form-label">Preuve</label>
                            <input type="file" name="document" class="form-control" id="document">
                        </div>
                        @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists() || Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists())
                        <div class="d-flex">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="for_dette" class="form-check-input" id="for_dette">
                                <label class="form-check-label" for="for_dette">Pour dette</label>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="old_solde" class="form-check-input" id="old_solde">
                                <label class="form-check-label" for="old_solde">Pour reversement de solde</label>
                            </div>
                        </div>
                        @endif
                        <button type="submit" title="Valider" hidden class="btn btn-primary" id="validUpdateBtn"><i class="bi bi-check-circle"></i> Valider</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // GESTION DE LA MODIFICATION DES APPROVISIONNMENTS
    function showUpdateModal(approId, document) {

        axios.get("{{env('APP_BASE_URL')}}reglement/approvisionnement/" + approId)
            .then(function(response) {
                var data = response.data

                $("#appro_amont_to_update").text(data.reference)
                $("#approId").val(data.id)
                $("#reference").val(data.reference)
                $("#date").val(data.date)
                $("#appro_montant").val(data.montant)
                $("#date").val(data.date)

                if (data.for_dette == 1) {
                    $("#for_dette").attr("checked", true)
                }
                if (data.old_solde == 1) {
                    $("#old_solde").attr("checked", true)
                }

                if (data.user_id=={{auth()->user()->id}}) {
                    $('#validUpdateBtn').removeAttr("hidden")
                }

                // window.location.reload()
                // Afficher un message de succès
                // alert("Approvisionnement validé avec succès!")
            })
            .catch(function(error) {
                
                // window.location.reload()
                // Afficher un message de succès
                alert("Opération échouée!")
            });
    }

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

        // 
        $("body").on('change', function() {
            const amount = new DataTable('#example1').column(4, {
                page: 'all',
                search: 'applied'
            }).data().sum()

            __V = amount < 0 ? -amount : amount
            $("#montant").html(__V.toLocaleString())
        })
    });
</script>
@endsection