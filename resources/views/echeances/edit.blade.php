@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">CLOTURE ECHEANCE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('echeances.index', ['vente'=>$vente->id]) }}">Listes des echeances</a></li>
                        <li class="breadcrumb-item active">Cloture</li>
                    </ol>
                </div>
            </div>
            @include('echeances.entete')
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-12">
                    <div class="card card-warning bg-warning">
                        <div class="card-header">
                            <h3 class="card-title">CLOTURE ECHEANCE</h3>
                        </div>
                        <div class="card-body">
                            Êtes vous sûr de vouloir clôturer l'échéance : {{ date_format(date_create($echeance->date),'d/m/Y') }}
                        </div>
                        <form method="POST" action="{{ route('echeances.update', ['vente'=>$vente->id, 'echeance'=>$echeance->id]) }}">
                            @csrf
                            <div class="card-footer">
                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        <a href="{{ route('echeances.index', ['vente' =>$vente->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                            <i class="fa-solid fa-circle-left"></i>
                                            {{ __('Retour') }}
                                        </a>

                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-sm btn-success btn-block">
                                            {{ __('Supprimer') }}
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </section>


@endsection
