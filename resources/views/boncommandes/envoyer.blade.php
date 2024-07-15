@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">ENVOYER COMMANDE POUR VALIDATION</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('boncommandes.index', ['id' => $boncommandes->id]) }}">Bon de commande</a></li>
                            <li class="breadcrumb-item active">Validation</li>
                        </ol>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12">

                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12">
                                    <div class="card d-flex flex-fill">
                                        <div class="card-body">
                                            <h1 class="pb-3">Bon de commande N° {{ $boncommandes->code }}</h1>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-calendar"></i></span> Date :  {{ date_format(date_create($boncommandes->dateBon), 'd/m/Y') }}</li></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <b><li class=""><span class="fa-li"><i class="fa-regular fa-money-bill-1"></i></span> Montant:  {{ number_format($boncommandes->montant,2,","," ") }}</li></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut:  {{ $boncommandes->statut }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class=""><span class="fa-li"><i class="nav-icon fas fa-solid fa-cart-flatbed"></i></span> Type commande:  {{ $boncommandes->typecommande->libelle }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-user-tie"></i></span> Utilisateur:  {{ $boncommandes->users }}</li>
                                                            </div>
                                                        </div>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-6">
                                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <b><li class=""><span class="fa-li"><i class="fa-regular fa-closed-captioning"></i></span> Sigle :  {{ $boncommandes->fournisseur->sigle }}</li></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-book-open"></i></span> Raison Sociale :  {{ $boncommandes->fournisseur->raisonSociale }}</li></b>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone :  {{ $boncommandes->fournisseur->telephone }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-envelope-circle-check"></i></span> E-mail :  {{ $boncommandes->fournisseur->email }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-location-dot"></i></span> Adresse :  {{ $boncommandes->fournisseur->adresse }}</li>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <li class=""><span class="fa-li"><i class="fa-solid fa-industry"></i></span> Catégorie :  {{ $boncommandes->fournisseur->categoriefournisseur->libelle }}</li>
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
                </div>
            </div>
        </section>



        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-success bg-success">
                                <div class="card-body text-center">
                                    <h4>Voulez vous envoyé la commande  {{ $boncommandes->code }} de {{ $boncommandes->fournisseur->sigle }} à la validation ?</h4>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4 text-center">
                                            <form method="POST" action="{{ route('boncommandes.postenvoyer', ['boncommandes'=>$boncommandes->id]) }}" id="envoyerForm">
                                            @csrf
                                                <input type="hidden" name="statut" value="Valider" />
                                                <div class="row text-center" id="action">
                                                    <div class="col-md-6">
                                                        <a href="{{ route('boncommandes.index') }}" class="btn btn-sm btn-secondary  btn-block">
                                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                                            {{ __('Non') }}
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="submit" class="btn btn-sm btn-primary  btn-block">
                                                            {{ __('Oui') }}
                                                            <i class="fa-solid fa-check ml-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="loader" hidden>
                                                    <i class="fa fa-spin fa-spinner fa-3x"></i>
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
@section('script')
    <script>
        $(document).ready(function () {
            $('#envoyerForm').submit(function (){
                $("#action").attr('hidden',true);
                $("#loader").removeAttr('hidden');
            });
        });
    </script>
@endsection
