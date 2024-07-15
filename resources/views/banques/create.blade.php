@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>BANQUES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('banques.index') }}">Banques</a></li>
                            <li class="breadcrumb-item active">Nouvelle banque</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Nouvelle banque</h3>
                            </div>
                            <form method="POST" action="{{ route('banques.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Sigle<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm @error('sigle') is-invalid @enderror" name="sigle"  value="{{ old('sigle') }}"  autocomplete="sigle" style="text-transform: uppercase" autofocus required>
                                                @error('sigle')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Raison Sociale<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="raisonSociale" style="text-transform: uppercase"  value="{{ old('raisonSociale') }}"  autocomplete="raisonSocial" autofocus required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Téléphone<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="number" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}"  autocomplete="telephone" required>
                                                    @error('telephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>E-mail<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                                                    </div>
                                                    <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" required>
                                                </div>
                                            </div>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Interlocuteur<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm @error('interlocuteur') is-invalid @enderror" name="interlocuteur"  value="{{ old('interlocuteur') }}"  autocomplete="interlocuteur" style="text-transform: uppercase" autofocus required >
                                                @error('interlocuteur')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Adresse<span class="text-danger">*</span></label>
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
                                        <div class="col-sm-4">
                                            <a href="{{ route('banques.index') }}" class="btn btn-sm btn-secondary btn-block">
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
