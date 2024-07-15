@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">COMMANDE CLIENTS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('commandeclients.index') }}">Commande client</a></li>
                        <li class="breadcrumb-item active">Modification</li>
                    </ol>
                </div>
            </div>
            @include('commandeclients.entete')
    </section>


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-body">
                                @if($message = session('message'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ $message }}
                                    </div>
                                @endif
                                    <form method="post" id="form_bc" action="{{$commander?route('commanders.store',['commandeclient'=>$commandeclient->id, 'commander'=>$commander->id]):route('commanders.store',['commandeclient' => $commandeclient->id])}}">
                                        @csrf
                                        <div class="row align-content-center">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Produits<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm select2  @error('produit_id') is-invalid @enderror" name="produit_id"   style="width: 100%;">
                                                        <option selected>**Choisissez un produit**</option>
                                                        @foreach($produits as $produit)
                                                            <option value="{{$produit->id}}" @if ($commander) @if (old('produit_id')) {{old('produit_id')==$produit->id?'selected':''}}  @else {{$commander->produit_id==$produit->id?'selected':''}}  @endif @else {{old('produit_id')==$produit->id?'selected':''}}  @endif>{{ $produit->libelle }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('produit_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Qté Cmder<span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control form-control-sm  @error('qteCommander') is-invalid @enderror" name="qteCommander"  value="@if($commander){{old('qteCommander')?old('qteCommander'):$commander->qteCommander}}@else{{old('qteCommander')}}@endif" min="1"  autocomplete="qteCommander" autofocus required>
                                                    @error('qteCommander')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>PU TTC<span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-dollar-sign"></i>
                                                                    </span>
                                                        </div>
                                                        <input type="number" class="form-control form-control-sm  @error('pu') is-invalid @enderror" name="pu"  value="@if($commander){{old('pu')?old('pu'):$commander->pu}}@else{{old('pu')}}@endif"  autofocus required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-cart-shopping"></i></div>
                                                        </div>
                                                    </div>
                                                    @error('pu')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label>Remise</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-dollar-sign"></i>
                                                                    </span>
                                                        </div>
                                                        <input type="number" class="form-control form-control-sm  @error('remise') is-invalid @enderror" name="remise"  value="@if($commander){{old('remise')?old('remise'):$commander->remise}}@else{{old('remise')}}@endif"  autofocus>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-cart-shopping"></i></div>
                                                        </div>
                                                    </div>
                                                    @error('remise')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2 text-center" id="pan_btn">
                                                <!--<i class="spinner-border" v-if="productLoader"></i>-->
                                                    <button type="submit" class="btn @if ($commander)
                                                        btn-warning
                                                        @else
                                                            btn-success btn-block
                                                        @endif btn-sm text-center" style="margin-top: 2.1em">@if ($commander)
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        @else
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        @endif
                                                    </button>

                                                @if ($commander)
                                                    <a href="{{ route('commandeclients.edit', ['commandeclient' => $commandeclient->id]) }}" class="btn btn-secondary btn-sm"  style="margin-top: 2.1em"><i class="fa-solid fa-delete-left"></i></a>
                                                @endif
                                            </div>
                                            <div class="col-sm-2 text-center" id="load" hidden>
                                                <i class="fa fa-spinner fa-spin fa-2x" style="margin-top: 1.1em"></i>
                                            </div>
                                        </div>
                                        <hr>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                                <thead class="text-white text-center bg-gradient-gray-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Produit</th>
                                                        <th>Qté Cmder</th>
                                                        <th>PU TTC</th>
                                                        <th>Remise</th>
                                                        <th>Montant</th>
                                                        <th class="action">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($commandeclient->count() > 0)
                                                        <?php $compteur=1; ?>
                                                        @foreach($commandeclient->commanders as $commander)
                                                                <td>{{ $compteur++ }}</td>
                                                                <td>{{ $commander->produit->libelle }}</td>
                                                                <td class="text-center">{{ $commander->qteCommander }}</td>
                                                                <td class="text-right">{{ number_format($commander->pu,2,","," ") }}</td>
                                                                <td class="text-right">{{ number_format($commander->remise,2,","," ") }}</td>
                                                                <td class="text-right">{{ number_format((($commander->qteCommander*$commander->pu)-$commander->remise),2,","," ") }}</td>
                                                                <td class="text-center action">
                                                                    <!--<a class="btn btn-success btn-sm" href="#"><i class="fa-regular fa-eye"></i></a>-->
                                                                    <a class="btn btn-warning btn-sm" href="{{ route('commandeclients.edit', ['commandeclient' => $commandeclient->id, 'commander'=>$commander->id]) }}"  ><i class="fa-solid fa-pen-to-square"></i></a>
                                                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-default"  onclick="chargeSuppression('{{$commander->id}}','{{$commander->produit->libelle}}')"><i class="fa-solid fa-trash"></i></button>

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                            <tr>
                                                                <br/>
                                                                <td><b>Total</b></td>
                                                                <td colspan="5" class="text-right"><b>{{ number_format($commandeclient->montant,2,","," ") }} F CFA</b></td>
                                                            </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center" id="footer_btn">
                                                <div class="col-sm-6">
                                                    <a href="{{ route('commandeclients.index') }}" class="btn btn-block btn-secondary" >
                                                        <i class="fa-solid fa-angles-left"></i>
                                                        OK
                                                    </a>
                                                </div>

                                            <div class="col-sm-6"><button @if($commandeclient->commanders->count() == 0) disabled @endif data-toggle="modal" data-target="#validation" class="btn btn-block btn-primary">
                                                Valider la commande
                                            <i class="fa-solid fa-check ml-1"></i></button></div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modal-default">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">SUPPRESSION</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST" action="" id="formSuppression">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 alert alert-warning">
                                                                Vous êtes sur le point de supprimer le détail <span class="font-weight-bold" id="produit">Test</span> Confirmez-vous cette suppression ?
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <div class="col-sm-4">
                                                            <button data-dismiss="modal" class="btn btn-sm btn-secondary btn-block">
                                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                                {{ __('NON') }}
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <button type="submit" class="btn btn-success btn-block">
                                                                OUI
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <div class="modal fade" id="validation">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Validation commande</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{route('commandeclients.valider',['client'=>$commandeclient])}}" id="formValidation">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 alert alert-warning">
                                                                Vous êtes sur le point de valider la commande. Confirmez-vous cette action ?
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <div class="col-sm-4">
                                                            <button data-dismiss="modal" class="btn btn-sm btn-secondary btn-block">
                                                                <i class="fa-solid fa-circle-left mr-1"></i>
                                                                {{ __('NON') }}
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <button type="submit" class="btn btn-success btn-block">
                                                                OUI
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('script')
    <script>
        function chargeSuppression(id,produit){
            let url = '{{env('app_web_url')}}'
            $('#formSuppression').attr('action',url+'commandeclients/commanders/destroy/'+id);
            $('#produit').html(produit);
        }
        $(function (){
            $("#form_bc").submit(function (){
                $('#pan_btn').attr('hidden','hidden');
                $('#load').removeAttr('hidden');
                $('.action').attr('hidden','hidden');
                $('#footer_btn').attr('hidden','hidden');
            })
        });
    </script>
@endsection
