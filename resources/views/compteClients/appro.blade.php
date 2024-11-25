@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">APPROVISIONNER COMPTE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('compteClient.show', ['client'=>$client->id]) }}">Listes des mouvements</a></li>
                        <li class="breadcrumb-item active">Approvisionner</li>
                    </ol>
                </div>
            </div>
    </section>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card d-flex flex-fill">
                    <div class="card-body">
                        <h1>
                            {{ $client->raisonSociale }}
                        </h1>
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <b>
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-person-dots-from-line"></i></span> Téléphone : {{ $client->telephone }}</li>
                                            </b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail: {{ $client->email }}</li>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Adresse: {{ $client->adresse }}</li>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut crédit: <span class="badge {{$client->credit == 0 ? 'badge-danger' : 'badge-success'}}">{{ $client->credit == 0 ? 'Non Eligible':'Eligible' }}</span></li>
                                        </div>
                                    </div>
                                </ul>
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
                <div class="col-md-12">
                    <div class="">
                        @if(session()->has("error"))
                        <div class="alert alert-danger">{{session()->get('error')}}</div>
                        @elseif(session()->has("message"))
                        <div class="alert alert-success">{{session()->get('message')}}</div>
                        @endif
                    </div>
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Nouvel Approvisionnement</h3>
                        </div>
                        <form method="POST" action="{{ route('compteClient.postAppro', ['client'=>$client->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Référence<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase" value="{{ old('reference') }}" autocomplete="off" autofocus>
                                            @error('reference')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Date<span class="text-danger">*</span></label>
                                            <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date" value="{{ old('date')?old('date'):date('Y-m-d') }}" autocomplete="off" autofocus required>
                                            @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Montant<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase" value="{{ old('montant') }}" autocomplete="off" min="1" autofocus required>
                                            @error('montant')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Comptes<span class="text-danger">*</span></label>
                                            <select class="select2 form-control form-control-sm @error('compte_id') is-invalid @enderror" name="compte_id" style="width: 100%;" required>
                                                <option value="{{ NULL }}" selected>** choisir un compte **</option>
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
                                            <select required class="select2 form-control form-control-sm @error('typedetailrecu_id') is-invalid @enderror" name="typedetailrecu_id" style="width: 100%;">
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
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="file">Libelle dépôt</label>
                                            <textarea name="libelleMvt" id="" class="form-control">{{old('libelleMvt')}}</textarea>
                                            @error('libelleMvt')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="file">Document <span class="text-danger">*</span></label>
                                            <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" value="{{ old('document') }}" required>
                                            @error('document')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    @if($client->debit_old)
                                    <div class="col-md-6">
                                        <div class="btn-group" role="group">
                                            <input name="for_dette" type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                                            <label class="btn btn-outline-primary" for="btncheck1">Pour rembourser la dette ancienne ?</label>
                                        </div>
                                    </div>
                                    @endif
                                    @if($client->credit_old)
                                    <div class="col-md-6">
                                        <div class="btn-group" role="group">
                                            <input name="old_solde" type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                                            <label class="btn btn-outline-primary" for="btncheck1">Pour reverser le solde ancien ?</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        <a href="{{ route('compteClient.show', ['client'=>$client->id]) }}" class="btn btn-sm btn-secondary btn-block">
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