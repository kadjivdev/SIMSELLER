<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Assurance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\CompagnieAssurance;
use Illuminate\Support\Facades\Validator;

class CompagnieAssuranceController extends Controller
{
    public function index()
    {
        $compagnieassurances = CompagnieAssurance::all(); 

        return view('compagnieassurances.index', compact('compagnieassurances'));
    }


    public function create()
    {
        return view('compagnieassurances.create');
    }


    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', 'unique:compagnie_assurances'],
            ]);

            if($validator->fails()){
                return redirect()->route('compagnieassurances.index')->withErrors($validator->errors())->withInput();
            }

            $compagnieassurances = CompagnieAssurance::create([
                'libelle' => strtoupper($request->libelle),
            ]);

            if($compagnieassurances){
                Session()->flash('message', 'Compagnie assurance ajoutée avec succès!');
                return redirect()->route('compagnieassurances.index');
            }

        } catch (Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('compagnieassurances.index');
            }
        }
    }


    public function show(CompagnieAssurance $compagineAssurance)
    {
        //
    }


    
    public function edit(CompagnieAssurance $compagnieassurance)
    {
        return view('compagnieassurances.edit', compact('compagnieassurance'));
    }


    
    public function update(Request $request, CompagnieAssurance $compagnieassurance)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('compagnie_assurances')->ignore($compagnieassurance->id)],
            ]);

            if($validator->fails()){
                return redirect()->route('compagnieassurances.edit', ['compagnieassurance'=>$compagnieassurance->id])->withErrors($validator->errors())->withInput();
            }
            $compagnieassurances = $compagnieassurance->update([
                'libelle' => strtoupper($request->libelle),
            ]);
            
            if($compagnieassurances){
                Session()->flash('message', 'Compagnie modifiée avec succès!');
                return redirect()->route('compagnieassurances.index');
            }

            } catch (Exception $e) {
                if(env('APP_DEBUG') == TRUE){
                    return $e;
                }else{
                    Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                    return redirect()->route('compagnieassurances.index');
                }
            }
    }


    public function delete(CompagnieAssurance $compagnieassurance)
    {
        $ver = Assurance::where('compagnie', $compagnieassurance->libelle)->get();

        if(count($ver)>0){
            Session()->flash('error', "Désolé! La compagnie d'assurance ".$compagnieassurance->libelle." a déjà assuré un camion, veuillez d'abord supprimé l'assurance.");
            return redirect()->route('compagnieassurances.index');
        }else{
            return view('compagnieassurances.delete', compact('compagnieassurance'));
        }
        
    }


    public function destroy(CompagnieAssurance $compagnieassurance)
    {
        $compagnieassurance = $compagnieassurance->delete();

        if($compagnieassurance){
            Session()->flash('message', 'Compagnie supprimée avec succès!');
            return redirect()->route('compagnieassurances.index');
        }
    }
}
