@extends('layouts.app')

@section('content')
      {{--   <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>COMPTABILITE</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Acceuil</a></li>
                                <li class="breadcrumb-item active">Listes des ventes en instences de comptabilisation.</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                @if($message = session('message'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ $message }}
                                    </div>
                                @endif
                                @if($message = session('error'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ $message }}
                                    </div>
                                @endif
                                @if($message = session('messageSupp'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ $message }}
                                    </div>
                                @endif
                                
                                 <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row --><!-- /.card-header -->
                           
                               
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div> --}}
        <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper kanban">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1>TRAITEMENT VENTE</h1>
          </div>
          <div class="col-sm-6 d-none d-sm-block">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Kanban Board</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content pb-3">
      <div class="container-fluid h-100">
        <div class="card card-row card-secondary">
          <div class="card-header">
            <h3 class="card-title">
              VENTE
            </h3>
          </div>
          <div class="card-body p-0">            
                
                  <table class="table table-sm pt-1">                    
                    <tbody>
                      <tr>
                        <th style="width: 50%">N° de Vente :</th>
                        <td style="width: 50%">{{ $vente->code }}</td>                      
                      </tr>
                      <tr>
                        <th>Date de Vente :</th>
                        <td>{{ date_format(date_create($vente->date),'d/m/Y') }}</td>
                      </tr>
                     {{--  <tr>
                        <th>Réf Facture :</th>
                        <td>123456</td>                      
                      </tr> --}}
                      <tr>
                        <th>N° IFU du Client :</th>
                         <td>@if(isset($client->ifu)) {{ $client->ifu  }} @endif</td>                      
                      </tr>
                      <tr>
                        <th>Nom du Client :</th>
                        <td>@if(isset($client->raisonSociale)) {{ $client->raisonSociale  }} @endif</td>                  
                      </tr>
                       <tr>
                        <th>N° de Commande :</th>
                        <td>{{ $vente->commandeclient->code }}</td>                      
                      </tr>
                      <tr>
                        <th>Article :</th>
                        <td>{{ $vente->produit->libelle }}</td>                      
                      </tr>
                      <tr>
                        <th>Quantité :</th>
                        <td id="qte">{{ number_format($vente->qteTotal,2,',',' ') }}</td>
                      </tr>
                      <tr>
                        <th>Prix de vente :</th>
                        <td id="prixUnitaire">{{ number_format($vente->pu,0,',',' ') }}</td>
                      </tr>
                      <tr>
                        <th>Montant:</th>
                        <td>{{ number_format(($vente->pu * $vente->qteTotal),0,""," ")}}</td>
                      </tr>
                      <tr>
                        <th>Transport :</th>
                        <td>{{number_format($vente->transport * $vente->qteTotal,0,"", " ")}}</td>
                      </tr>
                      <tr>
                        <th>Total Achat Client :</th>
                        <td> <span class="badge bg-success">{{ number_format(($vente->pu * $vente->qteTotal) + ($vente->transport * $vente->qteTotal),0,""," ") }}</span></td>
                      </tr>
                    </tbody>
                  </table>
                <!-- /.card-body -->
          </div>
        </div>
        <div class="card card-row card-secondary">
          <div class="card-header">
            <h3 class="card-title">
                TRAITEMENT
            </h3>
          </div>
          <div class="card-body">
            <form id="sendTraitement" action="{{ route('ventes.traiter',$vente->id) }}" method="POST"> 
              @csrf          
              <div class="form-group">
                  @php( $aifu = count($vente->filleuls) > 0 ? (bool)$vente->filleuls['ifu'] : (bool)$vente->commandeclient->client->ifu)
                  @php( $client = count($vente->filleuls) > 0 ? [$vente->filleuls,0] : [$vente->commandeclient->client,1])
                 <div class="border border-1 border-dark bg-dark text-white p-2">
                     {{  count($vente->filleuls) > 0 ? $vente->filleuls['nomPrenom']." (IFU: ".$vente->filleuls['ifu'].")" : $vente->commandeclient->client->raisonSociale.' ('.$vente->commandeclient->client->ifu.')' }}
                 </div>
              </div>                        
              <div class="form-group">
                  <label>Choix AIB</label>
                  <select class="form-control select2" id="aib" style="width: 100%;" onchange="operation()">
                      <option value="1.18" >AIB Inclus (PI/1.18)</option>
                      <option value="1.19" @if($aifu)selected="selected"@endif >AIB Inclus (PI/1.19)</option>
                      <option value="1.23" @if(!$aifu)selected="selected"@endif >AIB Inclus (PI/1.23)</option>
                  </select>
              </div>       
              
              <div class="form-group">
                  <label for="">Prix TTC</label>
                  <input type="number" name="prix_TTC" class="form-control" id="PrixTtc"  value="" onkeyup="operation()">
                  <input type="number" hidden=""  name="taux_aib" class="form-control" id="tauxAib" readonly value="0">
                  <input type="number"  hidden="" name="taux_tva" class="form-control" id="tauxTva" readonly value="0">
              </div> 
              <div class="form-group">
                <label for="">Prix Usine</label>
                <input type="text" class="form-control bg-dark font-weight-bolder" name="prixvisible" id="prixvisible" value="{{ number_format($vente->prix_Usine,0,""," ") }}" readonly>
                <input type="number" class="form-control bg-dark" hidden="" name="prix_Usine" id="PrixUsine" value="{{ $vente->prix_Usine }}" readonly>
              </div>
              <div class="form-group">
                  <label for="">Marge</label>
                  <input type="number" class="form-control" id="marge" name="marge" onkeyup="operation()" min="0" value="100">
              </div>
              
              <div class="card-footer">
                <button type="button" class="btn btn-sm btn-secondary ">Retour</button>
                <button type="submit" id="submit" class="btn btn-sm btn-success float-right">Valider</button>
              </div>
            </form> 
      </div>
        </div>
        
        <div class="card card-row card-secondary">
          <div class="card-header">
            <h3 class="card-title">
              DONNEE CALCULER
            </h3>
          </div>
          <div class="card-body">
                           
            <table class="table table-sm pt-1">                    
                <tbody>
                  <tr>
                    <th>Prix Usine Hors Taxe:</th>
                    <td id="PrixUsineHT">0</td>                      
                  </tr>
                 <tr>
                    <th>Prix Marge:</th>
                    <td id="PrixMarge">0</td>                      
                  </tr>
                  <tr>
                    <th>Prix Hors Taxe :</th>
                    <td id="PrixHT">0</td>                     
                  </tr>
                  <tr>
                    <th>Prix Bruite :</th>
                    <td id="PrixBruite">0</td>                  
                  </tr>
                  <tr>
                    <th>Net Hors Taxe :</th>
                     <td id="NHT">0</td>                       
                  </tr>
                  <tr>
                    <th>TVA :</th>
                    <td id="TVA">0</td>                      
                  </tr>
                  <tr>
                    <th>AIB :</th>
                    <td id="AIB">0</td>                      
                  </tr>
                  <tr>
                    <th>TTC :</th>
                    <td><span class="badge bg-success" id="TTC">0</span></td>                      
                  </tr>
                 
                </tbody>
              </table>
            
          </div>
        </div>
      </div>
      
