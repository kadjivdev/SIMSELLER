<div class="row mb-2" id="entete_produit">
    <div class="col-sm-12">

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card d-flex flex-fill">
                        <div class="card-body">
                            <h1 class="pb-3 ml-3">Bon de commande N° {{ $programmation->detailboncommande->boncommande->code }}</h1>
                            <div class="row ml-3">
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-truck-field"></i></span> Fournisseur :  {{ $programmation->detailboncommande->boncommande->fournisseur->sigle  }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-brands fa-product-hunt"></i></span> Produit :  {{ $programmation->detailboncommande->produit->libelle }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-calendar"></i></span> Date :  {{ date_format(date_create($programmation->dateprogrammer), 'd/m/Y') }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-box-open"></i></span> Quantité :  {{ number_format($programmation->qteprogrammer,2,","," ") }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <div class="row ml-3">
                                <div class="col-sm-4">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-truck-moving"></i></span> Camion :  {{ $programmation->camion->immatriculationTracteur }} ({{ $programmation->camion->marque->libelle }})</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-user-tag"></i></span> Chauffeur :  {{ $programmation->chauffeur->nom }} {{ $programmation->chauffeur->prenom }} ({{ $programmation->chauffeur->telephone }})</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-earth-africa"></i></span> Zone :  {{ $programmation->zone->libelle }} ({{ $programmation->zone->departement->libelle }})</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('message'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{session('message')}}
                </div>
            @endif
    </div>
</div>
