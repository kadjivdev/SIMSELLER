<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\CompagnieAssurance;
use App\tools\AssuranceVisiteTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Avaliseur;
use App\Models\Chauffeur;
use App\Models\Marque;
use App\Rules\UniqueImmatriculationCamionRule;

class CamionController extends Controller
{
    protected $camions, $chauffeurs;

    public function __construct(Camion $camions, Chauffeur $chauffeurs)
    {
        $this->camions = $camions;
        $this->chauffeurs = $chauffeurs;
    }


    public function index()
    {
        $camions = $this->camions::all();
        return view('camions.index', compact('camions'));
    }



    public function create()
    {
        $chauffeurs = Chauffeur::where('statut', 'Actif')->get();
        $avaliseurs = Avaliseur::all();
        $marques = Marque::all();
        return view('camions.create', compact('chauffeurs', 'avaliseurs', 'marques'));
    }



    public function store(Request $request)
    {
        //dd($request->all());
            $datas = $request->all();
            if($request->immatriculationTracteur)
               $datas['immatriculationTracteur'] = str_replace(' ','', $request->immatriculationTracteur);
            if($request->immatriculationRemorque)
                $datas['immatriculationRemorque'] = str_replace(' ','', $request->immatriculationRemorque);
            if($request->photo == NULL){
                $validator = Validator::make($datas,[
                    'marque_id' => ['required'],
                    'immatriculationTracteur' => ['required', 'unique:camions', new UniqueImmatriculationCamionRule($datas['immatriculationRemorque'])],
                    'immatriculationRemorque' => ['required', 'unique:camions'],
                    'nombreIssieu' => ['required'],
                    'tonnage' => ['required'],
                    'avaliseur_id' => ['required', 'string', 'max:255'],
                    'chauffeur_id' => ['required', 'string', 'max:255'],
                    'statut' => ['required'],
                ]);
                if($validator->fails()){
                    return redirect()->route('camions.create')->withErrors($validator->errors())->withInput();
                }

                $camions = $this->camions::create([
                    'marque_id' => $request->marque_id,
                    'immatriculationTracteur' => str_replace(' ','',strtoupper($request->immatriculationTracteur)),
                    'immatriculationRemorque' => str_replace(' ','', strtoupper($request->immatriculationRemorque)),
                    'nombreIssieu' => strtoupper($request->nombreIssieu),
                    'tonnage' => $request->tonnage,
                    'avaliseur_id' => $request->avaliseur_id,
                    'statut' => ucfirst($request->statut),
                    'chauffeur_id' => $request->chauffeur_id,
                ]);
            }else{
                $validator = Validator::make($datas,[
                    'marque_id' => ['required'],
                    'immatriculationTracteur' => ['required', 'unique:camions', new UniqueImmatriculationCamionRule($datas['immatriculationRemorque'])],
                    'immatriculationRemorque' => ['required', 'unique:camions'],
                    'nombreIssieu' => ['required'],
                    'tonnage' => ['required'],
                    'avaliseur_id' => ['required', 'string', 'max:255'],
                    'chauffeur_id' => ['required', 'string', 'max:255'],
                    'statut' => ['required'],
                    'photo' => ['image', 'mimes:jpg,bmp,png'],
                ]);
                if($validator->fails()){
                    return redirect()->route('camions.create')->withErrors($validator->errors())->withInput();
                }
                /* Uploader les images dans la base de données */
                $image = $request->file('photo');
                $photo = time().'.'.$image->extension();
                $image->move(public_path('images'), $photo);

                $camions = $this->camions::create([
                    'marque_id' => $request->marque_id,
                    'immatriculationTracteur' => str_replace(' ','', strtoupper($request->immatriculationTracteur)),
                    'immatriculationRemorque' => str_replace(' ', '', strtoupper($request->immatriculationRemorque)),
                    'nombreIssieu' => strtoupper($request->nombreIssieu),
                    'tonnage' => $request->tonnage,
                    'avaliseur_id' => $request->avaliseur_id,
                    'statut' => ucfirst($request->statut),
                    'chauffeur_id' => $request->chauffeur_id,
                    'photo' => $photo,
                ]);
            }


            if($camions){
                Session()->flash('message', 'Camion ajouté avec succès!');
                return redirect()->route('camions.index');
            }
            else{
                Session()->flash('error', 'Echec: Une erreur est survenue lors de l\'enregistrement du bon de commande.
                Veuiller réessayer et si l\'erreur persiste, contacter le concepteur!');
                return redirect()->route('camions.index');
            }
    }



