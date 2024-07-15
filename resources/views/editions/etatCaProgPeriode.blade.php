@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3"> ETAT DES CAMIONS </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                            <li class="breadcrumb-item active">Etat des camions</li>
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
                                <form method="post" id="form_bc" action="{{route('edition.postEtatCaProgPeriode')}}">
                                    @csrf
                                    <div class="row no-print" >
                                        <div class="col-1"></div>
                                        
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="">Situation Camion</label>
                                                <select  id="charger" class="form-control  select2"  name="charger">
                                                    <option class="" value="" selected>Tous</option>
                                                    <option value="2" {{old('charger') == 2 ? 'selected':''}} >Camions chargés</option>
                                                    <option value="1" {{old('charger') == 1 ? 'selected':''}} >Camions non chargés</option>
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
                                        <div class="col-1"></div>
                                    </div>
                                </form>

                                <div class="row">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['programmations']) > 0)

                                            <div class="col-md-12">
                                                <h4 class="col-12 text-center border border-info p-2 mb-2">
                                                    Point des camions programmer au cours d'une la période du {{date_format(date_create(session('resultat')['debut']),'d/m/Y')}}  au {{date_format(date_create(session('resultat')['fin']),'d/m/Y')}}
                                                </h4>
                                                <table class="table table-bordered table-striped table-sm mt-2"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Code</th>
                                                        <th>date</th>
                                                        <th> Camion</th>
                                                        <th>Chauffeur</th>
                                                        <th>Avaliseur</th>
                                                        <th>Quantité</th>
                                                        <th>Zone</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                   
                                                    @foreach(session('resultat')['programmations'] as $key=>$item)
                                                        <tr>
                                                            
                                                            <td>{{++$key}}</td>
                                                            <td>{{$item->code}}</td>
                                                            <td>{{date_format(date_create($item->dateprogrammer),'d/m/Y')}}</td>
                                                            <td> {{$item->camion}}</td>
                                                            <td>{{$item->nom_chauffeur}}  {{$item->prenom_chauffeur}}</td>
                                                            <td>{{$item->nom_avaliseur}}  {{$item->prenom_avaliseur}}</td>
                                                            <td>{{$item->qteprogrammer}}</td>
                                                            <td>{{$item->libelle}}</td>
                                                            
                                                            <td class="text-center font-weight-bold">
                                                                @if ($item->statut == 'Valider')
                                                                     <span class="badge bg-success"> {{$item->statut}}</span>        
                                                                @endif
                                                                @if ($item->statut == 'Livrer')
                                                                     <span class="badge bg-primary"> {{$item->statut}}</span>        
                                                                @endif
                                                               
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                  
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
                                @if(!(Auth::user()->roles()->where('libelle', ['CONTROLEUR'])->exists() || Auth::user()->roles()->where('libelle', ['VALIDATEUR'])->exists() || Auth::user()->roles()->where('libelle', ['SUPERVISEUR'])->exists()))
                                <div class="card-footer text-center no-print">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['programmations']) > 0)
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
