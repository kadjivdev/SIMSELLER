@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">ETAT DE REGLEMENT D'UNE PERIODE PAR REPRESENTANT</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">Etat des reglements </li>
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
                                <form method="post" id="form_bc" action="{{route('edition.postEtatReglementPeriodeRep')}}">
                                    @csrf
                                    
                                    <div class="row no-print" >
                                        <div class="col-1"></div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Représentant</label>
                                                <select  id="zone" class="form-control form-control-sm select2"  name="zone">
                                                    <option class="" value="" selected>Tous</option>
                                                    @foreach($Rep as $zone)
                                                        <option value="{{$zone->id}}" {{old('zone')==$zone->id?'selected':''}}>{{$zone->nom}}  {{$zone->prenom}}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Date début</label>
                                                <input type="date" class="form-control" name="debut" value="{{old('debut')}}" required>
                                            </div>
                                            @error('debut')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Date Fin</label>
                                                <input type="date" class="form-control" name="fin" value="{{old('fin')}}" required>
                                            </div>
                                            @error('fin')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-primary" type="submit" style="margin-top: 2em">Afficher</button>
                                        </div>
                                        
                                    </div>
                                    <div class="row no-print" >
                                        <div class="col-1"></div>
                                        <div class="col-1"></div>
                                    </div>
                                </form>

                                <div class="row">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['reglements']) > 0)

                                            <div class="col-md-12">
                                                <h4 class="col-12 text-center border border-info p-2 mb-2">
                                                    Point des r_ de la période du {{date_format(date_create(session('resultat')['debut']),'d/m/Y')}}  au {{date_format(date_create(session('resultat')['fin']),'d/m/Y')}}
                                                </h4>
                                                <table class="table table-bordered table-striped table-sm mt-2"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Dates</th>
                                                        <th class="text-center" style="width:20%">Clients</th>
                                                        <th>Zones</th>
                                                        <th>Code Vente</th>
                                                        <th>Montant Vente</th>
                                                        <th class="text-center" >Banque</th>
                                                        <th>Code Règlement</th>
                                                        <th>Montant Règlement</th>
                                                        <th>Reste</th>
                                                        <th>Constat</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php($montant = 0)
                                                    @php($montantTotal = 0)
                                                    @php($cpte = 0)
                                                    @php($regle = 0)
                                                    @php($regleTotal = 0)
                                                    @php($venteId = 0)
                                                    @foreach(session('resultat')['reglements'] as $key=>$item)
                                                        @php($cpte++)
                                                        @if ($venteId == 0)
                                                            @php( $montantTotal = $item->montant_vente) ;
                                                            @php(  $regleTotal = $regleTotal+ $regle);
                                                        
                                                            
                                                        @endif
                                                        @if(($venteId == $item->id_vente)||($venteId == 0)) 

                                                            <tr>
                                                                <td>{{++$key}}</td>
                                                                <td>{{$item->date}}</td>
                                                                <td class="text-center">{{$item->raisonSociale}} <br> ({{$item->telephone}})</td>
                                                                <td><b>{{$item->zone}}</b></td>
                                                                <td>{{ $item->code_vente}}</td>
                                                                <td class="text-right">{{ $item->montant_vente}}</td>
                                                                <td class="text-center">{{ $item->banque}} <br>({{ $item->numero}})</td>                                                         
                                                                <td>{{ $item->code_reglement}}</td>
                                                                <td class="text-right">{{ $item->montant_reglement}}</td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            @php($venteId = $item->id_vente)
                                                            @php($montant = $item->montant_vente)
                                                            @php($regle = $regle + ($item->montant_reglement))
                                                                  
                                                            @php(  $regleTotal = $regleTotal+ ($item->montant_reglement));
                                                                    
                                                                
                                                           

                                                        @else
                                                            @php( $montantTotal = $montantTotal+$item->montant_vente) ;
                                                            @php(  $regleTotal = $regleTotal+ ($item->montant_reglement));
                                                            
                                                                <tr>
                                                                    <td colspan="5" class="font-weight-bold">Total</td>
                                                                    <td class="text-right font-weight-bold">{{number_format($montant,0,',',' ')}}</td>
                                                                    <td class="text-right font-weight-bold"></td>
                                                                    <td colspan="2" class="text-right font-weight-bold">{{number_format($regle,0,',',' ')}}</td>
                                                                    <td class="text-right @if (number_format(($montant - $regle),0,',',' ')>0) text-danger @else @endif font-weight-bold">{{number_format(($montant - $regle),0,',',' ')}}</td>
                                                                    <td class="text-right font-weight-bold"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>{{++$key}}</td>
                                                                    <td>{{$item->date}}</td>
                                                                    <td class="text-center">{{$item->raisonSociale}} <br> ({{$item->telephone}})</td>
                                                                    <td><b>{{$item->zone}}</b></td>
                                                                    <td>{{ $item->code_vente}}</td>
                                                                    <td  class="text-right">{{ $item->montant_vente}}</td>
                                                                    <td  class="text-center">{{ $item->banque}} ({{ $item->numero}})</td>                                                         
                                                                    <td>{{ $item->code_reglement}}</td>
                                                                    <td class="text-right">{{ $item->montant_reglement}}</td>
                                                                    <td></td>
                                                                    <td>{{ $item->recouvreur}}</td>
                                                                </tr> 

                                                                @php($venteId = $item->id_vente)
                                                                @php($montant = $item->montant_vente)
                                                                @php($regle = 0)
                                                                @php($regle = $regle + ($item->montant_reglement))
                                                            @if ($key == session('resultat')['nbre']) 
                                                           
                                                                <tr>
                                                                    <td colspan="5" class="font-weight-bold">Total</td>
                                                                    <td class="text-right font-weight-bold">{{number_format($montant,0,',',' ')}}</td>
                                                                    <td class="text-right font-weight-bold"></td>
                                                                    <td colspan="2" class="text-right font-weight-bold">{{number_format($regle,0,',',' ')}}</td>
                                                                    <td class="text-right font-weight-bold">{{number_format(($montant - $regle),0,',',' ')}}</td>
                                                                    <td class="text-right font-weight-bold"></td>
                                                                </tr>                                                                
                                                            @endif
                                                               
                                                        @endif                                                       
                                                            
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5" class="font-weight-bold text-success ">Total</td>
                                                        <td class="text-right font-weight-bold text-success">{{number_format($montantTotal,0,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold text-success"></td>
                                                        <td colspan="2" class="text-right font-weight-bold text-success">{{number_format($regleTotal,0,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold text-success">{{number_format(($montantTotal - $regleTotal),0,',',' ')}}</td>
                                                        <td class="text-right font-weight-bold text-success"></td>
                                                    </tr>   
                                                    </tbody>
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
                                        @if(count(session('resultat')['reglements']) > 0)
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
