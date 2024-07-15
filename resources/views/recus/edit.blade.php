@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">RECUS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('recus.index', ['boncommandes'=>$boncommandes->id]) }}">Accuse documents</a></li>
                        <li class="breadcrumb-item active">Modification</li>
                    </ol>
                </div>
            </div>
            @include('recus.entete')
    </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau réçu</h3>
                            </div>
                            <form method="POST" action="{{ route('recus.update', ['boncommande'=>$boncommandes->id, 'recu'=>$recu]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Numéro<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="numero" style="text-transform: uppercase"  value="{{ @old('numero')?@old('numero'):$recu->numero }}"  autocomplete="off" autofocus readonly required>
                                                @error('numero')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="libelle" style="text-transform: uppercase"  value="{{ @old('libelle')?@old('libelle'):$recu->libelle }}"  autocomplete="off" autofocus required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Date<span class="text-danger">*</span></label>
                                                <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date"  value="{{ @old('date')?@old('date'):$recu->date }}"  autocomplete="off" autofocus required>
                                                @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Tonnage<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="tonnage" style="text-transform: uppercase"  value="{{ @old('tonnage')?@old('tonnage'):$recu->tonnage }}"  autocomplete="off" min="1" autofocus required>
                                                @error('tonnage')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Montant<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase"  value="{{ @old('montant')?@old('montant'):$recu->montant }}"  autocomplete="off" min="1" autofocus required>
                                                @error('montant')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Référence<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase"  value="{{ @old('reference')?@old('reference'):$recu->reference }}"  autocomplete="off" autofocus>
                                                @error('reference')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label for="file">Document</label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" data-mask value="{{ @old('document')?@old('document'):$recu->document }}" >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if($recu->document)
                                        <div class="row mb-2">
                                            <div class="col-6 text-center">
                                                <h4><a href="{{ asset('storage/'.$recu->document) }}" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf"></i> Afficher le document</a></h4>
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
                                            <a href="{{ route('recus.index', ['boncommandes'=>$boncommandes->id]) }}" class="btn btn-sm btn-secondary btn-block">
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
