@extends('layouts.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>APPROVISIONNEMENT A CONTROLLER</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                        <li class="breadcrumb-item active">Listes des règlement à contrôler</li>
                    </ol>
                </div>
            </div>
        </div>
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
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row ">
                                <div class="col-lg-12 alert border-warning shadow-2 text-center">
                                    Vous ête sur le point de valider l'Approvisionnement {{$reglement->code}}.<br>
                                    Veuillez verifier l'exactitude des informations avant de confirmer la validation
                                </div><!-- col -->
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <table class="table">
                                        
                                        <tr>
                                            <td>Date de l'approvisionnement</td>
                                            <td class="text-right font-weight-bold">{{date_format(date_create($reglement->date),'d/m/Y')}}</td>
                                        </tr>
                                        <tr>
                                            <td>Client</td>
                                            <td class="text-right font-weight-bold">{{$reglement->_clt->raisonSociale}}</td>
                                        </tr>
                                        <tr>
                                            <td>Montant</td>
                                            <td class="text-right font-weight-bold">{{number_format($reglement->montant,0,',',' ')}}</td>
                                        </tr>
                                        <tr class="bg-success text-white font-weight-bold">
                                            <td colspan="2" class="text-center">Règlement : {{$reglement->code}} associé</td>
                                        </tr>
                                        <tr>
                                            <td>Référence</td>
                                            <td class="text-right font-weight-bold">{{$reglement->reference}}</td>
                                        </tr>
                                        <tr>
                                            <td>Responsable</td>
                                            <td class="font-weight-bold text-right">{{$reglement->utilisateur->name}}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-8">
                                    @if($reglement->document)
                                    <img src="{{asset('storage/'.$reglement->document)}}" alt="" width="100%">
                                    <!-- <img src="{{$reglement->document}}" alt="" width="100%"> -->
                                    @else
                                    Sans document
                                    @endif
                                </div>
                            </div>
                            <div class="row row-sm">
                                <div class="col-lg-12 text-center mt-3" id="valider">
                                    <a href="{{route('ctlventes.index')}}" onclick="annuler()" class="btn btn-dark btn-rounded "><i class="fa fa-arrow-circle-left"></i> J'annule.</a>
                                    <button class="btn btn-success btn-rounded " type="button" data-toggle="modal" data-target="#modal-default"><i class="fa fa-check"></i> Je valide</button>
                                    <button class="btn btn-danger btn-rounded " type="button" data-toggle="modal" data-target="#modal-rejet"><i class="fa fa-close"></i> Je Rejete</button>
                                </div>
                                <div class="col-lg-12 text-center mt-3" id="valider_chargement" hidden>
                                    <button class="btn btn-danger btn-rounded" disabled id="btn_charg"><i class="fa fa-spin fa-spinner"></i> Validation encours...</button>
                                </div>
                            </div>
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

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Validation vente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="POST" action="{{ route('ctlventes.validerApprovisionnement',['reglement'=>$reglement->id]) }}">
                @csrf
                <div class="modal-footer justify-content-between">
                    <div class="col-12 alert-warning alert">
                        Vous êtes entrain de vouloir valider: <br>
                        <ul>
                            <li>Règlement N° {{$reglement->code}}</li>
                            <li>Date : {{date_format(date_create($reglement->date),'d/m/Y')}}</li>
                            <li>Montant : {{number_format($reglement->montant,0,',',' ')}}</li>
                        </ul>
                        Cette opération est irreversible. Merci d'en tenir compte dans votre action.
                    </div>
                    <div class="col-12 text-center">
                        <button data-dismiss="modal" aria-label="Close" class="btn  btn-secondary">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit" class="btn btn-success">Je valide</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-rejet">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger ">
                <h5 class="modal-title">Demande de modification d'un règlement.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('ctlventes.rejetApprovisionnement',['reglement'=>$reglement->id]) }}">
                @csrf
                <div class="modal-footer justify-content-between">

                    <div class="form-group col-12">
                        <label>Motifs de la demande de modification </label>
                        <textarea class="form-control" name="observation" required rows="3"></textarea>
                    </div>
                    <div class="col-12 text-center">
                        <button data-dismiss="modal" aria-label="Close" class="btn  btn-secondary">
                            {{ __('Annuler') }}
                        </button>
                        <button type="submit" class="btn btn-success">Je valide</button>
                    </div>

                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection