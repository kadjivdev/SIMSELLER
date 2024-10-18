@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">REGLEMENT VENTE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reglements.index', ['vente'=>$vente->id]) }}">Listes des reglements</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>
            @include('reglements.entete')
    </section>
    <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau Règlement </h3>
                            </div>
                            <form method="POST" id="reglement" action="{{ route('reglements.store', ['vente'=>$vente->id]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label>Source règlement.<span class="text-danger">*</span></label>
                                                <select onchange="selectReglement()" name="srcReg" class="form-select form-control" id="srcReg">
                                                    <option @if(old('srcReg') == 'indirect') selected @endif value="indirect" >Règlément sur compte Bancaire </option>
                                                    <option @if(old('srcReg') == 'direct') selected @endif value="direct">Règlément direct sur Compte Client</option>
                                                </select>
                                                @error('srcReg')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-8 text-center" id="solde" hidden>
                                            <span class="h2">2000</span>
                                        </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Référence<span class="text-danger">*</span></label>
                                                    <input id="reference" type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase"  value="{{ old('reference') }}"  autocomplete="off" autofocus>
                                                    @error('reference')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Date<span class="text-danger">*</span></label>
                                                    <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date"  value="{{ old('date')?old('date'):date('Y-m-d') }}"  autocomplete="off" autofocus required>
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
                                                <select id="compte_id" class="select2 form-control form-control-sm @error('compte_id') is-invalid @enderror" name="compte_id" style="width: 100%;">
                                                    <option value="{{ NULL }}" selected>** choisir un compte **</option>
                                                    @foreach($comptes as $compte)
                                                        <option value="{{ $compte->id }}" {{ old('compte_id') == $compte->id ? 'selected' : '' }}>{{ $compte->banque->sigle }} | <strong>{{ $compte->intitule }}</strong> :  {{ $compte->numero }}</option>
                                                    @endforeach
                                                </select>
                                                @error('compte_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select id="type" required class="select2 form-control form-control-sm @error('typedetailrecu_id') is-invalid @enderror" name="typedetailrecu_id" style="width: 100%;">
                                                    @foreach($typedetailrecus as $typedetailrecu)
                                                        <option value="{{ $typedetailrecu->id }}" {{ old('typedetailrecu_id') == $typedetailrecu->id ? 'selected' : '' }}>{{ $typedetailrecu->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('typedetailrecu_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Montant<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase"  value="{{ old('montant')?:($vente->montant-$vente->remise) - $vente->reglements->sum('montant') }}"  autocomplete="off" min="1" autofocus required>
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
                                                <input id="document" type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" value="{{ old('document') }}" required >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div id="spin" hidden>
                                        <i class="fa fa-spin fa-spinner fa-2x" ></i>
                                    </div>
                                    <div class="row justify-content-center" id="action">
                                        <div class="col-sm-4">
                                            <a href="{{ route('reglements.index', ['vente'=>$vente->id]) }}" class="btn btn-sm btn-secondary btn-block">
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
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </section>
</div>

@endsection
@section('script')
    <script>
        $('document').ready(function (){
                            
                $('#reference').attr('disabled',true);
                $('#compte_id').attr('disabled',true);
                $('#document').attr('disabled',false);
                $('#type').attr('disabled',true);
                
            selectReglement();
        })
        function selectReglement(){
            if($('#srcReg').val() == "indirect"){
                $('#reference').removeAttr('disabled');
                $('#compte_id').removeAttr('disabled');
                $('#document').removeAttr('disabled');
                $('#type').removeAttr('disabled');
                //$('#confirmation').attr('required','required');
            }
            else {
                $('#reference').attr('disabled',true);
                $('#compte_id').attr('disabled',true);
                $('#document').attr('disabled',true);
                $('#type').attr('disabled',true);
            }
        }
        $('#reglement').submit(function(){
            $('#action').attr('hidden','hidden');
            $('#spin').removeAttr('hidden');
        })
        $('#reglement').submit(function(){
            $('#action').attr('hidden','hidden');
            $('#chargement').removeAttr('hidden');
        })
    </script>
@endsection
