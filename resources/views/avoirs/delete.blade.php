@extends('layouts.app')

    @section('content')

        <div class="content-wrapper">
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
                                <li class="breadcrumb-item"><a href="{{ route('avoirs.index', (['user'=>$user->id])) }}">Rôles</a></li>
                                <li class="breadcrumb-item active">Supprimer Rôle</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="card card-warning bg-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Suppression du rôle {{ $role->libelle }} de l'utilisateur {{ $user->name }}</h3>
                                </div>
                                    <div class="card-body text-center">
                                        Êtes vous sûr de vouloir supprimer le rôle {{ $role->libelle }} de l'utilisateur {{ $user->name }}
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center">
                                            <div class="col-sm-4">
                                                <a href="{{ route('avoirs.index', ['user'=>$user->id, 'role' =>$role->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                                    <i class="fa-solid fa-circle-left"></i>
                                                    {{ __('Retour') }}
                                                </a>

                                            </div>
                                            <div class="col-sm-4">
                                                <a class="btn btn-sm btn-success btn-block"  href="{{ route('avoirs.destroy', ['user'=>$user->id, 'role'=>$role->id]) }}">
                                                    {{ __('Supprimer') }}
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </section>

        </div>

    @endsection
