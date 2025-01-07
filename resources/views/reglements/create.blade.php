@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">REGLEMENT VENTE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reglements.index', ['vente'=>$vente->id]) }}">Listes des reglements</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>
            @include('reglements.entete')
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Nouveau Règlement </h3>
                        </div>
                        <form method="POST" id="reglement" action="{{ route('reglements.store', ['vente'=>$vente->id]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Montant<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase" value="{{ old('montant')?:($vente->montant-$vente->remise) - $vente->reglements->sum('montant') }}" autocomplete="off" min="1" autofocus required>
                                            @error('montant')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Date<span class="text-danger">*</span></label>
                                            <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date" value="{{ old('date')?old('date'):date('Y-m-d') }}" autocomplete="off" autofocus required>
                                            @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @if(Auth::user()->roles()->where('libelle', 'ADMINISTRATEUR')->exists()==true || Auth::user()->roles()->where('libelle', 'SUPER REGLEMENT')->exists()==true)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="debloc_dette" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Déblocage de dette
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="card-footer">
                                    <div id="spin" hidden>
                                        <i class="fa fa-spin fa-spinner fa-2x"></i>
                                    </div>
                                    <div class="row justify-content-center" id="action">
                                        <div class="col-sm-4">
                                            <a href="{{ route('reglements.index', ['vente'=>$vente->id]) }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-success btn-block">
                                                {{ __('Enregistrer') }}
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center" id="chargement" hidden>
                                        <div class="col-sm-4">
                                            <a href="#" class="btn btn-sm btn-secondary btn-block" disabled>
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-success btn-block" disabled>
                                                {{ __('Enregistrement encours...') }}
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </section>
</div>

@endsection
@section('script')
<script>
    $('document').ready(function() {

        $('#reference').attr('disabled', true);
        $('#compte_id').attr('disabled', true);
        $('#document').attr('disabled', false);
        $('#type').attr('disabled', true);

        selectReglement();
    })

    function selectReglement() {
        if ($('#srcReg').val() == "indirect") {
            $('#reference').removeAttr('disabled');
            $('#compte_id').removeAttr('disabled');
            $('#document').removeAttr('disabled');
            $('#type').removeAttr('disabled');
            //$('#confirmation').attr('required','required');
        } else {
            $('#reference').attr('disabled', true);
            $('#compte_id').attr('disabled', true);
            $('#document').attr('disabled', true);
            $('#type').attr('disabled', true);
        }
    }
    $('#reglement').submit(function() {
        $('#action').attr('hidden', 'hidden');
        $('#spin').removeAttr('hidden');
    })
    $('#reglement').submit(function() {
        $('#action').attr('hidden', 'hidden');
        $('#chargement').removeAttr('hidden');
    })
</script>
@endsection