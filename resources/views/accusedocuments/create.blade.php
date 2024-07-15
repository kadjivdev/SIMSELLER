@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">ACCUSE DOCUMENTS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('accusedocuments.index', ['boncommandes'=>$boncommandes->id]) }}">Accuse documents</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-12">
            
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12">
                                <div class="card d-flex flex-fill">
                                    <div class="card-body">
                                        @if(session('messagebc'))
                                            <div class="alert alert-success alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                                {{session('messagebc')}}
                                            </div>
                                        @endif
                                        <h1 class="pb-3">Bon de commande N° {{ $boncommandes->code }}</h1>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-solid fa-calendar"></i></span> Date :  {{ date_format(date_create($boncommandes->dateBon), 'd/m/Y') }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-regular fa-money-bill-1"></i></span> Montant:  {{ number_format($boncommandes->montant,2,","," ") }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut:  {{ $boncommandes->statut }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="nav-icon fas fa-solid fa-cart-flatbed"></i></span> Type commande:  {{ $boncommandes->typecommande->libelle }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-user-tie"></i></span> Utilisateur:  {{ $boncommandes->users }}</li>
                                                        </div>
                                                    </div>
                                                    @if ($boncommandes->date_traitement == null)
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                    
                                                                    <div class="form-group row">
                                                                        <span class="fa-li"><i class="fa-solid fa-calendar"></i></span>
                                                                        <b class="mr-1">Date Traitement : </b>
                                                                        <input onchange="handleDateChange('{{ $boncommandes->id }}', this)" type="date" class="form-control form-control-sm col-sm-4"/>
                                                                    </div>
                                                                    <div class="message-container d-none"></div>
                                                                
                                                            </div>
                                                        </div>                                                        
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="col-sm-6">
                                                <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-regular fa-closed-captioning"></i></span> Sigle :  {{ $boncommandes->fournisseur->sigle }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <b><li class=""><span class="fa-li"><i class="fa-solid fa-book-open"></i></span> Raison Sociale :  {{ $boncommandes->fournisseur->raisonSociale }}</li></b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone :  {{ $boncommandes->fournisseur->telephone }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-envelope-circle-check"></i></span> E-mail :  {{ $boncommandes->fournisseur->email }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-location-dot"></i></span> Adresse :  {{ $boncommandes->fournisseur->adresse }}</li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <li class=""><span class="fa-li"><i class="fa-solid fa-industry"></i></span> Catégorie :  {{ $boncommandes->fournisseur->categoriefournisseur->libelle }}</li>
                                                        </div>
                                                    </div>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            
    </section>


        <section class="content">
            <div class="container">
                
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Nouveau accusé de document</h3>
                            </div>
                            <form method="POST" action="{{ route('accusedocuments.store', ['boncommande'=>$boncommandes->id]) }}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    <!-- <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Code<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="code" style="text-transform: uppercase"  value=""  autocomplete="code" autofocus required>
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label>Libellé<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" name="libelle" style="text-transform: uppercase"  value=""  autocomplete="libelle" autofocus required>
                                            </div>
                                        </div> 
                                    </div>-->
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
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label>Date<span class="text-danger">*</span></label>
                                                <input type="date" id="date" class="form-control form-control-sm @error('date') is-invalid @enderror" name="date"  value="{{ old('date') }}"  autocomplete="off" autofocus required>
                                                @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Montant<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" name="montant" style="text-transform: uppercase"  value="{{ old('montant') }}"  autocomplete="off" min="1" autofocus required>
                                                @error('montant')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Type<span class="text-danger">*</span></label>
                                                <select class="select2 form-control form-control-sm @error('typedocument') is-invalid @enderror" name="typedocument" style="width: 100%;">
                                                    <option selected disabled>** choisir type document **</option>
                                                    @foreach($typedocuments as $typedocument)
                                                        <option value="{{ $typedocument->id }}" {{ old('typedocument') == $typedocument->id ? 'selected' : '' }}>{{ $typedocument->libelle }}</option>
                                                    @endforeach
                                                </select>
                                                @error('typedocument')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="file">Document<span class="text-danger"></span></label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" data-mask value="{{ old('document') }}" >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Observation</label>
                                                <textarea class="form-control form-control-sm  @error('observation') is-invalid @enderror" name="observation" id="exampleFormControlTextarea1" style="text-transform: initial"  autocomplete="observation" autofocus rows="3">{{ old('observation') }}</textarea>
                                                @error('observation')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('accusedocuments.index', ['boncommandes'=>$boncommandes->id]) }}" class="btn btn-sm btn-secondary btn-block">
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

    <script>
        function handleDateChange(id, inputElement) {
           const dateValue = inputElement.value;

           // Récupérer le conteneur du message
           const messageContainer = inputElement.parentNode.querySelector('.message-container');

           // Vérifier si la date est renseignée
           if (dateValue) {
               //showLoader(messageContainer);
               // Appeler l'API via Axios
               axios.get('{{env('APP_BASE_URL')}}boncommandes/update-date-traitement/'+id +'/'+dateValue)
                   .then(function (response) {
                       // Afficher un message de succès
                       
                       // showMessage(messageContainer, '<i class="fas fa-check-circle"></i>', successMessage, 'text-success');
                   })
                   .catch(function (error) {
                       // Afficher un message d'erreur
                       const errorMessage = error.response.data;
                       // showMessage(messageContainer, '<i class="fas fa-times-circle"></i>', errorMessage, 'text-danger',false);
                   });
           } else {
               // Si la date n'est pas renseignée, effacer le message
               messageContainer.innerHTML = '';
           }
       }

   </script>
@endsection
@section('scripts')
@endsection
