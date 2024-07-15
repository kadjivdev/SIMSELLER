<div class="row mb-2" id="entete_produit">
    <div class="col-sm-12">

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card d-flex flex-fill">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-brands fa-product-hunt"></i></span> Produit :  {{ $detailboncommande->produit->libelle }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-box-open"></i></span> Quantité :  {{ number_format($detailboncommande->qteCommander,2,","," ") }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-box-open"></i></span> Qte Programmé :  {{ $total  }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-box-open"></i></span> Reste :  {{ number_format(($detailboncommande->qteCommander - intval($total)),2,","," ") }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-10"></div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-block btn-success btn-sm">Générer BL</button>
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
            @if(session('alert'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{session('alert')}}
                </div>
            @endif
    </div>
</div>
