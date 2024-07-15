@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">DETAIL BON COMMANDE</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('boncommandes.index') }}">Bon de commande</a></li>
                        <li class="breadcrumb-item active">Modification</li>
                    </ol>
                </div>
            </div>
            @include('boncommandes.entete')
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
                                    <form method="post" id="form_bc" action="{{$detailboncommande?route('detailboncommandes.store',['boncommande'=>$boncommandes->id, 'detailboncommandes'=>$detailboncommande->id]):route('detailboncommandes.store',['boncommande' => $boncommandes->id])}}">
                                        @csrf
                                        <div class="row align-content-center">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Produits<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-sm select2  @error('produit_id') is-invalid @enderror" name="produit_id"   style="width: 100%;">
                                                        <option selected>**Choisissez un produit**</option>
                                                        @foreach($produits as $produit)
                                                            <option value="{{$produit->id}}" @if ($detailboncommande) @if (old('produit_id')) {{old('produit_id')==$produit->id?'selected':''}}  @else {{$detailboncommande->produit_id==$produit->id?'selected':''}}  @endif @else {{old('produit_id')==$produit->id?'selected':''}}  @endif>{{ $produit->libelle }}</option>
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
                                                    <input type="number" class="form-control form-control-sm  @error('qteCommander') is-invalid @enderror" name="qteCommander"  value="@if($detailboncommande){{old('qteCommander')?old('qteCommander'):$detailboncommande->qteCommander}}@else{{old('qteCommander')}}@endif" min="1"  autocomplete="qteCommander" autofocus required>
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
                                                        <input type="number" class="form-control form-control-sm  @error('pu') is-invalid @enderror" name="pu"  value="@if($detailboncommande){{old('pu')?old('pu'):$detailboncommande->pu}}@else{{old('pu')}}@endif"  autofocus required>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-cart-shopping"></i></div>
                                                        </div>
                                                    </div>
                                                    @error('pu')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Remise</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                                    <span class="input-group-text">
                                                                        <i class="fas fa-dollar-sign"></i>
                                                                    </span>
                                                        </div>
                                                        <input type="number" class="form-control form-control-sm  @error('remise') is-invalid @enderror" name="remise"  value="@if($detailboncommande){{old('remise')?old('remise'):$detailboncommande->remise}}@else{{old('remise')}}@endif"  autofocus>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fas fa-cart-shopping"></i></div>
                                                        </div>
                                                    </div>
                                                    @error('remise')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-1 " id="pan_btn">
                                                <!--<i class="spinner-border" v-if="productLoader"></i>-->
                                                @if ((count($boncommandes->detailboncommandes) < env('NOMBRE_PRODUIT')))
                                                    <button type="submit" class="btn @if ($detailboncommande)
                                                        btn-warning
                                                        @else
                                                            btn-success btn-block
                                                        @endif btn-sm text-center" style="margin-top: 2.1em">@if ($detailboncommande)
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        @else
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        @endif
                                                    </button>
                                                @elseif($detailboncommande)
                                                    <button type="submit" class="btn @if ($detailboncommande)
                                                        btn-warning
                                                        @else
                                                            btn-success btn-block
                                                        @endif btn-sm text-center" style="margin-top: 2.1em">@if ($detailboncommande)
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        @else
                                                            <i class="fa-solid fa-circle-check"></i>
                                                        @endif
                                                    </button>
                                                @else
                                                    <i class="fa-solid fa-thumbs-up text-success fa-2x" style="margin-top: 1em"></i>
                                                @endif

                                                @if ($detailboncommande)
                                                    <a href="{{ route('boncommandes.edit', ['boncommande' => $boncommandes->id]) }}" class="btn btn-secondary btn-sm"  style="margin-top: 2.1em"><i class="fa-solid fa-delete-left"></i></a>
                                                @endif
                                            </div>
                                            <div class="col-sm-1" id="load" hidden>
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
                                                    @if ($boncommandes->count() > 0)
                                                        <?php $compteur=1; ?>
                                                        @foreach($boncommandes->detailboncommandes as $detailboncommande)
                                                            <tr>
                                                                <td>{{ $compteur++ }}</td>
                                                                <td>{{ $detailboncommande->produit->libelle }}</td>
                                                                <td class="text-center">{{ $detailboncommande->qteCommander }}</td>
                                                                <td class="text-right">{{ number_format($detailboncommande->pu,2,","," ") }}</td>
                                                                <td class="text-right">{{ number_format($detailboncommande->remise,2,","," ") }}</td>
                                                                <td class="text-right">{{ number_format((($detailboncommande->qteCommander*$detailboncommande->pu)-$detailboncommande->remise),2,","," ") }}</td>
                                                                <td class="text-center action">
                                                                    @if(Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists() == null)
                                                                        <!--<a class="btn btn-success btn-sm" href="#"><i class="fa-regular fa-eye"></i></a>-->
                                                                        <a class="btn btn-warning btn-sm" href="{{ route('boncommandes.edit', ['boncommande' => $boncommandes->id, 'detailboncommande'=>$detailboncommande->id]) }}"  ><i class="fa-solid fa-pen-to-square"></i></a>
                                                                        <suppression-detail
                                                                            detailboncommandeid = "{{ $detailboncommande->id }}"
                                                                            produit = "{{ $detailboncommande->produit->libelle }}"
                                                                            baseurl ="{{ env('APP_BASE_URL') }}"
                                                                            weburl ="{{ route('boncommandes.edit', ['boncommande' => $boncommandes->id]) }}"
                                                                        ></suppression-detail>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                            <tr>
                                                                <br/>
                                                                <td><b>Total</b></td>
                                                                <td colspan="5" class="text-right"><b>{{ number_format($boncommandes->montant,2,","," ") }} F CFA</b></td>
                                                            </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center" id="footer_btn">
                                            <div class="  col-sm-6 ">
                                                <a href="{{ route('boncommandes.index') }}" class="btn btn-block btn-secondary" >
                                                    <i class="fa-solid fa-angles-left"></i>
                                                    OK
                                                </a>
                                            </div>
                                            @if(Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists())                                                                    
                                                <div class="col-sm-6">
                                                @if(count($boncommandes->detailboncommandes) > 0)
                                                        <a href="{{ route('boncommandes.valider', ['boncommande'=>$boncommandes->id]) }}"  class="btn btn-block btn-primary" >
                                                            Valider la commande
                                                            <i class="fa-solid fa-check ml-1"></i>
                                                        </a>
                                                    @else
                                                        <button   class="btn btn-block btn-primary" disabled>
                                                            Valider la commande
                                                            <i class="fa-solid fa-check ml-1"></i>
                                                        </button>
                                                @endif
                                                </div>
                                            @endif

                                        </div>
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
