<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Client;
use App\Models\TypeClient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeClientController extends Controller
{
    public function index()
    {
        $typeclients = TypeClient::all()->whereNotNull('type_client_id');
        $categories  = TypeClient::whereNull('type_client_id')->get();

        return view('typeclients.index', compact('typeclients', 'categories'));
    }


    public function create()
    {
        return view('typeclients.create');
    }



    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', 'unique:type_clients'],
                'type_client_id' => ['required'],
            ]);

            if($validator->fails()){
                return redirect()->route('typeclients.index')->withErrors($validator->errors())->withInput();
            }

            $typeclients = TypeClient::create([
                'libelle' => strtoupper($request->libelle),
                'type_client_id'=>$request->type_client_id
            ]);

            if($typeclients){
                Session()->flash('message', 'Type client ajouté avec succès!');
                return redirect()->route('typeclients.index');
            }

    } catch (Exception $e) {
        if(env('APP_DEBUG') == TRUE){
            return $e;
        }else{
            Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
            return redirect()->route('typeclients.index');
        }
    }
    }


    public function show(TypeClient $typeclient)
    {
        //
    }



    public function edit(TypeClient $typeclient)
    {
        $categories  = TypeClient::whereNull('type_client_id')->get();
        return view('typeclients.edit', compact('typeclient', 'categories'));
    }



    public function update(Request $request, TypeClient $typeclient)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('type_clients')->ignore($typeclient->id)],
                'type_client_id' => ['required'],
            ]);

            if($validator->fails()){
                return redirect()->route('typeclients.edit', ['typeclient'=>$typeclient->id])->withErrors($validator->errors())->withInput();
            }

            $typeclients = $typeclient->update([
                'libelle' => strtoupper($request->libelle),
                'type_client_id'=>$request->type_client_id
            ]);

            if($typeclients){
                Session()->flash('message', 'Type client modifié avec succès!');
                return redirect()->route('typeclients.index');
            }

            } catch (Exception $e) {
                if(env('APP_DEBUG') == TRUE){
                    return $e;
                }else{
                    Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                    return redirect()->route('typeclients.index');
                }
            }
    }

    public function delete(TypeClient $typeclient)
    {
        
        $ver = Client::where('type_client_id', $typeclient->id)->get();

        if(count($ver)>0){
            Session()->flash('error', "Désolé! Le type de client ".$typeclient->libelle." est déjà lié à un client, veuillez d'abord supprimé le client.");
            return redirect()->route('typeclients.index');
        }else{
            return  view('typeclients.delete', compact('typeclient'));
        }

    }


    public function destroy(TypeClient $typeclient)
    {
        $typeclient = $typeclient->delete();

        if($typeclient){
            Session()->flash('message', 'Type client supprimé avec succès!');
            return redirect()->route('typeclients.index');
        }
    }
}
