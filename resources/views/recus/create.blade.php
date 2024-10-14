@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">RECUS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('recus.index', ['boncommandes'=>$boncommandes->id]) }}">Accuse documents</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>
            @include('recus.entete')
    </section>


        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau Reçu</h3>
                            </div>
                            <form method="POST" action="{{ route('recus.store', ['boncommande'=>$boncommandes->id]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Référence<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="reference" style="text-transform: uppercase"  value="{{ old('reference') }}"  autocomplete="off" autofocus>
                                                @error('reference')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="libelle" style="text-transform: uppercase"  value="{{ old('libelle') }}"  autocomplete="off" autofocus required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Date<span class="text-danger">*</span></label>
                                                <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date"  value="{{ old('date') }}"  autocomplete="off" autofocus required>
                                                @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Tonnage<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="tonnage" style="text-transform: uppercase"  value="{{ old('tonnage') }}"  autocomplete="off" min="1" autofocus required>
                                                @error('tonnage')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Montant<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase"  value="{{ old('montant') }}"  autocomplete="off" min="1" autofocus required readonly >
                                                @error('montant')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="file">Document<span class="text-danger">*</span></label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" data-mask value="{{ old('document') }}" require>
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('recus.index', ['boncommandes'=>$boncommandes->id]) }}" class="btn btn-sm btn-secondary btn-block">
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
                    <div class="col-md-2"></div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('script')
<script>
    
    $(document).ready(function() {
        // Obtenir les deux inputs
        const tonnage = $(`input[name="tonnage"]`)[0];
        const montant = $(`input[name="montant"]`)[0];

        // Listener sur l'événement keyup de l'input tonnage
        tonnage.onkeyup = function() {
            // Obtenir la valeur de l'input tonnage
            const quantite = parseInt(tonnage.value);

            // Calculer le montant
            const montantCalcule =  quantite * <?php echo $prixTonnage ?>;

            // Mettre à jour la valeur de l'input montant
            montant.value = Math.round(montantCalcule);
        };
    });


</script>

@endsection
