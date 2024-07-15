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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
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
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                            {{session('error')}}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="card-head pt-3 pl-4">
                                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-solid fa-plus"></i>
                                            {{ __('Ajouter') }}
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-sm table-bordered table-striped table-sm"  style="font-size: 12px">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>libellé</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($roles->count() > 0)
                                                    <?php $compteur=1; ?>
                                                    @foreach($roles as $role)
                                                        <tr>
                                                            <td>{{ $compteur++ }}</td>
                                                            <td class="ml-5 pr-5">{{ $role->libelle }}</td>
                                                            <td class="text-center">
                                                                <a class="btn btn-success btn-xs" href="{{ route('roles.show', ['role'=>$role->id]) }}"><i class="fa-solid fa-users"></i></a>
                                                                <a class="btn btn-warning btn-xs" href="{{ route('roles.edit', ['role'=>$role->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                                <a class="btn btn-danger btn-xs" href="{{ route('roles.delete', ['role'=>$role->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Libellé</th>
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
            </section>
        </div>

    @endsection
