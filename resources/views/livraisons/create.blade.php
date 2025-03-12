@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="pb-3">LIVRAISONS</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('livraisons.index') }}">Listes des livraisons</a></li>
                        <li class="breadcrumb-item active">Confirmation</li>
                    </ol>
                </div>
            </div>

            @include('livraisons.enteteproduit')
    </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Confirmer Livraison</h3>
                            </div>
                            <form method="POST" action="{{route('livraisons.store',['programmation'=>$programmation->id])}}"  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Date Livraison<span class="text-danger">*</span></label>
                                                <input type="date" id="datelivrer" class="form-control form-control-sm @error('datelivrer') is-invalid @enderror text-center" name="datelivrer"  value="@if($programmation->datelivrer){{old('datelivrer')?old('datelivrer'):$programmation->datelivrer}}@else{{ date('Y-m-d') }}@endif"  autocomplete="datelivrer" autofocus required>
                                                @error('datelivrer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Qte Livrer<span class="text-danger">*</span></label>
                                                <input type="number" hidden class="form-control form-control-sm" name="qtelivrer" style="text-transform: uppercase"  value="@if($programmation->qtelivrer){{old('qtelivrer')?old('qtelivrer'):$programmation->qteprogrammer-$programmation->vendus->sum('qteVendu')}}@else{{@old('qtelivrer')?@old('qtelivrer'):$programmation->qteprogrammer}}@endif"  autocomplete="qtelivrer" min="1" autofocus required>
                                                <input type="number" disabled class="form-control form-control-sm" style="text-transform: uppercase"  value="@if($programmation->qtelivrer){{old('qtelivrer')?old('qtelivrer'):$programmation->qteprogrammer-$programmation->vendus->sum('qteVendu')}}@else{{@old('qtelivrer')?@old('qtelivrer'):$programmation->qteprogrammer}}@endif"  autocomplete="qtelivrer" min="1" autofocus required>
                                                @error('qtelivrer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>BL<span class="text-danger">*</span> {{$programmation->bl_gest}} </label>
                                                <input type="text" class="form-control form-control-sm" name="bl" style="text-transform: uppercase" hidden value="{{ $programmation->bl_gest }}"  autocomplete="bl" autofocus>
                                                <input type="text" class="form-control form-control-sm" disabled style="text-transform: uppercase"  value="{{ $programmation->bl_gest }}">
                                                @error('bl')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="file">Document @if ($programmation->bl == NULL)
                                                    <span class="text-danger">*</span>
                                                @endif</label>
                                                <input type="file" name="document" class="form-control form-control-sm @error('document') is-invalid @enderror" data-mask value="{{ old('document') }}" {{-- {{ $programmation->transfert ? : 'required' }} --}} >
                                                @error('document')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Observation</label>
                                                <textarea class="form-control form-control-sm  @error('observation') is-invalid @enderror" name="observation" id="exampleFormControlTextarea1" style="text-transform: initial"  autocomplete="observation" autofocus rows="3">{{ old('observation')?old('observation'):$programmation->observation }}</textarea>
                                                @error('observation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if($programmation->document)
                                        <div class="row mb-2">
                                            <div class="col-6 text-center">
                                                <h4><a href="{{ asset('storage/'.$programmation->document) }}" class="btn btn-success" target="_blank"><i class="fa fa-file-pdf"></i> Afficher le document</a></h4>
                                            </div>
                                            <div class="col-sm-6 text-center">
                                                <div class="form-group">
                                                    <label for="statut" style="display: block">Supprimer le document</label>
                                                    <input id="statut" type="checkbox"  name="remoovdoc" style="width: 20px; height: 20px"  />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <a href="{{ route('livraisons.index') }}" class="btn btn-sm btn-secondary btn-block">
                                                <i class="fa-solid fa-circle-left"></i>
                                                {{ __('Retour') }}
                                            </a>
                                        </div>
                                        <div class="col-sm-4">
                                            <!-- @if ($programmation->bl && !$programmation->transfert)
                                                <button type="submit" class="btn btn-sm btn-warning btn-block">
                                                    {{ __('Modifier') }}
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-success btn-block" @if ((intval($programmation->qteprogrammer) - intval($programmation->vendus->sum("qteVendu"))) == 0) disabled @endif>
                                                    {{ __('Enregistrer') }}
                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                </button>
                                            @endif -->

                                            <button type="submit" class="btn btn-sm btn-success btn-block" @if ((intval($programmation->qteprogrammer) - intval($programmation->vendus->sum("qteVendu"))) == 0) disabled @endif>
                                                {{ __('Enregistrer') }}
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(function(){
        @if(session('message') || $programmation || $errors)
            window.scrollTo(0, $("#entete_produit").offset().top);
        @endif
    })
    </script>    
@endsection
