<div class="row mb-2">
    <div class="col-sm-12">

            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card d-flex flex-fill">
                        <div class="card-body">
                            @if(session('message'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                    {{session('message')}}
                                </div>
                            @endif
                            @if($message = session('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                    {{ $message }}
                                </div>
                            @endif
                            <h1 class="pb-3">RECU N° : {{ $recu->numero }}</h1>
                            <div class="row">
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-file-invoice-dollar"></i></span> Libellé :  {{ $recu->libelle }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-calendar"></i></span> Date :  {{ date_format(date_create($recu->date), 'd/m/Y') }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-solid fa-scale-balanced"></i></span> Tonnage :  {{ $recu->tonnage }}</li></b>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                                <div class="col-sm-3">
                                    <ul class="m-4 mb-0 fa-ul text-muted text-md">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <b><li class=""><span class="fa-li"><i class="fa-regular fa-money-bill-1"></i></span> Montant :  {{ number_format($recu->montant,2,","," ") }}</li></b>
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
