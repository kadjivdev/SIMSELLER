@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">BON DE COMMANDE N° {{ $recu->boncommande->code }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('detailrecus.index', ['recu'=>$recu->id]) }}">Listes des details reçu</a></li>
                        <li class="breadcrumb-item active">Modification</li>
                    </ol>
                </div>
            </div>
            @include('detailrecus.entete')
    </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification détail reçu</h3>
                            </div>
                            <form method="POST" action="{{ route('detailrecus.update', ['recu'=>$recu->id, 'detailrecu'=>$detailrecu]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="code" style="text-transform: uppercase"  value="{{ @old('code')?@old('code'):$detailrecu->code }}"  autocomplete="off" autofocus readonly required>
                                                @error('code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Date<span class="text-danger">*</span></label>
                                                <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date"  value="{{ @old('date')?@old('date'):$detailrecu->date }}"  autocomplete="off" autofocus required>
                                                @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Montant<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase"  value="{{ @old('montant')?@old('montant'):$detailrecu->montant }}"  autocomplete="off" min="1" autofocus required>
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
                                                    @if ($detailrecu->compte == NULL)
                                                        <option value="{{ NULL }}" selected>PAS DE COMPTE EN BANQUE IMPLIQUE</option>
                                                    @else
                                                        <option value="{{$detailrecu->compte->id}}" selected>{{ $detailrecu->compte->banque->sigle }} {{ $detailrecu->compte->numero }}</option>
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
                                                    <option value="{{ $detailrecu->typedetailrecu->id }}" selected>{{ $detailrecu->typedetailrecu->libelle }}</option>
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
                                                <input type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase"  value="{{ @old('code')?@old('code'):$detailrecu->reference }}"  autocomplete="reference" autofocus>
                                                @error('reference')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="file">Document</label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" value="{{ @old('document')?@old('document'):$detailrecu->document }}" >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if($detailrecu->document)
                                        <div class="row mb-2">
                                            <div class="col-6 text-center">
                                                <h4><a href="{{ asset('storage/'.$detailrecu->document) }}" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf"></i> Afficher le document</a></h4>
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
                                            <a href="{{ route('detailrecus.index', ['recu'=>$recu->id]) }}" class="btn btn-sm btn-secondary btn-block">
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
