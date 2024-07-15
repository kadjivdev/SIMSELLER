<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> CIM SELLER </title>

    <style>
        .login-box {
            text-align: center;
            margin: auto;
        }

        img {
            height: 10%;
            border-radius: 10px;
            width: 35%;
            margin: 10px
        }

        .card {
            border: 2px #eaede0 solid;
            border-radius: 5px;
            width: 35%;
            text-align: center;
            box-shadow: 3px 6px 3px #83838253
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.685);
            border-radius: 5px;
        }

        .buton {

            color: black;
            text-align: center;
            background-color: rgb(243, 213, 16);
            padding: 5px;
            border-radius: 55px;
            margin: 10px;
            box-shadow: 3px 6px 3px #83838230;
            text-decoration-line: none;
            font-size: 19px;
            font-weight: bold;

        }
    </style>

</head>

<body class="" style="text-align: center">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card ">
            <div class="card-header text-center ">
                <img src="https://kadjivsarl.com/images/kadjiv.jpeg" class="profile-user-img img-flat  img-circle"
                    alt=""><br>
                <hr>
            </div>
            <div class="card-body">
                <h3>Cim Seller</h3>

                Ceci est votre Mots de passe par defaut. <br>
                <b>
                    <h2>{{ $password }}</h2>
                </b><br>
                <a class="buton" href="https://kadjivsarl.com/login"> connexion</a><br><br>
                <small><u>NB:</u> <span style="color: rgb(236, 63, 50)">Veuillez modifié le mots passe après la premier
                        connexion.</span></small>
            </div>


        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

</body>

</html>
