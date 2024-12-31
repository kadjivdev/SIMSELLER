<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Assurance;
use App\tools\AssuranceVisiteTools;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\DateAssuranceRule;
use App\Models\CompagnieAssurance;

class AssuranceController extends Controller
{
    protected $assurances, $camions;

    public function __construct(Assurance $assurances, Camion $camions)
    {
        $this->assurances = $assurances;
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

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances, $assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur, $visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque, $visiteremorqueEncours);

        return view('assurances.index', compact('camions', 'statutAssur', 'visitetracteur', 'statutVisiteRemorque', 'statutVisiteTracteur', 'visiteremorque'));
    }



    public function create($id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($id);

        $assurances = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances, $assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur, $visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque, $visiteremorqueEncours);
        $compagnieassurances = CompagnieAssurance::all();
        return view('assurances.create', compact('camions', 'statutAssur', 'statutVisiteRemorque', 'statutVisiteTracteur', 'visitetracteur', 'visiteremorque', 'compagnieassurances'));
    }



    public function store(Request $request)
    {
        if ($request->document == NULL) {
            request()->validate([
                'camion_id' => ['required'],
                'police' => ['required'],
                'dateDebut' => ['required', 'before_or_equal:dateFin', 'max:16', new DateAssuranceRule($request->camion_id, $request->dateFin)],
                'dateFin' => ['required', 'after_or_equal:dateDebut', 'max:16'],
                'compagnie' => ['required'],
            ]);

            $assurances = $this->assurances::create([
                'police' => strtoupper($request->police),
                'dateDebut' => $request->dateDebut,
                'dateFin' => $request->dateFin,
                'compagnie' => strtoupper($request->compagnie),
                'camion_id' => $request->camion_id,
            ]);
        } else {
            request()->validate([
                'camion_id' => ['required'],
                'police' => ['required'],
                'dateDebut' => ['required', 'before_or_equal:dateFin', 'max:16', new DateAssuranceRule($request->camion_id, $request->dateFin)],
                'dateFin' => ['required', 'after_or_equal:dateDebut', 'max:16'],
                'compagnie' => ['required'],
                'document' => ['required', 'file', 'mimes:pdf,docx,doc'],
            ]);

            /* Uploader les documents dans la base de données */
            $filename = time() . '.' . $request->document->extension();

            $file = $request->file('document')->storeAs(
                'documents',
                $filename,
                'public'
            );

            $assurances = $this->assurances::create([
                'police' => strtoupper($request->police),
                'dateDebut' => $request->dateDebut,
                'dateFin' => $request->dateFin,
                'compagnie' => strtoupper($request->compagnie),
                'camion_id' => $request->camion_id,
                'document' => $file,
            ]);
        }

        if ($assurances) {
            Session()->flash('message', 'Assurance ajoutée avec succès!');
            return redirect()->route('assurances.index', ['id' => $assurances->camion_id]);
        }
    }

    public function show(Assurance $interlocuteurs)
    {
        //
    }

    public function edit($camion_id, $assurance_id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($camion_id);
        $assurance = $camions->assurances()->where('id', $assurance_id)->orderByDesc('id')->first();

        $assurances = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances, $assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur, $visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque, $visiteremorqueEncours);
        $compagnieassurances = CompagnieAssurance::all();
        return view('assurances.edit', compact('camions', 'statutAssur', 'statutVisiteRemorque', 'statutVisiteTracteur', 'assurance', 'assurance', 'assurances', 'visitetracteur', 'visiteremorque', 'compagnieassurances'));
    }



    public function update(Request $request)
    {
        if ($request->document == NULL) {
            request()->validate([
                'camion_id' => ['required'],
                'police' => ['required'],
                'dateDebut' => ['required', 'before_or_equal:dateFin', 'max:16', new DateAssuranceRule($request->camion_id, $request->dateFin, $request->id)],
                'dateFin' => ['required', 'after_or_equal:dateDebut', 'max:16'],
                'compagnie' => ['required'],
            ]);

            $assurances = $this->assurances->findOrFail($request->id);

            $assurance = $assurances->update([
                'police' => strtoupper($request->police),
                'dateDebut' => $request->dateDebut,
                'dateFin' => $request->dateFin,
                'compagnie' => strtoupper($request->compagnie),
                'camion_id' => $request->camion_id,
                'document' => $request->remoovdoc ? null : $assurances->document,
            ]);
        } else {
            request()->validate([
                'camion_id' => ['required'],
                'police' => ['required'],
                'dateDebut' => ['required', 'before_or_equal:dateFin', 'max:16', new DateAssuranceRule($request->camion_id, $request->dateFIn, $request->id)],
                'dateFin' => ['required', 'after_or_equal:dateDebut', 'max:16'],
                'compagnie' => ['required'],
                'document' => ['required', 'file', 'mimes:pdf,docx,doc'],
            ]);

            $assurances = $this->assurances->findOrFail($request->id);

            /* Uploader les documents dans la base de données */
            $filename = time() . '.' . $request->document->extension();

            $file = $request->file('document')->storeAs(
                'documents',
                $filename,
                'public'
            );

            $assurance = $assurances->update([
                'police' => strtoupper($request->police),
                'dateDebut' => $request->dateDebut,
                'dateFin' => $request->dateFin,
                'compagnie' => strtoupper($request->compagnie),
                'camion_id' => $request->camion_id,
                'document' => $file,
            ]);
        }

        if ($assurance) {
            Session()->flash('message', 'Assurance modifiée avec succès!');
            return redirect()->route('assurances.index', ['id' => $assurances->camion_id]);
        }
    }


    public function delete($camion_id, $assurance_id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($camion_id);
        $assurance = $camions->assurances()->orderByDesc('id')->first();
        $assurances = $this->assurances->findOrFail($assurance_id);

        $assurancesChek = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurancesChek, $assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteur, $visitetracteurEncours);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque, $visiteremorqueEncours);

        return view('assurances.delete', compact('camions', 'statutAssur', 'statutVisiteRemorque', 'statutVisiteTracteur', 'assurance', 'assurances', 'visitetracteur', 'visiteremorque'));
    }



    public function destroy($id)
    {
        $assurances = $this->assurances->findOrFail($id);
        $assurance = $assurances->delete();
        if ($assurance) {
            Session()->flash('message', 'Assurance supprimée avec succès!');
            return redirect()->route('assurances.index', ['id' => $assurances->camion_id]);
        }
    }
}
