@extends('layouts.app')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>DATE DE SORTIE D'UN CAMION</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('banques.index') }}">Date de Sortie</a></li>
                            <li class="breadcrumb-item active">Date de Sortie</li>
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
                                <h3 class="card-title">Date de Sortie </h3>
                            </div>
                            <form method="POST" action="{{ route('programmations.postDateSortie',['detailboncommande' => $detailboncommande->id, 'programmation' => $programmation->id]) }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Date de Sortie Camion <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control form-control-sm @error('dateSortie') is-invalid @enderror" name="dateSortie"  value="{{ old('dateSortie') }}"  autocomplete="dateSortie" style="text-transform: uppercase" autofocus required>
                                                @error('dateSortie')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('programmations.create', ['detailboncommande'=>$detailboncommande->id]) }}" class="btn btn-sm btn-secondary btn-block">
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

@section('scripts')
<script>
    $("#save").submit(function(event) {
        event.preventDefault();
        // Envoyer les données du formulaire au serveur
        var data = $("#idform").serialize();
        $.post("{{env('APP_BASE_URL')}}programmation/dateSortie/{{$programmation->id}}" , data, function(response) {
            if (response.status === "success") {
                // Afficher un message d'alerte
                Swal.fire({
                    icon: "success",
                    title: "Enregistrement effectué",
                    text: "Votre date de sortie a été enregistrée avec succès !",
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                // Afficher un message d'erreur
                Swal.fire({
                    icon: "error",
                    title: "Erreur d'enregistrement",
                    text: "Une erreur s'est produite lors de l'enregistrement de votre date de sortie. Veuillez réessayer plus tard ou Contactez le service Informatique. ",
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });
</script>
    
@endsection
