<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cim Seller | Bon commande</title>
    <style>
        /**
            Set the margins of the page to 0, so the footer and the header
            can be of the full height and width !
         **/
        @page {
            margin: 0cm 0cm;
            font-size: 12px;
        }
        /** Define now the real margins of every page in the PDF **/
        body main {
            margin: 1cm 1cm;
        }
        .alignement {
            margin: 1cm 1cm;
        }
        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }
        .bordertd{
            border-width: thin;
            border-style: solid;
            border-color: #0a0a0a;
        }
        .bordertddetail{
            border-width: 0.01em;
            border-style: solid;
            border-bottom: 0px;
            border-top: 0px;
        }
        .bordertr{
            border-width: 0.01em;
            border-style: solid;
            border-color: #0a0a0a;
        }
        .copie{
            font-size: 30px;
            color: red;
            border: red double 1px ;
            padding: 0.3em;
            margin-left: 5.0em;
        }
        .copie-soustitre{
            font-size: 30px;
            color: red;

            padding: 0.3em;
            margin-left: 6.2em;
        }

        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }
        .col-md-6,
        .col-md-12 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
        }
        .col-md-12 {
            width: 100%;
        }
        .col-md-6 {
            width: 50%;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        td,
        th {
            padding: 0;
        }
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit;
        }
        h1 small,
        h2 small,
        h3 small,
        h4 small,
        h5 small,
        h6 small,
        .h1 small,
        .h2 small,
        .h3 small,
        .h4 small,
        .h5 small,
        .h6 small,
        h1 .small,
        h2 .small,
        h3 .small,
        h4 .small,
        h5 .small,
        h6 .small,
        .h1 .small,
        .h2 .small,
        .h3 .small,
        .h4 .small,
        .h5 .small,
        .h6 .small {
            font-weight: 400;
            line-height: 1;
            color: #777777;
        }
        h1,
        .h1,
        h2,
        .h2,
        h3,
        .h3 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        h1 small,
        .h1 small,
        h2 small,
        .h2 small,
        h3 small,
        .h3 small,
        h1 .small,
        .h1 .small,
        h2 .small,
        .h2 .small,
        h3 .small,
        .h3 .small {
            font-size: 65%;
        }
        h4,
        .h4,
        h5,
        .h5,
        h6,
        .h6 {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        h4 small,
        .h4 small,
        h5 small,
        .h5 small,
        h6 small,
        .h6 small,
        h4 .small,
        .h4 .small,
        h5 .small,
        .h5 .small,
        h6 .small,
        .h6 .small {
            font-size: 75%;
        }
        h1,
        .h1 {
            font-size: 36px;
        }
        h2,
        .h2 {
            font-size: 30px;
        }
        h3,
        .h3 {
            font-size: 24px;
        }
        h4,
        .h4 {
            font-size: 18px;
        }
        h5,
        .h5 {
            font-size: 14px;
        }
        h6,
        .h6 {
            font-size: 12px;
        }


    </style>

</head>
<body>
<div>
    <img style="margin: 0" width="100%" src="{{public_path('dist/img/entet.jpg')}}"  alt="">
</div>

<table  class="col-md-12 alignement">
    <tr style="margin-top: 1.5em">
        <td width="40%">

        </td>
        <td width="60%"  class="" style="vertical-align: top; padding: 8px"><i><b>Cotonou, le {{$dateFormater}}</b></i></td>
    </tr>
    <tr class="">
        <td width="50%" class="text-center">

        </td>
        <td width="50%">
            <div style="margin-top: 1.2em; padding: 8px">
                A <br><br>
                <address style="">
                    <strong>{{ $boncommandes->fournisseur->raisonSociale }} ({{ $boncommandes->fournisseur->sigle }})</strong><br>
                    {{ $boncommandes->fournisseur->categoriefournisseur->libelle }}<br>
                    {{ $boncommandes->fournisseur->adresse }}<br>
                    Téléphone: {{ $boncommandes->fournisseur->telephone }}<br>
                    E-mail: {{ $boncommandes->fournisseur->email }}
                </address>
            </div>
        </td>

    </tr>
    <tr >
        <td style="padding-top: 3em">
            <b>BON DE COMMANDE N° {{ $boncommandes->code }}</b><br>
            <br>
        </td>
    </tr>
</table>

<main style="margin-top: 1em">
    <!--Titre liste recus-->
    <div style="margin-top: 0.2em">
        <table class="col-md-12 text-center">
            <tr>
                <td><h6>DETAILS COMMANDE</h6>
                    <hr style="margin-top: -0.5em"></td>
            </tr>
        </table>
    </div>
    <!--Fin titre-->

    <!-- Tableau reçus-->
    <div>

        <table class="col-md-12">
                <thead>

                <tr class="" style="background-color: grey">
                    <th class="text-center bordertd" style="padding: 5px" width="39%">Produit</th>
                    <th class="text-center bordertd" style="padding: 5px" width="13%">Qté</th>
                    <th class="text-center bordertd" style="padding: 5px" width="13%" >PU</th>
                    <th class="text-center bordertd" style="padding: 5px" width="15%">Remise</th>
                    <th class="text-center bordertd" style="padding: 5px" width="20%">Montant</th>

                </tr>
                </thead>
                <tbody>
                @php($total = 0)
                @foreach($detailboncommandes as $detailboncommande)
                    @php($total += $detailboncommande->qteCommander*$detailboncommande->pu)
                    <tr>
                        <td class="bordertd" style="padding: 5px">{{ $detailboncommande->produit->libelle }}</td>
                        <td class="bordertd text-right" style="padding: 5px">{{ number_format($detailboncommande->qteCommander,2,',',' ') }}</td>
                        <td class="bordertd text-right" style="padding: 5px">{{ number_format($detailboncommande->pu,2,',',' ') }}</td>
                        <td class="bordertd text-right" style="padding: 5px">{{ number_format($detailboncommande->remise,2,',',' ') }}</td>
                        <td class="bordertd text-right" style="padding: 5px">{{ number_format($detailboncommande->qteCommander*$detailboncommande->pu,2,","," ") }}</td>
                    </tr>
                @endforeach
                <tr class="bordertr">
                    <td colspan="4" class="bordertd" style="padding: 5px"><b>Total</b></td>
                    <td class="text-right bordertd" style="padding: 5px"><b>{{number_format($total,0,',',' ')}}</b></td>
                </tr>
                </tbody>
            </table>

    </div>
    <!--  total -->
    <div style="margin-top: 0.9em">
        <table class="col-md-12">
            <tr>
                <td>
                    <u>Montant commande :</u> &nbsp;<b><i>{{ucwords($formater->format(number_format($total,0,',','')))}} ({{number_format($total,0,',',' ')}}) F CFA</i></b>
                </td>
            </tr>
        </table>
    </div>
    <!-- Fin total-->
    <div style="margin-top: 3em;">
        <table style="float: left; margin-left: 1.5em" >
            <tr>
                <td class="text-center">
                    <img src="data:image/png;base64, {!! $qrcode !!}">
                </td>
            </tr>
        </table>
        <table class="" style="float: right">
            <tr>
                <td class="text-center"><h6>Signature</h6></td>
            </tr>
            <tr>
                <td class="text-center"></td>
            </tr>
            <tr>
                <td class="text-center" style="padding-top: 2.5em"><h6><h6>{{env('SIGNATAIRE_COMMANDE')}}</h6></h6></td>
            </tr>
        </table>
        <div style="clear: left"></div>
    </div>
    @if(!in_array($boncommandes->statut,['Valider','Programmer']))
    <table class="col-md-12 mt-5" style="transform: rotate(-30deg)">
        <tr>
            <td class="text-center text-gray">
                <h3 class="" style="color: grey"><i>Commande en préparation</i></h3>
            </td>
        </tr>
    </table>
    @endif

</main>

<footer>
    <div>
        <img style="margin: 0" width="100%" src="{{public_path('dist/img/pied.jpg')}}"  alt="">
    </div>
</footer>

</body>
</html>

