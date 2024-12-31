<?php

namespace App\Http\Controllers;

use App\Models\TypeProduit;
use App\Http\Requests\StoreTypeProduitRequest;
use App\Http\Requests\UpdateTypeProduitRequest;

class TypeProduitController extends Controller
{
    protected $typeproduits;

    public function __construct(TypeProduit $typeproduits)
    {
        $this->typeproduits = $typeproduits;
    }

    public function index()
    {
        $typeproduits = $this->typeproduits->orderBy('libelle')->get();
        return view('typeproduits.index', compact('typeproduits'));
    }

    public function create()
    {
        return  view('typeproduits.create');
    }

    public function store(StoreTypeProduitRequest $request)
    {
        $typeproduits = TypeProduit::create([
            'libelle' => strtoupper($request->libelle),
        ]);

        if ($typeproduits) {
            Session()->flash('message', 'Type produit ajouté avec succès!');
            return redirect()->route('typeproduits.index');
        }
    }

    public function show(TypeProduit $typeproduits)
    {
        //
    }

    public function edit(TypeProduit $typeproduit)
    {
        return  view('typeproduits.edit', compact('typeproduit'));
    }

    public function update(UpdateTypeProduitRequest $request, TypeProduit $typeproduit)
    {
        $typeproduits = $typeproduit->update([
            'libelle' => strtoupper($request->libelle),
        ]);

        if ($typeproduits) {
            Session()->flash('message', 'Type produit modifié avec succès!');
            return redirect()->route('typeproduits.index');
        }
    }

    public function delete(TypeProduit $typeproduit)
    {
        $ver = $typeproduit->produits()->get();

        if (count($ver) > 0) {
            Session()->flash('error', "Désolé! Le type de produit " . $typeproduit->libelle . " est déjà lié à un produit, veuillez d'abord supprimé le produit.");
            return redirect()->route('typeproduits.index');
        } else {
            return  view('typeproduits.delete', compact('typeproduit'));
        }
    }

    public function destroy(TypeProduit $typeproduit)
    {
        $typeproduits = $typeproduit->delete();

        if ($typeproduits) {
            Session()->flash('message', 'Type produit supprimé avec succès!');
            return redirect()->route('typeproduits.index');
        }
    }
}
