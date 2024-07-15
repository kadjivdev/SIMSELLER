<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\tools\AssuranceVisiteTools;
use Illuminate\Http\Request;
use App\Models\VisiteTechnique;
use Illuminate\Validation\Rule;
use App\Rules\DateVisitetechniqueRule;

class VisiteTechniqueController extends Controller
{
    protected $camion, $visitetechniques;

    public function __construct(VisiteTechnique $visitetechniques, Camion $camions)
    {
        $this->visitetechniques = $visitetechniques;
        $this->camions = $camions;
    }


    public function index($id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($id);
        $assurances = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances,$assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur,$visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque,$visiteremorqueEncours);
        return view('visitetechniques.index', compact('camions', 'statutAssur','statutVisiteRemorque', 'statutVisiteTracteur', 'visitetracteur', 'visiteremorque'));
    }



    public function create($id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($id);
        $assurance = $camions->assurances()->orderByDesc('id')->first();

        $assurances = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances,$assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur,$visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque,$visiteremorqueEncours);
        return view('visitetechniques.create', compact('camions','statutAssur','statutVisiteRemorque', 'statutVisiteTracteur', 'assurance', 'visitetracteur', 'visiteremorque'));
    }



    public function store(Request $request)
    {
        request() ->validate([
            'camion_id' => ['required'],
            'dateDebut' => ['required', 'before_or_equal:dateFin', 'max:16', new DateVisitetechniqueRule($request->camion_id,$request->dateFin, $request->libelle)],
            'dateFin' => ['required', 'after_or_equal:dateDebut', 'max:16'],
            'libelle' => ['required'],
            'document' => ['file', 'mimes:pdf,docx,doc'],
        ]);

        //dd($request->all());
            /* Uploader les documents dans la base de données */
            $file = NULL;
            if($request->document){
                $filename = time().'.'.$request->document->extension();
                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
            }




        $visitetechniques = $this->visitetechniques::create([
            'dateDebut' => $request->dateDebut,
            'dateFin' => $request->dateFin,
            'libelle' => strtoupper($request->libelle),
            'camion_id' => $request->camion_id,
            'document' => $file,
        ]);

        if($visitetechniques){
            Session()->flash('message', 'Visite technique ajoutée avec succès!');
            return redirect()->route('visitetechniques.index', ['id'=>$visitetechniques->camion_id]);
        }
    }



    public function show(VisiteTechnique $visitetechniques)
    {
        //
    }


    public function edit($camion_id, $visitetechnique_id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($camion_id);
        $assurance = $camions->assurances()->orderByDesc('id')->first();
        $visitetechniques = $this->visitetechniques->findOrFail($visitetechnique_id);

        $assurances = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances,$assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur,$visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque,$visiteremorqueEncours);

        return view('visitetechniques.edit', compact('camions','statutAssur','statutVisiteRemorque', 'statutVisiteTracteur', 'assurance', 'visitetechniques', 'visitetracteur', 'visiteremorque'));

    }



    public function update(Request $request)
    {

            if($request->document == NULL){
                request() ->validate([
                    'camion_id' => ['required'],
                    'dateDebut' => ['required', 'before_or_equal:dateFin', 'max:16',new DateVisitetechniqueRule($request->camion_id,$request->dateFin,$request->libelle,$request->id)],
                    'dateFin' => ['required', 'after_or_equal:dateDebut', 'max:16'],
                    'libelle' => ['required'],
                ]);

                $visitetechniques = $this->visitetechniques->findOrFail($request->id);

                $visitetechnique = $visitetechniques->update([
                    'dateDebut' => $request->dateDebut,
                    'dateFin' => $request->dateFin,
                    'compagnie' => strtoupper($request->compagnie),
                    'camion_id' => $request->camion_id,
                    'document' => $request->remoovdoc ? null : $visitetechniques->document,
                ]);
            }else{

                request() ->validate([
                    'camion_id' => ['required'],
                    'dateDebut' => ['required', 'before_or_equal:dateFin', 'max:16',new DateVisitetechniqueRule($request->camion_id, $request->libelle,$request->id)],
                    'dateFin' => ['required', 'after_or_equal:dateDebut', 'max:16'],
                    'libelle' => ['required'],
                    'document' => ['file', 'mimes:pdf,docx,doc'],
                ]);

                $visitetechniques = $this->visitetechniques->findOrFail($request->id);
                /* Uploader les documents dans la base de données */
                $filename = time().'.'.$request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );

                $visitetechnique = $visitetechniques->update([
                    'dateDebut' => $request->dateDebut,
                    'dateFin' => $request->dateFin,
                    'compagnie' => strtoupper($request->compagnie),
                    'camion_id' => $request->camion_id,
                    'document' => $file,
                ]);
            }

        if($visitetechnique){
            Session()->flash('message', 'Visite technique modifiée avec succès!');
            return redirect()->route('visitetechniques.index', ['id'=>$visitetechniques->camion_id]);
        }
    }


    public function delete($camion_id, $visitetechnique_id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($camion_id);
        $assurance = $camions->assurances()->orderByDesc('id')->first();
        $visitetechniques = $this->visitetechniques->findOrFail($visitetechnique_id);
        $assurances = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances,$assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur,$visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque,$visiteremorqueEncours);

        return view('visitetechniques.delete', compact('camions','statutAssur','statutVisiteRemorque', 'statutVisiteTracteur', 'assurance', 'visitetechniques', 'visitetracteur', 'visiteremorque'));
    }



    public function destroy($id)
    {
        $visitetechniques = $this->visitetechniques->findOrFail($id);
        $visitetechnique = $visitetechniques->delete();
        if($visitetechnique){
            Session()->flash('message', 'Visite technique supprimée avec succès!');
            return redirect()->route('visitetechniques.index', ['id'=>$visitetechniques->camion_id]);
        }
    }

}
