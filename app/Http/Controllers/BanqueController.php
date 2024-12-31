<?php

namespace App\Http\Controllers;

use App\Models\Banque;
use App\Http\Requests\StoreBanqueRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class BanqueController extends Controller
{
    protected $banques;
    public function __construct(Banque $banques)
    {
        $this->banques = $banques;
    }

    public function index()
    {
        $banques = $this->banques->orderBy('sigle','desc')->get();
        return view('banques.index', compact('banques'));
    }

    public function create()
    {
        return view('banques.create');
    }

    public function store(StoreBanqueRequest $request)
    {
        $banque = Banque::create([
            'sigle' => strtoupper($request->sigle),
            'raisonSociale' => strtoupper($request->raisonSociale),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => ucwords($request->adresse),
            'interlocuteur' => strtoupper($request->interlocuteur),
        ]);

        if($banque){
            Session()->flash('message', 'Banque ajoutée avec succès!');
            return redirect()->route('banques.index');
        }
    }

    public function show(Banque $banque)
    {
        //
    }

    public function edit($id)
    {
        $banques = $this->banques->findOrFail($id);
        return view('banques.edit', compact('banques'));
    }

    public function update(Request $request, Banque $banque)
    {
        $banques = $this->banques->findOrFail($request->id);
        $request->validate([
            'sigle' => ['required', 'string', 'max:255',Rule::unique('banques')->ignore($banques->id)],
            'raisonSociale' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'adresse' => ['required', 'string'],
            'interlocuteur' => ['required', 'string'],
        ]);
        $banques = $banques->update([
            'sigle' => strtoupper($request->sigle),
            'raisonSociale' => strtoupper($request->raisonSociale),
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => ucwords($request->adresse),
            'interlocuteur' => strtoupper($request->interlocuteur),
        ]);

        if($banques){
            Session()->flash('message', 'Banque modifiée avec succès!');
            return redirect()->route('banques.index');
        }
    }


    public function delete($id)
    {
        $banques = $this->banques->findOrFail($id);
        $compte = $banques->comptes->first();
        if($compte){
            Session()->flash('suppression', 'Attention vous ne pouvez pas supprimer la banque '.$banques->sigle);
            return redirect()->route('banques.index');
        }
        return view('banques.delete', compact('banques'));

    }

    public function destroy($id)
    {
        $banques = $this->banques->findOrFail($id)->delete();
        if($banques){
            Session()->flash('message', 'Banque supprimée avec succès!');
            return redirect()->route('banques.index');
        }
    }
}
