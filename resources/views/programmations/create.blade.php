@extends('layouts.app')


@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">PROGRAMMATIONS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('programmations.index') }}">Listes des
                                programmations</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>

            @include('programmations.entete')
            @include('programmations.enteteproduit')
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Nouvelle programmation</h3>
                        </div>
                        <form method="POST" action="{{ $programmation ? route('programmations.store', ['detailboncommande' => $detailboncommande->id, 'programmation' => $programmation->id]) : route('programmations.store', ['detailboncommande' => $detailboncommande->id]) }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    @if ($programmation)
                                    <input type="hidden" class="form-control form-control-sm text-center" name="statut" value="{{ $programmation->statut }}" style="text-transform: uppercase" autofocus>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Code<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm text-center" name="code" value="@if ($programmation) {{ old('code') ? old('code') : $programmation->code }}@else{{ $programmation ? $programmation->code : 'A GENERER' }} @endif" style="text-transform: uppercase" autofocus readonly>
                                        </div>
                                    </div>
                                    @endif
                                    <div @if ($programmation==null) class="col-sm-4" @else class="col-sm-3" @endif>
                                        <div class="form-group">
                                            <label>Date<span class="text-danger">*</span></label>
                                            <input type="date" id="dateprogrammer" class="form-control form-control-sm @error('dateprogrammer') is-invalid @enderror text-center" name="dateprogrammer" value="@if ($programmation) {{ old('dateprogrammer') ? old('dateprogrammer') : $programmation->dateprogrammer }}@else{{ date('Y-m-d') }} @endif" autocomplete="dateprogrammer" autofocus required>
                                            @error('dateprogrammer')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div @if ($programmation==null) class="col-sm-4" @else class="col-sm-3" @endif>
                                        <div class="form-group">
                                            <label>Avaliseurs<span class="text-danger">*</span></label>
                                            <select id="avaliseur" class="select2 form-control form-control-sm @error('camion_id') is-invalid @enderror" name="avaliseur_id" style="width: 100%;">
                                                <option value="{{ null }}" selected>** choisissez un avaliseur
                                                    **</option>
                                                @foreach ($avaliseurs as $avaliseur)
                                                <option value="{{ $avaliseur->id }}" @if ($programmation) @if (old('avaliseur_id')) {{ old('avaliseur_id') == $avaliseur->id ? 'selected' : '' }} @else {{ $programmation->avaliseur_id == $avaliseur->id ? 'selected' : '' }} @endif @else {{ old('camion_id') == $avaliseur->id ? 'selected' : '' }} @endif>{{ $avaliseur->nom }}
                                                    {{ $avaliseur->prenom }} ({{ $avaliseur->telephone }})
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('avaliseur_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div @if ($programmation==null) class="col-sm-4" @else class="col-sm-3" @endif>
                                        <div class="form-group">
                                            <label>Camions<span class="text-danger">*</span></label>
                                            <select onchange="selectDefaultDriver()" id="camion" class="select2 form-control form-control-sm @error('camion_id') is-invalid @enderror" name="camion_id" style="width: 100%;">
                                                <option value="{{ null }}" selected>** choisissez un camion **
                                                </option>
                                                @foreach ($camions as $camion)
                                                <option value="{{ $camion->id }}" @if ($programmation) @if (old('camion_id')) {{ old('camion_id') == $camion->id ? 'selected' : '' }} @else {{ $programmation->camion_id == $camion->id ? 'selected' : '' }} @endif @else {{ old('camion_id') == $camion->id ? 'selected' : '' }} @endif>{{ $camion->marque->libelle }}
                                                    ({{ $camion->immatriculationTracteur }},
                                                    {{ $camion->immatriculationRemorque }})
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('camion_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <div hidden id="loader" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px">
                                                <i class="fa fa-spin fa-spinner"></i> Recherche chauffeur ...
                                            </div>
                                            <div id="champ">
                                                <label>Chauffeurs<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('chauffeur_id') is-invalid @enderror" name="chauffeur_id" style="width: 100%;" id="chauffeur">
                                                    <option id="chauffeur_0" selected>** choisissez un chauffeur **
                                                    </option>
                                                    @foreach ($chauffeurs as $chauffeur)
                                                    <option value="{{ $chauffeur->id }}" id="chauffeur_{{ $chauffeur->id }}" @if ($programmation) @if (old('chauffeur_id')) {{ old('chauffeur_id') == $chauffeur->id ? 'selected' : '' }} @else {{ $programmation->chauffeur_id == $chauffeur->id ? 'selected' : '' }} @endif @else {{ old('chauffeur_id') == $chauffeur->id ? 'selected' : '' }} @endif>{{ $chauffeur->prenom }}
                                                        {{ $chauffeur->nom }} ({{ $chauffeur->telephone }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('chauffeur_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Qte<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-sm" name="qteprogrammer" style="text-transform: uppercase" value="@if ($programmation) {{ old('qteprogrammer') ? old('qteprogrammer') : $programmation->qteprogrammer }}@else{{ old('qteprogrammer') }} @endif" autocomplete="qteprogrammer" min="1" autofocus required>
                                            @error('qteprogrammer')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Zones<span class="text-danger">*</span></label>
                                            <select class="select2 form-control form-control-sm @error('zone_id') is-invalid @enderror" name="zone_id" style="width: 100%;">
                                                <option value='' selected>** choisissez une zone **</option>
                                                @foreach ($zones as $zone)
                                                <option value="{{ $zone->id }}" @if ($programmation) @if (old('zone_id')) {{ old('zone_id') == $zone->id ? 'selected' : '' }} @else {{ $programmation->zone_id == $zone->id ? 'selected' : '' }} @endif @else {{ old('zone_id') == $zone->id ? 'selected' : '' }} @endif>{{ $zone->libelle }}</option>
                                                @endforeach
                                            </select>
                                            @error('zone_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        @if ($programmation)
                                        <a href="{{ route('programmations.create', ['detailboncommande' => $detailboncommande->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            {{ __('Annuler') }}
                                        </a>
                                        @else
                                        <a href="{{ route('programmations.index') }}" class="btn btn-sm btn-secondary btn-block">
                                            <i class="fa-solid fa-circle-left"></i>
                                            {{ __('Retour') }}
                                        </a>
                                        @endif
                                    </div>
                                    <div class="col-sm-4">
                                        @if ($programmation)
                                        <button type="submit" class="btn btn-sm btn-warning btn-block">
                                            {{ __('Modifier') }}
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        @else
                                        <button type="submit" class="btn btn-sm btn-success btn-block" @if ($detailboncommande->qteCommander - intval($total) == 0) disabled @endif>
                                            {{ __('Enregistrer') }}
                                            <i class="fa-solid fa-floppy-disk"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="alert alert-success alert-dismissible col-12" id="success">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        <div id="message"></div>
                                    </div>
                                    <div class="alert alert-danger alert-dismissible col-12" id="danger">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        <div id="messageError"></div>
                                    </div>
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped table-sm" style="font-size: 12px" id="example1">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Code</th>
                                                    <th>Date</th>
                                                    <th>Camion</th>
                                                    <th>Chauffeur</th>
                                                    <th>Avaliseur</th>
                                                    <th>Quantité</th>
                                                    <th>Zone</th>
                                                    <!-- <th>Date Sortie</th> -->
                                                    <th>BL</th>
                                                    <th>Statut</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($programmations->count() > 0)
                                                <?php $compteur = 1; ?>
                                                @foreach ($programmations as $programmation)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td class="text-center">{{ $programmation->code }}</td>
                                                    <td class="text-center">
                                                        {{ date_format(date_create($programmation->dateprogrammer), 'd/m/Y') }}
                                                    </td>
                                                    <td>{{ $programmation->camion->immatriculationTracteur }}
                                                        ({{ $programmation->camion->marque->libelle }})
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $programmation->chauffeur->prenom }}
                                                        {{ $programmation->chauffeur->nom }} <br>
                                                        ({{ $programmation->chauffeur->telephone }})
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $programmation->avaliseur->prenom }}
                                                        {{ $programmation->avaliseur->nom }} <br>
                                                        ({{ $programmation->avaliseur->telephone }})
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($programmation->qteprogrammer, 2, ',', ' ') }}
                                                    </td>
                                                    <td>{{ $programmation->zone->libelle }}
                                                        ({{ $programmation->zone->departement->libelle }})</td>
                                                    <!-- <td>{{ $programmation->dateSortie ? date_format(date_create($programmation->dateSortie),'d/m/Y'):'' }}</td> -->
                                                    <td>{{ $programmation->bl_gest ? $programmation->bl_gest:$programmation->bl}}</td>

                                                    <td class="text-center">
                                                        @if ($programmation->statut == 'Annuler')
                                                        <span class="badge badge-danger">{{ $programmation->statut }}</span>
                                                        @elseif($programmation->statut == 'Valider')
                                                        <span class="badge badge-success">{{ $programmation->statut }}</span>
                                                        @elseif($programmation->statut == 'Livrer')
                                                        <span class="badge badge-primary">{{ $programmation->statut }}</span>
                                                        @endif
                                                    </td>
                                                    @if ($programmation->detailboncommande->boncommande->status !='livrer')
                                                    <td class="text-center">
                                                        @if($programmation->statut == 'Valider')
                                                        <span id="annuler-{{ $programmation->id }}"> <a class="btn btn-primary btn-sm" title="Annuler une programmation" href="{{ route('programmations.show', ['detailboncommande' => $detailboncommande->id, 'programmation' => $programmation->id, 'total' => $total]) }}"><i class="fa-regular fa-rectangle-xmark"></i></a>
                                                        </span>
                                                        @endif

                                                        @if ($programmation->statut == 'Valider' && !$programmation->bl && !$programmation->bl_gest && !$programmation->dateSortie)
                                                        <!-- @if ($programmation->imprimer)
                                                                       <span id="annuler-{{ $programmation->id }}"> <a class="btn btn-primary btn-sm"
                                                                           title="Annuler une programmation"
                                                                           href="{{ route('programmations.show', ['detailboncommande' => $detailboncommande->id, 'programmation' => $programmation->id, 'total' => $total]) }}"><i
                                                                                    class="fa-regular fa-rectangle-xmark"></i></a>
                                                                      </span>
                                                                    @endif -->
                                                        @if (!$programmation->imprimer)
                                                        <a class="btn btn-warning btn-sm" href="{{ route('programmations.create', ['detailboncommande' => $detailboncommande->id, 'programmation' => $programmation->id]) }}">
                                                            <i class="fa-solid fa-pen-to-square"></i></a>
                                                        <a class="btn btn-danger btn-sm" href="{{ route('programmations.destroy', ['detailboncommande' => $detailboncommande->id, 'programmation' => $programmation->id]) }}">
                                                            <i class="fa-solid fa-trash-can"></i></a>
                                                        @endif
                                                        @endif

                                                        @if(($programmation->statut != 'Livrer') && ($programmation->statut != 'Annuler') && ($programmation->imprimer) && ($programmation->bl_gest == Null))
                                                        <span id="bl-{{ $programmation->id }}"> <a class="btn btn-dark btn-sm" onclick="getId({{ $programmation->id }})" id="bl_prog" title="BL Gestionnaire" href="{{-- {{ route('programmations.dateSortie', ['detailboncommande' => $detailboncommande->id, 'programmation' => $programmation->id]) }} --}}#" data-toggle="modal" data-target="#modal-sm"><b>BL</b></a></span>
                                                        @endif
                                                    </td>
                                                    @endif

                                                </tr>
                                                {{-- @include('programmations.modalSortie') --}}

                                                @endforeach

                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-sm">
                                            <tr>
                                                <br />
                                                <td class="" colspan="2"><b>Total Programmé</b></td>
                                                <td colspan="6" class="text-right"><b>{{ $total }}
                                                        Tonne(s)</b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if ($programmation)
                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        <a href="{{ route('programmations.index') }}" class="btn  btn-sm btn-block btn-primary">
                                            Ok
                                            <i class="fa-solid fa-circle-check ml-1"></i>
                                        </a>
                                    </div>
                                    <!--
                                                    <div class="col-sm-4">
                                                        <a href="" class="btn  btn-sm btn-block btn-success" >
                                                            Valider tout
                                                            <i class="fa-solid fa-check-double"></i>
                                                        </a>
                                                    </div>
                                                -->
                                </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="modal-sm">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title"><small>Bordereau de Livraison</small></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="bl_gest" placeholder="" required>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="saveBl()" class="btn btn-primary" data-dismiss="modal">Save changes</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

