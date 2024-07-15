<div class="row mb-2" id="entete_produit">
    <div class="col-sm-12">

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card d-flex flex-fill">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-box-open"></i></span> Qte Commandée :  {{ number_format($vente->commandeclient->commanders()->sum('qteCommander'),2,","," ") }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-box-open"></i></span> Qte Vendue :  {{ number_format($totalVendu,2,","," ")  }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-4">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-box-open"></i></span> Reste :  {{ number_format((intval($vente->commandeclient->commanders()->sum('qteCommander'))) - intval($totalVendu),2,","," ") }}</li></b>
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
            @if(session('msgSuppression'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{session('msgSuppression')}}
                </div>
            @endif
    </div>
</div>
