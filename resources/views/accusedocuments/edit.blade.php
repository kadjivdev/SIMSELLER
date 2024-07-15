
@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">ACCUSE DOCUMENTS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('accusedocuments.index', ['boncommandes'=>$boncommandes->id]) }}">Accuse documents</a></li>
                        <li class="breadcrumb-item active">Modification</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-12">
            
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="card d-flex flex-fill">
                                    <div class="card-body">
                                        @if(session('messagebc'))
                                            <div class="alert alert-success alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                                {{session('messagebc')}}
                                            </div>
                                        @endif
                                        <h1 class="pb-3">Bon de commande N° {{ $boncommandes->code }}</h1>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-solid fa-calendar"></i></span> Date :  {{ date_format(date_create($boncommandes->dateBon), 'd/m/Y') }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-regular fa-money-bill-1"></i></span> Montant:  {{ number_format($boncommandes->montant,2,","," ") }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut:  {{ $boncommandes->statut }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="nav-icon fas fa-solid fa-cart-flatbed"></i></span> Type commande:  {{ $boncommandes->typecommande->libelle }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-user-tie"></i></span> Utilisateur:  {{ $boncommandes->users }}</li>
                                                        </div>
                                                    </div>
                                                </ul>
                                            </div>
                                            <div class="col-sm-6">
                                                <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-regular fa-closed-captioning"></i></span> Sigle :  {{ $boncommandes->fournisseur->sigle }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-solid fa-book-open"></i></span> Raison Sociale :  {{ $boncommandes->fournisseur->raisonSociale }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone :  {{ $boncommandes->fournisseur->telephone }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-envelope-circle-check"></i></span> E-mail :  {{ $boncommandes->fournisseur->email }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-location-dot"></i></span> Adresse :  {{ $boncommandes->fournisseur->adresse }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-industry"></i></span> Catégorie :  {{ $boncommandes->fournisseur->categoriefournisseur->libelle }}</li>
                                                        </div>
                                                    </div>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            
    </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification accusé de document</h3>
                            </div>
                            <form method="POST" action="{{ route('accusedocuments.update', ['boncommande'=>$boncommandes->id, 'accusedocument'=>$accusedocument]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Code<span class="text-danger"></span></label>
                                                <input type="text" class="form-control form-control-sm" name="code" style="text-transform: uppercase"  value="{{ @old('code')?@old('code'):$accusedocument->code }}" readonly  autocomplete="off" autofocus required>
                                                @error('code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--<div class="col-sm-9">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="libelle" style="text-transform: uppercase"  value=""  autocomplete="libelle" autofocus required>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Référence<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase"  value="{{ @old('reference')?@old('reference'):$accusedocument->reference }}"  autocomplete="off" autofocus>
                                                @error('reference')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Date<span class="text-danger">*</span></label>
                                                <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date"  value="{{ @old('date')?@old('date'):$accusedocument->date }}"  autocomplete="off" autofocus required>
                                                @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Montant<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase"  value="{{ @old('montant')?@old('montant'):$accusedocument->montant }}"  autocomplete="off" min="1" autofocus required>
                                                @error('montant')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('typedocument') is-invalid @enderror" name="typedocument" style="width: 100%;">
                                                    <option value="{{ $accusedocument->typedocument->id }}" selected>{{ $accusedocument->typedocument->libelle }}</option>
                                                    @foreach($typedocuments as $typedocument)
                                                        <option value="{{ $typedocument->id }}" {{ old('typedocument') == $typedocument->id ? 'selected' : '' }}>{{ $typedocument->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('typedocument')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="file">Document<span class="text-danger"></span></label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" data-mask value="{{ @old('document')?@old('document'):$accusedocument->document }}" >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Observation</label>
                                                <textarea class="form-control form-control-sm  @error('observation') is-invalid @enderror" name="observation" id="exampleFormControlTextarea1" style="text-transform: initial"  autocomplete="observation" autofocus rows="3">{{ @old('document')?@old('document'):$accusedocument->observation }}</textarea>
                                                @error('observation')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if($accusedocument->document)
                                        <div class="row mb-2">
                                            <div class="col-6 text-center">
                                                <h4><a href="{{ asset('storage/'.$accusedocument->document) }}" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf"></i> Afficher le document</a></h4>
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Supprimer le document</label>
                                                    <input id="statut" type="checkbox"  name="remoovdoc" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('accusedocuments.index', ['boncommandes'=>$boncommandes->id]) }}" class="btn btn-sm btn-secondary btn-block">
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
