@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>TELEPHONES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fournisseurs.show', ['id'=>$telephones->fournisseur_id]) }}">Fournisseurs</a></li>
                            <li class="breadcrumb-item active">Modification téléphones</li>
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
                                <h3 class="card-title">Modification du téléphones</h3>
                            </div>
                            <form method="POST" action="{{ route('telephones.update') }}">
                                @csrf
                                <div class="card-body">
                                    <input type="hidden" name="id" value="{{ $telephones->id }}" />
                                    <input type="hidden" name="fournisseur_id" value="{{ $telephones->fournisseur_id }}" />
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>Numéro<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" name="numero" class="form-control form-control-sm @error('numero') is-invalid @enderror"  value="{{ @old('numero')? @old('numero'):$telephones->numero }}" required autocomplete="numero">
                                                    @error('numero')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" style="width: 100%;" name="type">
                                                    <option value="{{ $telephones->type }}" selected="selected">{{ $telephones->type }}</option>
                                                    <option value="Téléphonique">Téléphonique</option>
                                                    <option value="Whatsapp">Whatsapp</option>
                                                    <option value="Fax">Fax</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <a href="{{ route('fournisseurs.show', ['id'=>$telephones->fournisseur_id]) }}" class="btn btn-sm btn-secondary">
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
