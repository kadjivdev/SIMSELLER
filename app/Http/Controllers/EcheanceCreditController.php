<?php

namespace App\Http\Controllers;

use App\Models\DetailRecu;
use App\Models\EcheanceCredit;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EcheanceCreditController extends Controller
{
    public function index(Vente $vente)
    {
        return view('echeances.index', compact('vente'));
    }

    public function create(Vente $vente)
    {
        return view('echeances.create', compact('vente'));
    }

    public function store(Request $request, Vente $vente)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => ['required','after:now'],
            ]);

            if($validator->fails()){
                return redirect()->route('echeances.create', ['vente' => $vente->id])->withErrors($validator->errors())->withInput();
            }
            $echeance = $vente->echeances()->latest()->first();
            if($echeance->statut == 0){
                $echeance->update(['statut'=>1]);
            }
            EcheanceCredit::create([
                'date'=>$request->date,
                'vente_id'=>$vente->id,
                'user_id'=>auth()->user()->id
            ]);
            return redirect()->route('echeances.index', ['vente' => $vente->id]);
        }catch (\Exception $e){
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('echeances.index', ['vente' => $vente->id]);
            }
        }
    }



    public function show(DetailRecu $detailRecu)
    {
        //
    }

    public function edit(Vente $vente, EcheanceCredit $echeance)
    {
        return view('echeances.edit', compact('vente', 'echeance'));
    }



    public function update(Request $request, Vente $vente, EcheanceCredit $echeance)
    {

        try {
            $echeance->update(['statut'=>1]);
            Session()->flash('message', 'Echéance clôturée avec succès!');
            return redirect()->route('echeances.index', ['vente' => $vente->id]);

        }catch (\Exception $e){
            if(env('APP_DEBUG') == TRUE){
                return $e;
            }else{
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('reglements.index', ['vente' => $vente->id]);
            }
        }

    }


    public function delete(Vente $vente, EcheanceCredit $echeance)
    {
        return view('echeances.delete', compact('vente', 'echeance'));
    }



    public function destroy(Vente $vente, EcheanceCredit $echeance)
    {
        $echeance = $echeance->delete();

        if ($echeance) {
            Session()->flash('message', 'Echéance supprimée avec succès!');
            return redirect()->route('echeances.index', ['vente' => $vente->id]);
        }
    }
}
