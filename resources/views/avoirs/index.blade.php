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
                                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Utilisateurs</a></li>
                                <li class="breadcrumb-item active">Rôles</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div>
                                    @if(session('message'))
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                            {{session('message')}}
                                        </div>
                                    @endif
                                    @if(session('error'))
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                            {{session('error')}}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="card-head pt-3 pl-4 pr-4 row">
                                        <div class="col-sm-6">
                                            <a href="{{ route('avoirs.create', ['user'=>$user->id]) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-solid fa-plus"></i>
                                                {{ __('Nouveau(x)') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-6 text-right float-right">
                                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary right"><i class="fa-solid fa-circle-left mr-1"></i> Retour</a>
                                        </div>
                                    </div>
                                    <h4 class="text-center">Listes des rôles</h4>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table id="example1" class="table table-sm table-bordered table-striped" style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Libellé</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if ($user->count() > 0)
                                                        <?php $compteur=1; ?>
                                                        @foreach($user->roles as $role)
                                                            <tr>
                                                                <td>{{ $compteur++ }}</td>
                                                                <td>{{ $role->libelle }}</td>
                                                                <td class="text-center">
                                                                    <a class="btn btn-danger btn-xs" href="{{ route('avoirs.delete',  ['user'=>$user->id, 'role'=>$role->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                    <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Libelle</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    @endsection
