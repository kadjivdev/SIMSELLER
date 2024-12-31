<?php

namespace App\Http\Controllers;

use App\Models\TypeCommande;
use App\Http\Requests\StoreTypeCommandeRequest;
use App\Http\Requests\UpdateTypeCommandeRequest;

class TypeCommandeController extends Controller
{
    protected $typecommandes;

    public function __construct(TypeCommande $typecommandes)
    {
        $this->typecommandes = $typecommandes;
    }

    public function index()
    {
        $typecommandes = $this->typecommandes->orderBy('libelle')->get();
        return view('typecommandes.index', compact('typecommandes'));
    }

    public function create()
    {
        return  view('typecommandes.create');
    }

    public function store(StoreTypeCommandeRequest $request)
    {
        $typecommandes = TypeCommande::create([
            'libelle' => strtoupper($request->libelle),
        ]);

        if ($typecommandes) {
            Session()->flash('message', 'Type commande ajouté avec succès!');
            return redirect()->route('typecommandes.index');
        }
    }

    public function show(TypeCommande $typecommandes)
    {
        //
    }

    public function edit($id)
    {
        $typecommandes = $this->typecommandes->findOrFail($id);
        return  view('typecommandes.edit', compact('typecommandes'));
    }

    public function update(UpdateTypeCommandeRequest $request)
    {
        $typecommandes = $this->typecommandes->findOrFail($request->id);

        $typecommandes = $typecommandes->update([
            'libelle' => strtoupper($request->libelle),
        ]);

        if ($typecommandes) {
            Session()->flash('message', 'Type commande modifié avec succès!');
            return redirect()->route('typecommandes.index');
        }
    }

    public function delete($id)
    {
        $typecommandes = $this->typecommandes->findOrFail($id);
        return  view('typecommandes.delete', compact('typecommandes'));
    }

    public function destroy($id)
    {
        $typecommandes = $this->typecommandes->findOrFail($id)->delete();
        if ($typecommandes) {
            Session()->flash('message', 'Type commande supprimé avec succès!');
            return redirect()->route('typecommandes.index');
        }
    }
}
