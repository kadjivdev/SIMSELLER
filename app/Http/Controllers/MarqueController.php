<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreMarqueRequest;
use Illuminate\Support\Facades\Validator;

class MarqueController extends Controller
{
    public function index()
    {
        $marques = Marque::all(); 

        return view('marques.index', compact('marques'));
    }


    public function create()
    {
        return view('marques.create');
    }


    
    public function store(StoreMarqueRequest $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', 'unique:marques'],
            ]);

            if($validator->fails()){
                return redirect()->route('marques.index')->withErrors($validator->errors())->withInput();
            }

            $marques = Marque::create([
                'libelle' => strtoupper($request->libelle),
            ]);

            if($marques){
                Session()->flash('message', 'Marque ajoutée avec succès!');
                return redirect()->route('marques.index');
            }

    } catch (Exception $e) {
        if(env('APP_DEBUG') == TRUE){
            return $e;
        }else{
            Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
            return redirect()->route('marques.index');
        }
    }
    }


    public function show(Marque $marque)
    {
        //
    }


    public function edit(Marque $marque)
    {
        return view('marques.edit', compact('marque'));
    }


    
    public function update(StoreMarqueRequest $request, Marque $marque)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('marques')->ignore($marque->id)],
            ]);

            if($validator->fails()){
                return redirect()->route('marques.edit', ['marque'=>$marque->id])->withErrors($validator->errors())->withInput();
            }
            

            
            $marques = $marque->update([
                'libelle' => strtoupper($request->libelle),
            ]);

            if($marques){
                Session()->flash('message', 'Marque modifiée avec succès!');
                return redirect()->route('marques.index');
            }

            } catch (Exception $e) {
                if(env('APP_DEBUG') == TRUE){
                    return $e;
                }else{
                    Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                    return redirect()->route('marques.index');
                }
            }
    }


    public function delete(Marque $marque)
    {
        $ver = $marque->camions()->get();

        if(count($ver)>0){
            Session()->flash('error', "Désolé! La marque ".$marque->libelle." est déjà lié à un camion, veuillez d'abord supprimé le camion.");
            return redirect()->route('marques.index');
        }else{
            return  view('marques.delete', compact('marque'));
        }

    }


    public function destroy(Marque $marque)
    {
        $marques = $marque->delete();
        
        if($marques){
            Session()->flash('message', 'Marque supprimé avec succès!');
            return redirect()->route('marques.index');
        }
    }

}