<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Default Modal</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="filleuil" action=" {{ route('ventes.filleulfictive') }}" method="post">
          <div class="mb-3">
            <label for="" class="form-label">Nom et Prénoms</label>
            <input type="text" class="form-control" name="name" id="name" aria-describedby="helpId" placeholder=""/>
            <small id="helpId" class="form-text text-muted">Entrez le nom et le prénom </small>
          </div>
          <div class="mb-3">
            <label for="" class="form-label">IFU</label>
            <input type="number" class="form-control" name="ifu" id="ifu"  aria-describedby="helpId" placeholder=""/>
            <small id="helpId" class="form-text text-muted">Entrez l'ifu</small>
          </div>
        </form>        
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button"  class="btn btn-primary" onclick="enregistrer()">Save changes</button>
    </div>
  </div>

</div>

</div>
</section>

    
@endsection
@section('script')
    <script>
        $(document).ready(function() {
          const prixUnitaire = parseInt($('#prixUnitaire').text().replace(/\s/g, ""));
          const prixTtc = prixUnitaire;
            getPrixImpot()
           
        });
        
        function getPrixImpot() {
          // Récupérer le prix unitaire et convertir en nombre
         
          // Récupérer les taux de TVA et de marge
          const tauxHT = 1.19;
          const marge = $('#marge').val();

          // Calculer le prix HT à partir du prix usine TTC
          const prixUsineHT = ($('#PrixUsine').val() / tauxHT).toFixed(2);

          // Calculer le prix avec marge
          const prixMarge = parseFloat(prixUsineHT) + parseFloat(marge);

          
         // Calculer le prix hors Taxe à partir du prix TTC

          var prixHt = 0;

          if (parseFloat($('#aib').val()) == 1.23) {
            const PrixHTaib = ($('#PrixTtc').val() / 1.23).toFixed(2)
            prixHt = PrixHTaib
          } 
          if (parseFloat($('#aib').val()) == 1.19) {
            const PrixHTaib = ($('#PrixTtc').val() / 1.19).toFixed(2)
            prixHt = PrixHTaib
          } 
          if (parseFloat($('#aib').val()) == 1.18) {
            const PrixHTaib = ($('#PrixTtc').val() / 1.18).toFixed(2)
            prixHt = PrixHTaib
          } 
          
          console.log(' Prix ht : ' , prixHt);

          // Calculer le prix bruite à partir du prix ht
          const prixBruite = ( parseFloat(prixHt) * 1.18).toFixed(2);

          // Calculer le prix net hors taxe à partir du prix hors taxe
          const quantite = parseInt($('#qte').text());
          const prixNHT = parseFloat(prixHt) * quantite;

          // Calculer le prix AIB
          var prixAIBok = 0;

          if (parseFloat($('#aib').val()) == 1.23) {
            const prixAIB = parseFloat((prixNHT*5)/100);
            prixAIBok = prixAIB;
          } 
          if (parseFloat($('#aib').val()) == 1.19) {
            const prixAIB = parseFloat(prixNHT/100);
            prixAIBok = prixAIB
          } 
          if (parseFloat($('#aib').val()) == 1.18) {
            const prixAIB = 0;
            prixAIBok = prixAIB
          } 

          //Calculer TVA à partir du net hors taxe 
          const prixTVA = parseInt(prixNHT*0.18);
          
          
          console.log('AIB', $('#aib').val() );
          console.log('prixAIBok', prixAIBok);
          //Calculer TTC 
           const prixTTC = prixNHT+prixTVA+prixAIBok;

          // Mettre à jour les éléments à l'écran
         // $('#PrixTtc').val(prixTtc)
          $('#prixUsineHT').text(prixUsineHT);
          $('#PrixMarge').text(prixMarge.toFixed(2)); 
          $('#PrixHT').text(prixHt);
          $('#tauxAib').val(parseFloat($('#aib').val()).toFixed(2));
          $('#tauxTva').val(1.18);
          $('#PrixUsineHT').text(prixUsineHT); 
          $('#PrixBruite').text(prixBruite.toFixed(2));
          $('#NHT').text(prixNHT.toFixed(2));
          $('#TVA').text(prixTVA.toFixed(2));
          $('#AIB').text(prixAIBok.toFixed(2));
          $('#TTC').text(prixTTC.toFixed(2));
          
        //  operation();         
        }


        

        
        
        function operation() {
          console.log($('#PrixTtc').val());
          // Récupérer les taux de TVA et de marge
          const tauxHT = 1.19;
          const marge = $('#marge').val();

          // Calculer le prix HT à partir du prix usine TTC
          const prixUsineHT = ($('#PrixUsine').val() / tauxHT).toFixed(2);

          // Calculer le prix avec marge
          const prixMarge = parseFloat(prixUsineHT) + parseFloat(marge);

           
         // Calculer le prix hors Taxe à partir du prix TTC

         var prixHt = 0;

          if (parseFloat($('#aib').val()) == 1.23) {
            const PrixHTaib = ($('#PrixTtc').val() / 1.23).toFixed(2)
            prixHt = PrixHTaib
          } 
          if (parseFloat($('#aib').val()) == 1.19) {
            const PrixHTaib = ($('#PrixTtc').val() / 1.19).toFixed(2)
            prixHt = PrixHTaib
          } 
          if (parseFloat($('#aib').val()) == 1.18) {
            const PrixHTaib = ($('#PrixTtc').val() / 1.18).toFixed(2)
            prixHt = PrixHTaib
          } 

          console.log('Prix ht : ' , prixHt);

          // Calculer le prix bruite à partir du prix ht
          const prixBruite = parseFloat(prixHt) * 1.18

          // Calculer le prix net hors taxe à partir du prix hors taxe
          const quantite = parseInt($('#qte').text());

          const prixNHT = parseFloat(prixHt) * quantite;

          //Calculer TVA à partir du net hors taxe 
          const prixTVA = parseInt(prixNHT * 0.18);
          
          // Calculer le prix AIB
          var prixAIBok = 0

          if ($('#aib').val() == 1.23) {
            const prixAIB = parseFloat((prixNHT*5)/100);
            prixAIBok = prixAIB;
          } 
          if ($('#aib').val() == 1.19) {
            const prixAIB = parseFloat(prixNHT/100);
            prixAIBok = prixAIB
          } 
          if ($('#aib').val() == 1.18) {
            const prixAIB = 0;
            prixAIBok = prixAIB
          } 

          console.log('prixAIB', $('#aib').val());
          console.log('prixAIBok', prixAIBok);
          //Calculer TTC 
           const prixTTC = prixNHT+prixTVA+prixAIBok;

          // Mettre à jour les éléments à l'écran
          //$('#PrixTtc').val(prixTtc)
          $('#prixUsineHT').text(prixUsineHT);
          $('#PrixMarge').text(prixMarge.toFixed(2)); 
          $('#PrixHT').text(prixHt);
          $('#PrixUsineHT').text(prixUsineHT); 
          $('#PrixBruite').text(prixBruite);
          $('#NHT').text(prixNHT.toFixed(2));
          $('#TVA').text(prixTVA.toFixed(2));
          $('#AIB').text(prixAIBok.toFixed(2));
          $('#TTC').text(prixTTC.toFixed(2));
            
        }

        

    </script>
    
@endsection
