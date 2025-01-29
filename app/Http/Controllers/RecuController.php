<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Recu;
use App\Models\Parametre;
use App\Models\BonCommande;
use App\Models\DetailRecu;
use App\Rules\ActionMontantRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Rules\ActionQteRecuRule;
use App\tools\ControlesTools;

class RecuController extends Controller
{
    public function index(BonCommande $boncommandes)
    {
        return view('recus.index', compact('boncommandes'));
    }

    public function create(BonCommande $boncommande)
    {
        $prixTonnage = $boncommande->montant / $boncommande->detailboncommandes->toArray()[0]['qteCommander'];

        $boncommandes = $boncommande;
        return view('recus.create', compact('boncommandes', 'prixTonnage'));
    }

    public function store(Request $request, BonCommande $boncommande)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reference' => ['required', 'string', 'max:255', 'unique:recus'],
                'libelle' => ['required', 'string', 'max:255'],
                'date' => ['required', 'before_or_equal:now'],
                'document' => ['required', 'file', 'mimes:pdf,png,jpeg,jpg'],
                'tonnage' => ['required', new ActionQteRecuRule($boncommande)],
                'montant' => ['required', new ActionMontantRule($boncommande)],
                'document' => ['nullable', 'file', 'mimes:pdf,png,jpeg,jpg'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('recus.create', ['boncommande' => $boncommande->id])->withErrors($validator->errors())->withInput();
            }

            /* Uploader les documents dans la base de données */

            if ($request->document) {
                $filename = time() . '.' . $request->document->extension();
                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
            } else {
                $file = null;
            }
            $format = env('FORMAT_RECU');
            $parametre = Parametre::where('id', env('RECU'))->first();
            $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

            $recus = Recu::create([
                'numero' => $code,
                'reference' => strtoupper($request->reference),
                'libelle' => $request->libelle,
                'date' => $request->date,
                'tonnage' => $request->tonnage,
                'montant' => $request->montant,
                'document' => $file,
                'bon_commande_id' => $boncommande->id,
            ]);

            if ($recus) {

                $valeur = $parametre->valeur;

                $valeur = $valeur + 1;

                $parametres = Parametre::find(env('RECU'));

                $parametre = $parametres->update([
                    'valeur' => $valeur,
                ]);

                if ($parametre) {
                    Session()->flash('message', 'Réçu ajouté avec succès!');
                    return redirect()->route('recus.index', ['boncommandes' => $boncommande->id]);
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('recus.index', ['boncommandes' => $boncommande->id]);
            }
        }
    }

    public function show(Recu $recu)
    {
        //
    }

    public function edit(BonCommande $boncommande, Recu $recu)
    {
        $boncommandes = $boncommande;
        return view('recus.edit', compact('boncommandes', 'recu'));
    }

    public function update(Request $request, BonCommande $boncommande, Recu $recu)
    {
        try {

            if ($request->document == NULL) {
                $validator = Validator::make($request->all(), [
                    'reference' => ['required', 'string', 'max:255', Rule::unique('recus')->ignore($recu->id)],
                    'libelle' => ['required', 'string', 'max:255'],
                    'date' => ['required', 'before_or_equal:now'],
                    'document' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg'],
                    'tonnage' => ['required', new ActionQteRecuRule($boncommande, $recu)],
                    'montant' => ['required',  new ActionMontantRule($boncommande, $recu)],
                ]);

                if ($validator->fails()) {
                    return redirect()->route('recus.edit', ['boncommande' => $boncommande->id, 'recu' => $recu])->withErrors($validator->errors())->withInput();
                }

                $recu = $recu->update([
                    'numero' => strtoupper($request->numero),
                    'reference' => strtoupper($request->reference),
                    'libelle' => $request->libelle,
                    'date' => $request->date,
                    'tonnage' => $request->tonnage,
                    'montant' => $request->montant,
                    'bon_commande_id' => $boncommande->id,
                    'document' => $request->remoovdoc ? null : $recu->document,
                ]);

                if ($recu) {
                    Session()->flash('message', 'Réçu modifié avec succès!');
                    return redirect()->route('recus.index', ['boncommandes' => $boncommande->id]);
                }
            } else {
                $validator = Validator::make($request->all(), [
                    'reference' => ['required', 'string', 'max:255', Rule::unique('recus')->ignore($recu->id)],
                    'libelle' => ['required', 'string', 'max:255'],
                    'date' => ['required', 'before_or_equal:now'],
                    'document' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg'],
                    'tonnage' => ['required'],
                    'montant' => ['required'],
                    'document' => ['required', 'file', 'mimes:pdf,png,jpeg,jpg'],
                ]);

                if ($validator->fails()) {
                    return redirect()->route('recus.create', ['boncommande' => $boncommande->id])->withErrors($validator->errors())->withInput();
                }
                /* Uploader les documents dans la base de données */
                $filename = time() . '.' . $request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
                $recu = $recu->update([
                    'numero' => strtoupper($request->numero),
                    'reference' => strtoupper($request->reference),
                    'libelle' => $request->libelle,
                    'date' => $request->date,
                    'tonnage' => $request->tonnage,
                    'montant' => $request->montant,
                    'document' => $file,
                    'bon_commande_id' => $boncommande->id,
                ]);

                if ($recu) {
                    Session()->flash('message', 'Réçu modifié avec succès!');
                    return redirect()->route('recus.index', ['boncommandes' => $boncommande->id]);
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('recus.index', ['boncommande' => $boncommande->id]);
            }
        }
    }

    public function delete(BonCommande $boncommande, Recu $recu)
    {
        $boncommandes = $boncommande;
        return view('recus.delete', compact('boncommandes', 'recu'));
    }

    public function destroy(BonCommande $boncommande, Recu $recu)
    {
        ControlesTools::generateLog($recu, 'Recu', 'Suppression ligne');
        $recu->detailrecus()->delete();
        $recus = $recu->delete();
        if ($recus) {
            Session()->flash('message', 'Réçu supprimé avec succès!');
            return redirect()->route('recus.index', ['boncommandes' => $boncommande->id]);
        }
    }
}
