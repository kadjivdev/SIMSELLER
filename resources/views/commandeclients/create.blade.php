@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>COMMANDE CLIENTS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('commandeclients.index') }}">Commande clients</a></li>
                            <li class="breadcrumb-item active">Nouveau commande client</li>
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
                                <h3 class="card-title">Création d'un nouveau commande client</h3>
                            </div>
                            <form method="post" action="{{$commandeclient?route('commandeclients.store',['commandeclient'=>$commandeclient->id]):route('commandeclients.store')}}" >
                                @csrf
                                <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Code<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm text-center" name="code"  value="@if($commandeclient){{old('code')?old('code'):$commandeclient->code}}@else{{$commandeclient?$commandeclient->code:'A GENERER'}}@endif" style="text-transform: uppercase" autofocus readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control form-control-sm text-center" name="dateBon" value="@if($commandeclient){{old('dateBon')?old('dateBon'):$commandeclient->dateBon}}@else{{old('dateBon')?old('dateBon'):date('Y-m-d')}}@endif"  autofocus required>
                                                    @error('dateBon')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Clients<span class="text-danger">*</span></label>
                                                    <select onchange="selectDefaultDriver()" id="client" class="form-control form-control-sm select2" id="client_id" name="client_id" style="width: 100%;" @if($commandeclient)onchange="editClient('{{$commandeclient->client_id}}')"@endif>
                                                        <option class="text-center" selected disabled>** choisissez un client **</option>
                                                        @foreach($clients as $client)
                                                            <option value="{{$client->id}}"@if ($commandeclient) @if (old('client_id')) {{old('client_id')==$client->id?'selected':''}}  @else {{$commandeclient->client_id==$client->id?'selected':''}}  @endif @else {{old('client_id')==$client->id?'selected':''}}  @endif>
                                                                {{ $client->raisonSociale }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('client_id')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3" hidden>
                                                <div class="form-group">
                                                    <div hidden id="loader" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px" >
                                                        <i class="fa fa-spin fa-spinner"></i> Recherche Type ...
                                                    </div>
                                                    <div id="champ">
                                                        <label>Type<span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm select2" name="type_commande_id" style="width: 100%;" id="typecommande">
                                                            <option class="text-center" selected disabled id="type_commande_0">** choisissez un type commande **</option>
                                                        </select>
                                                        @error('zone_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Zones<span class="text-danger">*</span></label>
                                                    <select class="select2 form-control form-control-sm @error('zone_id') is-invalid @enderror" name="zone_id" style="width: 100%;">
                                                        <option class="text-center" selected disabled>** choisissez une zone **</option>
                                                        @foreach($zones as $zone)
                                                            <option value="{{$zone->id}}"  @if ($commandeclient) @if (old('zone_id')) {{old('zone_id')==$zone->id?'selected':''}}  @else {{$commandeclient->zone_id==$zone->id?'selected':''}}  @endif @else {{old('zone_id')==$zone->id?'selected':''}}  @endif>{{ $zone->libelle }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('zone_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="confirmation_msg" hidden>
                                            <div class="col-sm-12 alert alert-warning">
                                                <p>Vous êtes sur le point de modifier le client. Cette action supprimera les produits commandés par client. </p>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="confimation" id="confirmation" style="margin-top: -0.1em; width: 20px; height: 20px;">
                                                    <label class="form-check-label pl-3" for="flexSwitchCheckDefault" >Confirmer vous cette action ?</label>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                <div class="card-footer">
                                    <div class="row text-center">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3">
                                            <a href="@if($redirectto=='detailbc') {{route('commandeclients.edit', ['commandeclient' => $commandeclient->id])}} @else {{ route('commandeclients.index') }}@endif" class="btn btn-sm btn-secondary  btn-block">
                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-3">
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
        @if($commandeclient || @old('type_commande_id'))
        $(document).ready(function (){
            selectDefaultDriver('{{@old('type_commande_id')?@old('type_commande_id'):$commandeclient->type_commande_id}}')
        })
        @endif
    </script>
    <script>
        function selectDefaultDriver(old=null){

            if($('#client').val()) {
                $('#champ').attr('hidden','hidden');
                $('#loader').removeAttr('hidden')
                $('#typecommande option').removeAttr('selected');
                $('#typecommande').empty();
                axios.get('{{env('APP_BASE_URL')}}commandeclients/typecommande/' + $('#client').val()+'/'+$('#commande_client_id').val()).then((response) => {
                    var typecommandes = response.data;
                    $('#typecommande').append("<option selected disabled>Choisissez le type commane</option>");
                    for (var i = 0; i < typecommandes.length; i++) {
                        var val = typecommandes[i].id;
                        var text = typecommandes[i].libelle;
                        if(1 == val)
                            $('#typecommande').append("<option value="+ val +" selected>" + text + "</option>");
                        else
                            $('#typecommande').append("<option value="+ val +">" + text + "</option>");
                    }
                    $('.select2').select2();
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                }).catch(()=>{
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                })
            }
            else {
                $('#chauffeur_0').attr('selected', 'true');
                $('.select2').select2()
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


@endsection
