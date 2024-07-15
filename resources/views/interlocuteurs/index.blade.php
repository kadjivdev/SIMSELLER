@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>FOURNISSEURS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                                <li class="breadcrumb-item active">Listes des fournisseurs</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                @if($message = session('message'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ $message }}
                                    </div>
                                @endif
                                <div class="card-header">
                                    <h3 class="card-title"></h3>
                                    <a class="btn btn-success btn-sm" href="{{route('fournisseurs.create')}}">
                                        <i class="fas fa-solid fa-plus"></i>
                                        Ajouter
                                    </a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Logo</th>
                                            <th>Sigle</th>
                                            <th>Raison Sociale</th>
                                            <th>Téléphone</th>
                                            <th>E-mail</th>
                                            <th>Catégorie</th>
                                            <th>Adresse</th>
                                            <th>Actualisation</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if ($fournisseurs->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($fournisseurs as $fournisseur)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td><img class="profile-user-img img-fluid img-circle" style="height: 50px; width: 50px"
                                                        src="{{ asset('images')}}/{{ $fournisseur->logo }}"
                                                        alt="Profile de l'agent"></td>
                                                    <td>{{ $fournisseur->sigle }}</td>
                                                    <td>{{ $fournisseur->raisonSociale }}</td>
                                                    <td>{{ $fournisseur->telephone }}</td>
                                                    <td>{{ $fournisseur->email }}</td>
                                                    <td>{{ $fournisseur->categoriefournisseur->libelle }}</td>
                                                    <td>{{ $fournisseur->adresse }}</td>
                                                    <td class="text-center">
                                                        <a class="btn btn-success btn-sm" href="{{ route('fournisseurs.show', ['id'=>$fournisseur->id]) }}"><i class="fa-regular fa-eye"></i></a>
                                                        <a class="btn btn-warning btn-sm" href="{{ route('fournisseurs.edit', ['id'=>$fournisseur->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                        <a class="btn btn-danger btn-sm" href="{{ route('fournisseurs.delete', ['id'=>$fournisseur->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                        <a class="btn btn-secondary btn-sm" href=""><i class="fa-solid fa-bed"></i></a>
                                                        <a class="btn btn-primary btn-sm" href=""><i class="fas fa-running"></i></a>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button type="button" class="dropdown-toggle btn btn-success btn-sm" href="#" role="button" data-toggle="dropdown">
                                                                Actions<i class="dw dw-more"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-md-right dropdown-menu-icon-list drop text-sm">
                                                                <a class="dropdown-item" href=""><i class="fa-solid fa-person-dots-from-line"></i> Responsables</a>
                                                                <a class="dropdown-item" href=""><i class="fa-solid fa-head-side-mask"></i> Agents</a>
                                                                <a class="dropdown-item" href=""><i class="fa-solid fa-industry"></i> Chantiers</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Logo</th>
                                            <th>Sigle</th>
                                            <th>Raison Sociale</th>
                                            <th>Téléphone</th>
                                            <th>E-mail</th>
                                            <th>Catégorie</th>
                                            <th>Adresse</th>
                                            <th>Actualisation</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
    @endsection
