@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Catégorie Fournisseur</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categoriefournisseurs.index') }}">Catégorie Fournisseur</a></li>
                            <li class="breadcrumb-item active">Modification Catégorie Fournisseur</li>
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
                                <h3 class="card-title">Modification de la Catégorie <b class="text-warning">{{$categoriefournisseurs->libelle}}</b> </h3>
                            </div>
                            <form method="POST" action="{{ route('categoriefournisseurs.update') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <input type="hidden" name="id" value="{{ $categoriefournisseurs->id }}" />
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" id="libelle" class="form-control form-control-sm @error('libelle') is-invalid @enderror" name="libelle" style="text-transform: uppercase"  value="{{ @old('libelle')? @old('libelle'):$categoriefournisseurs->libelle }}"  autocomplete="libelle" autofocus required>
                                                @error('libelle')
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
                                            <a href="{{ route('categoriefournisseurs.index') }}" class="btn btn-sm btn-secondary">
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
