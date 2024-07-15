@extends('layouts.app')

@section('content')
<!-- <template> -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mt-3">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tableau de Bord</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item active">Tableau de Bord</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <hr>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6" style="border-right: 1px solid;">
                    <!-- Small boxes (Stat box) -->
                    <h3 class="text-center">APPROVISIONNEMENT</h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{$boncommandesP}}</h3>
                                    <p>Commande en préparation.</p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-solid fa-cart-shopping"></i>
                                </div>
                                <a href="{{ route('boncommandes.index') }}" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i> </a>
                            </div>
                        </div>
                        <!-- ./col -->
        
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $boncommandesV }}</h3>
        
                                    <p>Bon de Commande Validé</p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fas fa-solid fa-cart-shopping"></i>
                                </div>
                                <a href="{{ route('boncommandes.index') }}" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i> @if($sansRecu > 0) <h2 class="badge badge-warning float-lg-right small-box-footer" style="margin-top: 3px; margin-right: 5px">Sans reçu : {{$sansRecu}}</h2> @endif</a>
                            </div>
                        </div>
        
                        <!-- ./col -->
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-dark">
                                <div class="inner">
                                    <h3>{{ $produitNP }}</h3>
        
                                    <p>Camion en attente.</p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa-brands fa-product-hunt"></i>
                                </div>
                                <a href="{{ route('livraisons.index') }}?statuts=3" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $nbrLiv }} | {{$qteLiv}} T</h3>
        
                                    <p>Camion livré | Quantité</p>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon far fa-solid fa-truck"></i>
                                </div>
                                <a href="{{ route('edition.stock') }}" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                </div>
                <div class="col-md-6">
                    <h3 class="text-center">VENTE</h3>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{number_format($vente,0,'',' ')}}</h3>
        
                                    <p>Point vente semaine</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="{{ route('edition.etatventeperiode') }}" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{$cde}}</h3>
        
                                    <p>Commandes clients de la semaine</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{route('commandeclients.index')}}" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{$client}}</h3>
        
                                    <p>Client à relancer</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="{{route('edition.revouvrement')}}" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-md-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{number_format($impayer,0,'',' ')}}</h3>
        
                                    <p>Impayés</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="{{route('edition.revouvrement')}}" class="small-box-footer">En savoir plus <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                </div>
            </div>
            <hr>
            <!-- /.row -->
            <!-- Main row -->

            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- </template> -->

<script>
    export default {

        data() {

            return {
                message: "Tableau de bord",
            }
        }
    }
</script>

<style scoped>

</style>

@endsection