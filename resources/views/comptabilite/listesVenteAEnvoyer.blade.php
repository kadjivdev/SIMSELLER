@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>COMPTABILITE</h1>
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
                <div class="col-12">
                    <div class="card">
                        @if($message = session('message'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ $message }}
                        </div>
                        @endif
                        @if($message = session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ $message }}
                        </div>
                        @endif
                        @if($message = session('messageSupp'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ $message }}
                        </div>
                        @endif

                        <!-- /.card-header -->
                        <div class="card-body">
                            @if(Auth::user()->roles()->where('libelle', 'CONTROLEUR')->exists()||Auth::user()->roles()->where('libelle', 'CONTROLEUR VENTE')->exists())
                            @if(!IS_FOFANA_ACCOUNT(auth()->user()))
                            <form action="{{route('ventes.envoieComptabilite')}}" method="post">
                                @csrf
                                <input type="text" id="ventes" name="ventes" hidden>
                                <div class="row mb-3">
                                    <div class="col-12 text-right ">
                                        <button class="btn btn-success col-2 btn-block btn-sm " type="submit"> Envoyer les Ventes </button>
                                    </div>
                                </div>
                            </form>
                            @endif
                            @endif
                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 12px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Date Vente</th>
                                        <th>Type de Vente</th>
                                        <th>Payeur</th>

                                        <th>Chauffeur</th>
                                        <th>Bl</th>
                                        <th>Camion</th>
                                        <th>Destination</th>

                                        <th>PU Ciment</th>
                                        <th>Qté</th>
                                        <th>Total. Ciment</th>
                                        <th>PU Trans</th>
                                        <th>Total. Transport</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        @if(Auth::user()->roles()->where('libelle', 'CONTROLEUR')->exists()||Auth::user()->roles()->where('libelle', 'CONTROLEUR VENTE')->exists())
                                        @if(!IS_FOFANA_ACCOUNT(auth()->user()))
                                        <th>Action</th>
                                        @endif
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($AEnvoyers as $key => $AEnvoyer )
                                    <tr class="{{$AEnvoyer->statut == "Vendue" ? 'bg-warning':'' }}">
                                        <td class="">
                                            @if(Auth::user()->roles()->where('libelle', 'CONTROLEUR')->exists()||Auth::user()->roles()->where('libelle', 'CONTROLEUR VENTE')->exists())
                                            <div class="form-group">
                                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                    <input type="checkbox" class="custom-control-input checkbox-vente" id="customSwitch{{ $AEnvoyer->id }}" data-vente-id="{{ $AEnvoyer->id }}">
                                                    <label class="custom-control-label" for="customSwitch{{ $AEnvoyer->id }}"></label>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $AEnvoyer->code }}</td>
                                        <td class="text-center">{{ date_format(date_create($AEnvoyer->date),'d/m/Y') }}</td>
                                        <td class="text-center">{{ $AEnvoyer->typeVente->libelle }}</td>
                                        <td class="">{{ count($AEnvoyer->filleuls) > 0 ? $AEnvoyer->filleuls['nomPrenom']." (IFU: ".$AEnvoyer->filleuls['ifu'].")" : $AEnvoyer->commandeclient->client->raisonSociale.' ('.$AEnvoyer->commandeclient->client->ifu.')' }}</td>
                                        <td class="text-center">
                                            @foreach ( $AEnvoyer->vendus as $vendu )
                                            <b>{{ $vendu->programmation->chauffeur->nom }} ({{ $vendu->programmation->chauffeur->prenom  }})</b> <br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach ( $AEnvoyer->vendus as $vendu )
                                            <b>{{ $vendu->programmation->bl_gest ? $vendu->programmation->bl_gest : $vendu->programmation->bl }}</b> <br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @foreach ( $AEnvoyer->vendus as $vendu )
                                            <b>{{ $vendu->programmation->camion->immatriculationTracteur }} ({{ $vendu->programmation->camion->immatriculationRemorque  }})</b> <br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{$AEnvoyer->destination}}</td>


                                        <td class="text-right">{{ number_format($AEnvoyer->pu,0,'',' ') }}</td>
                                        <td class="text-right">{{ number_format($AEnvoyer->qteTotal,0,'',' ') }}</td>
                                        <td class="text-right">{{ number_format(($AEnvoyer->pu*$AEnvoyer->qteTotal),0,'',' ') }}</td>
                                        <td class="text-right">{{ number_format(($AEnvoyer->transport),0,'',' ') }}</td>
                                        <td class="text-right">{{ number_format(($AEnvoyer->transport*$AEnvoyer->qteTotal),0,'',' ') }}</td>
                                        <td class="text-right">{{ number_format((($AEnvoyer->pu*$AEnvoyer->qteTotal)+($AEnvoyer->transport*$AEnvoyer->qteTotal)),0,'',' ') }}</td>
                                        <td class="text-right "><span class="badge badge-success">{{ $AEnvoyer->statut }}</span></td>

                                        @if(Auth::user()->roles()->where('libelle', 'CONTROLEUR')->exists()||Auth::user()->roles()->where('libelle', 'CONTROLEUR VENTE')->exists())
                                        @if(!IS_FOFANA_ACCOUNT(auth()->user()))
                                        <td class="text-right "><a data-id="{{$AEnvoyer->id}}" class="btn btn-success btn-sm" href="#" onclick="charger({{ $AEnvoyer->id }})" data-toggle="modal" data-target="#modal-lg"> Demande </a></td>
                                        @endif
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Date Vente</th>
                                        <th>Type de Vente</th>
                                        <th>Payeur</th>

                                        <th>Chauffeur</th>
                                        <th>Bl</th>
                                        <th>Camion</th>
                                        <th>Destination</th>

                                        <th>PU Ciment</th>
                                        <th>Qté</th>
                                        <th>Total. Ciment</th>
                                        <th>PU Trans</th>
                                        <th>Total. Transport</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        @if(Auth::user()->roles()->where('libelle', 'CONTROLEUR')->exists()||Auth::user()->roles()->where('libelle', 'CONTROLEUR VENTE')->exists())
                                        @if(!IS_FOFANA_ACCOUNT(auth()->user()))
                                        <th>Action</th>
                                        @endif
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
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Demande de Modification de la Vente <b id="code"></b> </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('ventes.demandeVente') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="number" hidden class="form-control" id="id" aria-describedby="id" name="id">

                        <center>
                            <div class="info-box">

                                <div class="info-box-content">
                                    <span class="info-box-number">
                                        <h5>Au lieu de :</h5>
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </center>


                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="PrixUnitaire">Prix Unitaire</label>
                                    <input type="number" name="PrixUnitaireNew" class="form-control" id="PrixUnitaireNew" aria-describedby="PrixUnitaire">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="PrixUnitaire">Prix Unitaire</label>
                                    <input type="number" name="PrixUnitaireOld" class="form-control" id="PrixUnitaireOld" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="transport">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="PrixTransport">Prix Transport</label>
                                    <input type="number" name="PrixTransportNew" class="form-control" id="PrixTransportNew" aria-describedby="PrixTransportNew">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="PrixTransport">Prix Transport</label>
                                    <input type="number" name="PrixTransportOld" class="form-control" id="PrixTransportOld" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="qte">Quantité</label>
                                    <input type="number" name="qteNew" class="form-control" id="qte" aria-describedby="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="qte">Quantité</label>
                                    <input type="number" name="qteOld" class="form-control" id="qteOld" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="qte">Observation</label>
                                    <textarea type="text" name="observation" class="form-control" id="" aria-describedby="" rows="3" required></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Retour</button>
                        <button type="submit" class="btn btn-success">Envoyer</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        // Function to collect vente-id values of checked checkboxes
        function getCheckedVentes() {
            const checkedVentes = [];
            $('.checkbox-vente:checked').each(function() {
                checkedVentes.push($(this).data('vente-id'));
            });
            return checkedVentes;
        }

        $("#example1").on('change', function() {
            $('#ventes').val(getCheckedVentes())
        });
    });
</script>
<script>
    $(document).ready(function() {});

    function charger(id) {
        axios.get("{{env('APP_BASE_URL')}}ventes/show/" + id).then((response) => {
            var vente = response.data
            // console.log(vente);
            $('#id').val(vente.id)
            $('#PrixUnitaireOld').val(vente.pu);
            $('#PrixTransportOld').val(vente.transport);
            $('#qteOld').val(vente.qteTotal);
            $('#code').text(vente.code);

            if (!($('#PrixTransportOld').val())) {
                $('#transport').hide();
            }
        }).catch(() => {
            console.error("Erreur");
        })
    };
</script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["pdf", "print"],
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