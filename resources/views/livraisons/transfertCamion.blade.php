@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">TRANSFERT CAMION</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Suivi sortie</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if(session('transfert'))
                            <div class="row">
                                <div class="col-md-12 alert-success alert">
                                    <i class="fa fa-check"></i> Transfert de livraison effectué avec succès.
                                </div>
                            </div>
                            @endif

                            @if(session('message'))
                            <div class="row">
                                <div class="col-md-12 alert-success alert">
                                    <i class="fa fa-check"></i> {{session('message')}}
                                </div>
                            </div>
                            @endif

                            @if(session()->has('error'))
                            <div class="row">
                                <div class="col-md-12 alert-dange alert">
                                    <i class="fa fa-check"></i> {{session()->get('error')}}
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- /.card-header -->
                        <!-- FORM -->
                        <form action="{{route('livraisons.transfert')}}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h4 class="modal-title text-center">Transfert de livraison</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="programmation" value="{{$programmation->id}}">
                                <div class="row">
                                    <div class="col-md-12 alert alert-danger" id="error" hidden>
                                        <i class="fa fa-warning"></i> Une erreur interne est
                                        survenue lors du chargement des données. Merci de
                                        reprendre ou de contacter l'administrateur.
                                    </div>
                                </div>
                                <div class="row mb-2" id="form_modal">
                                    <div class="col-md-5">
                                        <label for="">Zone source</label>
                                        <input type="text" disabled id="zone_souce" name="zone_souce" class="form-control" value="{{$programmation->zone->libelle}}">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <i class="fa fa-arrow-alt-circle-right fa-2x text-success" style="margin-top: 0.2em"></i>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Zone destination.</label>
                                        <select name="zone_id" id="zone_dest" class="form-control" required>
                                            @foreach($zones as $zone)
                                            <option value="{{$zone->id}}" class=""> {{$zone->libelle}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2 mb-2" id="motif">
                                    <div class="col-md-12">
                                        <label for="">Motif de transfert</label>
                                        <textarea class="form-control" name="observation" required></textarea>
                                    </div>
                                </div>
                                <div class="alert alert-warning" id="warming">
                                    <i class="fa fa-warning"></i> Le transfert de la livraison
                                    est irreversible. Si vous êtes un résentant, cette livraison
                                    quittera votre responsablité.
                                    êtes vous sur de vouloir transferer cette livraison vers la
                                    destination sélectionnée ?
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between" id="btn">
                                <a href="/livraisons/suivi-camion" type="button" class="btn btn-default">Retour
                                </a>
                                <button type="submit" class="btn btn-primary">Ouiss je confirme le
                                    transfert
                                </button>
                            </div>
                        </form>

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
</div>
@endsection

@section('script')

<script>
    function submitStatuts() {
        $('#statutsForm').submit();
    }

    function handleDateChange(id, inputElement) {
        const dateValue = inputElement.value;

        // Récupérer le conteneur du message
        const messageContainer = inputElement.parentNode.querySelector('.message-container');

        // Vérifier si la date est renseignée
        if (dateValue) {
            showLoader(messageContainer);
            // Appeler l'API via Axios
            axios.post("{{env('APP_BASE_URL ')}}update-date-sortie/" + id, {
                    userName: '{{Auth::user()->name}}',
                    dateSortie: dateValue
                })
                .then(function(response) {
                    // Afficher un message de succès
                    const successMessage = response.data
                    showMessage(messageContainer, '<i class="fas fa-check-circle"></i>', successMessage, 'text-success');
                })
                .catch(function(error) {
                    // Afficher un message d'erreur
                    const errorMessage = error.response.data;
                    showMessage(messageContainer, '<i class="fas fa-times-circle"></i>', errorMessage, 'text-danger', false);
                });
        } else {
            // Si la date n'est pas renseignée, effacer le message
            messageContainer.innerHTML = '';
        }
    }

    function handleBordLivChange(id, inputElement) {
        const BLValue = inputElement.value;

        // Récupérer le conteneur du message
        const messageContainer = inputElement.parentNode.querySelector('.message-container-bl');

        // Vérifier si la date est renseignée
        if (BLValue) {
            showLoader(messageContainer);

            // Appeler l'API via Axios
            axios.get(`{{env('APP_BASE_URL')}}programmation/livraison/bl/${id}/${BLValue}/` + "{{Auth::user()->name }}")
                .then(function(response) {
                    // Afficher un message de succès
                    const successMessage = response.data

                    showMessage(messageContainer, '<i class="fas fa-check-circle"></i>', successMessage, 'text-success');

                })
                .catch(function(error) {
                    // Afficher un message d'erreur
                    console.log(error.response.data);
                    const errorMessage = error.response.data;
                    showMessage(messageContainer, '<i class="fas fa-times-circle"></i>', errorMessage, 'text-danger', false);
                });
        } else {
            // Si la date n'est pas renseignée, effacer le message
            messageContainer.innerHTML = '';
        }
    }

    function showLoader(container) {
        // Créer un élément pour le loader
        const loaderElement = document.createElement('div');
        loaderElement.innerHTML = '<i class="fas fa-spinner fa-spin fa-2x font-weight-bold"></i>';
        loaderElement.classList.add('loader');

        // Ajouter le loader au conteneur
        container.innerHTML = '';
        container.appendChild(loaderElement);

        // Afficher le conteneur
        container.classList.remove('d-none');
    }

    function showMessage(container, iconHtml, message, textStyle, shouldDisappear = true) {
        // Créer un élément pour le message
        const messageElement = document.createElement('div');
        messageElement.innerHTML = `${iconHtml} ${message}`;
        messageElement.classList.add('message', textStyle);

        // Ajouter le message au conteneur
        container.innerHTML = '';
        container.appendChild(messageElement);

        // Afficher le conteneur
        container.classList.remove('d-none');

        // Si le message ne devrait pas disparaître, ne pas définir de minuteur
        if (shouldDisappear) {
            // Supprimer le message après quelques secondes
            setTimeout(function() {
                container.innerHTML = '';
                container.classList.add('d-none'); // Cacher le conteneur après avoir supprimé le message
            }, 3000);
        }
    }

    function loadProgrammation(id) {
        $('#zone_dest option').remove()
        $('#zone_dest').removeAttr('selected');
        $('#loader').removeAttr('hidden');
        $('#form_modal').attr('hidden', 'hidden')
        $('#warming').attr('hidden', 'hidden')
        $('#btn').attr('hidden', 'hidden')
        $('#motif').attr('hidden', 'hidden')

        let optionsAsString = "<option value=''>--Sélectionnez la zone--</option>";
        axios.get("{{env('APP_BASE_URL')}}programmation/livraison/" + id)
            .then((response) => {
                let zones = response.data.zones;

                console.log(response.data);
                // for (let i = 0; i < zones.length; i++) {
                //     optionsAsString += "<option value='" + zones[i].id + "'>" + zones[i].libelle + "</option>";
                // }

                // $('#zone_dest').append(optionsAsString);
                // $('#id').val(response.data.programmation.zone_id)
                // $('#prog').val(response.data.programmation.id)
                // $('#zone_souce').val(response.data.zone_source)
                // $('#form_modal').removeAttr('hidden')
                // $('#warming').removeAttr('hidden')
                // $('#btn').removeAttr('hidden')
                // $('#motif').removeAttr('hidden')
                // $('#loader').attr('hidden', 'hidden')

            }).catch(() => {
                $('#loader').attr('hidden', 'hidden');
                $('#error').removeAttr('hidden')
            })
    }

    function loadDetailTransfert(id) {
        $('#detailTransfert > tbody > tr').remove();
        $('#loader1').removeAttr('hidden');
        axios.get('{{env('
            APP_BASE_URL ')}}programmation/detail-transfert/' + id).then((response) => {
            let details = response.data;
            let table = document.getElementById("#detailTransfert");

            ligneTableau = `
                         <tr>
                            <th>N°</th>
                            <th>Date</th>
                            <th>Zone source</th>
                            <th>Zone destination</th>
                            <th>Observation</th>
                        </tr>
                    `

            //table.innerHTML = ligneTableau

            $('#detailTransfert > tbody').append(ligneTableau);
            for (let i = 0; i < details.length; i++) {
                ligneTableau = `
                             <tr>
                                <td>${(details[i].compteur+1)}</td>
                                <td>${details[i].date}</td>
                                <td>${details[i].source}</td>
                                <td>${details[i].destination}</td>
                                <td>${details[i].observation}</td>
                            </tr>
                        `
                $('#detailTransfert > tbody').append(ligneTableau);
                /*optionsAsString += "<tr>"
                optionsAsString += "<td>" + (details[i].compteur+1) + "</td>";
                optionsAsString += "<td>" + details[i].date + "</td>";
                optionsAsString += "<td>" +  + "</td>";
                optionsAsString += "<td>" + details[i].destination + "</td>";

                optionsAsString += "</tr>"*/
            }


            $('#loader1').attr('hidden', 'hidden')

        }).catch(() => {
            $('#loader1').attr('hidden', 'hidden');
            $('#error1').removeAttr('hidden')
        })
    }

    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["pdf", "print"],
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
                },
                {
                    "targets": 12,
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