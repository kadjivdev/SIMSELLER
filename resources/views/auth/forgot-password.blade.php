<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>CIM SELLER | Mot de passe oublié</title>

            <!-- Font Awesome -->
            <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

            <!-- Theme style -->
            <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

            <!-- icheck bootstrap -->
            <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        </head>
        <body class="hold-transition login-page">
            <div class="login-box">
                <div class="card card-outline card-primary">
                    <div class="card-header text-center">
                        <a href="{{ route('login') }}" class="h1"><b>Cim Seller</b></a>
                    </div>
                    <div class="card-body">
                        <p class="login-box-msg">Avez vous oublié votre mot de passe? Saisissez votre e-mail pour récupérer votre mot de passe.</p>
                        @if($message = session('status'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                Votre motre de réinitialisation de mot de passe a été prise en compte. Veuillez consulter votre
                                compte mail pour la suite du processus.
                            </div>
                        @endif
                        @error('email'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-times"></i> Alert!</h5>
                                {{ $message }}
                            </div>
                        @enderror
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="input-group mb-3">
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block">Demande un nouveau mot de passe</button>
                                </div>
                            </div>
                        </form>
                        <p class="mt-3 mb-1">
                            <a href="{{ route('login') }}">Connexion</a>
                        </p>
                    </div>
                    <!-- /.login-card-body -->
                </div>
            </div>
<!-- /.login-box -->

            <!-- jQuery -->
            <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>


            <!-- Bootstrap 4 -->
            <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

            <!-- AdminLTE App -->
            <script src="{{ asset('dist/js/adminlte.js') }}"></script>
        </body>
    </html>
