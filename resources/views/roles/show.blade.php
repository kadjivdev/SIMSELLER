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
                                <li class="breadcrumb-item active">Utilisateurs</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div>
                                    <div class="card-head pt-3 pl-4 mb-5 mr-5">
                                        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-primary float-md-right">
                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                            {{ __('Retour') }}
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <table id="example1" class="table table-sm table-bordered table-striped table-sm"  style="font-size: 12px">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom Complet</th>
                                                    <th>E-mail</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($role->count() > 0)
                                                    <?php $compteur=1; ?>

                                                    @foreach($role->users as $user)

                                                            <tr>
                                                                <td>{{ $compteur++ }}</td>
                                                                <td>{{ $user->name }}</td>
                                                                <td class="ml-5 pr-5">{{ $user->email }}</td>
                                                            </tr>

                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nom Complet</th>
                                                    <th>E-mail</th>
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
