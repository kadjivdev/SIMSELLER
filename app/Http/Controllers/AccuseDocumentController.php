<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Parametre;
use App\Models\BonCommande;
use App\Models\TypeDocument;
use Illuminate\Http\Request;
use App\Models\AccuseDocument;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class AccuseDocumentController extends Controller
{

    
    public function index(BonCommande $boncommandes)
    {
        return view('accusedocuments.index', compact('boncommandes'));
    }
    public function indexAll(Request $request)
    {
        $accuseDocuments ='';
        if($request->debut && $request->fin ){ 
            $accuseDocuments = AccuseDocument::whereBetween('date',[$request->debut, $request->fin])->orderByDesc('id')->get();
        }else{
            $accuseDocuments = AccuseDocument::orderByDesc('id')->get();
        }
        return view('accusedocuments.indexAll', compact('accuseDocuments'));
    }


    
    public function create(BonCommande $boncommande)
    {
        $boncommandes = $boncommande;
        $typedocuments = TypeDocument::all();
        return view('accusedocuments.create', compact('boncommandes', 'typedocuments'));
    }


    
    public function store(Request $request, BonCommande $boncommande)
    {
        try {

            $validator = Validator::make($request->all(), [
                'reference' => ['required', 'string', 'max:255', 'unique:accuse_documents'],
                //'libelle' => ['required', 'string', 'max:255'],
                'typedocument' => ['required', 'string', 'max:255'],
                'date' => ['required', 'before_or_equal:now'],
                'montant' => ['required'],
                'document' => ['nullable', 'file', 'mimes:pdf,docx,doc'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('accusedocuments.create', ['boncommande' => $boncommande->id])->withErrors($validator->errors())->withInput();
            }

            if ($request->document){
                /* Uploader les documents dans la base de données */
                $filename = time() . '.' . $request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
            }
            else{
                $file = null;
            }
                
                $format = env('FORMAT_ACCUSE_DOCUMENT');
                $parametre = Parametre::where('id', env('ACCUSE_DOCUMENT'))->first();
                $code = $format.str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);
                
                    $accusedocuments = AccuseDocument::create([
                        'code' => $code,
                        'reference' => strtoupper($request->reference),
                        'libelle' => $code,
                        'type_document_id' => $request->typedocument,
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'observation' => $request->observation,
                        'bon_commande_id' => $boncommande->id,
                    ]);


                    if ($accusedocuments) {

                        $valeur = $parametre->valeur;

                        $valeur = $valeur+1;

                        $parametres = Parametre::find(env('ACCUSE_DOCUMENT'));

                        $parametre = $parametres->update([
                            'valeur' => $valeur,
                        ]);

                        if($parametre){
                            Session()->flash('message', 'Accusé document ajouté avec succès!');
                            return redirect()->route('accusedocuments.index', ['boncommandes' => $boncommande->id]);
                        }
                    }

    }catch (Exception $e){
        if(env('APP_DEBUG') == TRUE){
            return $e;
        }else{
            Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
            return redirect()->route('accusedocuments.index', ['boncommandes' => $boncommande->id]);
        }
    }
    }


    
    public function show(AccuseDocument $accuseDocument)
    {
        //
    }


    
    public function edit(BonCommande $boncommande, AccuseDocument $accusedocument)
    {
        $boncommandes = $boncommande;
        $typedocuments = TypeDocument::all();
        return view('accusedocuments.edit', compact('boncommandes', 'accusedocument', 'typedocuments'));
    }


    
    public function update(Request $request, BonCommande $boncommande, AccuseDocument $accusedocument)
    {
        try {

            if($request->document == NULL){
                $validator = Validator::make($request->all(), [
                    'code' => ['required', 'string', 'max:255', Rule::unique('accuse_documents')->ignore($accusedocument->id)],
                    'reference' => ['required', 'string', 'max:255', Rule::unique('accuse_documents')->ignore($accusedocument->id)],
                    //'libelle' => ['required', 'string', 'max:255'],
                    'typedocument' => ['required', 'string', 'max:255'],
                    'date' => ['required'],
                    'montant' => ['required'],
                ]);
    
                if($validator->fails()){
                    return redirect()->route('accusedocuments.edit', ['boncommande' => $boncommande->id, 'accusedocument' => $accusedocument])->withErrors($validator->errors())->withInput();
                }
    
                    $accusedocument = $accusedocument->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'libelle' => $request->code,
                        'type_document_id' => $request->typedocument,
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'observation' => $request->observation,
                        'bon_commande_id' => $boncommande->id,
                        'document' => $request->remoovdoc ? null : $accusedocument->document,
                    ]);
    
    
                    if ($accusedocument) {
                        Session()->flash('message', 'Accusé document modifié avec succès!');
                        return redirect()->route('accusedocuments.index', ['boncommandes' => $boncommande->id]);
                    }
            }else{
                $validator = Validator::make($request->all(), [
                    'code' => ['required', 'string', 'max:255', Rule::unique('accuse_documents')->ignore($accusedocument->id)],
                    'reference' => ['required', 'string', 'max:255', Rule::unique('accuse_documents')->ignore($accusedocument->id)],
                    //'libelle' => ['required', 'string', 'max:255'],
                    'typedocument' => ['required', 'string', 'max:255'],
                    'date' => ['required'],
                    'montant' => ['required'],
                    'document' => ['required', 'file', 'mimes:pdf,docx,doc'],
                ]);
    
                if($validator->fails()){
                    return redirect()->route('accusedocuments.create', ['boncommande' => $boncommande->id])->withErrors($validator->errors())->withInput();
                }

                /* Uploader les documents dans la base de données */
                $filename = time().'.'.$request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );                
    
                    $accusedocument = $accusedocument->update([
                        'code' => strtoupper($request->code),
                        'reference' => strtoupper($request->reference),
                        'libelle' => $request->code,
                        'type_document_id' => $request->typedocument,
                        'date' => $request->date,
                        'montant' => $request->montant,
                        'document' => $file,
                        'observation' => $request->observation,
                        'bon_commande_id' => $boncommande->id,
                    ]);
    
    
                    if ($accusedocument) {
                        Session()->flash('message', 'Accusé document modifié avec succès!');
                        return redirect()->route('accusedocuments.index', ['boncommandes' => $boncommande->id]);
                    }
            }

    }catch (Exception $e){
        if(env('APP_DEBUG') == TRUE){
            return $e;
        }else{
            Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
            return redirect()->route('accusedocuments.index', ['boncommande' => $boncommande->id]);
        }
    }
    }


    public function delete(BonCommande $boncommande, AccuseDocument $accusedocument)
    {
        $boncommandes = $boncommande;
        return view('accusedocuments.delete', compact('boncommandes', 'accusedocument'));
    }



    public function destroy(BonCommande $boncommande, AccuseDocument $accusedocument)
    {
        $accusedocuments = $accusedocument->delete();

        if ($accusedocuments) {
            Session()->flash('message', 'Accusé document supprimé avec succès!');
            return redirect()->route('accusedocuments.index', ['boncommandes' => $boncommande->id]);
        }
    }
}
