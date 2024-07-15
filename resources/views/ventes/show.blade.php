@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>ETAT VENTE</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/dashboard">Acceuil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('ventes.index') }}">Vente</a></li>
                                <li class="breadcrumb-item active">Etat vente</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                        <!-- Main content -->
                            <h3>Bientôt disponible</h3>
                        <!-- /.invoice -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>

        </div>
    @endsection
