<?php

namespace App\Http\Controllers\Api;

use App\Models\Produit;
use App\Models\Parametre;
use App\Models\BonCommande;
use App\Models\Fournisseur;
use App\Models\TypeCommande;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreBonCommandeRequest;
use App\Http\Requests\UpdateBonCommandeRequest;

class BonCommandeController extends Controller
{
    protected $boncommandes, $typecommandes, $fournisseurs, $produits;

    public function __construct(BonCommande $boncommandes, TypeCommande $typecommandes, Fournisseur $fournisseurs, Produit $produits)
    {
        $this->boncommandes = $boncommandes;
        $this->typecommandes = $typecommandes;
        $this->fournisseurs = $fournisseurs;
        $this->produits = $produits;
    }


    public function index()
    {
        /*$boncommandes = $this->boncommandes->orderBy('code')->get();
        $typecommandes = $this->typecommandes->orderBy('libelle')->get();
        $fournisseurs = $this->fournisseurs->orderBy('sigle')->get();
        $produits = $this->produits->orderBy('libelle')->get();*/
        return view('boncommandes.index', compact('boncommandes', 'typecommandes','fournisseurs', 'produits'));
    }



    public function create()
    {
        $typecommandes = $this->typecommandes->orderBy('libelle')->get();
        $fournisseurs = $this->fournisseurs->orderBy('sigle')->get();
        $produits = $this->produits->orderBy('libelle')->get();
        return view('boncommandes.create', compact('typecommandes', 'fournisseurs', 'produits'));
    }



    public function store(Request $request)
    {
        $validator= Validator::make($request->all(), BonCommande::$rules);
        if ($validator->fails()){
            return response($validator->errors(), 422);
        }
        else
        {
            $boncommandes = BonCommande::create([
                'code' => $request->code,
                'dateBon' => $request->dateBon,
                'statut' => $request->statut,
                'type_commande_id' => $request->type_commande_id,
                'fournisseur_id' => $request->fournisseur_id,
                'users' => Auth::user()->name,
            ]);

            if($boncommandes){
                $valeur = $request->valeur;

                $valeur = $valeur+1;

                $parametres = Parametre::find(env('PARAMETRE'));

                $parametre = $parametres->update([
                    'valeur' => $valeur,
                ]);

                if ($parametre) {
                    return response( ['commande'=>$boncommandes,'message'=>'Bon de commande enregistrer avec succès!'], 200);
                }

            }else{

                return response(['error'=>'Echec: Une erreur est survenue lors de l\'enregistrement du bon de commande.
            Veuiller réessayer et si l\'erreur persiste, contacter le concepteur'], 401);

        }
        }
    }



    public function show($id)
    {
        $boncommandes = $this->boncommandes->findOrFail($id);

        return view('boncommandes.show', compact('boncommandes'));
    }


    public function edit(TypeCommande $typecommandes, $id)
    {
        $typecommandes = $typecommandes->orderBy('libelle')->get();
        $boncommandes = $this->boncommandes->findOrFail($id);

        return view('fournisseurs.edit', compact('boncommandes', 'typecommandes'));

    }



    public function update(Request $request)
    {
       
        $rules = [
            'dateBon' => ['required', 'date'],
            'statut' => ['required', 'string', 'max:255'],
            'type_commande_id' => ['required'],
            'fournisseur_id' => ['required'],
        ];

        $validator= Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response($validator->errors(), 422);
        }
        else
        {
            $boncommande =$this->boncommandes->findOrFail($request->commande_id);
            
            $boncommandes = $boncommande->update([
                'code' => $request->code,
                'dateBon' => $request->dateBon,
                'statut' => $request->statut,
                'type_commande_id' => $request->type_commande_id,
                'fournisseur_id' => $request->fournisseur_id,
                'users' => $request->users,
            ]);
            if ($boncommandes) {
                return response( ['message'=>'Bon de commande modifier avec succès!']);
            }else{
                return response(['error'=>'Echec: Une erreur est survenue lors de l\'enregistrement du bon de commande. Veuiller réessayer et si l\'erreur persiste, contacter le concepteur'], 401); 
            }
        }
    }


    public function delete($id)
    {
        $boncommandes = $this->boncommandes->findOrFail($id);

        return view('boncommandes.delete', compact('boncommandes'));

    }



    public function destroy($id)
    {
        $boncommandes = $this->boncommandes->findOrFail($id)->delete();
        if($boncommandes){
            Session()->flash('message', 'Bon de commande supprimé avec succès!');
            return redirect()->route('boncommandes.index');
        }

    }

    public function dateTraitement(BonCommande $boncommandes,$date){
        $boncommandes->date_traitement_bc = $date;
        if ($boncommandes->update()) {
            return response()->json('Date de traitement Enrégistrer avec succès',200);
        }else {
            return response()->json('Echec d\' enrégistrement de Date de traitement',403);
        }
    }


}
