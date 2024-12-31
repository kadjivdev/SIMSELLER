<?php

namespace App\Http\Controllers;

use App\Models\Prix;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prixes = Prix::all();
        return view('prix.index', compact('prixes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = Zone::all();
        return view('prix.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'datePriseEffet' => ['required'],
                'prix' => ['required'],
                'dateFin' => ['required'],
                /*  'status' => ['required'], */
                'zone_id' => ['required'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('prix.create')->withErrors($validator->errors())->withInput();
            }

            $prix = Prix::create([
                'datePriseEffet' => $request->datePriseEffet,
                'prix' => $request->prix,
                'dateFin' => $request->dateFin,
                'status' => 'inactif',
                'user_id' => auth()->user()->id,
                'zone_id' => $request->zone_id,
            ]);

            if ($prix) {
                Session()->flash('message', 'Prix ajoutée avec succès!');
                return redirect()->route('prix.index');
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('prix.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prix  $prix
     * @return \Illuminate\Http\Response
     */
    public function show(Prix $prix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prix  $prix
     * @return \Illuminate\Http\Response
     */
    public function edit(Prix $prix)
    {
        $zones = Zone::all();
        return view('prix.edit', compact('prix', 'zones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prix  $prix
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prix $prix)
    {
        try {
            $validator = Validator::make($request->all(), [
                'datePriseEffet' => ['required'],
                'prix' => ['required'],
                'dateFin' => ['required'],
                /* 'status' => ['required'], */
            ]);

            if ($validator->fails()) {
                return redirect()->route('prix.create')->withErrors($validator->errors())->withInput();
            }

            $prixes = $prix->update([
                'datePriseEffet' => $request->datePriseEffet,
                'prix' => $request->prix,
                'dateFin' => $request->dateFin,
                'status' => $request->status,
                'user_id' => auth()->user()->id,
            ]);

            if ($prixes) {
                Session()->flash('message', 'Prix ajoutée avec succès!');
                return redirect()->route('prix.index');
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('prix.index');
            }
        }
    }

    public function delete(Prix $prix)
    {
        $ver = 0;
        if ($prix->datePriseEffet > date('Y-m-d')) {
            if ($prix->status != 'actif') {
                $ver = 1;
            }
        }

        if ($ver > 0) {
            Session()->flash('error', "Désolé! Le prix ne peut être supprimé.");
            return redirect()->route('prix.index');
        } else {
            return  view('prix.delete', compact('prix'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prix  $prix
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prix $prix)
    {
        $prixes = $prix->delete();
        if ($prixes) {
            Session()->flash('message', 'Prix supprimée avec succès!');
            return redirect()->route('prix.index');
        }
    }

    public function status(Prix $prix)
    {
        try {
            if (($prix->datePriseEffet <= date('Y-m-d')) && ($prix->dateFin >= date('Y-m-d'))) {
                $verif = Prix::where('status', '=', 'actif')->where('zone_id', '=', $prix->zone_id)->first();
                if ($verif) {
                    $verif->update([
                        'status' => 'inactif'
                    ]);
                };
                $prix->update([
                    'status' => 'actif'
                ]);
                return redirect()->route('prix.index');
            } else {
                Session()->flash('error', 'Opps! Vous ne pouvez pas modifier ce  status car la Date d\'activiation n\'est pas atteint.');
                return redirect()->route('prix.index');
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('prix.index');
            }
        }
    }
}
