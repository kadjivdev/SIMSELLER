@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">VENTE N° {{ $vente->code }} du {{ date_format(date_create($vente->date), 'd/m/Y') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ventes.index') }}">Listes des ventes</a></li>
                        <li class="breadcrumb-item active">Ajouter</li>
                    </ol>
                </div>
            </div>

            @include('vendus.entete')
            @if(!$vente->commandeclient->byvente)
                @include('vendus.enteteproduit')
            @endif
        </div>
        @php
            if ($vente->ask_history != NULL){

                $ask_history = json_decode($vente->ask_history);
                $nowUpdate = end($ask_history);
                json_encode($nowUpdate);
                $dataUpdate = json_decode($nowUpdate);

            }
        @endphp
        @if (($vente->ask_history != NULL))
            <div class="col-md-12">
                <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Modification à effectuer</h3>

                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="info-box-content">
                                <b class="info-box-number">Date Demande</b> :
                                <span class="info-box-text">{{ date_format(date_create($dataUpdate->dateDemande), 'd/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="info-box-content">
                                <b class="info-box-number">Quantité vendue</b> :
                                <span class="info-box-text">{{ $dataUpdate->qteNew }}</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="info-box-content">
                                <b class="info-box-number">Prix de vente Unitaire</b> :
                                <span class="info-box-text">{{ $dataUpdate->prixUnitaireNew }}</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="info-box-content">
                                <b class="info-box-number">Prix de Transport/Tonne</b> :
                                <span class="info-box-text">{{ $dataUpdate->transportNew }}</span>
                            </div>
                        </div>
                    
                    </div>

                </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>            
        @endif
          <!-- /.col -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-body">
                                <form method="post" id="form_bc" action="{{$vendu?route('vendus.store',['vente'=>$vente->id, 'vendu'=>$vendu->id]):route('vendus.store',['vente' => $vente->id])}}">
                                    @csrf
                                        
                                    <div class="row align-content-center">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Produits<span class="text-danger">*</span></label>

                                                <select  class="form-control form-control-sm select2   @error('produit_id') is-invalid @enderror" onchange="selectDefaultDriver('{{old('programmation_id')}}')"  id="produit" name="produit_id"   style="width: 100%;">
                                                    <option selected>**Choisissez un produit**</option>
                                                    @if($vente->commandeclient->byvente == 1)
                                                        @foreach($produits as $produit)
                                                            <option value="{{$produit->id}}" @if ($vente) @if (old('produit_id')) {{old('produit_id')==$produit->id?'selected':''}}  @else {{$vente->produit_id==$produit->id?'selected':''}}  @endif @else {{old('produit_id')==$produit->id?'selected':''}}  @endif>{{ $produit->libelle }}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach($vente->commandeclient->commanders as $cder)
                                                            <option value="{{$cder->produit->id}}" @if ($vente) @if (old('produit_id')) {{old('produit_id')==$cder->produit->id?'selected':''}}  @else {{$vente->produit_id==$cder->produit->id?'selected':''}}  @endif @else {{old('produit_id')==$cder->produit->id?'selected':''}}  @endif>{{ $cder->produit->libelle }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('produit_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="@if ($vente->transport)  col-sm-3   @else col-sm-2 @endif">
                                            <div class="form-group">
                                                <label>Qté Vendu<span class="text-danger">*</span></label>
                                                <input type="number" @if($vente->qteTotal) readonly @endif onkeyup="calculMontant()" id="qteTotal" class="form-control form-control-sm  @error('qteTotal') is-invalid @enderror" name="qteTotal"  value="@if($vente){{old('qteTotal')?old('qteTotal'):$vente->qteTotal}}@else{{old('qteTotal')}}@endif" min="1"  autocomplete="off" autofocus required>
                                                @error('qteTotal')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="@if ($vente->transport)  col-sm-3   @else col-sm-2 @endif">
                                            <div class="form-group">
                                                <label>PU TTC<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input onkeyup="calculMontant()" @if($vente->pu) readonly @endif type="number" id="pu" class="form-control form-control-sm  @error('pu') is-invalid @enderror" name="pu"  value="@if($vente){{old('pu')?old('pu'):$vente->pu}}@else{{old('pu')}}@endif"  autofocus required>
                                                </div>
                                                @error('pu')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @if ($vente->transport)                                        
                                        <div class="col-sm-3" id="tt1">
                                            <div class="form-group">
                                                <label>Prix transport / Tonne<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" id="tt" onkeyup="calculMontant()" class="form-control form-control-sm @error('transport') is-invalid @enderror" value=" @if($vente){{old('transport')?old('transport'):$vente->transport}}@else{{old('transport')}}@endif " name="transport" min="0"  autocomplete="off" autofocus required >
                                                </div>
                                                @error('transport')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3" id="tt2">
                                        </div>
                                        @endif
                                        <div class="@if ($vente->transport)  col-sm-3   @else col-sm-2 @endif">
                                            <div class="form-group">
                                                <label>Remise</label>
                                                <div class="input-group">
                                                    <input onkeyup="calculMontant()"  @if($vente->qteTotal) readonly @endif type="number"  id="remise" class="form-control form-control-sm  @error('remise') is-invalid @enderror" name="remise"  value="@if($vente){{old('remise')?old('remise'):$vente->remise}}@else{{old('remise')}}@endif"  autofocus>
                                                </div>
                                                @error('remise')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="@if ($vente->transport)  col-sm-3   @else col-sm-2 @endif">
                                            <div class="form-group">
                                                <div id="">
                                                <label>Montant<span class="text-danger"></span></label>
                                                    <input type="text" name="montant" id="montant"  style="height: 30px" class="form-control" value="@if($vente){{old('montant')?old('montant'):$vente->montant}}@else{{old('montant')}}@endif" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1 text-center">
                                            <button class="btn btn-primary btn-sm " @if($vente->montant) data-toggle="modal" data-target="#initialise_vente" @else disabled @endif  type="button" style="margin-top: 2em"><i class="fa fa-undo"></i></button>
                                        </div>
                                        <div class="col-sm-11">
                                            <div class="form-group">
                                                <label>Destination<span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="text"  @if($vente->destination) readonly @endif  class="form-control form-control-sm  @error('destination') is-invalid @enderror" name="destination"  value="@if($vente){{old('destination')?old('destination'):$vente->destination}}@else{{old('destination')}}@endif"  autofocus required>
                                                </div>
                                                @error('destination')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: -0.2em; border-color: black;" class="my-sm-1">
                                    @if($vente->qteTotal && $vente->qteTotal == $qteTotal)
                                        <div class="row align-content-center">
                                            <div class="col-12 text-center text-info my-1 border border-info">
                                                <h4>La quantité de la vente est constituée. Vous pouvez donc la valider.</h4>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row align-content-center " style="margin-top: 1.5em">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <div hidden id="loader" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px" >
                                                        <i class="fa fa-spin fa-spinner"></i> Recherche Type ...
                                                    </div>
                                                    <div id="champ">
                                                        <label>Camion <span class="text-danger">*</span></label>
                                                        <select required class="form-control form-control-sm select2  @error('programmation_id') is-invalid @enderror" name="programmation_id" id="bl"  onchange="getStockDisponible()"  style="width: 100%;">
                                                            @if ($vendu)
                                                                <option value="{{ $vendu->programmation->id }}" selected>{{ $vendu->programmation->bl }}</option>
                                                            @else
                                                                <option id="bl_0" selected disabled>** Choisissez un Camion **</option>
                                                            @endif

                                                        </select>
                                                    </div>
                                                    @error('programmation_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <div hidden id="loaderstock" class="text-center  text-info" style="font-style: italic; margin-top: 2em; font-size: 14px" >
                                                        <i class="fa fa-spin fa-spinner"></i> Calcul stock disponible ...
                                                    </div>
                                                    <div id="champstock" >
                                                        <label>Stock Disponible<span class="text-danger">*</span></label>
                                                        <input type="text" id="stockDispo" style="height: 30px" class="form-control" disabled>
                                                    </div>
                                                    @error('programmation_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>Qte à prelever<span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number"  class="form-control form-control-sm  @error('qteVendu') is-invalid @enderror" name="qteVendu"  value="@if($vendu){{old('qteVendu')?old('qteVendu'):$vendu->pu}}@else{{old('qteVendu')}}@endif"  autofocus required>
                                                    </div>
                                                    @error('qteVendu')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-2 " id="pan_btn">
                                                <!--<i class="spinner-border" v-if="productLoader"></i>-->
                                                <button type="submit" class="btn @if ($vendu)
                                                        btn-warning
@else
                                                        btn-success btn-block
                                                    @endif btn-sm text-center" style="margin-top: 2.1em">@if ($vendu)
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    @else
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    @endif
                                                </button>

                                                @if ($vendu)
                                                    <a href="{{ route('vendus.create', ['vente'=>$vente->id, 'vendu' => $vendu->id]) }}" class="btn btn-secondary btn-sm"  style="margin-top: 2.1em"><i class="fa-solid fa-delete-left"></i></a>
                                                @endif
                                            </div>
                                            <div class="col-sm-2" id="load" hidden>
                                                <i class="fa fa-spinner fa-spin fa-2x" style="margin-top: 1.1em"></i>
                                            </div>
                                        </div>
                                    @endif

                                    <hr>
                                </form>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped table-sm"  style="font-size: 12px">
                                            <thead class="text-white text-center bg-gradient-gray-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Camion</th>
                                                    <th>Qté Vendu</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($vente->count() > 0)
                                                    <?php $compteur=1; ?>
                                                    @php($somme = 0)
                                                    @php($total = 0)
                                                    @foreach($vente->vendus as $vendu)
                                                            @php($somme += ($vendu->qteVendu*$vendu->pu))
                                                            @php($total += $vendu->qteVendu)
                                                            <td>{{ $compteur++ }}</td>
                                                            <td>{{ $vendu->programmation->camion->immatriculationTracteur }}</td>
                                                            <td class="text-right">{{ $vendu->qteVendu }}</td>
                                                            <td class="text-center action">
                                                                <!--<a class="btn btn-success btn-sm" href="#"><i class="fa-regular fa-eye"></i></a>-->
                                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-default"  onclick="suppressionVendus({{$vendu->id}})" ><i class="fa-solid fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                        <tr>
                                                            <br/>
                                                            <td colspan="2" ><b>Total</b></td>
                                                            <td  class="text-right"><b>{{ number_format($total,2,","," ") }} T / {{number_format($vente->qteTotal, 2,","," ")}} T</b></td>
                                                            <td></td>
                                                        </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-center" id="footer_btn">
                                            <div class="col-sm-4">
                                                <a href="{{ route('ventes.index') }}" class="btn  btn-secondary" >
                                                    <i class="fa-solid fa-angles-left"></i>
                                                    Retour
                                                </a>
                                                <button class="btn btn-success"

                                                        @if(!$vente->byvente)
                                                            @if($vente->vendus()->sum('qteVendu') && $vente->vendus()->sum('qteVendu') == $vente->qteTotal) data-toggle="modal" data-target="#Validation_vente" @else disabled @endif
                                                        @else
                                                            @if($total == $vente->qteTotal) data-toggle="modal" data-target="#Validation_vente" @else disabled @endif
                                                        @endif
                                                ><i class="fa fa-check"></i> Valider la vente</button>
                                            </div>
                                    </div>
                                </div>
                            <div class="modal fade" id="modal-default">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Suppression</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="" id="form-suppression">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12 alert alert-warning">
                                                        Confirmez vous la suppression de la ligne ?
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <div class="col-sm-4">
                                                    <button data-dismiss="modal" class="btn btn-sm btn-secondary btn-block">
                                                        <i class="fa-solid fa-times"></i>
                                                        {{ __('Non') }}
                                                    </button>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="submit" class="btn btn-success btn-block">Oui
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>

                            <div class="modal fade" id="Validation_vente">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h4 class="modal-title">ATTENTION!</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{route('ventes.valider',['vente'=>$vente])}}" id="validation_vente">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12 alert alert-warning " >
                                                        <h3 class="text-danger font-italic">Lorsque vous validez la vente, vous ne pouvez plus apporter des modifications. Êtes vous sûr de cette modification ?</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <div class="col-sm-4">
                                                    <button data-dismiss="modal" class="btn btn-sm btn-secondary btn-block">
                                                        <i class="fa-solid fa-times"></i>
                                                        {{ __('Non') }}
                                                    </button>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="submit" class="btn btn-success btn-block">Oui
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>

                            <div class="modal fade" id="initialise_vente">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Rénitialisation vente</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="{{route('ventes.init',['vente'=>$vente])}}" id="init_vente">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12 alert alert-warning">
                                                        Confirmez vous la réinitialisation de la vente en cours. Cette action supprimera les camions déjà ajoutés.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <div class="col-sm-4">
                                                    <button data-dismiss="modal" class="btn btn-sm btn-secondary btn-block">
                                                        <i class="fa-solid fa-times"></i>
                                                        {{ __('Non') }}
                                                    </button>
                                                </div>
                                                <div class="col-sm-4">
                                                    <button type="submit" class="btn btn-success btn-block">Oui
                                                        <i class="fa-solid fa-check"></i>
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
        
        let user = {{auth()->user()->id}};
        $(document).ready(function (){
            selectDefaultDriver('{{old('programmation_id')}}');
        })

        function selectDefaultDriver(old){
            if($('#produit').val()) {
                $('#champ').attr('hidden','hidden');
                $('#loader').removeAttr('hidden')
                $('#bl option').removeAttr('selected');
                $('#bl').empty();
                
                axios.get('{{env('APP_BASE_URL')}}programmation/produits/' + $('#produit').val() + '/'+ user).then((response) => {
                    var programmation = response.data;
                    console.log(programmation);
                    $('#bl').append("<option selected disabled>Choisissez un BL</option>");
                    for (var i = 0; i < programmation.length; i++) {
                        var val = programmation[i].id;
                        var text = programmation[i].camion.immatriculationTracteur;
                        if(old == val)
                            $('#bl').append("<option selected value="+ val +">" + text + "</option>");
                        else
                            $('#bl').append("<option value="+ val +">" + text + "</option>");
                    }
                    $('.select2').select2(); 
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                    if(old)
                        getStockDisponible();
                }).catch(()=>{
                    $('#loader').attr('hidden', 'hidden');
                    $('#champ').removeAttr('hidden')
                })
            }
            else {
                $('#bl_0').attr('selected', 'true');
                $('.select2').select2()
            }
        }

        function suppressionVendus(id){
            let url = '{{env('APP_WEB_URL')}}';
            console.log(url);
            $('#form-suppression').attr('action','{{env('APP_WEB_URL')}}ventes/vendus/destroy/'+id);
        }
       
        function calculMontant(){
            let qteTotal = $('#qteTotal').val();
            let pu = $('#pu').val();
            let tt = $('#tt').val();
            let remise = $('#remise').val() ? $('#remise').val() : 0;
            if(qteTotal && pu){
                let montant = (pu*qteTotal) - remise;
                if(tt){
                    montant = montant + (tt*qteTotal);
                }
                $('#montant').val(montant);

            }
            else {
                $('#montant').val(0);
            }
        }

        function getStockDisponible(){

            if($('#bl').val()) {
                $('#champstock').attr('hidden','hidden');
                $('#loaderstock').removeAttr('hidden')
                axios.get('{{env('APP_BASE_URL')}}programmation/stock/' + $('#bl').val()).then((response) => {
                    var programmation = response.data;
                    $('#stockDispo').val(programmation);
                    $('#loaderstock').attr('hidden', 'hidden');
                    $('#champstock').removeAttr('hidden')
                }).catch(()=>{
                    $('#loaderstock').attr('hidden', 'hidden');
                    $('#champstock').removeAttr('hidden')
                    $('#stockDispo').val('')
                })
            }
            else {


            }
        }
    </script>
@endsection
