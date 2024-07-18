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
                        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">clients</a></li>
                        <li class="breadcrumb-item active">Nouveau client</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Ajouter un nouveau client</h3>

                        </div>

                        <form method="POST" action="{{ route('newclient.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">

                                            <label for="file">Logo</label>
                                            <input type="file" name="logo" class="form-control form-control-sm @error('logo') is-invalid @enderror" value="{{ old('logo') }}" onchange="previewFile(this)" />
                                            <img id="previewImg" style="max-width: 130px; margin-top:20px" ; />
                                            @error('logo')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Client type<span class="text-danger">*</span></label>
                                            <select name="type_client_id" id="" class="form-control form-control-sm" required>
                                                <option value="">Sélectionnez catégorie</option>
                                                @foreach ($typeclients as $typeclient)
                                                <option value="{{ $typeclient->id }}" {{ old('type_client_id') == $typeclient->id ? 'selected' : '' }}>
                                                    {{ $typeclient->libelle }}
                                                </option>
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
                                            <input type="text" id="sigle" class="form-control form-control-sm @error('sigle') is-invalid @enderror" name="sigle" style="text-transform: uppercase" value="{{ old('sigle') }}" autocomplete="off" autofocus>
                                            @error('sigle')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label> Nom / Raison Sociale<span class="text-danger">*</span></label>
                                            <input type="text" id="raisonsociale" class="form-control form-control-sm @error('raisonsociale') is-invalid @enderror" name="raisonsociale" style="text-transform: uppercase" value="{{ old('raisonsociale') }}" autocomplete="off" autofocus required>
                                            @error('raisonsociale')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Domaine</label>
                                            <input type="text" id="domaine" class="form-control form-control-sm @error('domaine') is-invalid @enderror" name="domaine" style="text-transform: uppercase" value="{{ old('domaine') }}" autocomplete="true" autofocus>
                                            @error('domaine')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>IFU</label>
                                            <input type="number" id="ifu" class="form-control form-control-sm @error('ifu') is-invalid @enderror" name="ifu" style="text-transform: uppercase" value="{{ old('ifu') }}" autocomplete="off" autofocus>
                                            @error('ifu')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>RCCM</label><span class="text-danger">*</span>
                                            <input type="text" id="rccm" class="form-control form-control-sm @error('rccm') is-invalid @enderror" name="rccm" style="text-transform: uppercase" value="{{ old('rccm') }}" autocomplete="off" autofocus>
                                            @error('rccm')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Numéro de compte comptable<span class="text-danger">*</span></label>
                                            <input type="text" id="numerocompte" class="form-control form-control-sm @error('numerocompte') is-invalid @enderror" name="numerocompte" style="text-transform: uppercase" value="{{ old('numerocompte') }}" autocomplete="off" autofocus required>
                                            @error('numerocompte')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" id="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" style="text-transform: uppercase" value="{{ old('email') }}" autocomplete="off" autofocus>
                                            @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Téléphone<span class="text-danger">*</span></label>
                                            <input type="text" id="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror" name="telephone" style="text-transform: uppercase" value="{{ old('telephone') }}" autocomplete="off" autofocus required>
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
                                            <select name="departement_id" id="" class="form-control form-control-sm" required>
                                                <option value="">Sélectionnez le département</option>
                                                @foreach ($departements as $departement)
                                                <option value="{{ $departement->id }}" {{ old('departement_id') == $departement->id ? 'selected' : '' }}>
                                                    {{ $departement->libelle }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('departement_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Adresse<span class="text-danger">*</span></label>
                                            <input type="text" id="adresse" class="form-control form-control-sm @error('adresse') is-invalid @enderror" name="adresse" style="text-transform: uppercase" value="{{ old('adresse') }}" autocomplete="off" autofocus required>
                                            @error('adresse')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Client Payeur<span class="text-danger"></span></label>
                                            <select name="filleulFisc" id="" class="form-control form-control-sm select2">
                                                <option value="">Sélectionnez le Client payeur</option>
                                                @foreach ($filleulFisc as $ff)
                                                <option value="{{ $ff->id }}" {{ old('filleulFisc') == $ff->id ? 'selected' : '' }}>
                                                    {{ $ff->raisonSociale }} | <span class="text-success">{{ $ff->ifu }}</span>
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('filleulFisc')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bordereau de reçu <span class="text-danger">*</span></label>
                                            <input type="file" name="bordereau_receit" class="form-control" id="" required>
                                            @error('bordereau_receit')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-6" hidden="">
                                        <div class="row">
                                            <div class="col-2" hidden="">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Agent</label>
                                                    <input id="statut" type="checkbox" name="statut" style="width: 20px; height: 20px" />
                                                </div>
                                            </div>

                                            <div id="agent" style="display:none" class="col-10">
                                                <div class="form-group">
                                                    <label></label>
                                                    <select name="agent_id" class="form-control form-control-sm select2" id="" class="form-control form-control-sm">
                                                        <option value="">Sélectionnez l'agent</option>
                                                        @foreach ($agents as $agent)
                                                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                                            {{ $agent->nom }} {{ $agent->prenom }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('type_client_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                </div>

                                <span id="agent1" style="display:none">

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Date Début</label>
                                                <input type="date" id="datedebut" class="form-control form-control-sm @error('datedebut') is-invalid @enderror" name="datedebut" style="text-transform: uppercase" value="{{ old('datedebut') }}" autocomplete="off" autofocus>
                                                @error('datedebut')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Date Fin</label>
                                                <input type="date" id="datefin" class="form-control form-control-sm @error('datefin') is-invalid @enderror" name="datefin" style="text-transform: uppercase" value="{{ old('datefin') }}" autocomplete="off" autofocus>
                                                @error('datefin')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </span>

                            </div>
                            <div class="card-footer">
                                <div class="row justify-content-center">
                                    <div class="col-sm-3">
                                        <a href="{{ route('newclient.index') }}" class="btn btn-sm btn-block btn-secondary">
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
    function submitStatuts() {
        $('#statutsForm').submit();
    }
</script>
<script>
    let check = document.getElementById('statut');
    let agent = document.getElementById('agent');
    let agent1 = document.getElementById('agent1');
    check.addEventListener("change", function() {
        if (agent.style.display === "none") {
            agent.style.display = "block";
            agent1.style.display = "block";
        } else {
            agent.style.display = "none";
            agent1.style.display = "none";
        }
    });
</script>
@endsection