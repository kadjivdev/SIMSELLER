@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">PROGRAMMATIONS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('programmations.index') }}">Listes des programmations</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>

            @include('programmations.entete')
            @include('programmations.enteteproduit')
    </section>


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Nouvelle programmation</h3>
                            </div>
                            <form method="POST" action="{{ route('programmations.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Date<span class="text-danger">*</span></label>
                                                <input type="date" id="dateprogrammer" class="form-control form-control-sm @error('dateprogrammer') is-invalid @enderror" name="dateprogrammer"  value="{{ old('dateprogrammer')?old('dateprogrammer'):date('Y-m-d') }}"  autocomplete="dateprogrammer" autofocus required>
                                                @error('dateprogrammer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Qte<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="qteprogrammer" style="text-transform: uppercase"  value="{{ old('qteprogrammer') }}"  autocomplete="qteprogrammer" min="1" autofocus required>
                                                @error('qteprogrammer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Zones<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('zone_id') is-invalid @enderror" name="zone_id" style="width: 100%;">
                                                    <option selected>** choisissez une zone **</option>
                                                    @foreach($zones as $zone)
                                                        <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('zone_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Camions<span class="text-danger">*</span></label>
                                                <select onchange="selectDefaultDriver()" id="camion" class="select2 form-control form-control-sm @error('camion_id') is-invalid @enderror" name="compte_id" style="width: 100%;">
                                                    <option value="{{ NULL }}" selected>** choisissez un camion **</option>
                                                    @foreach($camions as $camion)
                                                        <option value="{{ $camion->id }}" onclick="alert('bonjour')//selectDefaultDriver({{$camion->chauffeur_id}})" {{ old('camion_id') == $camion->id ? 'selected' : '' }}>{{ $camion->marque }} {{ $camion->immatriculationTracteur }}</option>
                                                    @endforeach
                                                </select>
                                                @error('camion_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div hidden id="loader" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px" >
                                                    <i class="fa fa-spin fa-spinner"></i> Recherche chauffeur ...
                                                </div>
                                                <div id="champ">
                                                    <label>Chauffeurs<span class="text-danger">*</span></label>
                                                    <select class="select2 form-control form-control-sm @error('chauffeur_id') is-invalid @enderror" name="chauffeur_id" style="width: 100%;" >
                                                        <option id="chauffeur_0" selected>** choisissez un chauffeur **</option>
                                                        @foreach($chauffeurs as $chauffeur)
                                                            <option value="{{ $chauffeur->id }}" id="chauffeur_{{$chauffeur->id}}" {{ old('chauffeur_id') == $chauffeur->id ? 'selected' : '' }}>{{ $chauffeur->prenom }} {{ $chauffeur->nom }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('chauffeur_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Quantité</th>
                                                <th>Zone</th>
                                                <th>Camion</th>
                                                <th>Chauffeur</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                    <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-center"></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-center">
                                                                <!--<a class="btn btn-success btn-sm" href=""><i class="fa-solid fa-p"></i>  Programmer</a>
                                                                <a class="btn btn-warning btn-sm" href=""><i class="fa-solid fa-truck-arrow-right"></i>  Livraison</a>
                                                                <a class="btn btn-warning btn-sm" href=""><i class="fa-solid fa-pen-to-square"></i></a>
                                                                <a class="btn btn-danger btn-sm" href=""><i class="fa-solid fa-trash-can"></i></a>
                                                                <a class="btn btn-secondary btn-sm" href=""><i class="fa-solid fa-bed"></i></a>
                                                                <a class="btn btn-primary btn-sm" href=""><i class="fas fa-running"></i></a>-->
                                                            </td>
                                                    </tr>
                                            </tbody>
                                            <tfoot  class="text-white text-center bg-gradient-gray-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Quantité</th>
                                                <th>Zone</th>
                                                <th>Camion</th>
                                                <th>Chauffeur</th>
                                                <th>Action</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('programmations.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-sm btn-success btn-block">
                                                {{ __('Enregistrer') }}
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('script')
    <script>
        function selectDefaultDriver(){
            if($('#camion').val()) {
                //$('#champ').attr('hidden','hidden');
                //$('#loader').removeAttr('hidden')
                axios.get('{{env('APP_BASE_URL')}}programmation/chauffeur/' + $('#camion').val()).then((response) => {
                    console.log(response);
                    $('#chauffeur_' + response.data.id).attr('selected', 'true');
                    $('.select2').select2()
                    //$('#loader').attr('hidden', 'hidden');
                    //$('#champ').removeAttr('hidden')
                }).catch(()=>{
                    //$('#loader').attr('hidden', 'hidden');
                    //$('#champ').removeAttr('hidden')
                })
            }
            else {
                $('#chauffeur_0').attr('selected', 'true');
                $('.select2').select2()
            }
        }
    </script>
@endsection
