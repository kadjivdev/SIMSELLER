@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h1 class="pb-3">COMPTE CLIENT</h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Banques</a></li>
                            <li class="breadcrumb-item active">Comptes</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="card d-flex flex-fill">
                            <div class="card-body">
                                <h1>
                                    {{ $client->raisonSociale }}
                                </h1>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <b><li class=""><span class="fa-li"><i class="fa-solid fa-person-dots-from-line"></i></span> Téléphone :  {{ $client->telephone }}</li></b>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $client->email }}</li>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Adresse:  {{ $client->adresse }}</li>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut crédit:  <span class="badge {{$client->credit == 0 ? 'badge-danger' : 'badge-success'}}">{{ $client->credit == 0 ? 'Non Eligible':'Eligible' }}</span></li>
                                                </div>
                                            </div>
                                        </ul>
                                        
                                    <div class="row">
                                        <div class=" col-1"></div>
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">AVOIR EN COMPTE</span>
                                                    <span class="info-box-number">{{ number_format($compteClient->solde , 0, ',', ' ')  }}</span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-4 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-danger"><i class="fas fa-hand-holding-usd"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">DEBIT</span>
                                                    <span class="info-box-number">{{number_format($client->debit, 0, ',', ' ')   }}</span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-md-3 col-sm-6 col-12">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">CREDIT</span>
                                                    <span class="info-box-number">{{number_format($client->credit, 0, ',', ' ')  }}</span>
                                                </div>

                                            </div>

                                        </div>
                                        <div class=" col-1"></div>


                                    </div>


                                        
                                    <a href="{{ route('newclient.index' )}}" class="btn btn-sm btn-primary float-md-right">
                                        <i class="fa-solid fa-circle-left mr-1"></i>
                                        {{ __('Retour') }}
                                    </a>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <div class="col-sm-12 text-center">
                                        <h2>
                                            SOLDE : {{number_format($compteClient->solde,0,',',' ')}}
                                        </h2>
                                        @if (Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists())
                                             <a href="{{route('compteClient.appro',['client'=>$client->id])}}" class="float-right btn btn-primary">Approvisionner</a>
                                        @endif
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
                                        <th>Date</th>
                                        <th>Libelle</th>
                                        <th>Crédit</th>
                                        <th>Débit</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mouvements['data'] as $key=>$mouvement)
                                            <tr>
                                                <td class="text-center">MVT-{{str_pad($key+1,6,'0',STR_PAD_LEFT)}}</td>
                                                <td class="text-center">{{date_format(date_create($mouvement['dateMvt']),'d/m/Y H:i')}}</td>
                                                <td>{{$mouvement['libelleMvt']}}</td>
                                                <td class="text-right">{{$mouvement['sens'] == 0 ? number_format($mouvement['montantMvt'],0,',',' '):''}}</td>
                                                <td class="text-right">{{$mouvement['sens'] == 2 ?  number_format(-$mouvement['montantMvt'],0,',',' ') : ''}}</td>
                                                <td class="text-right">@if ($mouvement['destroy'] == false) 
                                                    <a class="btn btn-danger btn-sm" href="{{ route('compteClient.delete', ['mouvement'=>$mouvement['id'],'client' => $client->id]) }}">
                                                    <i class="fa-solid fa-trash-can"></i></a>@endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Libelle</th>
                                        <th>Crédit</th>
                                        <th>Débit</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <div class="float-right mt-3">
                                    <nav aria-label="...">
                                        <ul class="pagination">
                                            @foreach($mouvements['links'] as $link)
                                                <li class="page-item {{$link['active'] == true ? 'active':''}}"><a class="page-link" href="{{$link['url']}}">{!!$link['label']!!}</a></li>
                                            @endforeach
                                        
                                        </ul>
                                    </nav>
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
@endsection