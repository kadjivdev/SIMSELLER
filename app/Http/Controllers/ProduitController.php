<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\TypeProduit;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Models\Fournisseur;

class ProduitController extends Controller
{
    protected $produits;

    public function __construct(Produit $produits)
    {
        $this->produits = $produits;
    }

    public function index()
    {
        $produits = $this->produits->orderBy('libelle','desc')->get();
        return view('produits.index', compact('produits'));
    }

    public function create(TypeProduit $typeproduits)
    {
        $typeproduits = $typeproduits->orderBy('libelle')->get();
        return view('produits.create', compact('typeproduits'));
    }

    public function store(StoreProduitRequest $request)
    {
        $request->all();
        /* Uploader les images dans la base de données */

        if($request->file('photo')){
            $image = $request->file('photo');
            $photo = time().'.'.$image->extension();
            $image->move(public_path('images'), $photo);
        }
        else{
            $photo = null;
        }

        //dd($profile);
        $produits = $this->produits::create([
            'libelle' => strtoupper($request->libelle),
            'description' => ucwords($request->description),
            'type_produit_id' => $request->typeproduit,
            'prix_fourniture' => $request->prix_fourniture,
            'photo' => $photo,
        ]);

        if($produits){
            Session()->flash('message', 'Produit ajouté avec succès!');
            return redirect()->route('produits.index');
        }
    }



    public function show($id)
    {
        $produits = $this->produits->findOrFail($id);

        return view('produits.show', compact('produits'));
    }


    public function edit(TypeProduit $typeproduits, $id)
    {
        $typeproduits = $typeproduits->orderBy('libelle')->get();
        $produits = $this->produits->findOrFail($id);

        return view('produits.edit', compact('produits', 'typeproduits'));

    }


    public function photo(Request $request)
    {
        if(!$request->remoov){
            request() ->validate([
                'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
            ]);

            /* Uploader les images dans la base de données */
            $image = $request->file('photo');
            $photo = time().'.'.$image->extension();
            $image->move(public_path('images'), $photo);

        }
        else{
            $photo = null;
        }

        $produits = $this->produits->findOrFail($request->id);

        $produits = $produits->update([
            'photo'=>$photo
        ]);


        if($produits){
            Session()->flash('message', 'Logo produit modifié avec succès!');
            return redirect()->route('produits.index');
        }
    }



    public function update(UpdateProduitRequest $request)
    {

        $produits = $this->produits->findOrFail($request->id);

        $produits = $produits->update([
            'libelle' => strtoupper($request->libelle),
            'description' => ucwords($request->description),
            'type_produit_id' => $request->type_produit_id,
            'prix_fourniture' => $request->prix_fourniture,
        ]);

        if($produits){
            Session()->flash('message', 'Produit modifié avec succès!');
            return redirect()->route('produits.index');
        }
    }


    public function delete($id)
    {
        $produits = $this->produits->findOrFail($id);
        $ver = $produits->fournisseurs()->get();
        if(count($ver)>0){
            Session()->flash('error', "Désolé! Le produit ".$produits->libelle." est déjà lié à une entité!");
            return redirect()->route('produits.index');
        }else{
            return view('produits.delete', compact('produits'));
        }

    }



    public function destroy($id)
    {
        $produits = $this->produits->findOrFail($id)->delete();
        if($produits){
            Session()->flash('message', 'Produit modifié avec succès!');
            return redirect()->route('produits.index');
        }
    }
}
