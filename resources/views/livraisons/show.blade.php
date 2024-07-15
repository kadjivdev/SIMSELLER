@extends('layouts.app')

    @section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <h1 class="pb-3">DETAIL FOURNISSEUR</h1>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12">
                                        <div class="card d-flex flex-fill">
                                            <div class="card-body">
                                                <h1 class="pb-3">{{ $fournisseurs->raisonSociale }}  ({{ $fournisseurs->sigle }})</h1>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <img class="profile-user-img img-fluid img-circle" style="height: 150px; width: 150px"
                                                            src="{{ asset('images')}}/{{ $fournisseurs->logo }}"
                                                            alt="Profile de l'agent">
                                                    </div>
                                                    <div class="col-sm-9">
                                                        <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <b><li class=""><span class="fa-li"><i class="nav-icon fas fa-solid fa-truck-field"> </i></span> Catégorie:  {{ $fournisseurs->categoriefournisseur->libelle }}</li></b>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone:  {{ $fournisseurs->telephone }}</li>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <li class=""><span class="fa-li"><i class="fa-solid fa-envelope"></i></span> E-mail:  {{ $fournisseurs->email }}</li>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <li class="big"><span class="fa-li"><i class="fa-solid fa-building"></i></span> Adresse:  {{ $fournisseurs->adresse }}</li>
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
                        <div class="col-sm-4">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('fournisseurs.index') }}">Fournisseurs</a></li>
                                <li class="breadcrumb-item active">Détails fournisseur</li>
                            </ol>
                        </div>
                    </div>

                    <div class="card card-secondary card-outline">
                        @if($message = session('message'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                {{ $message }}
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5 col-sm-2">
                                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Produits</a>
                                        <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Interlocuteurs</a>
                                        <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Autres Téléphone</a>
                                        <!--<a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings" aria-selected="false">Settings</a>-->
                                    </div>
                                </div>
                                <div class="col-7 col-sm-10">
                                    <div class="tab-content" id="vert-tabs-tabContent">
                                        <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                            <div class="card-header">
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-default">
                                                    <i class="fas fa-solid fa-plus"></i>
                                                    Ajouter
                                                </button>
                                            </div>
                                            <div class="modal fade" id="modal-default">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Nouvelle(s) Produit(s)</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST" action="{{ route('commercialisers.store') }}">
                                                            @csrf
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="fournisseur_id" value="{{ $fournisseurs->id }}" />
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <div class="form-group select2-success">
                                                                                <label>Produits</label>
                                                                                <select class="form-control form-control-sm select2 @error('produit_id') is-invalid @enderror" multiple="multiple" style="width: 100%;" name="produit_id[]" data-placeholder="**Choisissez les produits**">
                                                                                    @foreach ($produits as $produit)
                                                                                        <option value="{{ $produit->id }}" {{ (collect(old('produit_id'))->contains($produit->id)) ? 'selected':'' }}>{{ $produit->libelle }} ({{ $produit->typeproduit->libelle }})</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                @error('produit_id')
                                                                                    <span class="text-danger">{{ $message }}</span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">{{ __('Retour') }}</button>
                                                                    <button type="submit" class="btn btn-sm btn-success">Enregistrer</button>
                                                                </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                                    <thead class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Libellé</th>
                                                            <th>Type</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($fournisseurs->count() > 0)
                                                            <?php $compteur=1; ?>
                                                            @foreach($fournisseurs->produits as $produit)
                                                                <tr>
                                                                    <td>{{ $compteur++ }}</td>
                                                                    <td>{{ $produit->libelle }}</td>
                                                                    <td>{{ $produit->typeproduit->libelle }}</td>
                                                                    <td class="text-center">
                                                                        <!-- <a class="btn btn-warning btn-sm" href=""><i class="fa-solid fa-pen-to-square"></i></a> -->
                                                                        <a class="btn btn-danger btn-sm" href="{{ route('commercialisers.delete', ['fournisseur_id'=>$fournisseurs->id, 'produit_id'=>$produit->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Libellé</th>
                                                            <th>Type</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                                <div class="card-header">
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-lg">
                                                        <i class="fas fa-solid fa-plus"></i>
                                                        Ajouter
                                                    </button>
                                                </div>
                                                <div class="modal fade" id="modal-lg">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Nouveau Interlocuteur</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" action="{{ route('interlocuteurs.store') }}">
                                                                @csrf
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="fournisseur_id" value="{{ $fournisseurs->id }}" />
                                                                        <div class="row">
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label>Nom<span class="text-danger">*</span></label>
                                                                                    <input type="text" class="form-control form-control-sm  @error('nom') is-invalid @enderror" name="nom"  value="{{ old('nom') }}"  autocomplete="nom" style="text-transform: uppercase" autofocus required>
                                                                                    @error('telephone')
                                                                                        <span class="text-danger">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-5">
                                                                                <div class="form-group">
                                                                                    <label>Prénom<span class="text-danger">*</span></label>
                                                                                    <input type="text" class="form-control form-control-sm  @error('prenom') is-invalid @enderror" name="prenom" style="text-transform: capitalize"  value="{{ old('prenom') }}"  autocomplete="prenom" autofocus required>
                                                                                    @error('telephone')
                                                                                        <span class="text-danger">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <div class="form-group">
                                                                                    <label>Qualification<span class="text-danger">*</span></label>
                                                                                    <input type="text" class="form-control form-control-sm  @error('qualification') is-invalid @enderror" name="qualification" style="text-transform: capitalize"  value="{{ old('qualification') }}"  autocomplete="qualification" autofocus required>
                                                                                    @error('qualification')
                                                                                        <span class="text-danger">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-5">
                                                                                <div class="form-group">
                                                                                    <label>Téléphone<span class="text-danger">*</span></label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="telephone" class="form-control form-control-sm @error('telephone') is-invalid @enderror"  value="{{ old('telephone') }}" required autocomplete="telephone">
                                                                                        @error('telephone')
                                                                                            <span class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-7">
                                                                                <div class="form-group">
                                                                                    <label>E-mail<span class="text-danger">*</span></label>
                                                                                    <input id="email" type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                                                                    @error('email')
                                                                                        <span class="text-danger">{{ $message }}</span>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">{{ __('Retour') }}</button>
                                                                        <button type="submit" class="btn btn-sm btn-success">Enregistrer</button>
                                                                    </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nom</th>
                                                                <th>Prénom</th>
                                                                <th>Qualification</th>
                                                                <th>Téléphone</th>
                                                                <th>E-mail</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($fournisseurs->count() > 0)
                                                                <?php $compteur=1; ?>
                                                                @foreach($fournisseurs->interlocuteurs as $interlocuteur)
                                                                    <tr>
                                                                        <td>{{ $compteur++ }}</td>
                                                                        <td>{{ $interlocuteur->nom }}</td>
                                                                        <td>{{ $interlocuteur->prenom }}</td>
                                                                        <td>{{ $interlocuteur->qualification }}</td>
                                                                        <td>{{ $interlocuteur->telephone }}</td>
                                                                        <td>{{ $interlocuteur->email }}</td>
                                                                        <td class="text-center">
                                                                            <a class="btn btn-warning btn-sm" href="{{ route('interlocuteurs.edit', ['id'=>$interlocuteur->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                                            <a class="btn btn-danger btn-sm" href="{{ route('interlocuteurs.delete', ['id'=>$interlocuteur->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                        <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nom</th>
                                                                <th>Prénom</th>
                                                                <th>Qualification</th>
                                                                <th>Téléphone</th>
                                                                <th>E-mail</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                        </div>
                                        <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                                            <div class="tab-pane text-left fade show active" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                                <div class="card-header">
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-default1">
                                                        <i class="fas fa-solid fa-plus"></i>
                                                        Ajouter
                                                    </button>
                                                </div>
                                                <div class="modal fade" id="modal-default1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Nouveau Téléphone</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" action="{{ route('telephones.store') }}">
                                                                @csrf
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="fournisseur_id" value="{{ $fournisseurs->id }}" />
                                                                        <div class="row">
                                                                            <div class="col-sm-8">
                                                                                <div class="form-group">
                                                                                    <label>Numéro<span class="text-danger">*</span></label>
                                                                                    <div class="input-group">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                                                        </div>
                                                                                        <input type="text" name="numero" class="form-control form-control-sm @error('numero') is-invalid @enderror"  value="{{ old('numero') }}" required autocomplete="numero">
                                                                                        @error('numero')
                                                                                            <span class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group">
                                                                                    <label>Type<span class="text-danger">*</span></label>
                                                                                    <select class="form-control form-control-sm" style="width: 100%;" name="type">
                                                                                        <option value="Téléphonique" selected="selected">Téléphonique</option>
                                                                                        <option value="Whatsapp">Whatsapp</option>
                                                                                        <option value="Fax">Fax</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer justify-content-between">
                                                                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">{{ __('Retour') }}</button>
                                                                        <button type="submit" class="btn btn-sm btn-success">Enregistrer</button>
                                                                    </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                                        <thead class="text-white text-center bg-gradient-gray-dark">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Numéro</th>
                                                                <th>Type</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($fournisseurs->count() > 0)
                                                                <?php $compteur=1; ?>
                                                                @foreach($fournisseurs->telephones as $telephone)
                                                                    <tr>
                                                                        <td>{{ $compteur++ }}</td>
                                                                        <td>{{ $telephone->numero }}</td>
                                                                        <td>{{ $telephone->type }}</td>
                                                                        <td class="text-center">
                                                                            <a class="btn btn-warning btn-sm" href="{{ route('telephones.edit', ['id'=>$telephone->id]) }}"><i class="fa-solid fa-pen-to-square"></i></a>
                                                                            <a class="btn btn-danger btn-sm" href="{{ route('telephones.delete', ['id'=>$telephone->id]) }}"><i class="fa-solid fa-trash-can"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                        <tfoot class="text-white text-center bg-gradient-gray-dark">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Numéro</th>
                                                                <th>Type</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--<div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel" aria-labelledby="vert-tabs-settings-tab">
                                            Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    @endsection
