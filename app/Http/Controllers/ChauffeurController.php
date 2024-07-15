<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Chauffeur;
use App\Rules\DateChauffeurRule;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ChauffeurController extends Controller
{
    protected $chauffeurs;

    public function __construct(Chauffeur $chauffeurs)
    {
        $this->chauffeurs = $chauffeurs;
    }


    public function index()
    {
        $chauffeurs = $this->chauffeurs::all();
        return view('chauffeurs.index', compact('chauffeurs'));
    }



    public function create()
    {
        return view('chauffeurs.create');
    }



    public function store(Request $request)
    {
        if($request->photo){
            request() ->validate([
               'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'dateNaissance' => ['nullable', 'date', 'max:255', new DateChauffeurRule()],
                'telephone' => ['nullable', 'string', 'max:255', 'unique:chauffeurs,telephone'],
                'photo' => ['nullable', 'image', 'mimes:jpg,bmp,png'],
                'document' => ['nullable', 'file', 'mimes:pdf,docx,doc'],
                'statut' => ['string', 'max:255'],
                'numero' => ['nullable', 'unique:chauffeurs,numero'],
            ]);

            /* Uploader la photo de profile */

            $image = $request->file('photo');
            $photo = time().'.'.$image->extension();
            $image->move(public_path('images'), $photo);


            if($request->document){
                /* Uploader les documents dans la base de données */
                $filename = time().'.'.$request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
            }
            else{
                $file = null;
            }


            $chauffeurs = $this->chauffeurs::create([
                'nom' => strtoupper($request->nom),
                'prenom' => ucfirst($request->prenom),
                'dateNaissance' => $request->dateNaissance,
                'telephone' => $request->telephone,
                'numero' => $request->numero,
                'permis' => $file,
                'photo' => $photo,
                'statut' => ucfirst($request->statut),
            ]);

            if($chauffeurs){
                Session()->flash('message', 'Chauffeur ajouté avec succès!');
                return redirect()->route('chauffeurs.index');
            }
            else{
                Session()->flash('error', 'Echec: Une erreur est survenue lors de l\'enregistrement du chauffeur.
                Veuiller réessayer et si l\'erreur persiste, contacter le concepteur!');
                return redirect()->route('chauffeurs.index');
            }

        }else{
            request() ->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'dateNaissance' => ['nullable', 'date', 'max:255',new DateChauffeurRule()],
                'telephone' => ['nullable', 'string', 'max:255', 'unique:chauffeurs,telephone'],
                'numero' => ['nullable', 'unique:chauffeurs,numero'],
                'document' => ['nullable', 'file', 'mimes:pdf,docx,doc'],
                'statut' => ['string', 'max:255'],
            ]);

            if($request->document){
                /* Uploader les documents dans la base de données */
                $filename = time().'.'.$request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
            }
            else{
                $file = null;
            }


            $chauffeurs = $this->chauffeurs::create([
                'nom' => strtoupper($request->nom),
                'prenom' => ucfirst($request->prenom),
                'dateNaissance' => $request->dateNaissance,
                'telephone' => $request->telephone,
                'permis' => $file,
                'statut' => ucfirst($request->statut),
                'numero' => $request->numero,
            ]);

            if($chauffeurs){
                Session()->flash('message', 'Chauffeur ajouté avec succès!');
                return redirect()->route('chauffeurs.index');
            }
            else{
                Session()->flash('error', 'Echec: Une erreur est survenue lors de l\'enregistrement du chauffeur.
                Veuiller réessayer et si l\'erreur persiste, contacter le concepteur!');
                return redirect()->route('chauffeurs.index');
            }
        }
    }



    public function show($id)
    {
        $chauffeurs = $this->chauffeurs->findOrFail($id);

        return view('chauffeurs.show', compact('chauffeurs'));
    }


    public function edit($id)
    {
        $chauffeurs = $this->chauffeurs->findOrFail($id);

        return view('chauffeurs.edit', compact('chauffeurs'));

    }


    public function addPhoto($id)
    {
        $chauffeurs = $this->chauffeurs->findOrFail($id);

        return view('chauffeurs.addPhoto', compact('chauffeurs'));

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

        $chauffeurs = $this->chauffeurs->findOrFail($request->id);

        $chauffeurs = $chauffeurs->update([
            'photo' => $photo,
        ]);

        if($chauffeurs){
            Session()->flash('message', 'Photo chauffeur actualisée avec succès!');
            return redirect()->route('chauffeurs.index');
        }
    }



    public function update(Request $request)
    {
        if($request->document){
            request() ->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'dateNaissance' => ['nullable', 'date', 'max:255',new DateChauffeurRule()],
                'telephone' => ['nullable', 'string', 'max:255', Rule::unique('chauffeurs')->ignore($request->id)],
                'document' => ['nullable', 'file', 'mimes:pdf,docx,doc'],
                'statut' => ['string', 'max:255'],
                'numero' => ['nullable', Rule::unique('chauffeurs')->ignore($request->id)],
            ]);
            $chauffeurs = $this->chauffeurs->findOrFail($request->id);
            if($request->statut == 'on'){
                $statut = 'Actif';
            }
            else{
                $statut = 'Inactif';
            }
            //dd($request->all());
                /* Uploader les documents dans la base de données */

                $filename = time().'.'.$request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
            

            $chauffeurs = $chauffeurs->update([
                'nom' => strtoupper($request->nom),
                'prenom' => ucfirst($request->prenom),
                'dateNaissance' => empty($request->dateNaissance) ? $chauffeurs->dateNaissance : $request->dateNaissance,
                'telephone' => $request->telephone,
                'permis' => $file,
                'statut' => ucfirst($statut),
                'numero' => $request->numero,
            ]);

            if($chauffeurs){
                Session()->flash('message', 'chauffeurs modifier avec succès!');
                return redirect()->route('chauffeurs.index');
            }
        }else{
            request() ->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'dateNaissance' => ['nullable', 'date', 'max:255',new DateChauffeurRule()],
                'telephone' => ['nullable', 'string', 'max:255', Rule::unique('chauffeurs')->ignore($request->id)],
                'statut' => ['string', 'max:255'],
                'numero' => ['nullable', Rule::unique('chauffeurs')->ignore($request->id)],
            ]);
            $chauffeurs = $this->chauffeurs->findOrFail($request->id);
            if($request->statut == 'on'){
                $statut = 'Actif';
            }
            else{
                $statut = 'Inactif';
            }
            $chauffeurs = $chauffeurs->update([
                'nom' => strtoupper($request->nom),
                'prenom' => ucfirst($request->prenom),
                'dateNaissance' => $request->dateNaissance,
                'telephone' => $request->telephone,
                'statut' => ucfirst($statut),
                'numero' => $request->numero,
                'permis' => $request->remoovdoc ? null : $chauffeurs->permis,
            ]);

            if($chauffeurs){
                Session()->flash('message', 'chauffeurs modifier avec succès!');
                return redirect()->route('chauffeurs.index');
            }

        }

    }


    public function delete($id)
    {
        $chauffeurs = $this->chauffeurs->findOrFail($id);

        return view('chauffeurs.delete', compact('chauffeurs'));

    }



    public function destroy($id)
    {
        $chauffeurs = $this->chauffeurs->findOrFail($id)->delete();
        if($chauffeurs){
            Session()->flash('message', 'chauffeurs supprimé avec succès!');
            return redirect()->route('chauffeurs.index');
        }
    }
}
