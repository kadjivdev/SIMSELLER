@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">LISTE DES TRAITEMENT D'UNE PERIODE</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">Liste des traitements</li>
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
                                <form method="post" id="form_bc" action="{{route('ventes.postListeDesTraitementPeriode')}}">
                                    @csrf
                                    <div class="row no-print" >
                                        <div class="col-1"></div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">Date début</label>
                                                <input type="date" class="form-control" name="debut" value="{{old('debut')}}" required>
                                            </div>
                                            @error('debut')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">Date début</label>
                                                <input type="date" class="form-control" name="fin" value="{{old('fin')}}" required>
                                            </div>
                                            @error('fin')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                </form>

                                <div class="row">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['ventes']) > 0)

                                            <div class="col-md-12">
                                                <h4 class="col-12 text-center border border-info p-2 mb-2">
                                                    Point des ventes traitées de la période du {{date_format(date_create(session('resultat')['debut']),'d/m/Y')}}  au {{date_format(date_create(session('resultat')['fin']),'d/m/Y')}}
                                                </h4>
                                                <table class="table table-bordered table-striped table-sm mt-2"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>Date Vente</th>
                                                        <th>Client</th>
                                                        <th>IFU</th>
                                                        <th>Nom fiscal</th>
                                                        <th>IFU fiscal</th>
                                                        <th>Date Achat</th>
                                                        <th>Produit</th>
                                                        <th>Qté</th>
                                                        <th>Prix TTC</th>
                                                        <th>Prix HT</th>
                                                        <th>Prix Bruit</th>
                                                        <th>Mts Net Ht</th>
                                                        <th>TVA</th>
                                                        <th>AIB</th>
                                                        <th>Frs</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php($cpt = 0)
                                                    @php($montant = 0)
                                                    @php($regle = 0)
                                                    @foreach(session('resultat')['ventes'] as $key=>$item)
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td>{{$item->code}}</td>
                                                            <td>{{date_format(date_create($item->date),'d/m/Y')}}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{$item->typeVente->libelle}}</td>
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
                                                                        <span class="badge bg-danger"><i class="fa fa-times"></i> Non défini</span>
                                                                    @endif
                                                                @elseif($item->montant - $item->reglements()->sum('montant') == 0)
                                                                    <span class="badge bg-success"><i class="fa fa-check"></i> Soldé</span>
                                                                @else
                                                                    <span class="badge bg-danger"><i class="fa fa-times"></i> Anomalie</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach                                                    
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="text-white text-center bg-gradient-gray-dark">
                                                            <th>Date Vente</th>
                                                            <th>Client</th>
                                                            <th>IFU</th>
                                                            <th>Nom fiscal</th>
                                                            <th>IFU fiscal</th>
                                                            <th>Date Achat</th>
                                                            <th>Produit</th>
                                                            <th>Qté</th>
                                                            <th>Prix TTC</th>
                                                            <th>Prix HT</th>
                                                            <th>Prix Bruit</th>
                                                            <th>Mts Net Ht</th>
                                                            <th>TVA</th>
                                                            <th>AIB</th>
                                                            <th>Frs</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        @else
                                            <div class="col-12 text-center border border-info p-2">
                                                Aucun information trouvée pour votre requête.
                                            </div>
                                        @endif

                                    @endif
                                </div>
                                <div class="card-footer text-center no-print">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['ventes']) > 0)
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
