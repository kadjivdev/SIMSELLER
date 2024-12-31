<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\TypeAvaliseur;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TypeAvaliseurController extends Controller
{
    public function index()
    {
        $typeavaliseurs = TypeAvaliseur::all();
        return view('typeavaliseurs.index', compact('typeavaliseurs'));
    }

    public function create()
    {
        return view('typeavaliseurs.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', 'unique:type_avaliseurs'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('typeavaliseurs.index')->withErrors($validator->errors())->withInput();
            }

            $typeavaliseurs = TypeAvaliseur::create([
                'libelle' => strtoupper($request->libelle),
            ]);

            if ($typeavaliseurs) {
                Session()->flash('message', 'Type avaliseur ajouté avec succès!');
                return redirect()->route('typeavaliseurs.index');
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('typeavaliseurs.index');
            }
        }
    }


    public function show(TypeAvaliseur $typeAvaliseur)
    {
        //
    }

    public function edit(TypeAvaliseur $typeavaliseur)
    {
        return view('typeavaliseurs.edit', compact('typeavaliseur'));
    }

    public function update(Request $request, TypeAvaliseur $typeavaliseur)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('type_avaliseurs')->ignore($typeavaliseur->id)],
            ]);

            if ($validator->fails()) {
                return redirect()->route('typeavaliseurs.edit', ['typeavaliseur' => $typeavaliseur->id])->withErrors($validator->errors())->withInput();
            }

            $typeavaliseurs = $typeavaliseur->update([
                'libelle' => strtoupper($request->libelle),
            ]);

            if ($typeavaliseurs) {
                Session()->flash('message', 'Type avaliseurs modifié avec succès!');
                return redirect()->route('typeavaliseurs.index');
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('typeavaliseurs.index');
            }
        }
    }

    public function delete(TypeAvaliseur $typeavaliseur)
    {
        $ver = $typeavaliseur->avaliseurs()->get();

        if (count($ver) > 0) {
            Session()->flash('error', "Désolé! Le type de avaliseur " . $typeavaliseur->libelle . " est déjà lié à un avaliseur, veuillez d'abord supprimé l'avaliseur.");
            return redirect()->route('typeavaliseurs.index');
        } else {
            return  view('typeavaliseurs.delete', compact('typeavaliseur'));
        }
    }


    public function destroy(TypeAvaliseur $typeavaliseur)
    {
        $typeavaliseur = $typeavaliseur->delete();

        if ($typeavaliseur) {
            Session()->flash('message', 'Type avaliseur supprimé avec succès!');
            return redirect()->route('typeavaliseurs.index');
        }
    }
}
