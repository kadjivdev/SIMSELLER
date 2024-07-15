@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>CLIENTS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('newclient.index') }}"> Client</a></li>
                            <li class="breadcrumb-item active">Modification Client</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification du Client <b
                                        class="text-warning">{{ $client->raisonSociale }}</b></h3>
                            </div>
                            <form method="POST" action="{{ route('newclient.update', ['client' => $client->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">

                                                <label for="file">Logo</label>
                                                <input type="file" name="logo"
                                                    class="form-control form-control-sm @error('logo') is-invalid @enderror"
                                                    value="{{ old('logo') }}" onchange="previewFile(this)" />
                                                <img id="previewImg"
                                                    @if ($client->logo) src="{{ asset('images') }}/{{ $client->logo }}"@endif                                                        
                                                    style="max-width: 130px; margin-top:20px"; />
                                                @error('logo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                             @if ($client->logo) 
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <div class="form-group">
                                                            <label for="statut" style="display: block">Supprimer
                                                                l'image</label>
                                                            <input id="statut" type="checkbox" name="remoov"
                                                                style="width: 20px; height: 20px" />
                                                        </div>
                                                    </div>
                                                </div>
                                             @endif 
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Client type<span class="text-danger">*</span></label>
                                                <select name="type_client_id" id=""
                                                    class="form-control form-control-sm" required>
                                                    <option value="">Sélectionnez catégorie</option>
                                                    @foreach ($typeclients as $typeclient)
                                                        <option value="{{ $typeclient->id }}"
                                                            {{ old('type_client_id') == $typeclient->id ? 'selected' : '' }}>
                                                            {{ $typeclient->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('type_client_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Sigle</label>
                                                <input type="text" id="sigle"
                                                    class="form-control form-control-sm @error('sigle') is-invalid @enderror"
                                                    name="sigle" style="text-transform: uppercase"
                                                    value="{{ old('sigle') ? old('sigle') : $client->sigle }}"
                                                    autocomplete="off" autofocus required>
                                                @error('sigle')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Domaine<span class="text-danger">*</span></label>
                                                <input type="text" id="domaine"
                                                    class="form-control form-control-sm @error('domaine') is-invalid @enderror"
                                                    name="domaine"
                                                    value="{{ old('domaine') ? old('domaine') : $client->domaine }}"
                                                    autocomplete="true" autofocus required>
                                                @error('domaine')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Raison Sociale<span class="text-danger">*</span></label>
                                                <input type="text" id="raisonsociale"
                                                    class="form-control form-control-sm @error('raisonsociale') is-invalid @enderror"
                                                    name="raisonsociale" style="text-transform: uppercase"
                                                    value="{{ old('raisonsociale') ? old('raisonsociale') : $client->raisonSociale }}"
                                                    autocomplete="off" autofocus required>
                                                @error('raisonsociale')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>IFU</label>
                                                <input type="number" id="ifu"
                                                    class="form-control form-control-sm @error('ifu') is-invalid @enderror"
                                                    name="ifu" style="text-transform: uppercase"
                                                    value="{{ old('ifu') ? old('ifu') : $client->ifu }}" autocomplete="off"
                                                    autofocus >
                                                @error('ifu')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>RCCM<span class="text-danger">*</span></label>
                                                <input type="text" id="rccm"
                                                    class="form-control form-control-sm @error('rccm') is-invalid @enderror"
                                                    name="rccm" style="text-transform: uppercase"
                                                    value="{{ old('rccm') ? old('rccm') : $client->RCCM }}"
                                                    autocomplete="off" autofocus required>
                                                @error('rccm')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Numéro de compte<span class="text-danger">*</span></label>
                                                <input type="text" id="numerocompte"
                                                    class="form-control form-control-sm @error('numerocompte') is-invalid @enderror"
                                                    name="numerocompte" style="text-transform: uppercase"
                                                    value="{{ old('numerocompte') ? old('numerocompte') : $client->numerocompte }}"
                                                    autocomplete="off" autofocus required>
                                                @error('telephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Email<span class="text-danger">*</span></label>
                                                <input type="email" id="email"
                                                    class="form-control form-control-sm @error('email') is-invalid @enderror"
                                                    name="email" style="text-transform: uppercase"
                                                    value="{{ old('email') ? old('email') : $client->email }}"
                                                    autocomplete="off" autofocus required>
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Téléphone<span class="text-danger">*</span></label>
                                                <input type="text" id="telephone"
                                                    class="form-control form-control-sm @error('telephone') is-invalid @enderror"
                                                    name="telephone" style="text-transform: uppercase"
                                                    value="{{ old('telephone') ? old('telephone') : $client->telephone }}"
                                                    autocomplete="off" autofocus required>
                                                @error('telephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Département<span class="text-danger">*</span></label>
                                                <select name="departement_id" id=""
                                                    class="form-control form-control-sm" required>
                                                    <option value="">Sélectionnez le département</option>
                                                    @foreach ($departements as $departement)
                                                        <option value="{{ $departement->id }}"
                                                            {{ old('departement_id') == $departement->id ? 'selected' : '' }}>
                                                            {{ $departement->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('type_client_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Adresse<span class="text-danger">*</span></label>
                                                <input type="text" id="adresse"
                                                    class="form-control form-control-sm @error('adresse') is-invalid @enderror"
                                                    name="adresse" style="text-transform: uppercase"
                                                    value="{{ old('adresse') ? old('adresse') : $client->adresse }}"
                                                    autocomplete="off" autofocus required>
                                                @error('adresse')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        @if($client->ifu > 0)
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Filleuil Fiscal<span class="text-danger">*</span></label>
                                                    <select name="filleulFisc[]" id=""
                                                        class="form-control form-control-sm select2" multiple="multiple"
                                                        with-selected="{{ $client->filleulFisc }}">
                                                        <option value="">Sélectionnez le Filleuil Fiscal</option>
                                                        @foreach ($filleulFisc as $ff)
                                                            <option value="{{ $ff->id }}"> {{ $ff->raisonSociale }} </option>
                                                        @endforeach
                                                    </select>
                                                    @error('filleulFisc')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-3">
                                            <a href="{{ route('newclient.index') }}"
                                                class="btn btn-sm btn-block btn-secondary">
                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>

                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-sm btn-success btn-block ">
                                                {{ __('Ajouter') }}
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script>
        
        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        })

    </script>
@endsection
