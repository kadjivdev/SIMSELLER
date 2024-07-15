<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Departement;
use App\Models\Programmation;
use App\Models\Representant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::orderBy('id','desc')->get();
        return view('zones.index', compact('zones'));    
    }
    
    public function create()
    {
        $representants = Representant::all();
        $departements = Departement::all();

        return view('zones.create', compact('representants', 'departements'));
    }

    public function store(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                    'libelle' => ['required', 'string', 'max:255', 'unique:zones'],
                    'representant_id' => ['required'],
                    'departement_id' => ['required'],
                ]);

                if($validator->fails()){
                    return redirect()->route('zones.create')->withErrors($validator->errors())->withInput();
                }

                $zones = Zone::create([
                    'libelle' => strtoupper($request->libelle),
                    'representant_id' => strtoupper($request->representant_id),
                    'departement_id' => strtoupper($request->departement_id),
                ]);

                if($zones){
                    Session()->flash('message', 'Zone ajoutée avec succès!');
                    return redirect()->route('zones.index');
                }

        } catch (\Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('zones.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Departement  $departement
     * @return \Illuminate\Http\Response
     */
    public function show(Departement $departement)
    {
        //
    }

    public function edit(Zone $zone)
    {
        $representants = Representant::all();
        $departements = Departement::all();

        return view('zones.edit', compact('zone', 'representants', 'departements'));
    }

    public function update(Request $request, Zone $zone)
    {
        try {
            $validator = Validator::make($request->all(), [
                'libelle' => ['required', 'string', 'max:255', Rule::unique('zones')->ignore($zone->id)],
                'representant_id' => ['required'],
                'departement_id' => ['required'],
            ]);

            if($validator->fails()){
                return redirect()->route('zones.edit')->withErrors($validator->errors())->withInput();
            }

            $zones = $zone->update([
                'libelle' => strtoupper($request->libelle),
                'representant_id' => strtoupper($request->representant_id),
                'departement_id' => strtoupper($request->departement_id),
            ]);

            if($zones){
                Session()->flash('message', 'Zone modifiée avec succès!');
                return redirect()->route('zones.index');
            }

            } catch (\Exception $e) {
                if(env('APP_DEBUG') == TRUE){
                    return $e;
                }else{
                    Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                    return redirect()->route('zones.index');
                }
            }
    }

    public function delete(Zone $zone)
    {
        $ver = Programmation::where('zone_id', $zone->id)->get();

        if(count($ver)>0){
            Session()->flash('error', "Désolé! La zone ".$zone->libelle." est déjà lié à une entité.");
            return redirect()->route('zones.index');
        }else{
            return  view('zones.delete', compact('zone'));
        }

    }

    public function destroy(Zone $zone)
    {
        $zones = $zone->delete();

        if($zones){
            Session()->flash('message', 'Zone supprimée avec succès!');
            return redirect()->route('zones.index');
        }
    }

    public function prixZone(Zone $zone){

        try {
            $prixes = $zone->prix;
            return view('zones.prix', compact('zone', 'prixes'));

        } catch (\Exception $e) {
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('zones.index');
            }
        }
    }

}
