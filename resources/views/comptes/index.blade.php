@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h1 class="pb-3">COMPTE BANCAIRE</h1>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="card d-flex flex-fill">
                                        <div class="card-body">
                                            <h1>{{ $banque->raisonSociale }}  ({{ $banque->sigle }})</h1>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-person-dots-from-line"></i></span> Interlocuteur:  {{ $banque->interlocuteur }}</li></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone:  {{ $banque->telephone }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $banque->email }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Adresse:  {{ $banque->adresse }}</li>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('banques.index') }}">Banques</a></li>
                            <li class="breadcrumb-item active">Comptes</li>
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
                                <div class="row">
                                    <div class="col-sm-6 text-left">
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-default">
                                            <i class="fas fa-solid fa-plus"></i>
                                            Ajouter
                                        </button>
                                    </div>
                                    <div class="col-sm-6 text-right">
                                        <a href="{{ route('banques.index') }}" class="btn btn-sm btn-primary right"><i class="fa-solid fa-circle-left"></i> Retour</a>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modal-default">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Nouveau Compte</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{ route('comptes.store', ['banque'=>$banque->id]) }}">
                                            @csrf
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Numéro<span class="text-danger">*</span></label>
                                                                <input type="text" id="numero" class="form-control form-control-sm @error('numero') is-invalid @enderror" name="numero" style="text-transform: uppercase"  value="{{ old('numero') }}"  autocomplete="numero" autofocus required>
                                                                @error('numero')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Intitulé<span class="text-danger">*</span></label>
                                                                <input type="text" id="intitule" class="form-control form-control-sm @error('intitule') is-invalid @enderror" name="intitule" style="text-transform: uppercase"  value="{{ old('intitule') }}"  autocomplete="numero" autofocus required>
                                                                @error('intitule')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <div class="col-sm-4">
                                                            <a href="{{ route('comptes.index', ['banque'=>$banque->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                                {{ __('Retour') }}
                                                            </a>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <button type="submit" class="btn btn-success btn-block">Enregistrer
                                                            <i class="fa-solid fa-floppy-disk"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Numéro</th>
                                        <th>Actualisation</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if ($banque->count() > 0)
                                        <?php $compteur=1; ?>
                                        @foreach($banque->comptes as $compte)
                                            <tr>
                                                <td>{{ $compteur++ }}</td>
                                                <td class="ml-5 pr-5 text-center">{{ $compte->numero }}</td>
                                                <td class="ml-5 pr-5 text-center">{{ $compte->intitule }}</td>
                                                <td class="text-center">
                                                    <!--<a class="btn btn-success btn-sm" href="#"><i class="fa-regular fa-eye"></i></a>-->
                                                    <a class="btn btn-warning btn-sm" href="{{ route('comptes.edit', ['banque'=>$banque->id, 'compte'=>$compte->id])  }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <a class="btn btn-danger btn-sm" href="{{ route('comptes.delete', ['banque'=>$banque->id, 'compte'=>$compte->id])  }}"><i class="fa-solid fa-trash-can"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Numéro</th>
                                        <th>Actualisation</th>
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
