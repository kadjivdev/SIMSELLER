<?php

namespace App\Http\Controllers;

use App\Mail\CommandeMail;
use App\Mail\NotificateurProgrammationMail;
use App\Models\Avaliseur;
use App\Models\Entreprise;
use App\Models\Fournisseur;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use App\Models\Zone;
use App\Models\Camion;
use App\Models\Chauffeur;
use App\Models\Parametre;
use App\Models\BonCommande;
use Illuminate\Http\Request;
use App\Models\Programmation;
use App\tools\BonCommandeTools;
use App\tools\ControlesTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Models\DetailBonCommande;
use App\Rules\QteProgrammationRule;
use App\Rules\DateSortieRule;
use App\Rules\camionProgrammationRule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProgrammationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['gest'])->only(['store', 'allvalidate', 'update', 'postImpression', 'confirmationImpression', 'show']);
        $this->middleware(['superviseur'])->only([]);
    }

    public function index(Request $request)
    {

        if ($request->statuts) {
            if ($request->statuts == 1) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer', 'Livrer'])->whereBetween('dateBon', [$request->debut, $request->fin])->pluck('id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                        ->orderByDesc('id')->get();
                } else {

                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->orderByDesc('id')->get();
                    //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get();    
                }
            } elseif ($request->statuts == 2) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::where('statut', 'Valider')->whereBetween('dateBon', [$request->debut, $request->fin])->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                        ->whereNotIn('id', $programmations)->orderByDesc('id')->get();
                    //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get();

                } else {

                    $boncommandesV = BonCommande::where('statut', 'Valider')->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                        ->whereNotIn('id', $programmations)->orderByDesc('id')->get();
                    //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get(); 

                }
            } elseif ($request->statuts == 3) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::where('statut', 'Valider')->whereBetween('dateBon', [$request->debut, $request->fin])->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                        ->whereIn('id', $programmations)->orderByDesc('id')->get();
                    //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get();
                } else {

                    $boncommandesV = BonCommande::where('statut', 'Valider')->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                        ->whereIn('id', $programmations)->orderByDesc('id')->get();
                }
            } elseif ($request->statuts == 4) {
                if ($request->debut && $request->fin) {

                    $boncommandesV = BonCommande::where('statut', 'Programmer')->whereBetween('dateBon', [$request->debut, $request->fin])->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)

                        ->whereNotIn('id', $programmations)->orderByDesc('id')->get();
                    //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get();

                } else {

                    $boncommandesV = BonCommande::where('statut', 'Programmer')->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                        ->whereIn('id', $programmations)->orderByDesc('id')->get();
                }
            } elseif ($request->statuts == 5) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::where('statut', 'Livrer')->whereBetween('dateBon', [$request->debut, $request->fin])->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)

                        ->whereNotIn('id', $programmations)->orderByDesc('id')->get();
                    //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get();

                } else {
                    $boncommandesV = BonCommande::where('statut', 'Livrer')->pluck('id');
                    $programmations = Programmation::pluck('detail_bon_commande_id');
                    $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                        ->whereIn('id', $programmations)->orderByDesc('id')->get();
                }
            }
        } else {
            if ($request->debut && $request->fin) {

                $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->whereBetween('dateBon', [$request->debut, $request->fin])->pluck('id');
                $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)
                    ->orderByDesc('id')->get();
                //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get();

            } else {

                $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer', 'Livrer'])->pluck('id');
                $detailboncommandes = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->orderByDesc('id')->get();
                //$programmations = $detailboncommandes->programmations()->where('statut', 'Valider')->get();
            }
        }

        $programmation = Programmation::findOrFail(3843);

        dd($programmation->vendus);
        $req = $request->statuts;
        return view('programmations.index', compact('detailboncommandes', 'req'));
    }

    public function create(DetailBonCommande $detailboncommande, Programmation $programmation = NULL)
    {
        $boncommandes = BonCommande::orderByDesc('code')->get();
        $zones = Zone::all();
        $camions = Camion::all();
        $chauffeurs = Chauffeur::all();
        $avaliseurs = Avaliseur::all();

        $programmations = $detailboncommande->programmations()->orderByDesc('id')->get();
        $totalValider = $detailboncommande->programmations()->whereIn('statut', ['Valider', 'Livrer', 'Vendu'])->orderByDesc('id')->get();

        $total = collect($totalValider)->sum('qteprogrammer'); # number_format(collect($totalValider)->sum('qteprogrammer'), 2, ",", " ");
        return view('programmations.create', compact('detailboncommande', 'boncommandes', 'zones', 'avaliseurs', 'camions', 'chauffeurs', 'programmations', 'programmation', 'total'));
    }

    public function store(Request $request, DetailBonCommande $detailboncommande, Programmation $programmation = NULL)
    {
        try {
            if ($programmation) {
                $validator = Validator::make($request->all(), [
                    'code' => ['required', 'string', 'max:255', Rule::unique('programmations')->ignore($programmation->id)],
                    'dateprogrammer' => ['required'],
                    'qteprogrammer' => ['required', new QteProgrammationRule($detailboncommande, $programmation)],
                    'zone_id' => ['required'],
                    'camion_id' => ['required', new camionProgrammationRule($request->qteprogrammer)],
                    'chauffeur_id' => ['required'],
                    'avaliseur_id' => ['required'],
                ]);

                if ($validator->fails()) {
                    Session()->flash('alert', 'Veuillez remplir tous les champs.');
                    return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id])->withErrors($validator->errors())->withInput();;
                }
                $historique = [];
                if ($programmation->historique) {
                    $historique = json_decode($programmation->historique);
                }
                $historique[] = [
                    'user' => Auth::user()->id,
                    'date' => date('Y-m-d H:i')
                ];
                $historique = json_encode($historique);
                $programmation = $programmation->update([
                    'code' => $request->code,
                    'dateprogrammer' => $request->dateprogrammer,
                    'qteprogrammer' => $request->qteprogrammer,
                    'statut' => $request->statut,
                    'detail_bon_commande_id' => $detailboncommande->id,
                    'zone_id' => $request->zone_id,
                    'camion_id' => $request->camion_id,
                    'chauffeur_id' => $request->chauffeur_id,
                    'avaliseur_id' => $request->avaliseur_id,
                    'historiques' => $historique,
                    'observation' => $request->observation,
                ]);


                if ($programmation) {
                    if (floatval($detailboncommande->qteCommander) == floatval((collect($detailboncommande->programmations->where('statut', 'Valider'))->sum('qteprogrammer')))) {
                        $boncommande = $detailboncommande->boncommande;
                        $statut = 'Programmer';
                        BonCommandeTools::statutUpdate($boncommande, $statut);
                    }
                    Session()->flash('message', 'Detail bon de commande modifié avec succès.');
                    return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id])->withErrors($validator->errors())->withInput();
                }
            } else {

                $validator = Validator::make($request->all(), [
                    'dateprogrammer' => ['required'],
                    'qteprogrammer' => ['required', new QteProgrammationRule($detailboncommande)],
                    'zone_id' => ['required'],
                    'camion_id' => ['required', new camionProgrammationRule($request->qteprogrammer)],
                    'chauffeur_id' => ['required'],
                    'avaliseur_id' => ['required'],
                ]);

                if ($validator->fails()) {
                    Session()->flash('alert', 'Veuillez remplir tous les champs.');
                    return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id])->withErrors($validator->errors())->withInput();
                }

                $format = env('FORMAT_PROGRAMMATION');
                $parametre = Parametre::where('id', env('PROGRAMMATION'))->first();
                $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);
                //Controle de la quantité programmer. 

                $SumQtiteProgDetailCmde = DB::table('programmations')
                    ->where('detail_bon_commande_id', $detailboncommande->id)
                    ->selectRaw('SUM(qteprogrammer)')
                    ->first();

                if (!($SumQtiteProgDetailCmde)) {;
                    if ($SumQtiteProgDetailCmde == $detailboncommande->qteCommander) {

                        Session()->flash('alert', 'Atention la quantité commander a été entièrement programmer déjâ!');
                        return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
                    } else {
                        $difference = $detailboncommande->qteCommander - $SumQtiteProgDetailCmde;
                        if ($difference > 0) {
                            if ($difference < $request->programmer) {
                                Session()->flash('alert', 'Atention la quantité restante à programmer est de' . $difference . 'T');
                                return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
                            };
                        };
                    };
                }

                //Vérification si le camion a été déjâ programmer. 
                /*  
                            $verifDoublonCamionSurDetailCmde = DB::table('programmations')
                                    ->where('camion_id', $request->camion_id)
                                    ->where('detail_bon_commande_id', $detailboncommande->id)
                                    ->exists();
                                if($verifDoublonCamionSurDetailCmde){                      
                                    Session()->flash('alert', 'Atention ce Camion a été déjà programmer une fois sur ce detail de bon commande!');
                                    return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);  
                                } 
                        */

                $programmation = Programmation::create([
                    'code' => $code,
                    'dateprogrammer' => $request->dateprogrammer,
                    'qteprogrammer' => $request->qteprogrammer,
                    'statut' => 'Valider',
                    'detail_bon_commande_id' => $detailboncommande->id,
                    'zone_id' => $request->zone_id,
                    'camion_id' => $request->camion_id,
                    'chauffeur_id' => $request->chauffeur_id,
                    'avaliseur_id' => $request->avaliseur_id,
                    'observation' => $request->observation,
                ]);

                if ($programmation) {

                    $valeur = $parametre->valeur;

                    $valeur = $valeur + 1;

                    $parametres = Parametre::find(env('PROGRAMMATION'));

                    $parametre = $parametres->update([
                        'valeur' => $valeur,
                    ]);

                    if ($parametre) {
                        if (floatval($detailboncommande->qteCommander) == floatval((collect($detailboncommande->programmations->where('statut', 'Valider'))->sum('qteprogrammer')))) {
                            $boncommande = $detailboncommande->boncommande;
                            $statut = 'Programmer';
                            BonCommandeTools::statutUpdate($boncommande, $statut);
                        }
                        Session()->flash('message', 'Detail bon de commande programmé avec succès!');
                        return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
            }
        }
    }

    public function show(Request $request, DetailBonCommande $detailboncommande, Programmation $programmation, $total)
    {
        $programmations = $detailboncommande->programmations()->orderByDesc('id')->get();
        return view('programmations.annuler', compact('detailboncommande', 'programmation', 'programmations', 'total'));
    }

    public function allvalidate(Request $request, DetailBonCommande $detailboncommande, Programmation $programmation)
    {
        $programmations = $detailboncommande->programmations()->orderByDesc('id')->get();
        return view('programmations.valider', compact('detailboncommande', 'programmation', 'programmations'));
    }

    public function edit(Programmation $programmation)
    {
        //
    }

    public function update(Request $request, DetailBonCommande $detailboncommande, Programmation $programmation)
    {
        try {
            $programmation->statut = 'Annuler';
            $zone = $programmation->zone;
            if ($programmation->update()) {

                $programme = DB::select(
                    "
                    SELECT
                        programmations.id,   
                        dateprogrammer,
                        camions.immatriculationTracteur,
                       CONCAT(chauffeurs.nom,' ',chauffeurs.prenom,' (',chauffeurs.telephone,')') AS chauffeur,
                       CONCAT(avaliseurs.nom,' ',avaliseurs.prenom,' (',avaliseurs.telephone,')') AS avaliseur,
                       produits.libelle AS produit,
                        qteprogrammer,
                        zones.libelle AS zone,
                        imprimer,
                        zones.id as zone_id
                    FROM programmations
                    INNER JOIN camions ON programmations.camion_id = camions.id
                    INNER JOIN chauffeurs ON programmations.chauffeur_id = chauffeurs.id
                    INNER JOIN detail_bon_commandes ON programmations.detail_bon_commande_id = detail_bon_commandes.id
                    INNER JOIN produits ON detail_bon_commandes.produit_id = produits.id
                    INNER JOIN bon_commandes ON detail_bon_commandes.bon_commande_id = bon_commandes.id
                    INNER JOIN avaliseurs ON programmations.avaliseur_id = avaliseurs.id
                    INNER JOIN zones ON programmations.zone_id = zones.id
                    WHERE programmations.id = ?
                    ",
                    [$programmation->id,]
                );

                $destinataire = [
                    'nom' => $zone->representant->nom . ' ' . $zone->representant->prenom,
                    'email' => $zone->representant->email
                ];

                $subject = 'ANNULATION PROGRAMMATION N° ' . $programmation->code . ' DU ' . date_format(date_create($programmation->dateprogrammer), 'd/m/Y');
                $message_html = "<p style='color: red'>Nous vous informons que la programmation ci-dessous a été annulée pour votre zone. merci de ne pas prendre ça en compte.</p>";
                $lienAction = route('programmations.index');
                $mail = new NotificateurProgrammationMail($destinataire, $subject, $message_html, $programmation->avaliseur->email ? [$programmation->avaliseur->email] : [], $programme[0], $lienAction);
                Mail::send($mail);
                Session()->flash('message', 'Programmation annulée avec succès!');
                return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
            }
        }
    }

    public function delete(DetailBonCommande $detailboncommande, Programmation $programmation) {}

    ####_____REDIRECTION VERS LA PAGE DE TRANSFERT
    public function getProgrammationById_redirect(Request $request, $programmation)
    {
        $programmation = Programmation::find($programmation);
        $zones = Zone::where('id', '<>', $programmation->zone_id)->get();
        return view("livraisons.transfertCamion", compact('programmation', 'zones'));
    }

    public function destroy(DetailBonCommande $detailboncommande, Programmation $programmation)
    {
        ####___VERIFIONS S'IL Y A UNE VENTE SUR CETTE PROGRAMMATION
        if ($programmation->vendus) {
            return back()->with("error", "Il y a des ventes attachées à cette programmations!");
        }
        
        ControlesTools::generateLog($programmation, 'Programmation', 'Suppression ligne');
        $programmation = $programmation->delete();
        if ($programmation) {
            return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
        }
    }

    public function editionProgramme()
    {
        $fournisseurs = Fournisseur::all();
        return view('programmations.editionprogramme', compact('fournisseurs'));
    }

    public function postEditionProgramme(Request $request)
    {
        $programmes = DB::select("
            SELECT
                dateprogrammer,
                camions.immatriculationTracteur,
                CONCAT(chauffeurs.nom,' ',chauffeurs.prenom,' (',chauffeurs.telephone,')') AS chauffeur,
                CONCAT(avaliseurs.nom,' ',avaliseurs.prenom,' (',avaliseurs.telephone,')') AS avaliseur,
                produits.libelle AS produit,
                qteprogrammer,
                zones.libelle AS zone,
                imprimer
            FROM programmations
            INNER JOIN camions ON programmations.camion_id = camions.id
            INNER JOIN chauffeurs ON programmations.chauffeur_id = chauffeurs.id
            INNER JOIN detail_bon_commandes ON programmations.detail_bon_commande_id = detail_bon_commandes.id
            INNER JOIN produits ON detail_bon_commandes.produit_id = produits.id
            INNER JOIN bon_commandes ON detail_bon_commandes.bon_commande_id = bon_commandes.id
            INNER JOIN zones ON programmations.zone_id = zones.id
            INNER JOIN avaliseurs ON programmations.avaliseur_id = avaliseurs.id
            WHERE bon_commandes.fournisseur_id = ?
            AND dateprogrammer BETWEEN  ? AND ? 
            AND programmations.statut <> 'Annuler'
            AND programmations.imprimer IS NULL
        ", [$request->fournisseur, $request->debut, $request->fin]);
        $fournisseur = Fournisseur::find($request->fournisseur);
        session(['fournisseur' => $fournisseur]);
        $lien = route('programmations.postImpression', ['debut' => $request->debut, 'fin' => $request->fin, 'fournisseur' => $fournisseur->id]);
        $imprimer = in_array('0', array_column($programmes, 'imprimer'));
        return redirect()->route('programmations.edition')->with([
            'programmes' => $programmes,
            'dateProgramme' => $request->jour,
            'fournisseur' => $fournisseur,
            'imprimer' => $imprimer,
            'lien' => $lien

        ])->withInput();
    }

    public function postImpression($debut, $fin, Fournisseur $fournisseur)
    {
        $programmes = DB::select("
            SELECT
                programmations.id,   
                dateprogrammer,
                camions.immatriculationTracteur,
               CONCAT(chauffeurs.nom,' ',chauffeurs.prenom,' (',chauffeurs.telephone,')') AS chauffeur,
               CONCAT(avaliseurs.nom,' ',avaliseurs.prenom,' (',avaliseurs.telephone,')') AS avaliseur,
               produits.libelle AS produit,
                qteprogrammer,
                zones.libelle AS zone,
                imprimer,
                zones.id as zone_id
            FROM programmations
            INNER JOIN camions ON programmations.camion_id = camions.id
            INNER JOIN chauffeurs ON programmations.chauffeur_id = chauffeurs.id
            INNER JOIN detail_bon_commandes ON programmations.detail_bon_commande_id = detail_bon_commandes.id
            INNER JOIN produits ON detail_bon_commandes.produit_id = produits.id
            INNER JOIN bon_commandes ON detail_bon_commandes.bon_commande_id = bon_commandes.id
            INNER JOIN avaliseurs ON programmations.avaliseur_id = avaliseurs.id
            INNER JOIN zones ON programmations.zone_id = zones.id
            WHERE bon_commandes.fournisseur_id = ?
            AND dateprogrammer BETWEEN  ? AND ? 
            AND programmations.statut <> 'Annuler'
            AND programmations.imprimer IS NULL

        ", [$fournisseur->id, $debut, $fin]);
        foreach ($programmes as $programme) {
            $zone = Zone::find($programme->zone_id);
            $prog = Programmation::find($programme->id);
            if ($prog->update(['imprimer' => 1])) {
                $destinataire = [
                    'nom' => $zone->representant->nom . ' ' . $zone->representant->prenom,
                    'email' => $zone->representant->email
                ];
                $subject = 'PROGRAMMATION DU ' . date_format(date_create($programme->dateprogrammer), 'd/m/Y');
                $message_html = "Nous vous informont d'une nouvelle programmation de camion pour votre zone.";
                $lienAction = route('programmations.index');
                $mail = new NotificateurProgrammationMail($destinataire, $subject, $message_html, $prog->avaliseur->email ? [$prog->avaliseur->email] : [], $programme, $lienAction);
                Mail::send($mail);
            }
        }
        return redirect()->route('programmations.impression', ['debut' => $debut, 'fin' => $fin, 'fournisseur' => $fournisseur->id]);
    }

    public function confirmationImpression($debut, $fin, Fournisseur $fournisseur)
    {

        $entreprise = Entreprise::find(1);
        $qrcode = base64_encode(QrCode::format('svg')->size(70)->errorCorrection('H')->generate($debut . ',' . $fournisseur->raisonSocial . ',' . md5($fournisseur->id)));
        $formater = new \NumberFormatter("fr", \NumberFormatter::SPELLOUT);
        $dateFormater = strftime('%A %d %B %Y', date_create(date('Y-m-d'))->format('U'));
        $date = date('Y-m-d');

        $programmes = DB::select("
            SELECT
                programmations.id,   
                dateprogrammer,
                camions.immatriculationTracteur,
                CONCAT(chauffeurs.nom,' ',chauffeurs.prenom,' (',chauffeurs.telephone,')') AS chauffeur,
                CONCAT(avaliseurs.nom,' ',avaliseurs.prenom,' (',avaliseurs.telephone,')') AS avaliseur,
                produits.libelle AS produit,
                qteprogrammer,
                zones.libelle AS zone,
                imprimer
            FROM programmations
            INNER JOIN camions ON programmations.camion_id = camions.id
            INNER JOIN chauffeurs ON programmations.chauffeur_id = chauffeurs.id
            INNER JOIN detail_bon_commandes ON programmations.detail_bon_commande_id = detail_bon_commandes.id
            INNER JOIN produits ON detail_bon_commandes.produit_id = produits.id
            INNER JOIN bon_commandes ON detail_bon_commandes.bon_commande_id = bon_commandes.id
            INNER JOIN zones ON programmations.zone_id = zones.id
            INNER JOIN avaliseurs ON programmations.avaliseur_id = avaliseurs.id
            WHERE bon_commandes.fournisseur_id = ?
            AND dateprogrammer BETWEEN  ? AND ? 
            AND programmations.statut <> 'Annuler'
            AND DATE(programmations.updated_at) = ?
        ", [$fournisseur->id, $debut, $fin, $date]);

        $pdf = PDF::loadView('programmations.pdfEdition', compact('debut', 'fin', 'fournisseur', 'programmes', 'entreprise', 'dateFormater', 'formater', 'qrcode'));
        return $pdf->stream('programme.pdf');
    }
}
