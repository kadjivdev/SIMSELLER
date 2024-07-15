@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>INTERLOCUTEURS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fournisseurs.show', ['id'=>$interlocuteurs->fournisseur_id]) }}">Fournisseurs</a></li>
                            <li class="breadcrumb-item active">Modification interlocuteurs</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification de l'interlocuterus</h3>
                            </div>
                            <form method="POST" action="{{ route('interlocuteurs.update') }}">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" name="id" value="{{ $interlocuteurs->id }}" />
                                    <input type="hidden" name="fournisseur_id" value="{{ $interlocuteurs->fournisseur_id }}" />
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Nom<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm  @error('nom') is-invalid @enderror" name="nom"  value="{{ @old('nom')? @old('nom'):$interlocuteurs->nom }}"  autocomplete="nom" style="text-transform: uppercase" autofocus required>
                                                @error('telephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>Prénom<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm  @error('prenom') is-invalid @enderror" name="prenom" style="text-transform: capitalize"  value="{{ @old('prenom')? @old('prenom'):$interlocuteurs->prenom }}"  autocomplete="prenom" autofocus required>
                                                @error('telephone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
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
                                                    <input type="text" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror"  value="{{ @old('telephone')? @old('telephone'):$interlocuteurs->telephone }}" required autocomplete="telephone">
                                                    @error('telephone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <label>E-mail<span class="text-danger">*</span></label>
                                                <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ @old('email')? @old('email'):$interlocuteurs->email }}" required autocomplete="email">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <a href="{{ route('fournisseurs.show', ['id'=>$interlocuteurs->fournisseur_id]) }}" class="btn btn-sm btn-secondary">
                                                {{ __('Retour') }}
                                            </a>

                                            <button type="submit" class="btn btn-sm btn-success">
                                                {{ __('Modifier') }}
                                            </button>
                                        </div>
                                        <div class="col-sm-4"></div>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;
