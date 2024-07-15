<?php

namespace App\Http\Controllers;

use App\Models\Avaliseur;
use App\Models\Parametre;
use Illuminate\Http\Request;
use App\Models\TypeAvaliseur;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AvaliseurController extends Controller
{

    public function index()
    {
        $avaliseurs = Avaliseur::all();

        return view('avaliseurs.index', compact('avaliseurs'));
    }



    public function create()
    {
        $typeavaliseurs = TypeAvaliseur::all();

        return view('avaliseurs.create', compact('typeavaliseurs'));
    }



    public function store(Request $request)
    {
        try {
            if($request->telephone){
                $telCompose = substr(trim($request->telephone), -8);
                $telCompose = str_pad($telCompose, 8, "0", STR_PAD_RIGHT);
                $concat = substr($request->nom,0,1).substr($request->prenom,0,1);
                $code = substr($telCompose,0,4).strtoupper($concat).substr($telCompose,-4);
            }
            if($request->photo == NULL){
                $validator = Validator::make($request->all(), [
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', 'unique:avaliseurs'],
                    'email' => ['nullable','email', 'max:255', 'unique:avaliseurs'],
                    'type_avaliseur_id' => ['required'],
                ]);
                if($validator->fails()){
                    return redirect()->route('avaliseurs.create')->withErrors($validator->errors())->withInput();
                }
                $avaliseurs = Avaliseur::create([
                    'matricule' => $code,
                    'civilite' => ucwords($request->civilite),
                    'nom' => strtoupper($request->nom),
                    'prenom' => ucwords($request->prenom),
                    'telephone' => $request->telephone,
                    'email' => $request->email,
                    'type_avaliseur_id' => $request->type_avaliseur_id,
                ]);

                if($avaliseurs){
                    Session()->flash('message', 'Avaliseur ajouté avec succès!');
                    return redirect()->route('avaliseurs.index');
                }
            }else{

                $validator = Validator::make($request->all(), [
                    'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', 'unique:avaliseurs'],
                    'email' => ['nullable','email', 'max:255', 'unique:avaliseurs'],
                    'type_avaliseur_id' => ['required'],

                ]);

                if($validator->fails()){
                    return redirect()->route('avaliseurs.create')->withErrors($validator->errors())->withInput();
                }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('photo');
                    $photo = time().'.'.$image->extension();
                    $image->move(public_path('images'), $photo);

                    $avaliseurs = Avaliseur::create([
                        'matricule' => $code,
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'photo' => $photo,
                        'type_avaliseur_id' => $request->type_avaliseur_id,

                    ]);

                    if($avaliseurs){
                        Session()->flash('message', 'Avaliseur ajouté avec succès!');
                        return redirect()->route('avaliseurs.index');
                    }
            }


        } catch (\Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('avaliseurs.index');
            }
        }
    }



    public function show(Avaliseur $avaliseur)
    {
        //
    }



    public function edit(Avaliseur $avaliseur)
    {
        $typeavaliseurs = TypeAvaliseur::all();

        return view('avaliseurs.edit', compact('typeavaliseurs', 'avaliseur'));
    }



    public function update(Request $request, Avaliseur $avaliseur)
    {
        if($request->telephone){
            $telCompose = substr(trim($request->telephone), -8);
            $telCompose = str_pad($telCompose, 8, "0", STR_PAD_RIGHT);
            $concat = substr($request->nom,0,1).substr($request->prenom,0,1);
            $code = substr($telCompose,0,4).strtoupper($concat).substr($telCompose,-4);
        }
        try {
            if($request->photo == NULL){
                $validator = Validator::make($request->all(), [
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', Rule::unique('avaliseurs')->ignore($avaliseur->id)],
                    'email' => ['nullable','email', 'max:255', Rule::unique('avaliseurs')->ignore($avaliseur->id)],
                    'type_avaliseur_id' => ['required'],
                ]);

                if($validator->fails()){
                    return redirect()->route('avaliseurs.edit',['avaliseur'=>$avaliseur->id])->withErrors($validator->errors())->withInput();
                }

                $avaliseurs = $avaliseur->update([
                    'matricule' => $code,
                    'civilite' => ucwords($request->civilite),
                    'nom' => strtoupper($request->nom),
                    'prenom' => ucwords($request->prenom),
                    'telephone' => $request->telephone,
                    'email' => $request->email,
                    'type_avaliseur_id' => $request->type_avaliseur_id,
                    'photo'=>$request->remoov ? null : $avaliseur->photo
                ]);

                if($avaliseurs){
                    Session()->flash('message', 'Avaliseur ajouté avec succès!');
                    return redirect()->route('avaliseurs.index');
                }
            }else{

                $validator = Validator::make($request->all(), [
                    'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', Rule::unique('avaliseurs')->ignore($avaliseur->id)],
                    'email' => ['nullable','email', 'max:255', Rule::unique('avaliseurs')->ignore($avaliseur->id)],
                    'type_avaliseur_id' => ['required'],
                ]);

                if($validator->fails()){
                    return redirect()->route('avaliseurs.edit',['avaliseur'=>$avaliseur->id])->withErrors($validator->errors())->withInput();
                }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('photo');
                    $photo = time().'.'.$image->extension();
                    $image->move(public_path('images'), $photo);


                    $avaliseurs = $avaliseur->update([
                        'matricule' => $code,
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'email' => $request->email,
                        'photo' => $photo,
                        'type_avaliseur_id' => $request->type_avaliseur_id,

                    ]);

                    if($avaliseurs){
                        Session()->flash('message', 'Avaliseur modifié avec succès!');
                        return redirect()->route('avaliseurs.index');
                    }
            }


        } catch (\Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('avaliseurs.index');
            }
        }
    }


    public function delete(Avaliseur $avaliseur)
    {
        $camions = $avaliseur->camions->first();
        if($camions){
            Session()->flash('suppression', 'Attention vous ne pouvez pas supprimer l\'avaliseur '.$avaliseur->nom.' '.$avaliseur->prenom.' Il a des camions');
            return redirect()->route('avaliseurs.index');
        }
        return view('avaliseurs.delete', compact('avaliseur'));
    }


    public function destroy(Avaliseur $avaliseur)
    {

        $avaliseurs = $avaliseur->delete();
        if($avaliseurs){
            Session()->flash('message', 'Avaliseur supprimé avec succès!');
            return redirect()->route('avaliseurs.index');
        }
    }

    public function camions(Avaliseur $avaliseur){
        $camions = $avaliseur->camions;
        return view('avaliseurs.camions',['avaliseur'=>$avaliseur,'camions'=>$camions]);
    }
}
