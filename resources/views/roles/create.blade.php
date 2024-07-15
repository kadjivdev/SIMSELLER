@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>RÔLES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Listes rôles</a></li>
                            <li class="breadcrumb-item active">Nouveau</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3  class="card-title">Nouveau rôle</h3>
                                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary float-md-right">
                                    <i class="fa-solid fa-circle-left mr-1"></i>
                                    {{ __('Retour') }}
                                </a>
                            </div>
                                <form method="POST" action="{{ route('roles.store') }}">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Libellé</label>
                                                    <input type="text" class="form-control form-control-sm @error('libelle') is-invalid @enderror" style="text-transform: uppercase" name="libelle" autocomplete="libelle"  value="{{ old('libelle') }}" autofocus required>
                                                    @error('libelle')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-3">
                                                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                    <i class="fa-solid fa-circle-left"></i>
                                                    Retour
                                                </a>
                                            </div>
                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-sm btn-success btn-block">
                                                    Enregistrer
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
