@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">LIVRAISONS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item active">Livraisons</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @if(session('transfert'))
                            <div class="row">
                                <div class="col-md-12 alert-success alert">
                                    <i class="fa fa-check"></i> Transfert de livraison effectué avec succès.
                                </div>
                            </div>
                            @endif
                            @if(session('message'))
                            <div class="row">
                                <div class="col-md-12 alert-success alert">
                                    <i class="fa fa-check"></i> {{session('message')}}
                                </div>
                            </div>
                            @endif
                            @if(session('error'))
                            <div class="row">
                                <div class="col-md-12 alert-dange alert">
                                    <i class="fa fa-check"></i> {{session('error')}}
                                </div>
                            </div>
                            @endif
                            <form id="statutsForm" action="" method="get">
                                <div class="col-sm-2 float-md-right">
                                    <div class="form-group">
                                        <select class="custom-select form-control" id="statuts" name="statuts" onchange="submitStatuts()">
                                            <option value="1" {{ $req == 1 ? 'selected':'' }}>Tout</option>
                                            <option value="2" {{ $req == 2 ? 'selected':'' }}>Livré</option>
                                            <option value="4" {{ $req == 4 ? 'selected':'' }}>Transferer</option>
                                            <option value="3" {{ $req == 3 ? 'selected':'' }}>Non Livré</option>
                                            <option value="5" {{ $req == 5 ? 'selected':'' }}>Annuler</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-2"></div>
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
                            </form>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped table-sm" style="font-size: 11px">
                                <thead class="text-white text-center bg-gradient-gray-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Code Prog</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Produit</th>
                                        <th>Camion</th>
                                        <th>Chauffeur</th>
                                        <th>Zone</th>
                                        <th>Qté Programmée</th>
                                        <th>Qté Livrée</th>
                                        <th>Qté Vendue</th>
                                        <th>Stock camion</th>
                                        <th>Date livraison</th>
                                        <th>BL</th>
                                        <th>Statut</th>
                                        @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()==true || Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()==true)
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($programmations->count() > 0)
                                    <?php $compteur = 1; ?>
                                        @foreach($programmations as $programmation)
                                            @if($programmation->bl)
                                                @if(($programmation->qteprogrammer - $programmation->vendus->sum('qteVendu')) > 0)
                                                <tr>
                                                    <td>{{ $compteur++ }}</td>
                                                    <td>{{ $programmation->detailboncommande->boncommande->code }}</td>
                                                    <td>{{ $programmation->code }}</td>
                                                    <td class="text-center">{{ $programmation->dateprogrammer?date_format(date_create($programmation->dateprogrammer), 'd/m/Y'):'' }}</td>
                                                    <td>{{ $programmation->detailboncommande->boncommande->fournisseur->sigle }}</td>
                                                    <td>{{ $programmation->detailboncommande->produit->libelle }}</td>
                                                    <td>{{ $programmation->camion->immatriculationTracteur }} ({{ $programmation->camion->marque->libelle }})</td>
                                                    <td>{{ $programmation->chauffeur->nom }} {{ $programmation->chauffeur->prenom }} ({{ $programmation->chauffeur->telephone }})</td>
                                                    <td>{{ $programmation->zone->libelle }} ({{ $programmation->zone->departement->libelle }})</td>
                                                    
                                                    <td class="text-right">{{ number_format($programmation->qteprogrammer,2,","," ") }}</td>
                                                    <td class="text-right">{{ number_format($programmation->qtelivrer,2,","," ") }}</td>
                                                    <td class="text-right">{{ number_format($programmation->vendus->sum('qteVendu'),2,","," ") }}</td>

                                                    <!-- <td class="text-right text-danger font-weight-bolder">{{number_format(($programmation->qteprogrammer-$programmation->qtelivrer),2,","," ")}}</td> -->
                                                    <td class="text-right text-danger font-weight-bolder">{{number_format(($programmation->qteprogrammer - $programmation->vendus->sum('qteVendu')),2,","," ")}}</td>

                                                    <td class="text-center"><b>{{ $programmation->datelivrer?date_format(date_create($programmation->datelivrer), 'd/m/Y'):'' }}</b></td>
                                                    <td>
                                                        <b>{{ $programmation->bl_gest?$programmation->bl_gest:$programmation->bl }}</b> 
                                                        @if ($programmation->document)
                                                        <a class="btn btn-success text-white btn-xs float-right" href="{{ $programmation->document?asset('storage/'.$programmation->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        
                                                        @if ($programmation->statut == 'Valider')
                                                        <span class="badge badge-danger">Non Livré</span>
                                                        @elseif (($programmation->statut == 'Livrer') && ($programmation->qteprogrammer == $programmation->qtelivrer))
                                                            @if($programmation->transfert)
                                                            <span class="badge badge-info">Transferer</span>
                                                            @else
                                                            <span class="badge badge-success">Livré</span>
                                                            @endif
                                                        @elseif (($programmation->statut == 'Livrer') && ($programmation->qteprogrammer != $programmation->qtelivrer))
                                                            @if($programmation->transfert)
                                                            <span class="badge badge-info">Transferer</span>
                                                            @else
                                                            <span class="badge badge-warning">Partiellement</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists() == true || Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() == true)
                                                    <td class="text-center p-2">

                                                        @if($programmation->transfert || $programmation->cloture == True)
                                                            @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists() || Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists())
                                                            <!-- <a href="#" data-toggle="modal" data-target="#modal-default" title="Transfert la livraison" class="btn  btn-warning  btn-xs"><i class="fa-solid fa-long-arrow-right" onclick="loadProgrammation({{$programmation->id}})"></i></a> -->
                                                            <a href="#" data-toggle="modal" data-target="#modal-detail" title="Détail transfert" class="btn  btn-success  btn-xs"><i class="fa-solid fa-list" onclick="loadDetailTransfert({{$programmation->id}})"></i></a>
                                                            @endif
                                                        @else
                                                            @if ($programmation->bl || $programmation->cloture == True)
                                                                @if(count($programmation->vendus) == 0)
                                                                <a href="{{ route('livraisons.annulation', ['programmation'=>$programmation->id]) }}" title="Annulation de livraison" class="btn btn-primary  btn-xs"><i class="fa-solid fa-eject"></i></a>
                                                                @endif
                                                                @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists() || Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists())
                                                                    @if(($programmation->qtelivrer - $programmation->vendus->sum('qteVendu')) > 0)
                                                                    <a href="#" data-toggle="modal" data-target="#modal-default" title="Transfert les {{$programmation->qtelivrer - $programmation->vendus->sum('qteVendu')}} T" class="btn  btn-warning  btn-xs"><b>{{$programmation->qtelivrer - $programmation->vendus->sum('qteVendu')}} T</b> <i class="fa-solid fa-long-arrow-right" onclick="loadProgrammation({{$programmation->id}})"></i></a>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if(($programmation->qteprogrammer - $programmation->vendus->sum('qteVendu'))>0)
                                                                    <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                                @endif
                                                            @endif
                                                        @endif

                                                        <!-- @if(($programmation->statut != 'Livrer') || ($programmation->qteprogrammer != $programmation->qtelivrer))
                                                        <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                        @endif -->

                                                        <!-- @if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() && count($programmation->vendus) <> 0 && $programmation->cloture == false)
                                                        <a class="badge badge-dark p-2" title="Clôturer une livraison" href="{{ route('livraisons.cloturer', ['programmation'=>$programmation->id]) }}">cloturer</a>
                                                        @endif -->

                                                        <!-- if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() && count($programmation->vendus) <> 0 && $programmation->cloture == false && $programmation->bl == null)
                                                        <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                        endif  -->

                                                        @if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() && count($programmation->vendus) <> 0)
                                                            @if(!$programmation->cloture)
                                                                <a class="badge badge-dark p-2" title="Clôturer une livraison" href="{{ route('livraisons.cloturer', ['programmation'=>$programmation->id]) }}">cloturer</a>
                                                            @else
                                                                @if(($programmation->qteprogrammer - $programmation->vendus->sum('qteVendu'))>0)
                                                                    <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                    @endif
                                                </tr>
                                                @endif
                                            @else
                                                <tr>
                                                    <td><span class="text-danger"></span> {{ $compteur++ }}</td>
                                                    <td>{{ $programmation->detailboncommande->boncommande->code }}</td>
                                                    <td>{{ $programmation->code }}</td>

                                                    <td class="text-center">{{ $programmation->dateprogrammer?date_format(date_create($programmation->dateprogrammer), 'd/m/Y'):'' }}</td>
                                                    <td>{{ $programmation->detailboncommande->boncommande->fournisseur->sigle }}</td>
                                                    <td>{{ $programmation->detailboncommande->produit->libelle }}</td>
                                                    <td>{{ $programmation->camion->immatriculationTracteur }} ({{ $programmation->camion->marque->libelle }})</td>
                                                    <td>{{ $programmation->chauffeur->nom }} {{ $programmation->chauffeur->prenom }} ({{ $programmation->chauffeur->telephone }})</td>
                                                    <td>{{ $programmation->zone->libelle }} ({{ $programmation->zone->departement->libelle }})</td>

                                                    <td class="text-right">{{ number_format($programmation->qteprogrammer,2,","," ") }}</td>
                                                    <td class="text-right">{{ number_format($programmation->qtelivrer,2,","," ") }}</td>
                                                    <td class="text-right">{{ number_format($programmation->vendus->sum('qteVendu'),2,","," ") }}</td>

                                                    <!-- <td class="text-right text-danger font-weight-bolder">{{number_format(($programmation->qteprogrammer-$programmation->qtelivrer),2,","," ")}}</td> -->
                                                    <td class="text-right text-danger font-weight-bolder">{{number_format(($programmation->qteprogrammer - $programmation->vendus->sum('qteVendu')),2,","," ")}}</td>


                                                    <td class="text-center"><b>{{ $programmation->datelivrer?date_format(date_create($programmation->datelivrer), 'd/m/Y'):'' }}</b></td>
                                                    <td><b>{{ $programmation->bl ? $programmation->bl : $programmation->bl_gest }}</b> @if ($programmation->document)<a class="btn btn-success text-white btn-xs float-right" href="{{ $programmation->document?asset('storage/'.$programmation->document):'' }}" target="_blank"><i class="fa-solid fa-file-pdf"></i></a>@endif</td>

                                                    <td class="text-center">
                                                        @if ($programmation->statut == 'Valider')
                                                        <span class="badge badge-danger">Non Livré</span>
                                                        @elseif (($programmation->statut == 'Livrer') && ($programmation->qteprogrammer == $programmation->qtelivrer))
                                                            @if($programmation->transfert)
                                                            <span class="badge badge-info">Transferer</span>
                                                            @else
                                                            <span class="badge badge-success">Livré</span>
                                                            @endif
                                                        @elseif (($programmation->statut == 'Livrer') && ($programmation->qteprogrammer != $programmation->qtelivrer))
                                                            @if($programmation->transfert)
                                                            <span class="badge badge-info">Transferer</span>
                                                            @else
                                                            <span class="badge badge-warning">Partiellement</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()==true || Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()==true)
                                                    <td class="text-center p-2">

                                                        @if($programmation->transfert || $programmation->cloture == false)
                                                            @if(Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()==false)
                                                            <!-- <a href="#" data-toggle="modal" data-target="#modal-default" title="Transfert la livraison" class="btn  btn-warning  btn-xs"><i class="fa-solid fa-long-arrow-right" onclick="loadProgrammation({{$programmation->id}})"></i></a> -->
                                                            <a href="#" data-toggle="modal" data-target="#modal-detail" title="Détail transfert" class="btn  btn-success  btn-xs"><i class="fa-solid fa-list" onclick="loadDetailTransfert({{$programmation->id}})"></i></a>
                                                            @elseif ($programmation->statut == 'Valider')
                                                            <!-- <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a> -->

                                                            @if(($programmation->qteprogrammer - $programmation->vendus->sum('qteVendu'))>0)
                                                                <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                            @endif

                                                            @endif
                                                        @else
                                                            <!-- @if ($programmation->bl) -->
                                                                @if(count($programmation->vendus) == 0)
                                                                <a href="{{ route('livraisons.annulation', ['programmation'=>$programmation->id]) }}" title="Annulation de livraison" class="btn btn-primary  btn-xs"><i class="fa-solid fa-eject"></i></a>
                                                                @endif
                                                                @if(($programmation->vendus->sum('qteVendu')) == 0 )
                                                                    @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists())
                                                                    <!-- <a href="#" data-toggle="modal" data-target="#modal-default" title="Transfert la livraison" class="btn  btn-warning  btn-xs"><i class="fa-solid fa-long-arrow-right" onclick="loadProgrammation({{$programmation->id}})"></i></a> -->
                                                                    @endif
                                                                @endif
                                                            <!-- @else
                                                                <a target="_blank" class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                            @endif -->
                                                        @endif

                                                        <!-- if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() && count($programmation->vendus) <> 0 && $programmation->cloture == false)
                                                        <a class="badge badge-dark p-2" title="Clôturer une livraison" href="{{ route('livraisons.cloturer', ['programmation'=>$programmation->id]) }}">cloturer</a>
                                                        endif -->

                                                        @if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists() && count($programmation->vendus) != 0)
                                                            @if(!$programmation->cloture)
                                                                <a class="badge badge-dark p-2" title="Clôturer une livraison" href="{{ route('livraisons.cloturer', ['programmation'=>$programmation->id]) }}">cloturer</a>
                                                            @else
                                                                @if(($programmation->qteprogrammer - $programmation->vendus->sum('qteVendu'))>0)
                                                                    <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a>
                                                                @endif
                                                                <!-- <a class="btn btn-success btn-sm btn-xs" title="Livraison de produit" href="{{ route('livraisons.create', ['programmation'=>$programmation->id]) }}"><i class="fa-solid fa-truck-arrow-right"></i></a> -->
                                                            @endif
                                                        @endif
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot class="text-white text-center bg-gradient-gray-dark">
                                <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Code Prog</th>
                                        <th>Date</th>
                                        <th>Fournisseur</th>
                                        <th>Produit</th>
                                        <th>Camion</th>
                                        <th>Chauffeur</th>
                                        <th>Zone</th>
                                        <th>Qté Programmée</th>
                                        <th>Qté Livrée</th>
                                        <th>Qté Vendue</th>
                                        <th>Stock camion</th>
                                        <th>Date livraison</th>
                                        <th>BL</th>
                                        <th>Statut</th>
                                        @if(Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()==true || Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()==true)
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{route('livraisons.transfert')}}" method="post">
                                            @csrf
                                            <div class="modal-header">
                                                <h4 class="modal-title text-center">Transfert de livraison</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row" id="loader">
                                                    <div class="col-md-12 text-center">
                                                        <i class="fa-spin spinner-border"></i><br>
                                                        Chargement...
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 alert alert-danger" id="error" hidden>
                                                        <i class="fa fa-warning"></i> Une erreur interne est survenue lors du chargement des données. Merci de reprendre ou de contacter l'administrateur.
                                                    </div>
                                                </div>
                                                <div class="row mb-2" id="form_modal" hidden>
                                                    <div class="col-md-5">
                                                        <label for="">Zone source</label>
                                                        <input type="hidden" name="id" id="id">
                                                        <input type="hidden" name="prog" id="prog">
                                                        <input type="text" disabled id="zone_souce" class="form-control">
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <i class="fa fa-arrow-alt-circle-right fa-2x text-success" style="margin-top: 0.2em"></i>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="">Zone destination.</label>
                                                        <select name="zone_id" id="zone_dest" class="form-control" required></select>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 mb-2" id="motif" hidden>
                                                    <div class="col-md-12">
                                                        <label for="">Motif de transfert</label>
                                                        <textarea class="form-control" name="observation" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="alert alert-warning" id="warming" hidden>
                                                    <i class="fa fa-warning"></i> Le transfert de la livraison est irreversible. Si vous êtes un résentant, cette livraison quittera votre responsablité.
                                                    êtes vous sur de vouloir transferer cette livraison vers la destination sélectionnée ?
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between" id="btn" hidden>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Oui je confirme le transfert</button>
                                            </div>
                                        </form>

                                    </div>

                                </div>

                            </div>
                            <div class="modal fade" id="modal-detail" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title text-center">Détail transfert</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="row p-3">
                                            <div class="col-md-12">
                                                <table class="table table-bordered" id="detailTransfert">
                                                    <tbody></tbody>

                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer justify-content-between" id="btn">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Oui je confirme le transfert</button>
                                        </div>
                                    </div>

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
</div>
@endsection

