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
                            <h1 class="pb-3">Commande client N° {{ $commandeclient->code }}</h1>
                            <div class="row">
                                <div class="col-sm-6">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-calendar"></i></span> Date :  {{ date_format(date_create($commandeclient->dateBon), 'd/m/Y') }}</li></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-regular fa-money-bill-1"></i></span> Montant:  {{ number_format($commandeclient->montant,2,","," ") }}</li></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-building"></i></span> Statut:  {{ $commandeclient->statut }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="nav-icon fas fa-solid fa-cart-flatbed"></i></span> Type commande:  {{ $commandeclient->typecommande->libelle }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="nav-icon fas fa-solid fa-earth-africa"></i></span> Zone:  {{ $commandeclient->zone->libelle }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-user-tie"></i></span> Utilisateur:  {{ $commandeclient->users }}</li>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-regular fa-closed-captioning"></i></span> @if ($commandeclient->client->typeclient->libelle == env('TYPE_CLIENT_P'))
                                                    Nom :  {{ $commandeclient->client->nom }}
                                                @elseif ($commandeclient->client->typeclient->libelle == env('TYPE_CLIENT_S'))
                                                    Sigle :  {{ $commandeclient->client->sigle }}
                                                @endif</li></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-book-open"></i></span> @if ($commandeclient->client->typeclient->libelle == env('TYPE_CLIENT_P'))
                                                    Prénom :  {{ $commandeclient->client->prenom }}
                                                @elseif ($commandeclient->client->typeclient->libelle == env('TYPE_CLIENT_S'))
                                                    Raison Sociale :  {{ $commandeclient->client->raisonSociale }}
                                                @endif </li></b>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-phone"></i></span> Téléphone :  {{ $commandeclient->client->telephone }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-envelope-circle-check"></i></span> E-mail :  {{ $commandeclient->client->email }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-location-dot"></i></span> Adresse :  {{ $commandeclient->client->adresse }}</li>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <li class=""><span class="fa-li"><i class="fa-solid fa-industry"></i></span> Type :  {{ $commandeclient->client->typeclient->libelle }}</li>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="{{route('commandeclients.create',['commandeclient'=>$commandeclient->id])}}?redirectto=detailbc" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
