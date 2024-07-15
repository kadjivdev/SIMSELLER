<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Representant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RepresentantController extends Controller
{

    public function index()
    {
        $representants = Representant::all();


        return view('representants.index', compact('representants'));
    }


    public function create()
    {
        return view('representants.create');
    }


    public function store(Request $request)
    {
        try {
            if($request->photo == NULL){

                $validator = Validator::make($request->all(), [
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', 'unique:representants'],
                    'telephonepro' => ['required', 'string', 'max:255', 'unique:representants'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:representants'],
                ]);

                if($validator->fails()){
                    return redirect()->route('representants.create')->withErrors($validator->errors())->withInput();
                }
                $code = str_split($request->telephonepro, 4);
                $nom = $request->nom[0];
                $prenom = $request->prenom[0];
                $matricule = $code[0].$nom.$prenom.$code[1];

                $representants = Representant::create([
                    'matricule' => strtoupper($matricule),
                    'civilite' => ucwords($request->civilite),
                    'nom' => strtoupper($request->nom),
                    'prenom' => ucwords($request->prenom),
                    'telephone' => $request->telephone,
                    'telephonepro' => $request->telephonepro,
                    'email' => $request->email,
                ]);

                if($representants){
                    Session()->flash('message', 'Representant ajouté avec succès!');
                    return redirect()->route('representants.index');
                }
            }else{

                $validator = Validator::make($request->all(), [
                    'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', 'unique:representants'],
                    'telephonepro' => ['required', 'string', 'max:255', 'unique:representants'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:representants'],
                ]);

                if($validator->fails()){
                    return redirect()->route('representants.create')->withErrors($validator->errors())->withInput();
                }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('photo');
                    $photo = time().'.'.$image->extension();
                    $image->move(public_path('images'), $photo);

                    $code = str_split($request->telephonepro, 4);
                    $nom = $request->nom[0];
                    $prenom = $request->prenom[0];
                    $matricule = $code[0].$nom.$prenom.$code[1];

                    $representants = Representant::create([
                        'matricule' => strtoupper($matricule),
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'telephonepro' => $request->telephonepro,
                        'email' => $request->email,
                        'photo' => $photo,
                    ]);

                    if($representants){
                        Session()->flash('message', 'Representant ajouté avec succès!');
                        return redirect()->route('representants.index');
                    }
            }


        } catch (Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('representants.index');
            }
        }
    }


    public function show(Representant $representant)
    {
        $representants = $representant;

        return view('representants.show', compact('representants'));
    }


    public function edit(Representant $representant)
    {

        return view('representants.edit', compact('representant'));
    }


    public function update(Request $request, Representant $representant)
    {

        try {
            if($request->remoov){
                $photo = null;
                $representants = $representant->update([
                    'photo' => $photo,
                ]);

                if($representants){
                    Session()->flash('message', 'Representant modifié avec succès!');
                    return redirect()->route('representants.index');
                }
                }elseif($request->photo == NULL){

                $validator = Validator::make($request->all(), [
                    'matricule' => ['required', 'string', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                    'telephonepro' => ['required', 'string', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                    'email' => ['required', 'string', 'email', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                ]);

                if($validator->fails()){
                    return redirect()->route('representants.edit', ['representant'=>$representant->id])->withErrors($validator->errors())->withInput();
                }

                $code = str_split($request->telephonepro, 4);
                $nom = $request->nom[0];
                $prenom = $request->prenom[0];
                $matricule = $code[0].$nom.$prenom.$code[1];

                $representants = $representant->update([
                    'matricule' => strtoupper($matricule),
                    'civilite' => ucwords($request->civilite),
                    'nom' => strtoupper($request->nom),
                    'prenom' => ucwords($request->prenom),
                    'telephone' => $request->telephone,
                    'telephonepro' => $request->telephonepro,
                    'email' => $request->email,
                ]);

                if($representants){
                    Session()->flash('message', 'Representant modifié avec succès!');
                    return redirect()->route('representants.index');
                }
            }else{

                $validator = Validator::make($request->all(), [
                    'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
                    'matricule' => ['required', 'string', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                    'civilite' => ['required', 'string', 'max:255'],
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'telephone' => ['required', 'string', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                    'telephonepro' => ['required', 'string', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                    'email' => ['required', 'string', 'email', 'max:255', Rule::unique('representants')->ignore($representant->id)],
                ]);

                if($validator->fails()){
                    return redirect()->route('representants.edit', ['representant'=>$representant->id])->withErrors($validator->errors())->withInput();
                }

                    /* Uploader les images dans la base de données */
                    $image = $request->file('photo');
                    $photo = time().'.'.$image->extension();
                    $image->move(public_path('images'), $photo);

                    $code = str_split($request->telephonepro, 4);
                    $nom = $request->nom[0];
                    $prenom = $request->prenom[0];
                    $matricule = $code[0].$nom.$prenom.$code[1];

                    $representants = $representant->update([
                        'matricule' => strtoupper($matricule),
                        'civilite' => ucwords($request->civilite),
                        'nom' => strtoupper($request->nom),
                        'prenom' => ucwords($request->prenom),
                        'telephone' => $request->telephone,
                        'telephonepro' => $request->telephonepro,
                        'email' => $request->email,
                        'photo' => $photo,
                    ]);

                    if($representants){
                        Session()->flash('message', 'Representant modifié avec succès!');
                        return redirect()->route('representants.index');
                    }
            }


        } catch (Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('representants.index');
            }
        }


    }


    public function delete(Representant $representant)
    {
        $zones = $representant->zones;
        if(count($zones) > 0){
            Session()->flash('suppression', 'Attention vous ne pouvez pas supprimer le representant '.$representant->nom.' '.$representant->prenom.'. Il est en relation avec d\'autres entités');
            return redirect()->route('representants.index');
        }
        return view('representants.delete', compact('representant'));
    }


    public function destroy(Representant $representant)
    {

        $representants = $representant->delete();

        if($representants){
            Session()->flash('message', 'Representant supprimé avec succès!');
            return redirect()->route('representants.index');
        }
    }


    public function zone(Representant $representant)
    {
        return view('representants.zone', compact('representant'));
    }
}
