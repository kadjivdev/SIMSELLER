<?php

namespace App\Http\Controllers;

use App\Mail\CommandeMail;
use App\Models\Entreprise;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use App\Models\Produit;

use App\Models\Parametre;
use App\Models\BonCommande;
use App\Models\Fournisseur;
use App\Models\TypeCommande;
use Illuminate\Http\Request;
use App\tools\BonCommandeTools;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Models\DetailBonCommande;
use App\Models\Recu;
use App\tools\ControlesTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BonCommandeController extends Controller
{
    protected $typecommandes, $fournisseurs, $produits;

    public function __construct(TypeCommande $typecommandes, Fournisseur $fournisseurs, Produit $produits)
    {
        $this->middleware('superviseur')->only(['valider', 'invalider', 'retournerCommande']);
        $this->middleware('gest')->only(['create', 'delete', 'envoyerCommande']);

        $this->typecommandes = $typecommandes;
        $this->fournisseurs = $fournisseurs;
        $this->produits = $produits;
    }

    public function index(Request $request)
    {
        // bons en préparation
        $pre_boncommandes = BonCommande::orderBy('code', 'desc')->whereIn("statut", ['Préparation', 'En attente de validation','Envoyé'])->get();
        $query = BonCommande::orderBy('code', 'desc')->whereNotIn("statut", ['Préparation', 'En attente de validation','Envoyé']);

        if ($request->debut && $request->fin) {
            $boncommandes = $query->whereBetween('dateBon', [$request->debut, $request->fin])->get();
            $req = $request->all();
            return view('boncommandes.index', compact('boncommandes', 'req', 'pre_boncommandes'));
        }

        $boncommandes = $query->get();
        return view('boncommandes.index', compact('boncommandes', 'pre_boncommandes'));
    }

    public function create(Request $request, BonCommande $boncommandes = NULL)
    {
        if (Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()) {
            $typecommandes = $this->typecommandes->orderBy('libelle')->get();
            $fournisseurs = $this->fournisseurs->orderBy('sigle')->get();
            $produits = $this->produits->orderBy('libelle')->get();
            $redirectto = $request->redirectto;
            return view('boncommandes.create', compact('boncommandes', 'typecommandes', 'fournisseurs', 'produits', 'redirectto'));
        } else {
            Session()->flash('message', 'Vous ne pouvez pas accéder à cette option!');
            return redirect()->route('boncommandes.index');
        }
    }

    public function store(Request $request, BonCommande $boncommandes = NULL)
    {
        try {
            if ($boncommandes) {

                $validator = Validator::make($request->all(), [
                    'code' => ['required', 'string', 'max:255', Rule::unique('bon_commandes')->ignore($boncommandes->id)],
                    'dateBon' => ['required'],
                    'fournisseur_id' => ['required'],
                    'type_commande_id' => ['required'],
                ]);

                if ($validator->fails()) {
                    return redirect()->route('boncommandes.create', ['boncommandes' => $boncommandes])->withErrors($validator->errors())->withInput();
                }

                $boncommande = $boncommandes->update([
                    'code' => $request->code,
                    'dateBon' => $request->dateBon,
                    'statut' => "Préparation",
                    'type_commande_id' => $request->type_commande_id,
                    'fournisseur_id' => $request->fournisseur_id,
                    'users' => Auth::user()->name,
                ]);

                if ($boncommande) {
                    BonCommandeTools::ajusterProduitFournisseur($boncommandes);
                    BonCommandeTools::calculerTotalCommande($boncommandes);
                    Session()->flash('messagebc', 'Les informations de l\'entête de la commande on été mise à jour avec succès.');
                    return redirect()->route('boncommandes.edit', ['boncommande' => $boncommandes->id]);
                }
            } else {

                $validator = Validator::make($request->all(), [
                    'dateBon' => ['required'],
                    'fournisseur_id' => ['required'],
                    'type_commande_id' => ['required'],
                ]);

                if ($validator->fails()) {
                    return redirect()->route('boncommandes.create')->withErrors($validator->errors())->withInput();
                }

                $format = env('FORMAT_BON_COMMANDE');
                $parametre = Parametre::where('id', env('PARAMETRE'))->first();
                $code = $format . str_pad($parametre->valeur, 4, "0", STR_PAD_LEFT);

                $boncommandes = BonCommande::create([
                    'code' => $code,
                    'dateBon' => $request->dateBon,
                    'statut' => "Préparation",
                    'type_commande_id' => $request->type_commande_id,
                    'fournisseur_id' => $request->fournisseur_id,
                    'users' => Auth::user()->name,
                ]);

                if ($boncommandes) {

                    $valeur = $parametre->valeur;

                    $valeur = $valeur + 1;

                    $parametres = Parametre::find(env('PARAMETRE'));

                    $parametre = $parametres->update([
                        'valeur' => $valeur,
                    ]);

                    if ($parametre) {
                        Session()->flash('message', 'Bon de commande ajouté avec succès!');
                        return redirect()->route('boncommandes.edit', ['boncommande' => $boncommandes->id]);
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('boncommandes.index');
            }
        }
    }

    public function show(BonCommande $boncommande)
    {
        $entreprise = Entreprise::find(1);
        $boncommandes = $boncommande;
        $detailboncommandes = DetailBonCommande::where('bon_commande_id', $boncommandes->id)->get();

        return view('boncommandes.show', compact('boncommandes', 'detailboncommandes', 'entreprise'));
    }


    public function valider(BonCommande $boncommande)
    {
        if (Auth::user()->roles()->where('libelle', 'VALIDATEUR')->exists()) {
            $boncommandes = $boncommande;
            $recus = Recu::all()->where('bon_commande_id', $boncommande->id);
            return view('boncommandes.valider', compact('boncommandes'));
        } else {

            Session()->flash('message', 'Vous ne pouvez pas accéder à cette option!');
            return redirect()->route('boncommandes.index');
        }
    }

    public function invalider(BonCommande $boncommande)
    {
        $boncommandes = $boncommande;
        return view('boncommandes.invalider', compact('boncommandes'));
    }

    public function show_print(BonCommande $boncommande)
    {
        $entreprise = Entreprise::find(1);
        $boncommandes = $boncommande;
        $detailboncommandes = DetailBonCommande::where('bon_commande_id', $boncommandes->id)->get();

        return view('boncommandes.show-print', compact('boncommandes', 'detailboncommandes', 'entreprise'));
    }

    public function pdf(BonCommande $boncommande)
    {
        $entreprise = Entreprise::find(1);
        $boncommandes = $boncommande;
        $qrcode = base64_encode(QrCode::format('svg')->size(70)->errorCorrection('H')->generate($boncommande->code . ',' . $boncommande->montant . ',' . md5($boncommande->id)));
        $formater = new \NumberFormatter("fr", \NumberFormatter::SPELLOUT);
        $dateFormater = strftime('%A %d %B %Y', date_create($boncommande->dateBon)->format('U'));
        $detailboncommandes = DetailBonCommande::where('bon_commande_id', $boncommandes->id)->get();

        $pdf = Pdf::loadView('boncommandes.show-print', compact('boncommandes', 'detailboncommandes', 'entreprise', 'dateFormater', 'formater', 'qrcode'));
        return $pdf->stream('boncommande.pdf');
    }

    public function edit(BonCommande $boncommande, DetailBonCommande $detailboncommande = NULL)
    {
        $fournisseur = $this->fournisseurs->findOrFail($boncommande->fournisseur_id);
        $produits = $fournisseur->produits()->get();
        $boncommandes = $boncommande;

        return view('boncommandes.edit', compact('boncommandes', 'detailboncommande', 'produits'));
    }

    public function update(Request $request, BonCommande $boncommande)
    {
        try {
            if ($boncommande->statut == 'Valider') {

                $timestamp = strtotime($boncommande->created_at);
                $now = time();
                $difference = $now - $timestamp;
                $hours = $difference / 3600;
                if ($hours >= 24) {
                    Session()->flash('message', 'Vous ne pouvez pas effectuer des opération sur cette commande!');
                    return redirect()->route('boncommandes.index');
                }
            } else {

                if ($request->statut == 'Valider') {
                    $recu = Recu::where('bon_commande_id', $boncommande->id)->get()->toArray();

                    if (($recu == null) && ($boncommande->type_commande_id == 1)) {
                        Session()->flash('error', 'Vous ne pouvez pas valider cette commande. Rattacher d\'abord un reçu');
                        return redirect()->route('boncommandes.index');
                    } else {

                        $sommeMontant = 0;
                        foreach ($recu as $item) {
                            $sommeMontant += $item['montant'];
                        }

                        if ($sommeMontant != $boncommande->montant) {
                            Session()->flash('error', 'Vous ne pouvez pas valider cette commande. le montant des versements ne correspond pas au montant de la commande');
                            return redirect()->route('boncommandes.index');
                        }
                    }

                    $boncommande->statut = $request->statut;
                    $boncommandes = $boncommande;
                    if ($boncommande->update()) {
                        $validateur = User::find(env('GESTIONNAIRE_ID'));
                        $destinataire = [
                            'nom' => $validateur->name,
                            'email' => $validateur->email
                        ];
                        $subject = 'CONFIRMATION DE VALIDATION DE COMMANDE';
                        $message_html = "Votre demande validation de la commande ci-dessous a été prise en compte. Vous pouvez passer à la programmation.<br>
                            <ul>
                            <li>Commande N° $boncommandes->code</li>
                            <li>Date : " . date_format(date_create($boncommandes->dateBon), 'd/m/Y') . "</li>
                            <li>Date : " . number_format($boncommandes->montant, 2, ',', ' ') . "</li>
                            </ul>
                            <p><b> Valider par : </b> " . Auth::user()->name . " le <b> " . date_format(date_create($boncommandes->dateBon), 'd/m/Y') . "</b></p>
                        ";
                        $lienAction = route('programmations.index');
                        $mail = new CommandeMail($destinataire, $subject, $message_html, [], $lienAction);
                        Mail::send($mail);
                        Session()->flash('message', 'Bon de commande validé avec succès!');
                        return redirect()->route('boncommandes.index');
                    }
                } elseif ($request->statut == 'Préparation') {
                    $boncommande->statut = $request->statut;
                    $boncommandes = $boncommande->update();

                    if ($boncommandes) {
                        Session()->flash('message', 'Bon de commande invalidé avec succès!');
                        return redirect()->route('boncommandes.index');
                    }
                }
            }
        } catch (Exception $e) {
            if (env('APP_DEBUG') == TRUE) {
                return $e;
            } else {
                Session()->flash('error', 'Opps! Enregistrement échoué. Veuillez contacter l\'administrateur système!');
                return redirect()->route('boncommandes.index');
            }
        }
    }

    public function etat()
    {
        // Une commande à crédit est une commande qui n'a pas encore reçu de versemment qu'elle soit au comptant ou credit 
        $commandeCredit = DB::table('bon_commandes')
            ->leftJoin('recus', 'recus.bon_commande_id', '=', 'bon_commandes.id')
            ->where('recus.bon_commande_id', null)->select('bon_commandes.*')->get();

        $commandeReglementEnCour =  DB::table('bon_commandes')
            ->join('recus', 'bon_commandes.id', '=', 'recus.bon_commande_id')
            ->groupBy('bon_commandes.id')
            ->havingRaw('SUM(recus.montant) < bon_commandes.montant')
            ->select('bon_commandes.*', DB::raw('SUM(recus.montant) AS montant_payer'))->get();
    }

    public function delete(BonCommande $boncommande)
    {
        $boncommandes = $boncommande;
        return view('boncommandes.delete', compact('boncommandes'));
    }

    public function destroy(BonCommande $boncommande)
    {
        ControlesTools::generateLog($boncommande, 'BonCommande', 'Suppression ligne');
        $boncommande->accusedocuments()->delete();
        foreach ($boncommande->recus as $recu) {
            $recu->detailrecus()->delete();
        }

        $boncommande->recus()->delete();
        $boncommande->detailboncommandes()->delete();
        $boncommande->delete();

        Session()->flash('message', 'Bon de commande supprimé avec succès!');
        return redirect()->route('boncommandes.index');
    }

    public function envoyerCommande(BonCommande $boncommandes)
    {
        return view('boncommandes.envoyer', compact('boncommandes'));
    }

    public function postEnvoyerCommande(BonCommande $boncommandes)
    {
        if ($boncommandes->statut == 'Préparation') {
            $validateur = User::find(env('VALIDATEUR_ID'));
            $destinataire = [
                'nom' => $validateur->name,
                'email' => $validateur->email
            ];
            $subject = 'DEMANDE DE VALIDATION COMMANDE';
            $message_html = "Vous avez une nouvelle commande en attente de validation. <br>
                <ul>
                <li>Commande N° $boncommandes->code</li>
                <li>Date : " . date_format(date_create($boncommandes->dateBon), 'd/m/Y') . "</li>
                <li>Date : " . number_format($boncommandes->montant, 2, ',', ' ') . "</li>
                </ul>
                <p><b>Validation demandée par :</b> $boncommandes->users le <b>" . date_format(date_create($boncommandes->dateBon), 'd/m/Y') . "</b></p>
                ";
            $lienAction = route('boncommandes.index');
            $mail = new CommandeMail($destinataire, $subject, $message_html, [], $lienAction);
            $boncommandes->statut = 'Envoyé';
            if ($boncommandes->update()) {
                Mail::send($mail);
                Session()->flash('message', 'Bon de commande envoyé avec succès!');
                return redirect()->route('boncommandes.index');
            } else {
                Session()->flash('error', 'Une erreur est survenue. Merci de reprendre ou contactez l\'administrateur si cela persiste.');
                return redirect()->route('boncommandes.index');
            }
        } else {
            abort('403');
        }
    }

    public function retournerCommande(BonCommande $boncommandes)
    {
        return view('boncommandes.retourner', compact('boncommandes'));
    }

    public function postRetournerCommande(BonCommande $boncommandes)
    {
        if ($boncommandes->statut == 'Envoyé') {
            $user = User::where('name', $boncommandes->users)->first();
            $validateur = User::find($user->id);
            $destinataire = [
                'nom' => $validateur->name,
                'email' => $validateur->email
            ];
            $subject = 'DEMANDE DE VALIDATION COMMANDE';
            $message_html = "<b style='color: red'>La validationd de votre nouvelle commande a été rejeter.</b> <br>
                <ul>
                <li>Commande N° $boncommandes->code</li>
                <li>Date : " . date_format(date_create($boncommandes->dateBon), 'd/m/Y') . "</li>
                <li>Date : " . number_format($boncommandes->montant, 2, ',', ' ') . "</li>
                </ul>
                <p><b>Validation demandée par :</b> $boncommandes->users le <b>" . date_format(date_create($boncommandes->dateBon), 'd/m/Y') . "</b></p>
                ";
            $lienAction = route('boncommandes.index');
            $mail = new CommandeMail($destinataire, $subject, $message_html, [], $lienAction);
            $boncommandes->statut = 'Préparation';
            if ($boncommandes->update()) {
                Mail::send($mail);
                Session()->flash('message', 'Bon de commande Retourner avec succès!');
                return redirect()->route('boncommandes.index');
            } else {
                Session()->flash('error', 'Une erreur est survenue. Merci de reprendre ou contactez l\'administrateur si cela persiste.');
                return redirect()->route('boncommandes.index');
            }
        } else {
            abort('403');
        }
    }
}
