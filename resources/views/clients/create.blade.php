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
                                <div class="float-sm-right">
                                    <form id="statutsForm" action="" method="get">
                                        <div class="form-group form-group-xs">
                                            <select class="custom-select form-control" id="statuts" name="statuts" onchange="submitStatuts()">
                                                <option value="">Sélection type client</option>
                                                @foreach($typeclt as $type)
                                                    <option value="{{$type->id}}" {{ $req == $type->id ? 'selected':'' }}>{{$type->libelle}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @if($req)
                                <form method="POST" action="{{ route('clients.store', ['typeclient'=>$typeclient->id]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                @if ($typeclient->libelle == env('TYPE_CLIENT_P'))
                                                    <label for="file">Photo</label>
                                                    <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror" value="{{ old('photo') }}" onchange="previewFile(this)" />
                                                    <img id="previewImg" style="max-width: 130px; margin-top:20px"; />
                                                    @error('photo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                @elseif ($typeclient->libelle == env('TYPE_CLIENT_S'))
                                                    <label for="file">Logo</label>
                                                    <input type="file" name="logo" class="form-control form-control-sm @error('logo') is-invalid @enderror" value="{{ old('logo') }}" onchange="previewFile(this)" />
                                                    <img id="previewImg" style="max-width: 130px; margin-top:20px"; />
                                                    @error('logo')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if ($typeclient->libelle == env('TYPE_CLIENT_P'))
                                        <div class="row">
                                            <input type="hidden" name="statuts" value="{{$req}}" />
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Civilité<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm" name="civilite" style="text-transform: capitalize; width:100%;">
                                                        <option selected disabled>** choisissez **</option>
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
                                                    <input type="text" class="form-control form-control-sm  @error('nom') is-invalid @enderror" name="nom"  value="{{ old('nom') }}"  autocomplete="nom" style="text-transform: uppercase" autofocus>
                                                </div>
                                                @error('nom')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Prénom<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm  @error('prenom') is-invalid @enderror" name="prenom" style="text-transform: capitalize"  value="{{ old('prenom') }}"  autocomplete="prenom" autofocus>
                                                    @error('prenom')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Domaine d'activité<span class="text-danger"></span></label>
                                                    <input type="text" class="form-control form-control-sm" name="domaine" style="text-transform: uppercase"  value="{{ old('domaine') }}"  autocomplete="domaine" autofocus>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif ($typeclient->libelle == env('TYPE_CLIENT_S'))
                                    <input type="hidden" name="statuts" value="{{$req}}" />
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Sigle<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm @error('sigle') is-invalid @enderror" name="sigle"  value="{{ old('sigle') }}"  autocomplete="sigle" style="text-transform: uppercase" autofocus>
                                                    @error('sigle')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="form-group">
                                                    <label>Raison Sociale<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control form-control-sm" name="raisonSociale" style="text-transform: uppercase"  value="{{ old('raisonSociale') }}"  autocomplete="raisonSocial" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label>Domaine d'activité<span class="text-danger"></span></label>
                                                    <input type="text" class="form-control form-control-sm" name="domaine" style="text-transform: uppercase"  value="{{ old('domaine') }}"  autocomplete="domaine" autofocus>
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
                                                    <input type="number" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror"  value="{{ old('telephone') }}" required autocomplete="telephone">
                                                </div>
                                            </div>
                                            @error('telephone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>E-mail<span class="text-danger"></span></label>
                                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="off">
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
                                                <input class="form-check-input" type="checkbox" name="statutCredit" value="1" {{ old('statutCredit') == '1' ? 'checked' : '' }}>
                                                <label>Eligibilié Crédit</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Adresse<span class="text-danger"></span></label>
                                                <textarea class="form-control form-control-sm  @error('adresse') is-invalid @enderror" name="adresse" id="exampleFormControlTextarea1" style="text-transform: capitalize"  autocomplete="adresse" autofocus rows="3">{{ old('adresse') }}</textarea>
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
                            @else
                                <div class="alert alert-info m-3">
                                    <h3><i class="fa fa-info-circle"></i> Pour créer un client, veuillez sélectionner un type de client.</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('script')
<script>
    function submitStatuts()
    {
        $('#statutsForm').submit();
    }
</script>
@endsection
