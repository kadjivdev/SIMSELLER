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
                        <img src="{{asset('dist/img/kadjiv.jpeg')}}" class="profile-user-img img-flat  img-circle" alt="" style="border-radius: 5px; width: 60%"><br>
                        <b>Cim Seller</b>
                    </div>
                    <div class="card-body">
                        <p class="login-box-msg">
                            @if(auth()->user()->pwd_change)
                                Veuillez renseigner votre nouveau mot de passe.
                            @else
                                Ceci est votre première connexion. Veuillez modifier votre mot de passe.
                            @endif
                        </p>
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
                                @error('password')
                                {{ $message }}
                                @enderror
                            </div>
                        @endif
                        <form method="POST" action="{{ route('postpwd') }}">
                            @csrf
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                                <div class="input-group mb-3">
                                    <input id="email" type="email" disabled name="email" value="{{\Illuminate\Support\Facades\Auth::user()->email}}" required  class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input id="password" type="password" placeholder="nouveau mot de passe" class="form-control" name="password" required >
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
                        <div class="row mt-3 mb-1 text-center">
                            <div class="@if(auth()->user()->pwd_change) col-6 @else col-12 @endif">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    {{ __('Deconnexion') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                            @if(auth()->user()->pwd_change)
                            <div class="col-6">
                                <a class="nav-link" href="{{route('dashboard')}}">Annuler</a>
                            </div>
                            @endif

                        </div>
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
