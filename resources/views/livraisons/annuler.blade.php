@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">ANNULATION DE LIVRAISON</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('livraisons.index') }}">Listes des livraisons</a></li>
                            <li class="breadcrumb-item active">Annulation</li>
                        </ol>
                    </div>
                </div>
                @include('livraisons.enteteproduit')
        </section>


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-warning bg-warning">
                                <div class="card-body text-center">
                                    <h4>Voulez vous annuler la livraison N° {{ $programmation->code }} du camion {{ $programmation->camion->marque->libelle }} ({{ $programmation->camion->immatriculationTracteur }}, {{ $programmation->camion->immatriculationRemorque }}) à la date du {{ date_format(date_create($programmation->dateprogrammer), 'd/m/Y') }} ?</h4>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4 text-center">
                                            <form method="POST" action="{{route('livraisons.update',['programmation'=>$programmation->id])}}">
                                            @csrf
                                                <div class="row text-center">
                                                    <div class="col-md-6">
                                                        <a href="{{ route('livraisons.index') }}" class="btn btn-sm btn-secondary  btn-block">
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
