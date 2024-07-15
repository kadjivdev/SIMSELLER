@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>BON DE COMMANDES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('boncommandes.index') }}">Bon de Commandes</a></li>
                            <li class="breadcrumb-item active">Nouveau bon de commande</li>
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
                                <h3 class="card-title">Création d'un nouveau bon de commande</h3>
                            </div>
                            <form method="post" action="{{$boncommandes?route('boncommandes.store',['boncommandes'=>$boncommandes->id]):route('boncommandes.store')}}" >
                                @csrf
                                <div class="card-body">

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Code<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm text-center" name="code"  value="@if($boncommandes){{old('code')?old('code'):$boncommandes->code}}@else{{$boncommandes?$boncommandes->code:'A GENERER'}}@endif" style="text-transform: uppercase" autofocus readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Date<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control form-control-sm text-center" name="dateBon" value="@if($boncommandes){{old('dateBon')?old('dateBon'):$boncommandes->dateBon}}@else{{old('dateBon')?old('dateBon'):date('Y-m-d')}}@endif"  autofocus required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Fournisseur<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm select2" id="fournisseur_id" name="fournisseur_id" style="width: 100%;" @if($boncommandes)onchange="editFournisseur('{{$boncommandes->fournisseur_id}}')"@endif>
                                                        <option class="text-center" value="" selected disabled>** choisir fournisseur **</option>
                                                        @foreach($fournisseurs as $fournisseur)
                                                            <option value="{{$fournisseur->id}}"@if ($boncommandes) @if (old('fournisseur_id')) {{old('fournisseur_id')==$fournisseur->id?'selected':''}}  @else {{$boncommandes->fournisseur_id==$fournisseur->id?'selected':''}}  @endif @else {{old('fournisseur_id')==$fournisseur->id?'selected':''}}  @endif>{{ $fournisseur->sigle }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Type<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm select2" name="type_commande_id" style="width: 100%;">
                                                        <option class="text-center" value="" selected disabled>** choisir type commande **</option>
                                                        @foreach($typecommandes as $typecommande)
                                                            <option value="{{$typecommande->id}}" @if ($boncommandes) @if (old('type_commande_id')) {{old('type_commande_id')==$typecommande->id?'selected':''}}  @else {{$boncommandes->type_commande_id==$typecommande->id?'selected':''}}  @endif @else {{old('type_commande_id')==$typecommande->id?'selected':''}}  @endif>{{ $typecommande->libelle }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="confirmation_msg" hidden>
                                            <div class="col-sm-12 alert alert-warning">
                                                <p>Vous êtes sur le point de modifier le fournisseur. Cette action réajustera les details du bon de commande. </p>
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
                                            <a href="@if($redirectto=='detailbc') {{route('boncommandes.edit', ['boncommande' => $boncommandes->id])}} @else {{ route('boncommandes.index') }}@endif" class="btn btn-sm btn-secondary  btn-block">
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
        function editFournisseur(oldFournisseur){
            if($('#fournisseur_id').val() != oldFournisseur){
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
