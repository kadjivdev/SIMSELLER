@extends('layouts.app')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="pb-3">LISTE DES CAMION AYANT PARTICIPER A UNE VENTE.</h1>
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
                                <form method="post" id="form_bc" action="{{route('edition.postVenteCamion')}}">
                                    @csrf
                                    <div class="row no-print" >
                                        <div class="col-1"></div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="">VENTE</label>
                                                <select  id="client" class="form-control form-control-sm select2"  name="id">
                                                    @foreach($ventes as $vente)
                                                        <option value="{{$vente->id}}" {{old('vente')==$vente->id?'selected':''}}>{{$vente->code}}</option>
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
                                        @if(count(session('resultat')['ventesCa']) > 0)

                                            <div class="col-md-12">
                                                <h4 class="col-12 text-center border border-info p-2 mb-2">
                                                   
                                                </h4>
                                                <table class="table table-bordered table-striped table-sm mt-2"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>ImmatriculationTracteur</th>
                                                        <th>Nom Chauffeur</th>
                                                        <th>Prenom Chauffeurs</th>
                                                        <th>Telephone Chauffeur</th>
                                                       
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php($cpt = 0)
                                                    @php($stock = 0)
                                                    @php($livre = 0)
                                                    @php($cder = 0)
                                                    @foreach(session('resultat')['ventesCa'] as $key=>$item)
                                                        <tr>
                                                            @php($cpt++)
                                                            
                                                            <td>{{$cpt}}</td>
                                                            <td>{{$item->immatriculationTracteur}}</td>
                                                            <td>{{$item->nom}}</td>
                                                            <td>{{$item->prenom}}</td>
                                                            <td>{{$item->telephone}}</td>
                                                        </tr>
                                                    @endforeach
                                                    
                                                    
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
                                <div class="card-footer text-center no-print">
                                    @if(session('resultat'))
                                        @if(count(session('resultat')['ventesCa']) > 0)
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
