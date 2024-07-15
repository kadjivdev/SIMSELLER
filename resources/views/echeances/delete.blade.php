@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">SUPPRESSION ECHEANCE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('echeances.index', ['vente'=>$vente->id]) }}">Listes des reglements</a></li>
                        <li class="breadcrumb-item active">Suppression</li>
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
                                <h3 class="card-title">Suppression de échéance</h3>
                            </div>
                                <div class="card-body">
                                    Êtes vous sûr de vouloir supprimer l'échéance' :  {{ date_format(date_create($echeance->date),'d/m/Y') }}
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('echeances.index', ['vente' =>$vente->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>

                                        </div>
                                        <div class="col-sm-4">
                                            <a class="btn btn-sm btn-success btn-block"  href="{{ route('echeances.destroy', ['vente'=>$vente->id, 'echeance'=>$echeance]) }}">
                                                {{ __('Supprimer') }}
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
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
