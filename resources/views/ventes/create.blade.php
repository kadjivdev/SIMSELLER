@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>VENTES</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ventes.index') }}">Ventes</a></li>
                        <li class="breadcrumb-item active">Nouvelle vente</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="card card-secondary">

                        <div class="card-header">
                            <h3 class="card-title">Enregistrer une nouvelle vente</h3>
                            <div class="float-sm-right">
                                <form id="statutsForm" action="" method="get" hidden="">
                                    <div class="form-group form-group-xs">
                                        <select class="custom-select form-control" id="statuts" name="statuts" onchange="submitStatuts()">
                                            <option value="1" {{ $req == 1 ? 'selected':'' }}>Vente Direct</option>
                                            <option value="2" {{ $req == 2 ? 'selected':'' }}>Vente sur Commande</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <form method="post" action="{{route('ventes.store')}}" id="saveVente">
                            @csrf
                            <div class="card-body">
                                @if ($req == 1)
                                <input type="hidden" name="statuts" value="1" />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Code<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm text-center" name="code" value="@if($vente){{old('code')?old('code'):$vente->code}}@else{{'A GENERER'}}@endif" style="text-transform: uppercase" autofocus readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Date<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm text-center" name="date" value="@if($vente){{old('date')?old('date'):$vente->date}}@else{{old('date')?old('date'):date('Y-m-d')}}@endif" autofocus>
                                            @error('date')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Clients<span class="text-danger">*</span></label>
                                            <select onchange="selectDefaultDriver()" id="client" class="form-control form-control-sm select2" id="client_id" name="client_id" style="width: 100%;" @if($vente)onchange="editClient('{{$vente->client_id}}')" @endif>
                                                <option class="text-center" selected disabled>** choisissez un client **</option>
                                                @foreach($clients as $client)
                                                <option value="{{$client->id}}" data-fiscal="{{ $client->filleulFisc ? true : false }}" @if ($vente) @if (old('client_id')) {{old('client_id')==$client->id?'selected':''}} @else {{$vente->commandeclient->client_id==$client->id?'selected':''}} @endif @else {{old('client_id')==$client->id?'selected':''}} @endif>
                                                    {{ $client->raisonSociale }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('client_id')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div>
                                                <label>Client payeur<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2" name="ctl_payeur" style="width: 100%;" id="ctl_payeur" onchange="filleulTypeCheck()">
                                                    <option selected disabled></option>
                                                    <option value="{{$client->id}}">Lui même</option>
                                                    <option value="0">Autre personne</option>
                                                </select>
                                                @error('ctl_payeur')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="detailFilleul" hidden="">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom & Prénom (CIP)<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="nomPrenom" value="@if($vente){{old('nomPrenom')?old('nomPrenom'):$vente->date}}@else{{old('nomPrenom')?old('nomPrenom'):''}}@endif" id="nomPrenom">
                                            @error('nomPrenom')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Téléphone<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm " name="telephone" value="@if($vente){{old('telephone')?old('telephone'):$vente->telephone}}@else{{old('telephone')?old('telephone'):''}}@endif" id="telephone">
                                            @error('telephone')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ifu<span class="text-danger"></span></label>
                                            <input type="text" class="form-control form-control-sm " name="ifu" max="16" value="@if($vente){{old('ifu')?old('ifu'):$vente->ifu}}@else{{old('ifu')?old('ifu'):''}}@endif">
                                            @error('ifu')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div hidden id="loader" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px">
                                                <i class="fa fa-spin fa-spinner"></i> Recherche Type ...
                                            </div>
                                            <div id="champ">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select onchange="typeSelected()" class="form-control form-control-sm select2" name="type_vente_id" style="width: 100%;" id="typecommande">
                                                    <option class="text-center" selected disabled id="type_commande_0">** choisissez un type vente **</option>
                                                </select>
                                                @error('type_vente_id')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Date paiement 1<span class="text-danger" id="r-echeance" hidden>*</span></label>
                                            <input id="echeance" type="date" name="echeance" value="{{old('echeance')}}" class="form-control form-control-sm " disabled>
                                            @error('echeance')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Zones<span class="text-danger">*</span></label>
                                            <select class="select2 form-control form-control-sm @error('zone_id') is-invalid @enderror" name="zone_id" style="width: 100%;">
                                                <option class="text-center" selected disabled>** choisissez une zone **</option>
                                                @foreach($zones as $zone)
                                                <option value="{{$zone->id}}" @if ($vente) @if (old('zone_id')) {{old('zone_id')==$zone->id?'selected':''}} @else {{$vente->commandeclient->zone_id==$zone->id?'selected':''}} @endif @else {{old('zone_id')==$zone->id?'selected':''}} @endif>{{ $zone->libelle }}</option>
                                                @endforeach
                                            </select>
                                            @error('zone_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Transport<span class="text-danger">*</span></label>
                                            <select class="select2 form-control form-control-sm" name="transport" style="width: 100%;">
                                                <option class="text-center" value="0" selected> Sans Transport</option>
                                                <option class="text-center" value="1"> Avec Transport</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="confirmation_msg" hidden>
                                    <div class="col-sm-12 alert alert-warning">
                                        <p>Vous êtes sur le point de modifier le client. Cette action supprimera les produits vendus au client. </p>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="confimation" id="confirmation" style="margin-top: -0.1em; width: 20px; height: 20px;">
                                            <label class="form-check-label pl-3" for="flexSwitchCheckDefault">Confirmer vous cette action ?</label>
                                        </div>
                                    </div>
                                </div>
                                @elseif ($req == 2)
                                <input type="hidden" name="statuts" value="2" />
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Code<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm text-center" name="code" value="@if($vente){{old('code')?old('code'):$vente->code}}@else{{$vente?$commandeclient->code:'A GENERER'}}@endif" style="text-transform: uppercase" autofocus readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Date<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm text-center" name="date" value="@if($vente){{old('date')?old('date'):$vente->date}}@else{{old('date')?old('date'):date('Y-m-d')}}@endif" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Commande client<span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm select2" id="commande_client_id" name="commande_client_id" style="width: 100%;" @if($vente)onchange="editClient('{{$vente->commandeclient->client_id}}')" @else onchange="selectDefaultDriver()" @endif>
                                                <option class="text-center" value="" selected disabled>** choisissez le type de vente **</option>
                                                @foreach($commandeclients as $commandeclient)
                                                <option value="{{$commandeclient->id}}" @if ($vente) @if (old('commande_client_id')) {{old('commande_client_id')==$commandeclient->id?'selected':''}} @else {{$vente->commande_client_id==$commandeclient->id?'selected':''}} @endif @else {{old('commande_client_id')==$commandeclient->id?'selected':''}} @endif>{{ $commandeclient->code }} {{ $commandeclient->client->sigle?$commandeclient->client->sigle:$commandeclient->client->nom.' '.$commandeclient->client->prenom }}</option>
                                                @endforeach
                                            </select>
                                            @error('commande_client_id')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div hidden id="loader" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px">
                                                <i class="fa fa-spin fa-spinner"></i> Recherche Type ...
                                            </div>
                                            <div id="champ">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select onchange="typeSelected()" class="form-control form-control-sm select2" name="type_vente_id" style="width: 100%;" id="typecommande">
                                                    <option class="text-center" selected disabled id="type_commande_0">** choisissez un type vente **</option>
                                                </select>
                                                @error('type_vente_id')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Date paiement 1<span class="text-danger" id="r-echeance" hidden>*</span></label>
                                            <input id="echeance" type="date" name="echeance" value="{{old('echeance')}}" class="form-control form-control-sm " disabled>
                                            @error('echeance')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Zones<span class="text-danger">*</span></label>
                                            <select class="select2 form-control form-control-sm @error('zone_id') is-invalid @enderror" name="zone_id" style="width: 100%;">
                                                <option class="text-center" selected disabled>** choisissez une zone **</option>
                                                @foreach($zones as $zone)
                                                <option value="{{$zone->id}}" @if ($vente) @if (old('zone_id')) {{old('zone_id')==$zone->id?'selected':''}} @else {{$vente->commandeclient->zone_id==$zone->id?'selected':''}} @endif @else {{old('zone_id')==$zone->id?'selected':''}} @endif>{{ $zone->libelle }}</option>
                                                @endforeach
                                            </select>
                                            @error('zone_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="card-footer">
                                <div hidden="" id="loaderSave" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 15px">
                                    <i class="fa fa-spin fa-spinner fa-2x"></i> Enregistrement ...
                                </div>
                                <div class="row text-center" id="action">
                                    <div class="col-sm-3"></div>
                                    <div class="col-3">
                                        <a href="@if($redirectto=='detailbc') {{route('ventes.edit', ['vente' => $vente->id])}} @else {{ route('ventes.index') }}@endif" class="btn btn-sm btn-secondary  btn-block">
                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                            {{ __('Retour') }}
                                        </a>
                                    </div>
                                    <div class="col-3">
                                        <button type="submit" class="btn btn-sm btn-block btn-success">
                                            <i class="fa-solid fa-floppy-disk"></i>
                                            Enregistrer
                                        </button>
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </section>
</div>

@endsection


@section('script')
    <script>
        $(document).ready(function (){
            selectDefaultDriver("@if(@old('type_vente_id')) {{@old('type_vente_id')}} @elseif($vente) {{$vente->type_vente_id}} @endif")
            filleulTypeCheck();
            $("#saveVente").submit(function (){
                $('#action').attr('hidden','hidden');
                $('#loaderSave').removeAttr('hidden');
            })
        })
    </script>
<script> 

    function selectDefaultDriver(old=null){
        if($('#client').val() || $('#commande_client_id').val()) {
            $('#champ').attr('hidden','hidden');
            $('#champ1').attr('hidden','hidden');
            $('#loader').removeAttr('hidden')
            $('#loader1').removeAttr('hidden')
            $('#typecommande option').removeAttr('selected');
            $('#typecommande').empty();
            axios.get("{{env('APP_BASE_URL')}}commandeclients/typecommande/" + $('#client').val()+'/'+$('#commande_client_id').val()).then((response) => {
                var typecommandes = response.data;
                $('#typecommande').append("<option selected disabled>Choisissez le type commande</option>");
                for (var i = 0; i < typecommandes.length; i++) {
                    var val = typecommandes[i].id;
                    var text = typecommandes[i].libelle;
                    if(old == val){
                        $('#typecommande').append("<option value="+ val +" selected>" + text + "</option>");
                    }
                    else{
                        $('#typecommande').append("<option value="+ val +">" + text + "</option>");
                    }
                }

                typeSelected();
                $('.select2').select2();
                $('#loader').attr('hidden', 'hidden');
                $('#loader1').attr('hidden', 'hidden');
                $('#champ').removeAttr('hidden')
                $('#champ1').removeAttr('hidden')
            }).catch(()=>{
                $('#loader').attr('hidden', 'hidden');
                $('#loader1').attr('hidden', 'hidden');
                $('#champ1').removeAttr('hidden')
                $('#champ').removeAttr('hidden')
            })

           
        }
        else {
            $('#chauffeur_0').attr('selected', 'true');
            $('.select2').select2()
        }

    }
    function cmdeSelected($client){

    }
    function typeSelected(){
        if($('#typecommande').val() == 2){
            $('#echeance').removeAttr('disabled');
            $('#r-echeance').removeAttr('hidden');
        }
        else {
            $('#echeance').attr('disabled',true);
            $('#r-echeance').attr('hidden',true);
        }
    }
    function filleulTypeCheck(){
        let fi = $("#ctl_payeur");
        let det = $("#detailFilleul");
        let np = $("#nomPrenom");
        let tel = $("#telephone");
        if(fi.val()){
            if(fi.val() == 0){
                det.removeAttr('hidden');
                np.attr('required',true);
                tel.attr('required',true);
            }
            else {
                det.attr('hidden',true)
                np.removeAttr('required');
                tel.removeAttr('required');
            }
        }
    }
</script>

    <script>
        function editClient(oldClient){
            if($('#client_id').val() != oldClient){
                $('#confirmation_msg').removeAttr('hidden');
                //$('#confirmation').attr('required','required');
            }
            else {
                $('#confirmation_msg').attr('hidden','hidden');
                $('#confirmation').removeAttr('required');
            }
        }
    </script>

    <script>
        function submitStatuts()
        {
            $('#statutsForm').submit();
        }

    </script>
@endsection