<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class DepartementController extends Controller
{


    public function index()
    {
        $departements = Departement::all();

        return view('departements.index', compact('departements'));
    }



    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                    'libelle' => ['required', 'string', 'max:255', 'unique:departements'],
                ]);

                if($validator->fails()){
                    return redirect()->route('departements.inddex')->withErrors($validator->errors())->withInput();
                }

                $departements = Departement::create([
                    'libelle' => strtoupper($request->libelle),
                ]);

                if($departements){
                    Session()->flash('message', 'Département ajouté avec succès!');
                    return redirect()->route('departements.index');
                }

        } catch (\Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('departements.index');
            }
        }
    }



    public function show(Departement $departement)
    {
        //
    }


    public function edit(Departement $departement)
    {
        return view('departements.edit', compact('departement'));
    }


    public function update(Request $request, Departement $departement)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('departements')->ignore($departement->id)],
            ]);

            if($validator->fails()){
                return redirect()->route('departements.edit', ['departement'=>$departement->id])->withErrors($validator->errors())->withInput();
            }

            $departements = $departement->update([
                'libelle' => strtoupper($request->libelle),
            ]);

            if($departements){
                Session()->flash('message', 'Département modifié avec succès!');
                return redirect()->route('departements.index');
            }

            } catch (Exception $e) {
                if(env('APP_DEBUG') == TRUE){
                    return $e;
                }else{
                    Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                    return redirect()->route('departements.index');
                }
            }
    }


    public function delete(Departement $departement)
    {
        $ver = $departement->zones()->get();

        if(count($ver)>0){
            Session()->flash('error', "Désolé! Le département ".$departement->libelle." est déjà lié à une zone, veuillez d'abord supprimé la zone.");
            return redirect()->route('departements.index');
        }else{
            return view('departements.delete', compact('departement'));
        }
    }


    public function destroy(Departement $departement)
    {
        $departements = $departement->delete();

        if($departements){
            Session()->flash('message', 'Département supprimé avec succès!');
            return redirect()->route('departements.index');
        }
    }


    public function zone(Departement $departement)
    {
        return view('departements.zone', compact('departement'));
    }
}
