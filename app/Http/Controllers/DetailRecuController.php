<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Recu;
use App\Models\Compte;
use App\Models\Parametre;
use App\Models\DetailRecu;
use Illuminate\Http\Request;
use App\Models\TypeDetailRecu;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Rules\ActionMontantDetailRecuRule;
use App\tools\ControlesTools;

class DetailRecuController extends Controller
{
    public function index(Recu $recu)
    {
        return view('detailrecus.index', compact('recu'));
    }
    
    public function details(Request $request)
    {
        $recuDetails = DetailRecu::with("recu")->get();
        return view('detailrecus.recus-details', compact('recuDetails'));
    }


    public function create(Recu $recu)
    {
        $comptes = Compte::all();
        $typedetailrecus = TypeDetailRecu::all();
        return view('detailrecus.create', compact('recu', 'comptes', 'typedetailrecus'));
    }

    public function store(Request $request, Recu $recu)
    {
        try {

            if ($request->compte_id == NULL) {
                if ($request->document == NULL) {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', 'unique:detail_recus'],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu)],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.create', ['recu' => $recu->id])->withErrors($validator->errors())->withInput();
                    }
                    $format = env('FORMAT_DETAIL_RECU');
                    $parametre = Parametre::where('id', env('DETAIL_RECU'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $detailrecus = DetailRecu::create([
                        'code' => $code,
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'recu_id' => $recu->id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                    ]);


                    if ($detailrecus) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('DETAIL_RECU'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);

                        if ($parametre) {
                            Session()->flash('message', 'Détail reçu ajouté avec succès!');
                            return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                        }
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', 'unique:detail_recus'],
                        'date' => ['required'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu)],
                        'document' => ['required', 'file', 'mimes:pdf,docx,doc'],
                        'typedetailrecu_id' => ['required'],


                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.create', ['recu' => $recu->id])->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les documents dans la base de données */
                    $filename = time() . '.' . $request->document->extension();

                    $file = $request->file('document')->storeAs(
                        'documents',
                        $filename,
                        'public'
                    );

                    $format = env('FORMAT_DETAIL_RECU');
                    $parametre = Parametre::where('id', env('DETAIL_RECU'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $detailrecus = DetailRecu::create([
                        'code' => $code,
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'recu_id' => $recu->id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                    ]);


                    if ($detailrecus) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('DETAIL_RECU'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);

                        if ($parametre) {
                            Session()->flash('message', 'Détail reçu ajouté avec succès!');
                            return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                        }
                    }
                }
            } else {
                if ($request->document == NULL) {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', 'unique:detail_recus'],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu)],
                        'compte_id' => ['required'],
                        'typedetailrecu_id' => ['required'],


                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.create', ['recu' => $recu->id])->withErrors($validator->errors())->withInput();
                    }

                    $format = env('FORMAT_DETAIL_RECU');
                    $parametre = Parametre::where('id', env('DETAIL_RECU'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $detailrecus = DetailRecu::create([
                        'code' => $code,
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'recu_id' => $recu->id,
                        'compte_id' => $request->compte_id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                    ]);


                    if ($detailrecus) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('DETAIL_RECU'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);

                        if ($parametre) {
                            Session()->flash('message', 'Détail reçu ajouté avec succès!');
                            return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                        }
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', 'unique:detail_recus'],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu)],
                        'document' => ['required', 'file', 'mimes:pdf,docx,doc'],
                        'compte_id' => ['required'],
                        'typedetailrecu_id' => ['required'],


                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.create', ['recu' => $recu->id])->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les documents dans la base de données */
                    $filename = time() . '.' . $request->document->extension();

                    $file = $request->file('document')->storeAs(
                        'documents',
                        $filename,
                        'public'
                    );

                    $format = env('FORMAT_DETAIL_RECU');
                    $parametre = Parametre::where('id', env('DETAIL_RECU'))->first();
                    $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                    $detailrecus = DetailRecu::create([
                        'code' => $code,
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'recu_id' => $recu->id,
                        'compte_id' => $request->compte_id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                    ]);


                    if ($detailrecus) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur + 1;

                        $parametres = Parametre::find(env('DETAIL_RECU'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);

                        if ($parametre) {
                            Session()->flash('message', 'Détail reçu ajouté avec succès!');
                            return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
            }
        }
    }

    public function show(DetailRecu $detailRecu)
    {
        //
    }

    public function edit(Recu $recu, DetailRecu $detailrecu)
    {
        $comptes = Compte::all();
        $typedetailrecus = TypeDetailRecu::all();
        return view('detailrecus.edit', compact('recu', 'comptes', 'typedetailrecus', 'detailrecu'));
    }

    public function update(Request $request, Recu $recu, DetailRecu $detailrecu)
    {
        try {
            if ($request->document == NULL) {
                if ($request->compte_id == NULL) {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('detail_recus')->ignore($detailrecu->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu, $detailrecu)],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.edit', ['recu' => $recu->id, 'detailrecu' => $detailrecu->id])->withErrors($validator->errors())->withInput();
                    }
                    $detailrecus = $detailrecu->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'recu_id' => $recu->id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                        'document' => $request->remoovdoc ? null : $detailrecu->document,
                    ]);

                    if ($detailrecus) {
                        Session()->flash('message', 'Détail reçu modifié avec succès!');
                        return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('detail_recus')->ignore($detailrecu->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu, $detailrecu)],
                        'compte_id' => ['required'],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.edit', ['recu' => $recu->id, 'detailrecu' => $detailrecu->id])->withErrors($validator->errors())->withInput();
                    }

                    $detailrecus = $detailrecu->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'recu_id' => $recu->id,
                        'compte_id' => $request->compte_id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                        'document' => $request->remoovdoc ? null : $detailrecu->document,
                    ]);

                    if ($detailrecus) {
                        Session()->flash('message', 'Détail reçu modifié avec succès!');
                        return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                    }
                }
            } else {
                if ($request->compte_id == NULL) {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('detail_recus')->ignore($detailrecu->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu, $detailrecu)],
                        'document' => ['required', 'file', 'mimes:pdf,docx,doc'],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.edit', ['recu' => $recu->id, 'detailrecu' => $detailrecu->id])->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les documents dans la base de données */
                    $filename = time() . '.' . $request->document->extension();

                    $file = $request->file('document')->storeAs(
                        'documents',
                        $filename,
                        'public'
                    );

                    $detailrecus = $detailrecu->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'recu_id' => $recu->id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                    ]);


                    if ($detailrecus) {
                        Session()->flash('message', 'Détail reçu modifié avec succès!');
                        return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'reference' => ['required', 'string', 'max:255', Rule::unique('detail_recus')->ignore($detailrecu->id)],
                        'date' => ['required', 'before_or_equal:now'],
                        'montant' => ['required', new ActionMontantDetailRecuRule($recu, $detailrecu)],
                        'document' => ['required', 'file', 'mimes:pdf,docx,doc'],
                        'compte_id' => ['required'],
                        'typedetailrecu_id' => ['required'],
                    ]);

                    if ($validator->fails()) {
                        return redirect()->route('detailrecus.edit', ['recu' => $recu->id, 'detailrecu' => $detailrecu->id])->withErrors($validator->errors())->withInput();
                    }

                    /* Uploader les documents dans la base de données */
                    $filename = time() . '.' . $request->document->extension();

                    $file = $request->file('document')->storeAs(
                        'documents',
                        $filename,
                        'public'
                    );

                    $detailrecus = $detailrecu->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'recu_id' => $recu->id,
                        'compte_id' => $request->compte_id,
                        'type_detail_recu_id' => $request->typedetailrecu_id,
                    ]);

                    if ($detailrecus) {
                        Session()->flash('message', 'Détail reçu modifié avec succès!');
                        return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
            }
        }
    }

    public function delete(Recu $recu, DetailRecu $detailrecu)
    {
        $comptes = Compte::all();
        $typedetailrecus = TypeDetailRecu::all();
        return view('detailrecus.delete', compact('recu', 'comptes', 'typedetailrecus', 'detailrecu'));
    }

    public function destroy(Recu $recu, DetailRecu $detailrecu)
    {
        ControlesTools::generateLog($detailrecu, 'DetailRecu', 'Suppression ligne');
        $detailrecus = $detailrecu->delete();

        if ($detailrecus) {
            Session()->flash('message', 'Détail reçu supprimé avec succès!');
            return redirect()->route('detailrecus.index', ['recu' => $recu->id]);
        }
    }
}
