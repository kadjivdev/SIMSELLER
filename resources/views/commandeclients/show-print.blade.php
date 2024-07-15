<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cim Seller | Bon commande</title>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>
<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <div class="row">
                <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- title row -->
                    <div class="row">
                    <div class="col-12">
                        <h4>
                        <i class="fas fa-globe"></i> AdminLTE, Inc.
                        <small class="float-right">Date: {{ date_format(date_create($boncommandes->dateBon), 'd/m/Y') }}</small>
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
                        A
                        <address>
                            <strong>{{ $boncommandes->fournisseur->raisonSociale }} ({{ $boncommandes->fournisseur->sigle }})</strong><br>
                        {{ $boncommandes->fournisseur->categoriefournisseur->libelle }}<br>
                        {{ $boncommandes->fournisseur->adresse }}<br>
                        Téléphone: {{ $boncommandes->fournisseur->telephone }}<br>
                        E-mail: {{ $boncommandes->fournisseur->email }}
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                        <b>BON DE COMMANDE N° {{ $boncommandes->code }}</b><br>
                        <br>
                        <b>Statut:</b> {{ $boncommandes->statut }}<br>
                        <b>Dévise:</b> Franc CFA<br>
                        <b>Type commande:</b> {{ $boncommandes->typecommande->libelle }}<br>
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
                            @foreach($detailboncommandes as $detailboncommande)
                            <tr>
                                <td>{{ $detailboncommande->qteCommander }}</td>
                                <td>{{ $detailboncommande->produit->libelle }}</td>
                                <td>{{ $detailboncommande->pu }}</td>
                                <td>{{ $detailboncommande->remise }}</td>
                                <td>{{ number_format($detailboncommande->qteCommander*$detailboncommande->pu,2,","," ") }}</td>
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
                            <td>{{ number_format($boncommandes->montant,2,","," ") }}</td>
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

                </div>
                <!-- /.invoice -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section>
        <!-- /.content -->
      </div>
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>

