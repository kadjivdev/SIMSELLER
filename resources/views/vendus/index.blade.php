@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="pb-3">PROGRAMMATIONS</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item active">Programmations</li>
                            </ol>
                        </div>
                    </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <!--  <h3 class="card-title"></h3>
                                    <a class="btn btn-success btn-sm" href="">
                                        <i class="fas fa-solid fa-plus"></i>
                                        Ajouter
                                    </a> -->
                                            <div class="col-sm-2 float-md-right">
                                                <form id="statutsForm" action="" method="get">
                                                    <div class="form-group">
                                                        <select class="custom-select form-control" id="statuts" name="statuts" onchange="submitStatuts()">
                                                            <option value="1" {{ $req == 1 ? 'selected':'' }}>Tout</option>
                                                            <option value="2" {{ $req == 2 ? 'selected':'' }}>Non Programmé</option>
                                                            <option value="3" {{ $req == 3 ? 'selected':'' }}>En cours</option>
                                                            <option value="4" {{ $req == 4 ? 'selected':'' }}>Programmé</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>                                    
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Date</th>
                                            <th>Fournisseur</th>
                                            <th>Produit</th>
                                            <th>Qté commander</th>
                                            <th>Qté programmer</th>
                                            <th>Qté Reste</th>
                                            <th>Statut</th>
                                            <th>Pourcentage</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if ($detailboncommandes->count() > 0)
                                            <?php $compteur=1; ?>
                                            @foreach($detailboncommandes as $detailboncommande)
                                                <tr>
                                                        <td>{{ $compteur++ }}</td>
                                                        <td>{{ $detailboncommande->boncommande->code }}</td>
                                                        <td class="text-center">{{ $detailboncommande->boncommande->dateBon?date_format(date_create($detailboncommande->boncommande->dateBon), 'd/m/Y'):'' }}</td>
                                                        <td>{{ $detailboncommande->boncommande->fournisseur->sigle }}</td>
                                                        <td>{{ $detailboncommande->produit->libelle }}</td>
                                                        <td class="text-right">{{ number_format($detailboncommande->qteCommander,2,","," ") }}</td>
                                                        <td class="text-right">{{ number_format(collect($detailboncommande->programmations()->whereIn('statut', ['Valider', 'Livrer'])->get())->sum('qteprogrammer'),2,","," ") }}</td>
                                                        <td class="text-right">{{ number_format(($detailboncommande->qteCommander - collect($detailboncommande->programmations->whereIn('statut', ['Valider', 'Livrer']))->sum('qteprogrammer')),2,","," ") }}</td>
                                                        <td class="text-center">
                                                            @if ( (collect($detailboncommande->programmations->whereIn('statut', ['Valider', 'Livrer']))->sum('qteprogrammer')) == 0)
                                                                <span class="badge badge-danger">Non programmé</span>
                                                            @elseif (floatval($detailboncommande->qteCommander) == floatval((collect($detailboncommande->programmations->whereIn('statut', ['Valider', 'Livrer']))->sum('qteprogrammer'))))
                                                                <span class="badge badge-success">Programmé</span>
                                                            @else
                                                                <span class="badge badge-warning">En cours</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-right text-lg"><b>{{ (intval(collect($detailboncommande->programmations()->whereIn('statut', ['Valider', 'Livrer'])->get())->sum('qteprogrammer'))*100)/intval($detailboncommande->qteCommander) }}%</b></td>
                                                        
                                                        <td class="text-center">
                                                            <a class="btn btn-success btn-sm" href="{{ route('programmations.create', ['detailboncommande'=>$detailboncommande->id]) }}" @if (($detailboncommande->boncommande->statut == 'Programmer') && (floatval($detailboncommande->qteCommander) == floatval((collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qteprogrammer')))))
                                                                disabled="disabled"
                                                            @endif id="programmer"><i class="fa-solid fa-p"></i>  Programmer</a>
                                                            <a class="btn btn-primary btn-sm" href=""><i class="fas fa-print"></i>  Imprimer</a>
                                                            <!--<a class="btn btn-warning btn-sm" href=""><i class="fa-solid fa-pen-to-square"></i></a>
                                                            <a class="btn btn-danger btn-sm" href=""><i class="fa-solid fa-trash-can"></i></a>
                                                            <a class="btn btn-secondary btn-sm" href=""><i class="fa-solid fa-bed"></i></a>
                                                            <a class="btn btn-primary btn-sm" href=""><i class="fas fa-running"></i></a>-->
                                                        </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Date</th>
                                            <th>Fournisseur</th>
                                            <th>Produit</th>
                                            <th>Qté commander</th>
                                            <th>Qté programmer</th>
                                            <th>Qté Reste</th>
                                            <th>Statut</th>
                                            <th>Pourcentage</th>
                                            <th>Action</th>
                                        </tr>
                                        </tfoot>
                                    </table>
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
            function submitStatuts()
            {
                $('#statutsForm').submit();
            }
        </script>
        <script>
            $('#programmer').removeAttr('disabled');
        </script>
    @endsection
