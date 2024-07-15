<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cim Seller | Programme-{{date_format(date_create($debut),'dmY')}} au {{date_format(date_create($fin),'dmY')}}</title>
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
                <address>
                    <strong>{{ $fournisseur->raisonSociale }} ({{ $fournisseur->sigle }})</strong><br>
                    {{ $fournisseur->categoriefournisseur->libelle }}<br>
                    {{ $fournisseur->adresse }}<br>
                    Téléphone: {{ $fournisseur->telephone }}<br>
                    E-mail: {{ $fournisseur->email }}
                </address>
            </div>
        </td>

    </tr>
    <tr >
        <td style="padding-top: 3em">
            <b>OBJET: </b>Programme de chargement du {{date_format(date_create($debut),'d-m-Y')}} au {{date_format(date_create($fin),'d-m-Y')}}<br>
            <br>
        </td>
    </tr>
</table>


<main style="margin-top: 1em">
    <!-- Tableau reçus-->
    <div>

        <table class="col-md-12">
            <thead>
            <tr class="" style="background-color: grey">
                <th class="text-center bordertd" style="padding: 5px" width="3%">#</th>
                <th class="text-center bordertd" style="padding: 5px" width="10%">Camion</th>
                <th class="text-center bordertd" style="padding: 5px" width="20%">Chauffeur</th>
                <th class="text-center bordertd" style="padding: 5px" width="20%" >Produit</th>
                <th class="text-center bordertd" style="padding: 5px" width="20%">Zone</th>
                <th class="text-center bordertd" style="padding: 5px" width="20%">Avaliseur</th>
                <th class="text-center bordertd" style="padding: 5px" width="7%">Quantité (T)</th>
            </tr>
            </thead>
            <tbody>
            @php($total = 0)
            @foreach($programmes as $key=> $programme)
                @php($total += $programme->qteprogrammer)
                <tr>
                    <td class="bordertd" style="padding: 5px">{{$key + 1}}</td>
                    <td class="bordertd" style="padding: 5px">{{$programme->immatriculationTracteur}}</td>
                    <td class="bordertd " style="padding: 5px">{{$programme->chauffeur}}</td>
                    <td class="bordertd " style="padding: 5px">{{$programme->produit}}</td>
                    <td class="bordertd " style="padding: 5px">{{$programme->zone}}</td>
                    <td class="bordertd " style="padding: 5px"> {{$programme->avaliseur}} </td>
                    <td class="bordertd text-right" style="padding: 5px">{{number_format($programme->qteprogrammer,2,',',' ')}}</td>
                </tr>
            @endforeach
            <tr class="bordertr">
                <td colspan="6" class="bordertd" style="padding: 5px"><b>Total</b></td>
                <td class="text-right bordertd" style="padding: 5px"><b>{{number_format($total,2,',',' ')}}</b></td>
            </tr>
            </tbody>
        </table>

    </div>

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
                <td class="text-center" style="padding-top: 2.5em"><h6><h6>{{env('SIGNATAIRE_PROGRAMME')}}</h6></h6></td>
            </tr>
        </table>
        <div style="clear: left"></div>
    </div>

</main>

<footer>
    <div>
        <img style="margin: 0" width="100%" src="{{public_path('dist/img/pied.jpg')}}"  alt="">
    </div>
</footer>

</body>
</html>

