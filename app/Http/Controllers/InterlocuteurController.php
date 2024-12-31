<?php

namespace App\Http\Controllers;

use App\Models\Interlocuteur;
use App\Http\Requests\StoreInterlocuteurRequest;
use App\Http\Requests\UpdateInterlocuteurRequest;

class InterlocuteurController extends Controller
{
    protected $interlocuteurs;

    public function __construct(Interlocuteur $interlocuteurs)
    {
        $this->interlocuteurs = $interlocuteurs;
    }

    public function index()
    {
        $interlocuteurs = $this->interlocuteurs->orderBy('nom')->get();
        return view('interlocuteurs.index', compact('interlocuteurs'));
    }

    public function create()
    {
        return view('interlocuteurs.create');
    }

    public function store(StoreInterlocuteurRequest $request)
    {
        $interlocuteurs = Interlocuteur::create([
            'nom' => strtoupper($request->nom),
            'prenom' => ucwords($request->prenom),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'fournisseur_id' => $request->fournisseur_id,
            'qualification' => ucwords($request->qualification),
        ]);

        if($interlocuteurs){
            Session()->flash('message', 'Interlocuteur ajouté avec succès!');
            return redirect()->route('fournisseurs.show', ['id'=>$interlocuteurs->fournisseur_id])->with('typeajout', 2);
        }
    }

    public function show(Interlocuteur $interlocuteurs)
    {
        //
    }

    public function edit($id)
    {
        $interlocuteurs = $this->interlocuteurs->findOrFail($id);
        return view('interlocuteurs.edit', compact('interlocuteurs'));
    }

    public function update(UpdateInterlocuteurRequest $request)
    {
        $interlocuteurs = $this->interlocuteurs->findOrFail($request->id);
        $interlocuteur = $interlocuteurs->update([
            'nom' => strtoupper($request->nom),
            'prenom' => ucwords($request->prenom),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'fournisseur_id' => $request->fournisseur_id,
            'qualification' => ucwords($request->qualification),
        ]);

        if($interlocuteur){
            Session()->flash('message', 'Interlocuteur modifié avec succès!');
            return redirect()->route('fournisseurs.show', ['id'=>$interlocuteurs->fournisseur_id]);
        }
    }

    public function delete($id)
    {
        $interlocuteurs = $this->interlocuteurs->findOrFail($id);
        return view('interlocuteurs.delete', compact('interlocuteurs'));
    }

    public function destroy($id)
    {
        $interlocuteurs = $this->interlocuteurs->findOrFail($id);
        $interlocuteur = $interlocuteurs->delete();
        if($interlocuteur){
            Session()->flash('message', 'Interlocuteur supprimé avec succès!');
            return redirect()->route('fournisseurs.show', ['id'=>$interlocuteurs->fournisseur_id]);
        }
    }
}
