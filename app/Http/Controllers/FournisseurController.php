<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\CategorieFournisseur;
use App\Http\Requests\UpdateLogoRequest;
use App\Http\Requests\StoreFournisseurRequest;
use App\Http\Requests\UpdateFournisseurRequest;

class FournisseurController extends Controller
{
    protected $fournisseurs;

    public function __construct(Fournisseur $fournisseurs)
    {
        $this->fournisseurs = $fournisseurs;
    }


    public function index()
    {
        $fournisseurs = $this->fournisseurs->orderBy('sigle','desc')->get();
        return view('fournisseurs.index', compact('fournisseurs'));
    }



    public function create(CategorieFournisseur $categoriefournisseurs)
    {
        $categoriefournisseurs = $categoriefournisseurs->orderBy('libelle')->get();
        return view('fournisseurs.create', compact('categoriefournisseurs'));
    }



    public function store(StoreFournisseurRequest $request)
    {

        if($request->photo){
            /* Uploader les images dans la base de données */
            $image = $request->file('photo');
            $logo = time().'.'.$image->extension();
            $image->move(public_path('images'), $logo);
            //dd($profile);

            $fournisseurs = Fournisseur::create([
                'sigle' => strtoupper($request->sigle),
                'raisonSociale' => strtoupper($request->raisonSociale),
                'telephone' => $request->telephone,
                'email' => $request->email,
                'adresse' => ucwords($request->adresse),
                'categorie_fournisseur_id' => $request->categorie_fournisseur_id,
                'logo' => $logo,
            ]);
        }else{
            $fournisseurs = Fournisseur::create([
                'sigle' => strtoupper($request->sigle),
                'raisonSociale' => strtoupper($request->raisonSociale),
                'telephone' => $request->telephone,
                'email' => $request->email,
                'adresse' => ucwords($request->adresse),
                'categorie_fournisseur_id' => $request->categorie_fournisseur_id,
            ]);
        }

        if($fournisseurs){
            Session()->flash('message', 'Fournisseur ajouté avec succès!');
            return redirect()->route('fournisseurs.index');
        }
    }



    public function show(Produit $produits, $id)
    {
        $fournisseurs = $this->fournisseurs->findOrFail($id);

        $commercialisers = $fournisseurs->produits()->pluck('produit_id');
        $produits = $produits->whereNotIn('id', $commercialisers)->get();

        return view('fournisseurs.show', compact('fournisseurs', 'produits'));
    }


    public function edit(CategorieFournisseur $categoriefournisseurs, $id)
    {
        $categoriefournisseurs = $categoriefournisseurs->orderBy('libelle')->get();
        $fournisseurs = $this->fournisseurs->findOrFail($id);

        return view('fournisseurs.edit', compact('fournisseurs', 'categoriefournisseurs'));

    }


    public function logo(Request $request, Fournisseur $fournisseurs)
    {

        if(!$request->remoov){
            request() ->validate([
                'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
            ]);

            /* Uploader les images dans la base de données */
            $image = $request->file('photo');
            $profile = time().'.'.$image->extension();
            $image->move(public_path('images'), $profile);

        }
        else{
            $profile = null;
        }

        $fournisseurs = $this->fournisseurs->findOrFail($request->id);

        $fournisseurs = $fournisseurs->update([
            'logo' => $profile,
        ]);

        if($fournisseurs){
            Session()->flash('message', 'Logo fournisseur modifié avec succès!');
            return redirect()->route('fournisseurs.index');
        }
    }



    public function update(UpdateFournisseurRequest $request, Fournisseur $fournisseurs)
    {

        $fournisseurs = $this->fournisseurs->findOrFail($request->id);

        $fournisseurs = $fournisseurs->update([
            'sigle' => strtoupper($request->sigle),
            'raisonSociale' => strtoupper($request->raisonSociale),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => ucwords($request->adresse),
            'categorie_fournisseur_id' => $request->categorie_fournisseur_id,
        ]);

        if($fournisseurs){
            Session()->flash('message', 'Fournisseur modifié avec succès!');
            return redirect()->route('fournisseurs.index');
        }
    }


    public function delete($id)
    {
        $fournisseurs = $this->fournisseurs->findOrFail($id);
        $interlocuteurs = $fournisseurs->interlocuteurs;
        $telephones = $fournisseurs->telephones;
        $produits = $fournisseurs->produits;
        $boncommandes = $fournisseurs->boncommandes;

        if(count($interlocuteurs) > 0 || count($telephones) > 0 || count($produits) > 0 || count($boncommandes) > 0){
            Session()->flash('suppression', 'Attention vous ne pouvez pas supprimer l\'avaliseur '.$fournisseurs->raisonSociale.'. Il est en relation avec d\'autres entités');
            return redirect()->route('fournisseurs.index');
        }

        return view('fournisseurs.delete', compact('fournisseurs'));

    }



    public function destroy($id)
    {
        $fournisseurs = $this->fournisseurs->findOrFail($id)->delete();
        if($fournisseurs){
            Session()->flash('message', 'Fournisseur supprimé avec succès!');
            return redirect()->route('fournisseurs.index');
        }
    }
}
