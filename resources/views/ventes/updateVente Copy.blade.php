@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>MODIFICATION D'UNE VENTE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item active">Listes des ventes en attente de modification</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <br><br><br>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @if(session()->has("message"))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{session()->get("message") }}
                        </div>
                        @endif

                        @if($message = session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ $message }}
                        </div>
                        @endif

                        <!-- /.card-header -->
                        <h4 class="text-center">Détail de la vente</h4>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="">
                                        <li class="nav-item"> <b> <var>Vente Code: </var></b> <span> {{$vente->code}} </span> </li>
                                        <li class="nav-item"> <b> <var>Vente date: </var></b> {{$vente->date}} </li>
                                        <li class="nav-item"> <b> <var>Qte total: </var></b> {{$vente->qteTotal}} </li>
                                        <li class="nav-item"> <b> <var>Vente montant: </var></b> {{$vente->montant}} </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="">
                                        <li class="nav-item"> <b> <var>Client: </var></b> {{$vente->commandeclient->client->nom}} {{$vente->commandeclient->client->prenom}} </li>
                                        <li class="nav-item"> <b> <var>Reponsable de la vente: </var></b> {{$vente->user->name}} </li>
                                        <li class="nav-item"> <b> <var>Produit: </var></b> {{$vente->produit?$vente->produit->libelle:""}} </li>
                                        <li class="nav-item"> <b> <var>Vente Type: </var></b> {{$vente->typeVente->libelle}} </li>
                                    </ul>
                                </div>
                            </div>
                            <br>
                            <form action="{{route('ventes.updateVente',$vente)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="vente" value="{{$vente->id}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="">P.U</label>
                                            <input type="text" class="form-control" value="{{$vente->pu}}" name="pu">

                                            @error('pu')
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="">Qte totale </label>
                                            <input type="text" value="{{$vente->qteTotal}}" name="qteTotal" class="form-control">
                                            @error('qteTotal')
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group mb-3">
                                            <label for="">Produit </label>
                                            <!-- <select class="form-control form-control-sm select2" disabled id="" name="produit">
                                                <option class="text-center" value="{{$vente->produit?$vente->produit->id:''}}" selected>{{$vente->produit?$vente->produit->libelle:""}}</option>
                                                @foreach($products as $product)
                                                <option value="{{$product->id}}">{{ $product->libelle }}</option>
                                                @endforeach
                                            </select>
                                            @error('produit')
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror -->

                                            <select class="form-control form-control-sm select2   @error('produit_id') is-invalid @enderror" onchange="selectDefaultDriver('{{old('programmation_id')}}')" id="produit" name="produit" style="width: 100%;">
                                                <option selected>**Choisissez un produit**</option>
                                                @if($vente->commandeclient->byvente == 1)
                                                    @foreach($products as $produit)
                                                    <option value="{{$produit->id}}" @if ($vente) @if (old('produit_id')) {{old('produit_id')==$produit->id?'selected':''}} @else {{$vente->produit_id==$produit->id?'selected':''}} @endif @else {{old('produit_id')==$produit->id?'selected':''}} @endif>{{ $produit->libelle }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach($vente->commandeclient->commanders as $cder)
                                                    <option value="{{$cder->produit->id}}" @if ($vente) @if (old('produit_id')) {{old('produit_id')==$cder->produit->id?'selected':''}} @else {{$vente->produit_id==$cder->produit->id?'selected':''}} @endif @else {{old('produit_id')==$cder->produit->id?'selected':''}} @endif>{{ $cder->produit->libelle }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('produit_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <!-- <label for="">Camion </label>
                                            <select class="form-control form-control-sm select2" id="" name="produit">
                                                <option class="text-center" value="{{$vente->produit?$vente->produit->id:''}}" selected>{{$vente->produit?$vente->produit->libelle:""}}</option>
                                                @foreach($products as $product)
                                                <option value="{{$product->id}}">{{ $product->libelle }}</option>
                                                @endforeach
                                            </select>
                                            @error('produit')
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror -->

                                            <div class="form-group">
                                                <div hidden id="loader" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px">
                                                    <i class="fa fa-spin fa-spinner"></i> Recherche Type ...
                                                </div>
                                                <div id="champ">
                                                    <label>Camion <span class="text-danger">*</span></label>
                                                    <select required class="form-control form-control-sm select2  @error('programmation_id') is-invalid @enderror" name="programmation_id" id="bl" onchange="getStockDisponible()" style="width: 100%;">
                                                        @foreach($vente->vendus as $vendu)
                                                        <option value="{{ $vendu->programmation->id }}" selected>{{ $vendu->programmation->bl_gest?$vendu->programmation->bl_gest:$vendu->programmation->bl }}</option>
                                                        <!-- <option id="bl_0" selected disabled>** Choisissez un Camion **</option> -->
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('programmation_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="">Clients</label>
                                            <select id="client" class="form-control form-control-sm select2" name="client_id">
                                                <option value="{{$vente->payeur->id}}" class="text-center" selected>{{$vente->commandeclient->client->nom}} {{$vente->commandeclient->client->prenom}}</option>
                                                @foreach($clients as $client)
                                                <option value="{{$client->id}}">
                                                    {{ $client->raisonSociale }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('client_id')
                                            <span class="text-danger">{{$message}} </span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm btn-success w-100 text-uppercase">Modifier</button>
                                <br>
                            </form>
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
                [0, 'desc']
            ],
            "pageLength": 15,
            "columnDefs": [{
                    "targets": 8,
                    "orderable": false
                },
                {
                    "targets": 9,
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
<script>
        let user = {{auth()->user()->id}};
        // $(document).ready(function (){
        //     //transport('{{old('$vente->transport')}}')
        //     selectDefaultDriver('{{old('programmation_id')}}');
        // })
        function selectDefaultDriver(old){
            if($('#produit').val()) {
                $('#champ').attr('hidden','hidden');
                $('#loader').removeAttr('hidden')
                $('#bl option').removeAttr('selected');
                $('#bl').empty();
                
                axios.get('{{env('APP_BASE_URL')}}programmation/produits/' + $('#produit').val() + '/'+ user).then((response) => {
                    var programmation = response.data;
                    console.log(programmation);
                    $('#bl').append("<option selected disabled>Choisissez un BL</option>");
                    for (var i = 0; i < programmation.length; i++) {
                        var val = programmation[i].id;
                        var text = programmation[i].camion.immatriculationTracteur;
                        if(old == val)
                            $('#bl').append("<option selected value="+ val +">" + text + "</option>");
                        else
                            $('#bl').append("<option value="+ val +">" + text + "</option>");
                    }
                    $('.select2').select2(); 
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                    if(old)
                        getStockDisponible();
                }).catch(()=>{
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                })
            }
            else {
                $('#bl_0').attr('selected', 'true');
                $('.select2').select2()
            }
        }

        function suppressionVendus(id){
            let url = '{{env('APP_WEB_URL')}}';
            console.log(url);
            $('#form-suppression').attr('action','{{env('APP_WEB_URL')}}ventes/vendus/destroy/'+id);
        }
       
        function calculMontant(){
            let qteTotal = $('#qteTotal').val();
            let pu = $('#pu').val();
            let tt = $('#tt').val();
            let remise = $('#remise').val() ? $('#remise').val() : 0;
            if(qteTotal && pu){
                let montant = (pu*qteTotal) - remise;
                if(tt){
                    montant = montant + (tt*qteTotal);
                }
                $('#montant').val(montant);

            }
            else {
                $('#montant').val(0);
            }
        }

        function getStockDisponible(){

            if($('#bl').val()) {
                $('#champstock').attr('hidden','hidden');
                $('#loaderstock').removeAttr('hidden')
                axios.get('{{env('APP_BASE_URL')}}programmation/stock/' + $('#bl').val()).then((response) => {
                    var programmation = response.data;
                    $('#stockDispo').val(programmation);
                    $('#loaderstock').attr('hidden', 'hidden');
                    $('#champstock').removeAttr('hidden')
                }).catch(()=>{
                    $('#loaderstock').attr('hidden', 'hidden');
                    $('#champstock').removeAttr('hidden')
                    $('#stockDispo').val('')
                })
            }
            else {


            }
        }
    </script>
@endsection