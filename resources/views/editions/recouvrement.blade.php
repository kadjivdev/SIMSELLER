@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">ETAT DE VENTE D'UNE PERIODE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">Vente à recouvrir</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-body">
                                <div class="row">
                                        @if(count($ventes) > 0)

                                            <div class="col-md-12">
                                                <h4 class="col-12 text-center border border-info p-2 mb-2">
                                                    Point des ventes crédit à recouvrir
                                                </h4>
                                                <table class="table table-bordered table-striped table-sm mt-2"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Client</th>
                                                        <th>Vente</th>
                                                        <th>Date</th>
                                                        <th>Montant</th>
                                                        <th>Réglé</th>
                                                        <th>Reste</th>
                                                        <th>Echéance</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php($cpt = 0)
                                                    @php($montant = 0)
                                                    @php($regle = 0)
                                                    @foreach($ventes as $key=>$item)
                                                        @if(($item->montant - $item->reglements()->sum('montant')) <> 0)
                                                            <tr>
                                                            @php($cpt++)
                                                            @php($montant = $montant + ($item->montant) )
                                                            @php($regle = $regle + $item->reglements()->sum('montant'))
                                                            <td>{{$cpt}}</td>
                                                            <td>
                                                                @if($item->commandeclient)
                                                                    @if ($item->commandeclient->client->typeclient)
                                                                        {{ $item->commandeclient->client->raisonSociale }}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>{{$item->code}}</td>
                                                            <td>{{date_format(date_create($item->date),'d/m/Y')}}</td>
                                                            <td class="text-right font-weight-bold">{{number_format($item->montant,'0','',' ')}}</td>
                                                            <td class="text-right font-weight-bold">{{number_format($item->reglements()->sum('montant'),'0','',' ')}}</td>
                                                            <td class="text-right font-weight-bold">{{number_format(($item->montant - $item->reglements()->sum('montant')),'0','',' ')}}</td>
                                                            <td class="text-center font-weight-bold">
                                                                @if($item->type_vente_id == 2)
                                                                    @if(($item->montant - $item->reglements()->sum('montant')) == 0)
                                                                        <span class="badge bg-success"><i class="fa fa-check"></i> Soldé</span>
                                                                    @elseif($item->echeances()->where('statut',0)->first())
                                                                        {{date_format(date_create($item->echeances()->where('statut',0)->first()->date),'d/m/Y')}}
                                                                    @else
                                                                        <span class="badge bg-danger"><i class="fa fa-times"></i> Non défini</span
                                                                    @endif
                                                                @elseif($item->montant - $item->reglements()->sum('montant') == 0)
                                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Soldé</span>
                                                                @else
                                                                    <span class="badge bg-danger"><i class="fa fa-times"></i> Anomalie</span
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endif

                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4" class="font-weight-bold">Total</td>
                                                        <td class="text-right font-weight-bold">{{number_format($montant,0,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold">{{number_format($regle,0,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold">{{number_format($montant - $regle,0,',',' ')}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="col-12 text-center border border-info p-2">
                                                Aucune information trouvée pour votre requête.
                                            </div>
                                        @endif
                                </div>
                                <div class="card-footer text-center no-print">
                                    @if($ventes)
                                        @if(count($ventes) > 0)
                                            <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
