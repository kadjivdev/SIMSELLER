<?php

namespace App\Http\Controllers;

use App\Models\CategorieFournisseur;
use App\Http\Requests\StoreCategorieFournisseurRequest;
use App\Http\Requests\UpdateCategorieFournisseurRequest;

class CategorieFournisseurController extends Controller
{
    protected $categoriefournisseurs;
    public function __construct(CategorieFournisseur $categoriefournisseurs){
        $this->categoriefournisseurs = $categoriefournisseurs;
    }

    public function index()
    {
        $categoriefournisseurs = $this->categoriefournisseurs->orderBy('libelle')->get();
        return view('categoriefournisseurs.index', compact('categoriefournisseurs'));
    }

    public function create()
    {
        return  view('categoriefournisseurs.create');
    }

    public function store(StoreCategorieFournisseurRequest $request)
    {
        $categoriefournisseurs = CategorieFournisseur::create([
            'libelle' => strtoupper($request->libelle),
        ]);

        if($categoriefournisseurs){
            Session()->flash('message', 'Catégorie fournisseur ajoutée avec succès!');
            return redirect()->route('categoriefournisseurs.index');
        }
    }

    public function show(CategorieFournisseur $categorieFournisseur)
    {
        //
    }

    public function edit($id)
    {
        $categoriefournisseurs = $this->categoriefournisseurs->findOrFail($id);

        return  view('categoriefournisseurs.edit', compact('categoriefournisseurs'));
    }

    public function update(UpdateCategorieFournisseurRequest $request)
    {
        $categoriefournisseurs = $this->categoriefournisseurs->findOrFail($request->id);

        $categoriefournisseurs = $categoriefournisseurs->update([
            'libelle' => strtoupper($request->libelle),
        ]);

        if($categoriefournisseurs){
            Session()->flash('message', 'Catégorie fournisseur modifiée avec succès!');
            return redirect()->route('categoriefournisseurs.index');
        }
    }

    public function delete($id)
    {
        $categoriefournisseurs = $this->categoriefournisseurs->findOrFail($id);

        return  view('categoriefournisseurs.delete', compact('categoriefournisseurs'));
    }

    public function destroy($id)
    {
        $categoriefournisseurs = $this->categoriefournisseurs->findOrFail($id)->delete();

        if($categoriefournisseurs){
            Session()->flash('message', 'Catégorie fournisseur supprimée avec succès!');
            return redirect()->route('categoriefournisseurs.index');
        }
    }
}
