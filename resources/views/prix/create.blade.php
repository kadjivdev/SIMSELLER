@extends('layouts.app')



@section('content')
    <div class="content-wrapper">

        <section class="content-header">

            <div class="container-fluid">

                <div class="row mb-2">

                    <div class="col-sm-6">

                        <h1>PRIX</h1>

                    </div>

                    <div class="col-sm-6">

                        <ol class="breadcrumb float-sm-right">

                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>

                            <li class="breadcrumb-item"><a href="{{ route('prix.index') }}">Prix</a></li>

                            <li class="breadcrumb-item active">Nouveau Prix</li>

                        </ol>

                    </div>

                </div>

            </div><!-- /.container-fluid -->

        </section>





        <section class="content">

            <div class="container">

                <div class="row">

                    <div class="col-md-2"></div>

                    <div class="col-md-8">

                        <div class="card card-secondary">

                            <div class="card-header">

                                <h3 class="card-title">Nouveau prix</h3>

                            </div>

                            <form method="POST" action="{{ route('prix.store') }}">

                                @csrf

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-sm-6">


                                            <div class="form-group">

                                                <label>Date de Prise d'Effet<span class="text-danger">*</span></label>

                                                <input type="Date" id="datePriseEffet"
                                                    class="form-control form-control-sm @error('datePriseEffet') is-invalid @enderror"
                                                    name="datePriseEffet" style="text-transform: uppercase"
                                                    value="{{ old('datePriseEffet') }}" autocomplete="datePriseEffet" autofocus required>

                                                @error('datePriseEffet')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>


                                        </div>

                                        <div class="col-sm-6">


                                            <div class="form-group">

                                                <label>Date de Fin<span class="text-danger">*</span></label>

                                                <input type="Date" id="dateFin"
                                                    class="form-control form-control-sm @error('dateFin') is-invalid @enderror"
                                                    name="dateFin" style="text-transform: uppercase"
                                                    value="{{ old('dateFin') }}" autocomplete="dateFin" autofocus required>

                                                @error('dateFin')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>


                                        </div>


                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6">


                                            <div class="form-group">

                                                <label>Prix<span class="text-danger">*</span></label>

                                                <input type="number" id="prix"
                                                    class="form-control form-control-sm @error('prix') is-invalid @enderror"
                                                    name="prix" style="text-transform: uppercase"
                                                    value="{{ old('prix') }}" autocomplete="prix" autofocus required>

                                                @error('prix')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>


                                        </div>

                                        <div class="col-sm-6">

                                            <div class="form-group">

                                                <label>Zone<span class="text-danger">*</span></label>

                                                <select
                                                    class="select2 form-control form-control-sm @error('zone_id') is-invalid @enderror"
                                                    name="zone_id" style="width: 100%;">

                                                    <option selected disabled>** choisissez une Zone **</option>

                                                    @foreach ($zones as $zone)
                                                        <option value="{{ $zone->id }}"
                                                            {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                                            {{ $zone->libelle }}</option>
                                                    @endforeach

                                                </select>

                                                @error('zone_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="card-footer">

                                    <div class="row justify-content-center">

                                        <div class="col-sm-4">

                                            <a href="{{ route('prix.index') }}" class="btn btn-sm btn-secondary btn-block">

                                                <i class="fa-solid fa-circle-left"></i>

                                                {{ __('Retour') }}

                                            </a>

                                        </div>

                                        <div class="col-sm-4">

                                            <button type="submit" class="btn btn-sm btn-success btn-block">

                                                <i class="fa-solid fa-floppy-disk"></i>

                                                {{ __('Enregistrer') }}

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                    <div class="col-md-2"></div>

                </div>

            </div>

        </section>

    </div>
@endsection
