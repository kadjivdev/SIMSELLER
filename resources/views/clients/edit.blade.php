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
                        <li class="breadcrumb-item active">Modification client</li>
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
                                <h3 class="card-title">Modification client</h3>
                            </div>
                            <form method="POST" action="{{ route('clients.update', ['typeclient'=>$client->typeclient->id, 'client'=>$client->id]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @if ($typeclient->libelle == env('TYPE_CLIENT_P'))
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <div class="form-group">
                                                    <img id="previewImg" @if ($client->photo)
                                                        src="{{ asset('images')}}/{{ $client->photo }}"
                                                    @else
                                                        src="{{asset('dist/img/default.jpg')}}"
                                                    @endif
                                                    alt="photo client" style="max-width: 200px;"; />
                                                </div>
                                            </div>
                                            @if($client->photo)
                                                <div class="col-sm-12 text-center">
                                                    <div class="form-group">
                                                        <label for="statut" style="display: block">Supprimer l'image</label>
                                                        <input id="statut" type="checkbox"  name="remoov" style="width: 20px; height: 20px"  />
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-sm-12 text-center">
                                                <div class="form-group">
                                                    <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                    @error('photo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>

                                    @elseif ($typeclient->libelle == env('TYPE_CLIENT_S'))
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <div class="form-group">
                                                    <img id="previewImg" @if ($client->logo)
                                                        src="{{ asset('images')}}/{{ $client->logo }}"
                                                    @else
                                                        src="{{asset('dist/img/logo.png')}}"
                                                    @endif
                                                    alt="logo client" style="max-width: 200px;"; />
                                                </div>
                                            </div>
                                            @if($client->logo)
                                                <div class="col-sm-12 text-center">
                                                    <div class="form-group">
                                                        <label for="statut" style="display: block">Supprimer l'image</label>
                                                        <input id="statut" type="checkbox"  name="remoov" style="width: 20px; height: 20px"  />
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input type="file" name="logo" class="form-control form-control-sm @error('logo') is-invalid @enderror" value="{{ old('logo') }}" onchange="previewFile(this)" />
                                                    @error('logo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($typeclient->libelle == env('TYPE_CLIENT_P'))
                                        <input type="hidden" name="statuts" value="{{$req}}" />
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    <label>Code<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm" name="code"  value="{{ old('code')?old('code'):$client->code }}" style="text-transform: uppercase" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Civilité<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="civilite" style="text-transform: capitalize; width:100%;">
                                                        <option Value="{{ $client->civilite }}" selected>{{ $client->civilite }}</option>
                                                        <option value="Madame" @if (old('civilite') == "Madame") {{ 'selected' }} @endif>Madame</option>
                                                        <option value="Madémoiselle" @if (old('civilite') == "Madémoiselle") {{ 'selected' }} @endif>Madémoiselle</option>
                                                        <option value="Monsieur" @if (old('civilite') == "Monsieur") {{ 'selected' }} @endif>Monsieur</option>
                                                    </select>
                                                    @error('civilite')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Nom<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm  @error('nom') is-invalid @enderror" name="nom"  value="{{ old('nom')?old('nom'):$client->nom }}"  autocomplete="nom" style="text-transform: uppercase" autofocus>
                                                </div>
                                                @error('nom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Prénom<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm  @error('prenom') is-invalid @enderror" name="prenom" style="text-transform: capitalize"  value="{{ old('prenom')?old('prenom'):$client->prenom }}"  autocomplete="prenom" autofocus>
                                                    @error('prenom')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Domaine d'activité<span class="text-danger"></span></label>
                                                    <input type="text" class="form-control form-control-sm" name="domaine" style="text-transform: uppercase"  value="{{ old('domaine')?old('domaine'):$client->domaine }}"  autocomplete="off" autofocus>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif ($typeclient->libelle == env('TYPE_CLIENT_S'))
                                    <input type="hidden" name="statuts" value="{{$req}}" />
                                        <div class="row">
                                            <div class="col-sm-1">
                                                <div class="form-group">
                                                    <label>Code<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm" name="code"  value="{{ old('code')?old('code'):$client->code }}" style="text-transform: uppercase" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Sigle<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm @error('sigle') is-invalid @enderror" name="sigle"  value="{{ old('sigle')?old('sigle'):$client->sigle }}"  autocomplete="sigle" style="text-transform: uppercase" autofocus>
                                                    @error('sigle')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label>Raison Sociale<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm" name="raisonSociale" style="text-transform: uppercase"  value="{{ old('raisonSociale')?old('raisonSociale'):$client->raisonSociale }}"  autocomplete="raisonSocial" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Domaine d'activité<span class="text-danger"></span></label>
                                                    <input type="text" class="form-control form-control-sm" name="domaine" style="text-transform: uppercase"  value="{{ old('domaine')?old('domaine'):$client->domaine }}"  autocomplete="domaine" autofocus>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Téléphone<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror"  value="{{ old('telephone')?old('telephone'):$client->telephone }}" required autocomplete="telephone">
                                                </div>
                                            </div>
                                            @error('telephone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>E-mail<span class="text-danger"></span></label>
                                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email')?old('email'):$client->email }}"  autocomplete="off">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- checkbox -->
                                            <div class="form-group">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="statutCredit" value="1" {{ old('statutCredit') || $client->statutCredit == '1' ? 'checked' : '' }}>
                                                <label>Eligibilié Crédit</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Adresse<span class="text-danger"></span></label>
                                                <textarea class="form-control form-control-sm  @error('adresse') is-invalid @enderror" name="adresse" id="exampleFormControlTextarea1" style="text-transform: capitalize"  autocomplete="off" autofocus rows="3">{{ old('adresse')?old('adresse'):$client->adresse }}</textarea>
                                                @error('adresse')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-3">
                                            <a href="{{ route('clients.index') }}" class="btn btn-sm btn-block btn-secondary">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>

                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-sm btn-warning btn-block ">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                {{ __('Modifier') }}
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
