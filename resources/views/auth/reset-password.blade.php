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
                        <p class="login-box-msg">Vous n'êtes qu'à un pas de votre nouveau mot de passe, récupérez votre mot de passe maintenant.</p>
                        @if($message = session('status'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                {{ $status }}
                            </div>
                        @endif
                        @if($message = session('errors'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                {{ $errors }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <div class="input-group mb-3">
                                    <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input id="password" type="password" name="password" required >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input  id="password_confirmation" type="password" name="password_confirmation" required class="form-control" placeholder="Confirm Password">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-block">Changer le mot de passe</button>
                                    </div>
                                    <!-- /.col -->
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
