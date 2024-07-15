<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use id;
use Exception;
use App\Models\Zone;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Commander;
use App\Models\Parametre;
use App\Models\TypeCommande;
use Illuminate\Http\Request;
use App\Models\CommandeClient;
use Illuminate\Validation\Rule;
use App\tools\CommandeClientTools;
use App\tools\ControlesTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommandeClientController extends Controller
{


    public function index()
    {
        $commandeclients = CommandeClient::orderBy('code', 'desc')->whereNull('byvente')->get();
        return view('commandeclients.index', compact('commandeclients'));
    }



    public function create(Request $request, CommandeClient $commandeclient=NULL)
    {
        $typecommandes = TypeCommande::orderBy('libelle')->get();
        $clients = Client::where('sommeil', FALSE)->orderBy('code')->get();
        $produits = Produit::orderBy('libelle')->get();
        $zones = Zone::orderBy('libelle')->get();
        $redirectto = $request->redirectto;
        return view('commandeclients.create', compact('commandeclient', 'typecommandes', 'clients', 'produits', 'zones','redirectto'));
    }



    public function store(Request $request,CommandeClient $commandeclient=NULL)
    {

        try {

            if($commandeclient){

                $validator = Validator::make($request->all(), [
                    'code' => ['required', 'string', 'max:255', Rule::unique('commande_clients')->ignore($commandeclient->id)],
                    'dateBon' => ['required','before_or_equal:'.date('Y-m-d')],
                    'client_id' => ['required'],
                    'zone_id' => ['required'],
                    'type_commande_id' => ['required'],
                    ]);

                    if($validator->fails()){
                        return redirect()->route('commandeclients.create', ['commandeclient' => $commandeclient->id])->withErrors($validator->errors())->withInput();
                    }

                    $ver = NULL;

                    if($commandeclient->client_id != $request->client_id){
                        $ver = TRUE;
                    }

                    $commandeclients = $commandeclient->update([
                        'dateBon' => $request->dateBon,
                        'client_id' => $request->client_id,
                        'zone_id' => $request->zone_id,
                    ]);


                    if ($commandeclients) {

                        if($ver == TRUE){
                            CommandeClientTools::viderProduitCommander($commandeclient);
                            CommandeClientTools::calculerTotalCommande($commandeclient);
                        }
                        Session()->flash('messagebc', 'Les informations de l\'entête de la commande client on été mise à jour avec succès.');
                        return redirect()->route('commandeclients.edit', ['commandeclient' => $commandeclient->id]);
                    }
            }else{

                $validator = Validator::make($request->all(), [
                    'dateBon' => ['required','before_or_equal:'.date('Y-m-d')],
                    'client_id' => ['required'],
                    'zone_id' => ['required'],
                    'type_commande_id' => ['required'],
                    ]);

                    if($validator->fails()){
                        return redirect()->route('commandeclients.create')->withErrors($validator->errors())->withInput();
                    }

                    $format = env('FORMAT_COMMANDE_CLIENT');
                    $parametre = Parametre::where('id', env('COMMANDE_CLIENT'))->first();
                    $code = $format.str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $commandeclients = CommandeClient::create([
                        'code' => $code,
                        'dateBon' => $request->dateBon,
                        'statut' => "Préparation",
                        'type_commande_id' => $request->type_commande_id,
                        'client_id' => $request->client_id,
                        'zone_id' => $request->zone_id,
                        'users' => Auth::user()->id,
                    ]);

                    if($commandeclients){

                        $valeur = $parametre->valeur;

                        $valeur = $valeur+1;

                        $parametres = Parametre::find(env('COMMANDE_CLIENT'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);


                        if ($parametre) {
                            Session()->flash('message', 'Commande client ajouté avec succès!');
                            return redirect()->route('commandeclients.edit', ['commandeclient' => $commandeclients->id]);
                        }
                    }

            }


    }catch (Exception $e){
        if(env('APP_DEBUG') == TRUE){
            return $e;
        }else{
            Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
            return redirect()->route('boncommandes.index');
        }
    }
    }

    public function validerCommande(CommandeClient $client){
        $client->update(['statut'=>'Validée']);
        Session()->flash('message', 'Commande client validée avec succès!');
        return redirect()->route('commandeclients.index');
    }
    public function annulation(CommandeClient $commandeclient)
    {
        return view('commandeclients.annulation', compact('commandeclient'));

    }


    public function annuler(CommandeClient $commandeclient)
    {
        try {
                $commandeclient->statut = 'Préparation';
                $commandeclients = $commandeclient->update();

                if($commandeclients){
                    Session()->flash('message', 'Commande client annulée avec succès!');
                    return redirect()->route('commandeclients.index');
                }
        }catch (Exception $e){
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('commandeclients.index');
            }
        }
    }


    public function show(CommandeClient $commandeclient)
    {
        $detailCommandes = $commandeclient->commanders;
        return view('commandeclients.show', compact('commandeclient', 'detailCommandes'));
    }


    public function edit(CommandeClient $commandeclient, Commander $commander = NULL)
    {
        $produits = Produit::all();
        return view('commandeclients.edit', compact('commandeclient', 'commander', 'produits'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommandeClient  $commandeClient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommandeClient $commandeClient)
    {
        //
    }

    public function delete(CommandeClient $commandeclient)
    {

        return view('commandeclients.delete', compact('commandeclient'));

    }



    public function destroy(CommandeClient $commandeclient)
    {
        ControlesTools::generateLog($commandeclient,'CommandeClient','Suppression ligne');
        $commandeclients = $commandeclient->delete();
        if($commandeclients){
            Session()->flash('message', 'Commande client supprimée avec succès!');
            return redirect()->route('commandeclients.index');
        }
    }
    
}
