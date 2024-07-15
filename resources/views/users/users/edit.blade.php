@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>UTILISATEURS</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Listes utilisateurs</a></li>
                            <li class="breadcrumb-item active">Nouveau utilisateur</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 register-box">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3  class="card-title">Nouveau utilisateur</h3>
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary float-md-right">
                                    <i class="fa-solid fa-circle-left mr-1"></i>
                                    {{ __('Retour') }}
                                </a>
                            </div>
                            <form method="POST" action="{{ route('users.update', ['user'=>$user->id]) }}">
                                @csrf
                                <div class="card-body">
                                    
                                    <div class="input-group mb-3 col-sm-12">
                                        <select name="representent_id" id="" class="form-control form-control-sm"
                                            required>
                                            <option value="">Selectionnez un représentant</option>
                                            @foreach ($representents as $representent)
                                                <option value="{{ $representent->id }}"
                                                    {{ @old('representent_id') == $representent->id ? 'selected' : '' }}>
                                                    {{ $representent->nom }} {{ $representent->prenom }} </option>
                                            @endforeach
                                        </select>
                                        @error('representent_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <input type="text" id="name" name="name" value="{{ @old('name')?@old('name'):$user->name }}" required autofocus class="form-control  @error('name') is-invalid @enderror" placeholder="**Nom complet**">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-user"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <input type="email" id="email" name="email" value="{{ @old('email')?@old('email'):$user->email }}" required class="form-control  @error('email') is-invalid @enderror" placeholder="**Email**">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-envelope"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password"  placeholder="Password">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-lock"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group mb-3">
                                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Retapez le password">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <span class="fas fa-lock"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('password_confirmation')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                Retour
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-warning btn-block">
                                                {{ __('Modifier') }}
                                                <i class="fa-solid fa-pen-to-square"></i>
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