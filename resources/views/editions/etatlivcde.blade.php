@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">ETAT DE LIVRAISON DES COMMANDES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">Etat des commandes</li>
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
                                <form method="post" id="form_bc" action="{{route('edition.postetatlivraisoncde')}}">
                                    @csrf
                                    <div class="row no-print" >
                                        
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Date début</label>
                                                <input type="date" class="form-control" name="debut" value="{{old('debut')}}" >
                                            </div>
                                            @error('debut')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Date début</label>
                                                <input type="date" class="form-control" name="fin" value="{{old('fin')}}" >
                                            </div>
                                            @error('fin')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">Fournisseur</label>
                                                <select  id="client" class="form-control form-control-sm select2"  name="fournisseur">
                                                    <option class="" value="" selected>Tous</option>
                                                    @foreach($fournisseurs as $frs)
                                                        <option value="{{$frs->id}}" {{old('fournisseur')==$frs->id?'selected':''}}>{{$frs->raisonSociale}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                </form>

                                <div class="row">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['bcs']) > 0)

                                            <div class="col-md-12">
                                                <h4 class="col-12 text-center border border-info p-2 mb-2">
                                                    @if(session('resultat')['fournisseur'])
                                                       Situation des bons de commandes  au {{date('d/m/Y')}} du fournissseur {{session('resultat')['bcs'][0]->bc->fournisseur->raisonSociale}}
                                                    @else
                                                        Situation des bons de commandes  au {{date('d/m/Y')}}
                                                    @endif
                                                </h4>
                                                <table class="table table-bordered table-striped table-sm mt-2"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Code</th>
                                                        <th>date</th>
                                                        <th>Type</th>
                                                        <th>Produit</th>
                                                        <th>Fournisseur</th>
                                                        <th>Qte cde</th>
                                                        <th>Qte Prog</th>
                                                        <th>Qte Liv</th>
                                                        <th>Qte vendu</th>
                                                        <th>Reste à Liv</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php($cpt = 0)
                                                    @php($stock = 0)
                                                    @php($prog = 0)
                                                    @php($livre = 0)
                                                    @php($Vendu = 0)
                                                    @php($cder = 0)

                                                    @php($Montstock = 0)
                                                    @php($Montprog = 0)
                                                    @php($Montlivre = 0)
                                                    @php($MontVendu = 0)
                                                    @php($Montcder = 0)
                                                    @foreach(session('resultat')['bcs'] as $key=>$item)
                                                        <tr>
                                                            @php($cpt++)
                                                            @php($stock = $stock + ($item->qteBc - $item->qteLiv) )
                                                            @php($prog = $prog + $item->qteprog)
                                                            @php($livre = $livre + $item->qteLiv)
                                                            @php($Vendu = $Vendu + $item->qteVendu)
                                                            @php($cder = $cder + $item->qteBc)

                                                            @php($Montstock = $Montstock + ($item->montBc - $item->MontLiv) )
                                                            @php($Montprog = $Montprog + $item->Montprog)
                                                            @php($Montlivre = $Montlivre + $item->MontLiv)
                                                            @php($MontVendu = $MontVendu + $item->montVendu)
                                                            @php($Montcder = $Montcder + $item->montBc)

                                                            <td>{{$cpt}}</td>
                                                            <td>{{$item->bc->code}}</td>
                                                            <td>{{date_format(date_create($item->bc->dateBon),'d/m/Y')}}</td>
                                                            <td>{{$item->bc->typecommande->libelle}}</td>
                                                            <td>{{$item->bc->detailboncommandes[0]->produit->libelle}}</td>
                                                            <td>{{$item->bc->fournisseur->raisonSociale}}</td>
                                                            <td class="text-right font-weight-bold">{{number_format($item->qteBc,2,',',' ')}}</td>
                                                            <td class="text-right font-weight-bold"> {{number_format($item->qteprog,2,',',' ')}} </td>
                                                            <td class="text-right font-weight-bold">{{number_format($item->qteLiv,2,',',' ')}}</td>
                                                            <td class="text-right font-weight-bold"> {{number_format($item->qteVendu,2,',',' ')}}</td>
                                                            <td class="text-right font-weight-bold">{{number_format($item->qteBc - $item->qteLiv,2,',',' ')}}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="6" class="font-weight-bold">Total</td>
                                                        <td class="text-right font-weight-bold">{{number_format($cder,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold"> {{number_format($prog,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold">{{number_format($livre,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold"> {{number_format($Vendu,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold">{{number_format($stock,2,',',' ')}}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td colspan="6" class="font-weight-bold text-success">Montant</td>
                                                        <td class="text-right font-weight-bold text-success">{{number_format($Montcder,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold text-success"> {{number_format($Montprog,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold text-success">{{number_format($Montlivre,2,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold text-success"> {{number_format($MontVendu,2,',',' ')}} </td>
                                                        <td class="text-right font-weight-bold text-success">{{number_format($Montstock,2,',',' ')}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="col-12 text-center border border-info p-2">
                                                Aucun stock trouvé pour votre requête.
                                            </div>
                                        @endif

                                    @endif
                                </div>
                                @if(!(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()))
                                <div class="card-footer text-center no-print">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['bcs']) > 0)
                                            <button class="btn btn-success" onclick="window.print()"><i class="fa fa-print"></i> Imprimer</button>
                                        @endif
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
