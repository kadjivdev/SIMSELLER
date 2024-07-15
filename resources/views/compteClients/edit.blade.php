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
                        <li class="breadcrumb-item active">Modification</li>
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
                                <h3 class="card-title">Modification de règlement</h3>
                            </div>
                            <form method="POST" action="{{ route('reglements.update', ['vente'=>$vente->id, 'reglement'=>$reglement->id]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="code" style="text-transform: uppercase"  value="{{ @old('code')?@old('code'):$reglement->code }}"  autocomplete="off" autofocus readonly required>
                                                @error('code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Date<span class="text-danger">*</span></label>
                                                <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date"  value="{{ @old('date')?@old('date'):$reglement->date }}"  autocomplete="off" autofocus required>
                                                @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Montant<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase"  value="{{ @old('montant')?@old('montant'):$reglement->montant }}"  autocomplete="off" min="1" autofocus required>
                                                @error('montant')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Comptes</label>
                                                <select class="select2 form-control form-control-sm @error('compte_id') is-invalid @enderror" name="compte_id" style="width: 100%;">
                                                    @if ($reglement->compte == NULL)
                                                        <option value="{{ NULL }}" selected>PAS DE COMPTE EN BANQUE IMPLIQUE</option>
                                                    @else
                                                        <option value="{{$reglement->compte->id}}" selected>{{ $reglement->compte->banque->sigle }} {{ $reglement->compte->numero }}</option>
                                                    @endif
                                                    @foreach($comptes as $compte)
                                                        <option value="{{ $compte->id }}" {{ old('compte_id') == $compte->id ? 'selected' : '' }}>{{ $compte->banque->sigle }} {{ $compte->numero }}</option>
                                                    @endforeach
                                                </select>
                                                @error('compte_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('typedetailrecu_id') is-invalid @enderror" name="typedetailrecu_id" style="width: 100%;">
                                                    <option value="{{ $reglement->typeReglement->id }}" selected>{{ $reglement->typeReglement->libelle }}</option>
                                                    @foreach($typedetailrecus as $typedetailrecu)
                                                        <option value="{{ $typedetailrecu->id }}" {{ old('typedetailrecu_id') == $typedetailrecu->id ? 'selected' : '' }}>{{ $typedetailrecu->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('typedetailrecu_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Référence<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase"  value="{{ @old('code')?@old('code'):$reglement->reference }}"  autocomplete="reference" autofocus>
                                                @error('reference')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="file">Document</label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" value="{{ @old('document')?@old('document'):$reglement->document }}" >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if($reglement->document)
                                        <div class="row mb-2">
                                            <div class="col-6 text-center">
                                                <h4><a href="{{ asset('storage/'.$reglement->document) }}" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf"></i> Afficher le document</a></h4>
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Supprimer document d'assurance</label>
                                                    <input id="statut" type="checkbox"  name="remoovdoc" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('reglements.index', ['vente'=>$vente->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-warning btn-block">
                                                {{ __('Modifier') }}
                                                <i class="fa-solid fa-pen-to-square"></i>
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