@section('script')
    <script>
            $('#success ').attr('hidden', 'hidden');
            $('#danger').attr('hidden', 'hidden');
        
            var id_prog = 0
            function getId(id){
                id_prog = id ;
            }
            function saveBl() {
                var bl_gest = $('#bl_gest').val();
                axios.get('{{env('APP_BASE_URL')}}programmation/livraison/bl/' + id_prog +'/'+ bl_gest +'/{{ Auth::user()->name }}').then((response) => {
                    console.log(response.data);
                    if (response.data == 'success') {
                        $("#message").text(response.data); 
                        $('#success').removeAttr('hidden');
                        $(`#annuler-${id_prog}`).attr('hidden', 'hidden');
                        $(`#bl-${id_prog}`).attr('hidden', 'hidden');
                    } else {
                        $("#messageError").text(response.data); 
                        $('#danger').removeAttr('hidden')    
                    }
                    var windowHeight = $(window).height(); 
                     var scrollTo = windowHeight / 2; 
                    $("html, body, .table").animate({ scrollTop: scrollTo }, "slow");
                    
                    //$('#success').attr('hidden', 'hidden');                 
                }).catch(()=>{
                    alert(response.data);                   

                });
            }
       
    </script>
    <script>
        function selectDefaultDriver() {
            if ($('#camion').val()) {
                $('#champ').attr('hidden', 'hidden');
                $('#loader').removeAttr('hidden')
                $('#chauffeur option').removeAttr('selected');
                axios.get("{{ env('APP_BASE_URL') }}programmation/chauffeur/" + $('#camion').val()).then((response) => {
                    $('#chauffeur_' + response.data.id).attr('selected', 'true');
                    $('.select2').select2();
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                }).catch(() => {
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                })
            } else {
                $('#chauffeur_0').attr('selected', 'true');
                $('.select2').select2()
            }
        }
    </script>
    <script>
        $(function() {
            @if (session('message') || $programmation || $errors)
                window.scrollTo(0, $("#entete_produit").offset().top);
            @endif
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["pdf", "print"],
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
        })
         
    </script>
@endsection