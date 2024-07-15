<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification de Programmation de Camions</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: rgba(215, 184, 29, 0.56); /* Jaune foncé */
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            max-width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px; /* Diminuer la taille de la police */
            font-size: 14px; /* Diminuer la taille de la police */
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        p {
            margin-bottom: 10px;
        }

        .logo-container {
            text-align: center;
        }

        .logo {
            width: 35%;
            border-radius: 10%;
            overflow: hidden;
            margin: 0 auto 20px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            background-color: #4caf50; /* Couleur verte */
            color: #ffffff;
            border-radius: 5px;
            font-weight: bold;
        }

        .signature {
            margin-top: 20px;
            text-align: center;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo-container">
        <div class="logo">
            <!-- Exemple de lien d'image provenant d'internet -->
            <img src="{{asset('dist/img/kadjiv.jpeg')}}" alt="Logo de l'entreprise">
        </div>
    </div>

    <h5>Bonjour {{$destinataire['nom']}},</h5>

    <p>{!! $message_html !!}</p>
    <table>
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Date</th>
                    <th>Produit</th>
                    <th>Client</th>
                    <th>Quantité</th>
                    <th>Montant</th>
                    <th>Transport</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <!-- Remplacez les données ci-dessous par vos propres informations -->
                <tr>
                    <td>{{$vente->code}}</td>
                    <td>{{date_format(date_create($vente->date),'d/m/Y')}}</td>
                    <td>{{$vente->produit->libelle}}</td>
                    <td>{{$vente->commandeclient->client->raisonSociale}}</td>
                    <td>{{$vente->vendus()->sum('qteVendu') && $vente->vendus()->sum('qteVendu')}}</td>
                    <td>{{number_format($vente->montant,2,","," ")}}</td>
                    <td>{{number_format($vente->transport,2,","," ")}}</td>
                    <td>{{number_format($vente->montant+$vente->transport,2,","," ")}}</td>
                </tr>
                </tbody>
            </table>
    <p>Nous vous invitons à faire diligence pour permettre à la comptabilité de traiter les ventes dans de bon délais.</p>
    <div class="button-container">
        <a href="{{$lienAction}}" class="button">Cliquez ici </a>
    </div>

    <p>Merci et meilleures salutations,</p>

</div>

<p class="signature">
    &copy CIMSELLER {{date('Y')}}<br>
    KADJIV SARL
</p>

</body>
</html>