@section('script')
<script>
    function submitStatuts() {
        $('#statutsForm').submit();
    }

    function loadProgrammation(id) {
        $('#zone_dest option').remove()
        $('#zone_dest').removeAttr('selected');
        $('#loader').removeAttr('hidden');
        $('#form_modal').attr('hidden', 'hidden')
        $('#warming').attr('hidden', 'hidden')
        $('#btn').attr('hidden', 'hidden')
        $('#motif').attr('hidden', 'hidden')

        let optionsAsString = "<option value=''>--Sélectionnez la zone--</option>";
        axios.get('{{env('APP_BASE_URL ')}}programmation/livraison/' + id).then((response) => {
            console.log(response);
            let zones = response.data.zones;
            for (let i = 0; i < zones.length; i++) {
                optionsAsString += "<option value='" + zones[i].id + "'>" + zones[i].libelle + "</option>";
            }
            $('#zone_dest').append(optionsAsString);
            $('#id').val(response.data.programmation.zone_id)
            $('#prog').val(response.data.programmation.id)
            $('#zone_souce').val(response.data.zone_source)
            $('#form_modal').removeAttr('hidden')
            $('#warming').removeAttr('hidden')
            $('#btn').removeAttr('hidden')
            $('#motif').removeAttr('hidden')
            $('#loader').attr('hidden', 'hidden')

        }).catch(() => {
            $('#loader').attr('hidden', 'hidden');
            $('#error').removeAttr('hidden')
        })
    }

    function loadDetailTransfert(id) {
        $('#detailTransfert > tbody > tr').remove();
        $('#detailTransfert > tbody > tr').empty();
        $('#loader1').removeAttr('hidden');
        axios.get('{{env('APP_BASE_URL ')}}programmation/detail-transfert/' + id).then((response) => {
            let details = response.data;
            let table = document.getElementById("#detailTransfert");

            ligneTableau = `
                         <tr>
                            <th>N°</th>
                            <th>Date</th>
                            <th>Zone source</th>
                            <th>Zone destination</th>
                            <th>Observation</th>
                        </tr>
                    `

            //table.innerHTML = ligneTableau

            $('#detailTransfert > tbody').append(ligneTableau);
            for (let i = 0; i < details.length; i++) {
                ligneTableau = `
                             <tr>
                                <td>${(details[i].compteur+1)}</td>
                                <td>${details[i].date}</td>
                                <td>${details[i].source}</td>
                                <td>${details[i].destination}</td>
                                <td>${details[i].observation}</td>
                            </tr>
                        `
                $('#detailTransfert > tbody').append(ligneTableau);
                /*optionsAsString += "<tr>"
                optionsAsString += "<td>" + (details[i].compteur+1) + "</td>";
                optionsAsString += "<td>" + details[i].date + "</td>";
                optionsAsString += "<td>" +  + "</td>";
                optionsAsString += "<td>" + details[i].destination + "</td>";

                optionsAsString += "</tr>"*/
            }


            $('#loader1').attr('hidden', 'hidden')

        }).catch(() => {
            $('#loader1').attr('hidden', 'hidden');
            $('#error1').removeAttr('hidden')
        })
    }

    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["excel", "pdf", "print"],
            "order": [
                [1, 'asc']
            ],
            "pageLength": 15,
            "columnDefs": [{
                    "targets": 2,
                    "orderable": false
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
            language: {
                "emptyTable": "Aucune donnée disponible dans le tableau",
                "lengthMenu": "Afficher _MENU_ éléments",
                "loadingRecords": "Chargement...",
                "processing": "Traitement...",
                "zeroRecords": "Aucun élément correspondant trouvé",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "previous": "Précédent",
                    "next": "Suiv"
                },
                "aria": {
                    "sortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                },
                "select": {
                    "rows": {
                        "_": "%d lignes sélectionnées",
                        "1": "1 ligne sélectionnée"
                    },
                    "cells": {
                        "1": "1 cellule sélectionnée",
                        "_": "%d cellules sélectionnées"
                    },
                    "columns": {
                        "1": "1 colonne sélectionnée",
                        "_": "%d colonnes sélectionnées"
                    }
                },
                "autoFill": {
                    "cancel": "Annuler",
                    "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                    "fillHorizontal": "Remplir les cellules horizontalement",
                    "fillVertical": "Remplir les cellules verticalement"
                },
                "searchBuilder": {
                    "conditions": {
                        "date": {
                            "after": "Après le",
                            "before": "Avant le",
                            "between": "Entre",
                            "empty": "Vide",
                            "equals": "Egal à",
                            "not": "Différent de",
                            "notBetween": "Pas entre",
                            "notEmpty": "Non vide"
                        },
                        "number": {
                            "between": "Entre",
                            "empty": "Vide",
                            "equals": "Egal à",
                            "gt": "Supérieur à",
                            "gte": "Supérieur ou égal à",
                            "lt": "Inférieur à",
                            "lte": "Inférieur ou égal à",
                            "not": "Différent de",
                            "notBetween": "Pas entre",
                            "notEmpty": "Non vide"
                        },
                        "string": {
                            "contains": "Contient",
                            "empty": "Vide",
                            "endsWith": "Se termine par",
                            "equals": "Egal à",
                            "not": "Différent de",
                            "notEmpty": "Non vide",
                            "startsWith": "Commence par"
                        },
                        "array": {
                            "equals": "Egal à",
                            "empty": "Vide",
                            "contains": "Contient",
                            "not": "Différent de",
                            "notEmpty": "Non vide",
                            "without": "Sans"
                        }
                    },
                    "add": "Ajouter une condition",
                    "button": {
                        "0": "Recherche avancée",
                        "_": "Recherche avancée (%d)"
                    },
                    "clearAll": "Effacer tout",
                    "condition": "Condition",
                    "data": "Donnée",
                    "deleteTitle": "Supprimer la règle de filtrage",
                    "logicAnd": "Et",
                    "logicOr": "Ou",
                    "title": {
                        "0": "Recherche avancée",
                        "_": "Recherche avancée (%d)"
                    },
                    "value": "Valeur"
                },
                "searchPanes": {
                    "clearMessage": "Effacer tout",
                    "count": "{total}",
                    "title": "Filtres actifs - %d",
                    "collapse": {
                        "0": "Volet de recherche",
                        "_": "Volet de recherche (%d)"
                    },
                    "countFiltered": "{shown} ({total})",
                    "emptyPanes": "Pas de volet de recherche",
                    "loadMessage": "Chargement du volet de recherche..."
                },
                "buttons": {
                    "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                    "collection": "Collection",
                    "colvis": "Visibilité colonnes",
                    "colvisRestore": "Rétablir visibilité",
                    "copy": "Copier",
                    "copySuccess": {
                        "1": "1 ligne copiée dans le presse-papier",
                        "_": "%ds lignes copiées dans le presse-papier"
                    },
                    "copyTitle": "Copier dans le presse-papier",
                    "csv": "CSV",
                    "excel": "Excel",
                    "pageLength": {
                        "-1": "Afficher toutes les lignes",
                        "_": "Afficher %d lignes"
                    },
                    "pdf": "PDF",
                    "print": "Imprimer"
                },
                "decimal": ",",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                "infoThousands": ".",
                "search": "Rechercher:",
                "thousands": ".",
                "infoFiltered": "(filtrés depuis un total de _MAX_ éléments)",
                "datetime": {
                    "previous": "Précédent",
                    "next": "Suivant",
                    "hours": "Heures",
                    "minutes": "Minutes",
                    "seconds": "Secondes",
                    "unknown": "-",
                    "amPm": [
                        "am",
                        "pm"
                    ],
                    "months": [
                        "Janvier",
                        "Fevrier",
                        "Mars",
                        "Avril",
                        "Mai",
                        "Juin",
                        "Juillet",
                        "Aout",
                        "Septembre",
                        "Octobre",
                        "Novembre",
                        "Decembre"
                    ],
                    "weekdays": [
                        "Dim",
                        "Lun",
                        "Mar",
                        "Mer",
                        "Jeu",
                        "Ven",
                        "Sam"
                    ]
                },
                "editor": {
                    "close": "Fermer",
                    "create": {
                        "button": "Nouveaux",
                        "title": "Créer une nouvelle entrée",
                        "submit": "Envoyer"
                    },
                    "edit": {
                        "button": "Editer",
                        "title": "Editer Entrée",
                        "submit": "Modifier"
                    },
                    "remove": {
                        "button": "Supprimer",
                        "title": "Supprimer",
                        "submit": "Supprimer",
                        "confirm": {
                            "1": "etes-vous sure de vouloir supprimer 1 ligne?",
                            "_": "etes-vous sure de vouloir supprimer %d lignes?"
                        }
                    },
                    "error": {
                        "system": "Une erreur système s'est produite"
                    },
                    "multi": {
                        "title": "Valeurs Multiples",
                        "restore": "Rétablir Modification",
                        "noMulti": "Ce champ peut être édité individuellement, mais ne fait pas partie d'un groupe. ",
                        "info": "Les éléments sélectionnés contiennent différentes valeurs pour ce champ. Pour  modifier et "
                    }
                }
            },
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection