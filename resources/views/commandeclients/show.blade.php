@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>BON DE COMMANDES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('boncommandes.index') }}">Bon de Commandes</a></li>
                            <li class="breadcrumb-item active">Détail bons de commande</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <i class="fas fa-globe"></i> AdminLTE, Inc.
                                        <small class="float-right">Date:
                                            {{ date_format(date_create($commandeclient->dateBon), 'd/m/Y') }}</small>
                                    </h4>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        <strong>Admin, Inc.</strong><br>
                                        795 Folsom Ave, Suite 600<br>
                                        San Francisco, CA 94107<br>
                                        Phone: (804) 123-5432<br>
                                        Email: info@almasaeedstudio.com
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    COMMANDE DE
                                    <address>
                                        <strong>
                                            {{--  @if ($commandeclient->client->typeclient->parent->libelle == env('TYPE_CLIENT_P')) --}}
                                            {{ $commandeclient->client->nom }} {{ $commandeclient->client->prenom }}
                                            {{--  @else --}}
                                            {{ $commandeclient->client->raisonSociale }}
                                            {{--  @endif --}}
                                        </strong><br>
                                        {{ $commandeclient->client->adresse }}<br>
                                        {{ $commandeclient->client->adresse }}<br>
                                        Téléphone: {{ $commandeclient->client->telephone }}<br>
                                        E-mail: {{ $commandeclient->client->email }}
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <b>BON DE COMMANDE N° {{ $commandeclient->code }}</b><br>
                                    <br>
                                    <b>Statut:</b> {{ $commandeclient->statut }}<br>
                                    <b>Dévise:</b> Franc CFA<br>
                                    <b>Type commande:</b> {{ $commandeclient->typecommande->libelle }}<br>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Qté</th>
                                                <th>Produit</th>
                                                <th>PU</th>
                                                <th>Remise</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detailCommandes as $detailCommande)
                                                <tr>
                                                    <td>{{ $detailCommande->produit->libelle }}</td>
                                                    <td>{{ $detailCommande->qteCommander }}</td>
                                                    <td>{{ $detailCommande->pu }}</td>
                                                    <td>{{ $detailCommande->remise }}</td>
                                                    <td>{{ number_format($detailCommande->qteCommander * $detailCommande->pu - $detailCommande->remise, 2, ',', ' ') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <div class="row">
                                <!-- accepted payments column -->
                                <div class="col-6">
                                    <!--    <p class="lead">Payment Methods:</p>
                                    <img src="../../dist/img/credit/visa.png" alt="Visa">
                                    <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                                    <img src="../../dist/img/credit/american-express.png" alt="American Express">
                                    <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

                                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                                    plugg
                                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                                    </p>-->
                                </div>
                                <!-- /.col -->
                                <div class="col-6">
                                    <!-- <p class="lead">Amount Due 2/22/2014</p> -->

                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Total TTC:</th>
                                                <td>{{ number_format($commandeclient->montant, 2, ',', ' ') }}</td>
                                            </tr>
                                            <!--<tr>
                                        <th>Tax (9.3%)</th>
                                        <td>$10.34</td>
                                        </tr>
                                        <tr>
                                        <th>Shipping:</th>
                                        <td>$5.80</td>
                                        </tr>
                                        <tr>
                                        <th>Total:</th>
                                        <td>$265.24</td>
                                        </tr>-->
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <!-- this row will not appear when printing -->
                            <div class="row no-print justify-content-center">
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-3">
                                    <a href="{{ route('commandeclients.index') }}" class="btn btn-secondary btn-block"
                                        style="margin-right: 5px;">
                                        <i class="fa-solid fa-angles-left"></i> Retour
                                    </a>
                                </div>
                                <div class="col-sm-3">
                                </div>
                            </div>
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>

    </div>
@endsection