    public function show($id)
    {
        $camions = $this->camions->orderByDesc('id')->findOrFail($id);
        $assurance = $camions->assurances()->orderByDesc('id')->first();
        $visitetracteur = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->orderByDesc('id')->first();
        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->orderByDesc('id')->first();

        $assurances = $camions->assurances()->where('dateFin', '>', date('Y-m-d'))->get();
        $assuEncours = $camions->assurances()->where('dateFin', date('Y-m-d'))->get();

        $visitetracteurt = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', '>', date('Y-m-d'))->get();
        $visitetracteurEncourst = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE TRACTEUR')->where('dateFin', date('Y-m-d'))->get();

        $visiteremorque = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', '>', date('Y-m-d'))->get();
        $visiteremorqueEncours = $camions->visitetechniques()->where('libelle', 'VISITE TECHNIQUE REMORQUE')->where('dateFin', date('Y-m-d'))->get();

        $statutAssur = AssuranceVisiteTools::getControleStat($assurances,$assuEncours);
        $statutVisiteTracteur = AssuranceVisiteTools::getControleStat($visitetracteurt,$visitetracteurEncourst);
        $statutVisiteRemorque = AssuranceVisiteTools::getControleStat($visiteremorque,$visiteremorqueEncours);
        $compagnieassurances = CompagnieAssurance::all();
        return view('camions.show', compact('camions','statutAssur','statutVisiteRemorque', 'statutVisiteTracteur', 'assurance', 'visitetracteur', 'visiteremorque'));
    }


    public function edit($id)
    {
        $camions = $this->camions->findOrFail($id);
        $avaliseurs = Avaliseur::all();
        $chauffeurs = Chauffeur::where('statut', 'Actif')->get();
        $marques = Marque::all();

        return view('camions.edit', compact('camions', 'avaliseurs','chauffeurs', 'marques'));

    }


    public function addPhoto($id)
    {
        $camions = $this->camions->findOrFail($id);

        return view('camions.addPhoto', compact('camions'));

    }


    public function photo(Request $request)
    {
        if(!$request->remoov){
            request() ->validate([
                'photo' => ['required', 'image', 'mimes:jpg,bmp,png'],
            ]);

            /* Uploader les images dans la base de données */
            $image = $request->file('photo');
            $photo = time().'.'.$image->extension();
            $image->move(public_path('images'), $photo);

        }
        else{
            $photo = null;
        }

        $camions = $this->camions->findOrFail($request->id);

        $camions = $camions->update([
            'photo'=>$photo
        ]);

        if($camions){
            Session()->flash('message', 'Photo camion actualisée avec succès!');
            return redirect()->route('camions.index');
        }
    }



    public function update(Request $request)
    {
        $datas = $request->all();
        if($request->immatriculationTracteur)
            $datas['immatriculationTracteur'] = str_replace(' ','', $request->immatriculationTracteur);
        if($request->immatriculationRemorque)
            $datas['immatriculationRemorque'] = str_replace(' ','', $request->immatriculationRemorque);

        $validator = Validator::make($datas,[
            'marque_id' => ['required'],
            'immatriculationTracteur' => ['required', Rule::unique('camions')->ignore($request->id), new UniqueImmatriculationCamionRule($datas['immatriculationRemorque'],$request->id)],
            'immatriculationRemorque' => ['required', Rule::unique('camions')->ignore($request->id)],
            'nombreIssieu' => ['required'],
            'tonnage' => ['required'],
            'avaliseur_id' => ['required'],
            'chauffeur_id' => ['required'],

        ]);
        if($validator->fails()){
            return redirect()->route('camions.edit',['id'=>$request->id])->withErrors($validator->errors())->withInput();
        }
        $camions = $this->camions->findOrFail($request->id);
        if($request->statut == 'on'){
            $statut = 'Actif';
        }
        else{
            $statut = 'Inactif';
        }
        $camions = $camions->update([
            'marque_id' => strtoupper($request->marque_id),
            'immatriculationTracteur' => str_replace(' ','', strtoupper($request->immatriculationTracteur)),
            'immatriculationRemorque' => str_replace(' ', '', strtoupper($request->immatriculationRemorque)),
            'nombreIssieu' => $request->nombreIssieu,
            'tonnage' => $request->tonnage,
            'avaliseur_id' => $request->avaliseur_id,
            'chauffeur_id' => $request->chauffeur_id,
            'statut' => $statut,
        ]);

        if($camions){
            Session()->flash('message', 'Camion modifier avec succès!');
            return redirect()->route('camions.index');
        }
    }


    public function delete($id)
    {
        $camions = $this->camions->findOrFail($id);
        if(count($camions->assurances) > 0 || count($camions->visitetechniques) > 0){
            Session()->flash('messageSupp', 'Vous ne pouvez pas supprimer ce camion car il a déjà des viste techniques ou assurances');
            return redirect()->route('camions.index');
        }
        return view('camions.delete', compact('camions'));
    }



    public function destroy($id)
    {
        $camions = $this->camions->findOrFail($id)->delete();
        if($camions){
            Session()->flash('message', 'Camion supprimé avec succès!');
            return redirect()->route('camions.index');
        }
    }
}
