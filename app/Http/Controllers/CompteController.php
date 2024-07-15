<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Banque;
use App\Models\Compte;
use App\tools\ControlesTools;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CompteController extends Controller
{

    public function index(Banque $banque)
    {
        return view('comptes.index', compact('banque'));
    }


    
    public function create(Banque $banque)
    {
        return view('comptes.index', compact('banque'));
    }


    
    public function store(Request $request, Banque $banque)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'numero' => ['required', 'string', 'max:255', 'unique:comptes'],
                'intitule' => ['nullable', 'string', 'max:255'],
            ]);

            if($validator->fails()){
                return redirect()->route('comptes.index', ['banque'=>$banque->id])->withErrors($validator->errors())->withInput();
            }

            $comptes = Compte::create([
                'intitule' => strtoupper($request->intitule),
                'numero' => strtoupper($request->numero),
                'banque_id' => $banque->id,
            ]);

            if($comptes){
                Session()->flash('message', 'Compte ajouté avec succès!');
                return redirect()->route('comptes.index', ['banque'=>$banque->id]);
            }

    } catch (\Exception $e) {
        if(env('APP_DEBUG') == TRUE){
            return $e;
        }else{
            Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
            return redirect()->route('comptes.index', ['banque'=>$banque->id]);
        }
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compte  $compte
     * @return \Illuminate\Http\Response
     */
    public function show(Compte $compte)
    {
        //
    }


    
    public function edit(Banque $banque, Compte $compte)
    {
        return view('comptes.edit', compact('banque', 'compte'));
    }


    
    public function update(Request $request, Banque $banque, Compte $compte)
    {
        try {
            $validator = Validator::make($request->all(), [
                'numero' => ['required', 'string', 'max:255', Rule::unique('comptes')->ignore($compte->id)],
                'intitule' => ['nullable', 'string', 'max:255'],
            ]);

            if($validator->fails()){
                return redirect()->route('comptes.edit', ['banque'=>$banque->id, 'compte'=>$compte->id])->withErrors($validator->errors())->withInput();
            }

            $comptes = $compte->update([
                'intitule' => strtoupper($request->numero),
                'numero' => strtoupper($request->numero),
                'banque_id' => $banque->id,
            ]);

            if($comptes){
                Session()->flash('message', 'Compte modifié avec succès!');
                return redirect()->route('comptes.index', ['banque'=>$banque->id]);
            }

            } catch (Exception $e) {
                if(env('APP_DEBUG') == TRUE){
                    return $e;
                }else{
                    Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                    return redirect()->route('comptes.index', ['banque'=>$banque->id]);
                }
            }
    }

    public function delete(Banque $banque, Compte $compte)
    {
        return view('comptes.delete', compact('banque', 'compte'));
    }


    public function destroy(Banque $banque, Compte $compte)
    {
        ControlesTools::generateLog($compte,'Compte','Suppression ligne');
        $compte = $compte->delete();

        if($compte){
            Session()->flash('message', 'Compte supprimé avec succès!');
            return redirect()->route('comptes.index', ['banque'=>$banque->id]);
        }
    }
}
