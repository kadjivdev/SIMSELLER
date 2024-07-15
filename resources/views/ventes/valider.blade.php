@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">VALIDATION DE BON DE COMMANDE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('boncommandes.index', ['id' => $boncommandes->id]) }}">Bon de commande</a></li>
                            <li class="breadcrumb-item active">Validation</li>
                        </ol>
                    </div>
                </div>
                @include('boncommandes.entete')
        </section>


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-success bg-success">
                                <div class="card-body text-center">
                                    <h4>Voulez vous valider le bon de commande N° {{ $boncommandes->code }} de {{ $boncommandes->fournisseur->sigle }} ?</h4>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4 text-center">
                                            <form method="POST" action="{{ route('boncommandes.update', ['boncommande'=>$boncommandes->id]) }}">
                                            @csrf
                                                <input type="hidden" name="statut" value="Valider" />
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <a href="{{ route('boncommandes.index') }}" class="btn btn-sm btn-secondary  btn-block">
                                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                                            {{ __('Retour') }}
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="submit" class="btn btn-sm btn-primary  btn-block">
                                                            {{ __('Ok') }}
                                                            <i class="fa-solid fa-check ml-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-4"></div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection;
