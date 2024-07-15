<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Fournisseur;
use App\Rules\commercialiserRule;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCommercialiserRequest;
use App\Http\Requests\UpdateCommercialiserRequest;

class CommercialiserController extends Controller
{
    protected $commercialisers, $fournisseurs, $produits;

    public function __construct(Fournisseur $fournisseurs, Produit $produits)
    {
        $this->fournisseurs = $fournisseurs;
        $this->produits = $produits;
    }

    
    public function index()
    {
        $banques = $this->commercialisers::all();
        return view('commercialisers.index', compact('commercialisers'));
    }


    
    public function create()
    {
        return view('banques.create');
    }


    
    public function store(StoreCommercialiserRequest $request)
    {

        $produits = $request->produit_id;

        Validator::make($produits, [
            'produit_id' => new commercialiserRule($request->fournisseur_id),
        ]);


        $fournisseurs = $this->fournisseurs->findOrFail($request->fournisseur_id);

        foreach($produits as $produit) {

            $commercialisers = $fournisseurs->produits()->attach([$produit ]);

        }

        if(!$commercialisers){
            Session()->flash('message', 'Produit fournisseur ajoutée avec succès!');
            Session()->flash('typeajout', '1');
            return redirect()->route('fournisseurs.show', ['id'=>$fournisseurs->id]);
        }
    }


    
    public function show()
    {
        //
    }

    
    public function edit($id)
    {
        $commercialisers = $this->commercialisers->findOrFail($id);

        return view('commercialisers.edit', compact('commercialisers'));

    }


    
    public function update(UpdateCommercialiserRequest $request)
    {

    }


    public function delete($fournisseur_id, $produit_id)
    {
        $fournisseurs = $this->fournisseurs->findOrFail($fournisseur_id);
        $produits = $fournisseurs->produits()->findOrFail($produit_id);
        //dd($fournisseurs, $produits);
        return view('commercialisers.delete', compact('fournisseurs', 'produits'));

    }


    
    public function destroy($fournisseur_id, $produit_id)
    {
        $fournisseurs = $this->fournisseurs->findOrFail($fournisseur_id);
        $commercialiser = $fournisseurs->produits()->detach([$produit_id]);
        if($commercialiser){
            Session()->flash('message', 'Produit fournisseur supprimé avec succès!');
            return redirect()->route('fournisseurs.show', ['id'=>$fournisseur_id]);
        }
    }
}
