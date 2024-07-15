@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1> COMPAGNIES ASSURANCES</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('typedetailrecus.index') }}"> Compagnie Assurances</a></li>
                            <li class="breadcrumb-item active">Modification Compagnie Assurance</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Modification Compagnie <b class="text-warning">{{$compagnieassurance->libelle}}</b></h3>
                            </div>
                            <form method="POST" action="{{ route('compagnieassurances.update', ['compagnieassurance'=>$compagnieassurance->id]) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" id="libelle" class="form-control form-control-sm @error('libelle') is-invalid @enderror" name="libelle" style="text-transform: uppercase"  value="{{ @old('libelle')? @old('libelle'):$compagnieassurance->libelle }}"  autocomplete="libelle" autofocus required>
                                                @error('libelle')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <div class="col-sm-4">
                                        <a href="{{ route('compagnieassurances.index') }}" class="btn btn-sm btn-secondary btn-block">
                                            <i class="fa-solid fa-circle-left mr-1"></i>
                                            {{ __('Retour') }}
                                        </a>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-warning btn-block">Modifier
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </section>
    </div>

@endsection;
