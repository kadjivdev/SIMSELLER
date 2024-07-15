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
                            @if(Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists()==false)
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <a href="{{route('boncommandes.create',['boncommandes'=>$boncommandes->id])}}?redirectto=detailbc" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
