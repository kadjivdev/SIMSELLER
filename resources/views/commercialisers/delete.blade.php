@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>FOURNISSEUR PRODUIT</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fournisseurs.show', ['id'=>$fournisseurs->id]) }}">Fournisseurs</a></li>
                            <li class="breadcrumb-item active">Suppression produits fournisseurs</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card card-warning bg-warning">
                            <div class="card-header">
                                <h3 class="card-title">Suppression produits fournisseurs</h3>
                            </div>
                                <div class="card-body">
                                    Êtes vous sûr de vouloir supprimer le produit du fournisseur {{ $fournisseurs->raisonSociale }} ({{ $fournisseurs->sigle }})
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <a href="{{ route('fournisseurs.show', ['id'=>$fournisseurs->id]) }}" class="btn btn-sm btn-secondary">
                                                {{ __('Retour') }}
                                            </a>

                                            <a class="btn btn-sm btn-success"  href="{{ route('commercialisers.destroy', ['fournisseur_id'=>$fournisseurs->id, 'produit_id'=>$produits->id]) }}">
                                                {{ __('Supprimer') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;
