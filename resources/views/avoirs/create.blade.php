@extends('layouts.app')

    @section('content')
        <div class="content-wrapper justify-content-center">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h1 class="pb-3">Utilisateur: {{ $user->name }}  </h1>
                                <div class="row">
                                    <div class="col-sm-6 col-md-12">
                                        <div class="card d-flex flex-fill">
                                            <div class="card-body">
                                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $user->email }}</li>
                                                        </div>
                                                    </div>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('avoirs.index', ['user'=>$user->id]) }}">Users Rôles</a></li>
                                <li class="breadcrumb-item active">Nouveau(x) Rôle(s)</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Nouveau(x) Rôle(s)</h3>
                                </div>
                                <form method="POST" action="{{ route('avoirs.store', ['user'=>$user->id]) }}">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group select2-success">
                                                    <label>Les rôles</label>
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <select class="form-control form-control-sm select2 @error('role_id') is-invalid @enderror" multiple="multiple" style="width: 100%;" name="role_id[]" data-placeholder="**Choisissez les rôles**">
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}" {{ (collect(old('role_id'))->contains($role->id)) ? 'selected':'' }}>{{ $role->libelle }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('role_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-3">
                                                <a href="{{ route('avoirs.index', ['user'=>$user->id]) }}" class="btn btn-sm btn-secondary btn-block">
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
                        <div class="col-md-2"></div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
