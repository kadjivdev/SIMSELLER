<?php

namespace App\Http\Controllers;

use App\Models\Telephone;
use App\Http\Requests\StoreTelephoneRequest;
use App\Http\Requests\UpdateTelephoneRequest;

class TelephoneController extends Controller
{
    protected $telephones;

    public function __construct(Telephone $telephones)
    {
        $this->telephones = $telephones;
    }

    public function index()
    {
        $telephones = $this->telephones->orderBy('type')->get();
        return view('telephones.index', compact('telephones'));
    }

    public function create()
    {
        return view('telephones.create');
    }

    public function store(StoreTelephoneRequest $request)
    {
        $telephones = $this->telephones::create([
            'numero' => $request->numero,
            'type' => $request->type,
            'fournisseur_id' => $request->fournisseur_id,
        ]);

        if ($telephones) {
            Session()->flash('message', 'Téléphone ajouté avec succès!');
            session()->flash('typeajout', 3);
            return redirect()->route('fournisseurs.show', ['id' => $telephones->fournisseur_id]);
        }
    }

    public function show(Telephone $telephones)
    {
        //
    }

    public function edit($id)
    {
        $telephones = $this->telephones->findOrFail($id);
        return view('telephones.edit', compact('telephones'));
    }

    public function update(UpdateTelephoneRequest $request)
    {
        $telephones = $this->telephones->findOrFail($request->id);

        $telephone = $telephones->update([
            'numero' => $request->numero,
            'type' => $request->type,
            'fournisseur_id' => $request->fournisseur_id,
        ]);
        if ($telephone) {
            Session()->flash('message', 'Téléphone modifié avec succès!');
            return redirect()->route('fournisseurs.show', ['id' => $telephones->fournisseur_id]);
        }
    }

    public function delete($id)
    {
        $telephones = $this->telephones->findOrFail($id);
        return view('telephones.delete', compact('telephones'));
    }

    public function destroy($id)
    {
        $telephones = $this->telephones->findOrFail($id);
        $telephone = $telephones->delete();
        if ($telephone) {
            Session()->flash('message', 'Téléphone supprimé avec succès!');
            return redirect()->route('fournisseurs.show', ['id' => $telephones->fournisseur_id]);
        }
    }
}
