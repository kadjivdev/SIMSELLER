<?php

namespace App\Http\Controllers;

use App\Mail\NotificateurProgrammationMail;
use App\Mail\SuspectMail;
use App\Models\BonCommande;
use App\Models\Chauffeur;
use App\Models\Fournisseur;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Models\Programmation;
use App\Rules\QteLivraisonRule;
use App\tools\BonCommandeTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\DetailBonCommande;
use App\Rules\BonLivRule;
use Illuminate\Support\Facades\Validator;

class LivraisonController extends Controller
{
    public function __construct()
    {
        $this->middleware('vendeur')->only(['create', 'store', 'delete', 'cloturer']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $repre = $user->representant;
        $zones = $repre->zones;

        if ($request->statuts) {
            if ($request->statuts == 1) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 2) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Livrer')->where('imprimer', '1')->where('transfert', NULL)->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)->where('statut', 'Livrer')->where('imprimer', '1')->where('transfert', NULL)->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 3) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Valider')->where('imprimer', '1')->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)->where('statut', 'Valider')->where('imprimer', '1')->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 4) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Livrer')->where('imprimer', '1')->orWhere('statut', 'Livrer')
                        ->where('transfert', '<>', NULL)->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->where('statut', 'Livrer')->where('imprimer', '1')->where('transfert', '<>', NULL)->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 5) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Annuler')->where('imprimer', '1')->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->where('statut', 'Annuler')->where('imprimer', '1')->orderByDesc('code')->get();
                }
            }
        } else {
           
            if ($request->debut && $request->fin) {
                $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                    ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                    ->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')->orderByDesc('code')->get();
            } else {
                $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                    ->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')->orderByDesc('code')->get();
            }
        }
        $req = $request->all();

        // ON AFFICHE TOUTES LES LIVRAISONS POUR LES COMPTES *AIME & CHRISTIAN*
        if (!IS_AIME_ACCOUNT($user) && !IS_CHRISTIAN_ACCOUNT($user)) {
            if (Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists() || Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists()) {
                if ($request->debut && $request->fin)
                    $programmations = $programmations->whereBetween('dateprogrammer', [$request->debut, $request->fin]);
                else
                    $programmations = $programmations;
            }
    
            if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()) {
                // LE VENDEUR NE VERRA DESORMAIS QUE LES LIVRAISONS DE SA ZONE
                if ($request->debut && $request->fin)
                    $programmations = $programmations->where('zone_id', $user->zone_id)->whereBetween('dateprogrammer', [$request->debut, $request->fin]);
                else
                    $programmations = $programmations->where('zone_id', $user->zone_id);
            }
        }

        return view('livraisons.index', compact('programmations', 'req'));
    }

    public function indexpartielle(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $repre = $user->representant;
        $zones = $repre->zones;

        if ($request->statuts) {
            if ($request->statuts == 1) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 2) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Livrer')->where('imprimer', '1')->where('transfert', NULL)->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)->where('statut', 'Livrer')->where('imprimer', '1')->where('transfert', NULL)->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 3) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Valider')->where('imprimer', '1')->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)->where('statut', 'Valider')->where('imprimer', '1')->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 4) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Livrer')->where('imprimer', '1')->orWhere('statut', 'Livrer')
                        ->where('transfert', '<>', NULL)->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->where('statut', 'Livrer')->where('imprimer', '1')->where('transfert', '<>', NULL)->orderByDesc('code')->get();
                }
            } elseif ($request->statuts == 5) {
                if ($request->debut && $request->fin) {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                        ->where('statut', 'Annuler')->where('imprimer', '1')->orderByDesc('code')->get();
                } else {
                    $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                    $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->where('statut', 'Annuler')->where('imprimer', '1')->orderByDesc('code')->get();
                }
            }
        } else {
            /*$boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmé','Livrer','Annuler'])->pluck('id');
            $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
            $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)->whereIn('statut', ['Valider','Livrer'])->where('imprimer','1')->orderByDesc('code')->get();*/

            if ($request->debut && $request->fin) {
                $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                    ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                    ->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')->orderByDesc('code')->get();
            } else {
                $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer'])->pluck('id');
                $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
                $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                    ->where('statut', 'Valider')->where('imprimer', '1')->orWhere('statut', 'Livrer')->orderByDesc('code')->get();
            }
        }

        $req = $request->all();
        if (Auth::user()->roles()->where('libelle', 'SUPERVISEUR')->exists()) {
            if ($request->debut && $request->fin)
                $programmations = $programmations->whereBetween('dateprogrammer', [$request->debut, $request->fin]);
            else
                $programmations = $programmations;
        }

        if (Auth::user()->roles()->where('libelle', 'VENDEUR')->exists()) {
            if ($request->debut && $request->fin)
                $programmations = $programmations->whereIn('zone_id', $zones->pluck('id'))->whereBetween('dateprogrammer', [$request->debut, $request->fin]);
            else
                $programmations = $programmations->whereIn('zone_id', $zones->pluck('id'));
        }

        return view('livraisons.indexPartielle', compact('programmations', 'req'));
    }

    public function annulation(Request $request, Programmation $programmation)
    {
        return view('livraisons.annuler', compact('programmation'));
    }

    public function create(Programmation $programmation)
    {
        return view('livraisons.create', compact('programmation'));
    }

    public function cloturer(Programmation $programmation)
    {
        try {
            $historiques = $programmation->historiques;
            if (count($programmation->vendus) <> 0) {
                $qteVendu = 0;
                foreach ($programmation->vendus as $vendu) {
                    $qteVendu += $vendu->qteVendu;
                }
                $qteRestituer = $programmation->qtelivrer - $qteVendu;
                $newProgrammer = $programmation->qteprogrammer - $programmation->qtelivrer + $qteRestituer;

                $itemecloture['user'] = Auth::user()->name;
                $itemecloture['qteProgrammer'] = $programmation->qteprogrammer;
                $itemecloture['qtelivrer'] = $programmation->qtelivrer;
                $itemecloture['bl'] = $programmation->bl;
                $itemecloture['qteVendu'] = $qteVendu;
                $itemecloture['qteRestituer'] = $qteRestituer;
                $itemecloture['qteResteLivrer'] = $newProgrammer;
                $itemecloture['datecloture'] = date('d/m/y H:i');
                $cloture[] = $itemecloture;
                $historiques['cloture'][date('d/m/y H:i')] = $cloture;

                $programmation->update([
                    'qtelivrer' => $qteVendu,
                    'bl' => null,
                    'historiques' => $historiques,
                    'cloture' => true
                ]);

                return  back();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store(Request $request, Programmation $programmation)
    {
        $bordereauLivraison = $programmation->bl;
        $historiques = $programmation->historiques;
        if (count($historiques) > 0) {
            if (array_key_exists('dateSortie', $historiques)) {
                $sortie = $historiques['dateSortie'];
            }
        }

        ###___
        $itemesortie['user'] = Auth::user()->name;
        $itemesortie['date'] = $programmation->dateSortie;
        $itemesortie['update_at'] = date('d/m/y H:i');
        $sortie[] = $itemesortie;
        $historiques['dateSortie'] = $sortie;

        if ($programmation->bl) {
            if ($request->document == NULL) {
                $validator = Validator::make($request->all(), [
                    'bl' => ['required', 'string', 'max:255', new BonLivRule($programmation)],
                    'datelivrer' => ['required', 'after_or_equal:today'],
                    'qtelivrer' => ['required', new QteLivraisonRule($programmation)],
                ]);

                if ($validator->fails()) {
                    return redirect()->route('livraisons.create', ['programmation' => $programmation->id])->withErrors($validator->errors())->withInput();
                }

                if ($bordereauLivraison != $request->bl) {
                    Session()->flash('error', 'Vous devez revoir le bordereau de livraison.');
                    return redirect()->route('livraisons.index', ['programmation' => $programmation->id]);
                }

                $programmations = $programmation->update([
                    'bl' => $request->bl,
                    'datelivrer' => $request->datelivrer,
                    // 'qtelivrer' => $programmation->qtelivrer + $request->qtelivrer,
                    'qtelivrer' => $programmation->qteprogrammer,
                    'observation' => $request->observation,
                    'statut' => 'Livrer',
                    'document' => $request->remoovdoc ? null : $programmation->document,
                    'dateSortie' => $programmation->dateSortie ?: date('Y-m-d'),
                    'historiques' => $programmation->dateSortie ? $programmation->historiques : json_encode($historiques)
                ]);

                if ($programmations) {
                    $detailboncommande = DetailBonCommande::findOrFail($programmation->detailboncommande->id);
                    // dd([$detailboncommande->qteCommander, collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer')]);
                    if (floatval($detailboncommande->qteCommander) == floatval(collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer'))) {
                        $boncommande = $programmation->detailboncommande->boncommande;
                        $statut = 'Livrer';
                        BonCommandeTools::statutUpdate($boncommande, $statut);
                    }

                    Session()->flash('message', 'Livraison commande modifiée avec succès.');
                    return redirect()->route('livraisons.index', ['programmation' => $programmation->id]);
                }
            } else {
                $validator = Validator::make($request->all(), [
                    'bl' => ['required', 'string', 'max:255', new BonLivRule($programmation)],
                    'datelivrer' => ['required', 'after_or_equal:today'],
                    'qtelivrer' => ['required', new QteLivraisonRule($programmation)],
                    'document' => ['required', 'file', 'mimes:pdf,docx,doc,jpg,jpeg'],
                ]);
                if ($bordereauLivraison != $request->bl) {

                    Session()->flash('error', 'Vous devez revoir le bordereau de livraison.');
                    return redirect()->route('livraisons.index', ['programmation' => $programmation->id]);
                }
                if ($validator->fails()) {
                    return redirect()->route('livraisons.create', ['programmation' => $programmation->id])->withErrors($validator->errors())->withInput();
                }

                /* Uploader les documents dans la base de données */
                $filename = time() . '.' . $request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );

                $programmations = $programmation->update([
                    'bl' => $request->bl,
                    'datelivrer' => $request->datelivrer,
                    // 'qtelivrer' => $request->qtelivrer,
                    'qtelivrer' => $programmation->qteprogrammer,
                    'document' => $file,
                    'observation' => $request->observation,
                    'statut' => 'Livrer',
                    'dateSortie' => $programmation->dateSortie ?: date('Y-m-d'),
                    'historiques' => $programmation->dateSortie ? $programmation->historiques : json_encode($historiques)
                ]);

                if ($programmations) {
                    $detailboncommande = DetailBonCommande::findOrFail($programmation->detailboncommande->id);
                    // dd([$detailboncommande->qteCommander, collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer')]);
                    if (floatval($detailboncommande->qteCommander) == floatval(collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer'))) {
                        $boncommande = $programmation->detailboncommande->boncommande;
                        $statut = 'Livrer';
                        BonCommandeTools::statutUpdate($boncommande, $statut);
                    }
                    Session()->flash('message', 'Livraison commande modifiée avec succès.');
                    return redirect()->route('livraisons.index', ['programmation' => $programmation->id]);
                }
            }
        } else {
            $validator = Validator::make($request->all(), [
                'bl' => ['required', 'string', 'max:255', 'unique:programmations', new BonLivRule($programmation)],
                'datelivrer' => ['required', 'after_or_equal:today'],
                'qtelivrer' => ['required', new QteLivraisonRule($programmation)],
                'document' => ['nullable', 'file', 'mimes:pdf,docx,doc,jpg,jpeg'],
            ]);

            if ($validator->fails()) {
                return redirect()->route('livraisons.create', ['programmation' => $programmation->id])->withErrors($validator->errors())->withInput();
            }

            if ($request->document) {
                /* Uploader les documents dans la base de données */
                $filename = time() . '.' . $request->document->extension();

                $file = $request->file('document')->storeAs(
                    'documents',
                    $filename,
                    'public'
                );
            } else {
                $file = null;
            }

            // $statut = ($programmation->qteprogrammer != $request->qtelivrer)? '':;

            $programmations = $programmation->update([
                'bl' => $request->bl,
                'datelivrer' => $request->datelivrer,
                // 'qtelivrer' => $programmation->qtelivrer + $request->qtelivrer,
                'qtelivrer' => $programmation->qteprogrammer,
                'document' => $file,
                'observation' => $request->observation,
                'statut' => 'Livrer',
                'dateSortie' => $programmation->dateSortie ?: date('Y-m-d'),
                'historiques' => $programmation->dateSortie ? $programmation->historiques : json_encode($historiques)
            ]);

            if ($programmations) {
                $detailboncommande = DetailBonCommande::findOrFail($programmation->detailboncommande->id);
                // dd([$detailboncommande->qteCommander, collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer')]);

                if (floatval($detailboncommande->qteCommander) == floatval(collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer'))) {
                    $boncommande = $programmation->detailboncommande->boncommande;
                    $statut = 'Livrer';
                    BonCommandeTools::statutUpdate($boncommande, $statut);
                }

                ####____
                Session()->flash('message', 'Commande livrée avec succès.');
                return redirect()->route('livraisons.index', ['programmation' => $programmation->id]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Programmation  $programmation
     * @return \Illuminate\Http\Response
     **/

    public function show(Programmation $programmation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Programmation  $programmation
     * @return \Illuminate\Http\Response
     */
    public function edit(Programmation $programmation)
    {
        //
    }

    public function update(Request $request, Programmation $programmation)
    {
        $programmations = $programmation->update([
            'bl' => NULL,
            'datelivrer' => NULL,
            'qtelivrer' => NULL,
            'document' => NULL,
            'observation' => NULL,
            'statut' => 'Valider',
        ]);


        if ($programmations) {
            $detailboncommande = DetailBonCommande::findOrFail($programmation->detailboncommande->id);
            // dd([$detailboncommande->qteCommander, collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer')]);
            if (floatval($detailboncommande->qteCommander) != floatval(collect($detailboncommande->programmations->where('statut', 'Livrer'))->sum('qtelivrer'))) {
                $boncommande = $programmation->detailboncommande->boncommande;
                $statut = 'Programmer';
                BonCommandeTools::statutUpdate($boncommande, $statut);
            }
            Session()->flash('message', 'Livraison annuler avec succès.');
            return redirect()->route('livraisons.index', ['programmation' => $programmation->id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Programmation  $programmation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programmation $programmation)
    {
        //
    }

    public function transfertLivraison(Request $request)
    {
        $programmation = Programmation::find($request->prog);
        $user = Auth::user();
        $gestionnaire = Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists();
        if (!$gestionnaire) {
            $mail = new SuspectMail(['email' => env("ADMIN_SUSPECT")], "TENTATIVE DE TRANSFERT", "L'utilisateur " . Auth::user()->name . " a tenté un transfert alors qu'il n'est pas autoriser");
            Mail::send($mail);
            session()->flash("error", "Vous n'êtes pas autorisé à effectuer cette action. Votre comportement sera notifier à l'administrateur.");
            return back() /* redirect()->route("livraisons.index") */;
        }
        if (($programmation->vendus->sum('qteVendu') > 0 && $programmation->cloture == false) || ($programmation->vendus->sum('qteVendu') > 0 && $programmation->cloture == null)) {
            session()->flash("message", "Vous n'êtes pas autorisé à effectuer le transfert d'un camion qui est en déjà en cours de vente.");
            return back()/* redirect()->route("livraisons.index") */;
        }
        //Constitution de table de transfert
        $transfert = [];
        if ($programmation->transfert) {
            $transfert = json_decode($programmation->transfert);
        }
        $transfert[] = [
            'source' => $request->id,
            'destination' => $request->zone_id,
            'user' => Auth::user()->id,
            'date' => date('Y-m-d H:i'),
            'qteReste' => $programmation->qteprogrammer - $programmation->vendus->sum('qteVendu'),
            'observation' => $request->observation,
            'compteur' => count($transfert)
        ];
        $transfert = json_encode($transfert);
        $programme = $programmation;
        $programmation->update([
            'zone_id' => $request->zone_id,
            'transfert' => $transfert,
            'cloture' => false
        ]);
        //Notification zone destination
        $zoneDest = Zone::find($request->zone_id);
        $destinataire = [
            'nom' => $zoneDest->representant->nom . ' ' . $zoneDest->representant->prenom,
            'email' => $zoneDest->representant->email
        ];
        $subject = 'TRANSFERT PROGRAMMATION DU ' . date_format(date_create($programme->dateprogrammer), 'd/m/Y');
        $message_html = "Vous avez reçu le transfert d'une programmation.";
        $lienAction = route('programmations.index');
        $mail = new NotificateurProgrammationMail($destinataire, $subject, $message_html, $programmation->avaliseur->email ? [$programmation->avaliseur->email] : [], $programme, $lienAction);
        Mail::send($mail);

        //Notification zone source
        $zone = Zone::find($programme->zone_id);
        $destinataire = [
            'nom' => $zone->representant->nom . ' ' . $zone->representant->prenom,
            'email' => $zone->representant->email
        ];

        $subject = 'ANNULATION PAR TRANSFERT DE LA PROGRAMMATION DU ' . date_format(date_create($programme->dateprogrammer), 'd/m/Y');
        $message_html = "Nous vous informons que le programme ci-dessous a été transferé de votre zone. .";
        $lienAction = route('programmations.index');
        $mail = new NotificateurProgrammationMail($destinataire, $subject, $message_html, $programme->avaliseur->email ? [$programme->avaliseur->email] : [], $programme, $lienAction);
        Mail::send($mail);

        return redirect()->route('livraisons.getSuivicamion', [
            'debut' => session('debut'),
            'fin' => session('fin'),
            'option' => session('option'),
            'fournisseur' => session('fournisseur')
        ]);
    }

    public function getTransfertPage(Request $request, Programmation $programmation)
    {
        $zone = Zone::find($programmation->zone_id);
        $zones = Zone::all();
        return view("livraisons.transfertCamion", compact("programmation", "zones", "zone"));
    }

    public function transfertLivraison_redirect(Request $request)
    {
        ###____
        $prog_id = $request->get("prog") ? $request->get("prog") : $request->get("programmation");

        $programmation = Programmation::find($prog_id);

        $user = Auth::user();
        $gestionnaire = Auth::user()->roles()->where('libelle', 'GESTIONNAIRE')->exists();

        if (!$gestionnaire) {
            $mail = new SuspectMail(['email' => env("ADMIN_SUSPECT")], "TENTATIVE DE TRANSFERT", "L'utilisateur " . Auth::user()->name . " a tenté un transfert alors qu'il n'est pas autoriser");
            Mail::send($mail);
            session()->flash("error", "Vous n'êtes pas autorisé à effectuer cette action. Votre comportement sera notifier à l'administrateur.");
            return back() /* redirect()->route("livraisons.index") */;
        }

        // if (($programmation->vendus->sum('qteVendu') > 0 && $programmation->cloture == false) || ($programmation->vendus->sum('qteVendu') > 0 && $programmation->cloture == null)) {
        //     session()->flash("error", "Vous n'êtes pas autorisé à effectuer le transfert d'un camion qui est déjà en cours de vente.");
        //     return back();
        // }

        //Constitution de table de transfert
        $transfert = [];
        if ($programmation->transfert) {
            $transfert = json_decode($programmation->transfert);
        }

        // $transfert = (array) $programmation->transfert;

        $transfert[] = [
            'source' => $request->get("zone_souce"),
            'destination' => $request->get("zone_id"),
            'user' => Auth::user()->id,
            'date' => date('Y-m-d H:i'),
            'qteReste' => $programmation->qteprogrammer - $programmation->vendus->sum('qteVendu'),
            'observation' => $request->observation,
            'compteur' => count($transfert)
        ];

        // $transfert = json_encode($transfert);
        $programme = $programmation;
        $programmation->update([
            'zone_id' => $request->zone_id,
            'transfert' => $transfert,
            'cloture' => false
        ]);

        //Notification zone destination
        $zoneDest = Zone::find($request->zone_id);
        $destinataire = [
            'nom' => $zoneDest->representant->nom . ' ' . $zoneDest->representant->prenom,
            'email' => $zoneDest->representant->email
        ];

        $subject = 'TRANSFERT PROGRAMMATION DU ' . date_format(date_create($programme->dateprogrammer), 'd/m/Y');
        $message_html = "Vous avez reçu le transfert d'une programmation.";
        $lienAction = route('programmations.index');

        // dd($programmation->avaliseur);
        $mail = new NotificateurProgrammationMail($destinataire, $subject, $message_html, $programmation->avaliseur->email ? [$programmation->avaliseur->email] : [], $programme, $lienAction);
        Mail::send($mail);

        //Notification zone source
        $zone = Zone::find($programme->zone_id);
        $destinataire = [
            'nom' => $zone->representant->nom . ' ' . $zone->representant->prenom,
            'email' => $zone->representant->email
        ];

        $subject = 'ANNULATION PAR TRANSFERT DE LA PROGRAMMATION DU ' . date_format(date_create($programme->dateprogrammer), 'd/m/Y');
        $message_html = "Nous vous informons que le programme ci-dessous a été transferé de votre zone. .";
        $lienAction = route('programmations.index');
        $mail = new NotificateurProgrammationMail($destinataire, $subject, $message_html, $programme->avaliseur->email ? [$programme->avaliseur->email] : [], $programme, $lienAction);
        Mail::send($mail);

        // return redirect()->back()->with('message', 'Programme transferé avec succès!');

        return redirect()->back()->with("message", 'Programme transferé avec succès!');
    }

    public function livraisonPeriode(Request $request)
    {
        $boncommandesV = BonCommande::whereIn('statut', ['Livrer'])->pluck('id');
        $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
            ->where('statut', 'Livrer')->where('imprimer', '1')->where('transfert', NULL)
            ->orderByDesc('code')->get();
        return view('livraisons.livraisonPeriode', ['programmations' => $programmations]);
    }

    function suiviSortieForm()
    {
        $fournisseurs = Fournisseur::all();
        return view('livraisons.suivisortie', compact('fournisseurs'));
    }

    public function suiviSortie(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $repre = $user->representant;
        $zones = $repre->zones;
        $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer', 'Livrer', 'Annuler'])->pluck('id');
        $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
        $fournisseur = Fournisseur::find($request->fournisseur);
        session(['debut' => $request->debut]);
        session(['fin' => $request->fin]);
        session(['option' => $request->option ?: 'Tous']);
        session(['fournisseur' => $request->fournisseur ?: 'Tous']);

        $debut = date_format(date_create($request->debut), "Y-m-d");
        $fin = date_format(date_create($request->fin), "Y-m-d");

        if ($request->debut && $request->fin) {
            switch ($request->option) {
                case 'Tous':
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereIn('statut', ['Valider', 'Livrer'])
                        ->where('imprimer', '1')
                        ->whereBetween('dateprogrammer', [$debut, $fin])
                        ->orderByDesc('code')->get();
                    $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                    $messageReq = "Liste des programmations de la période du " . $debut . " au " . $fin . $fournisseur;
                    // dd($programmations);
                    break;
                case 'OUI':
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereIn('statut', ['Valider', 'Livrer'])
                        ->where('imprimer', '1')
                        ->whereBetween('dateprogrammer', [$debut, $fin])
                        ->whereNotNull('dateSortie')
                        ->orderByDesc('code')->get();
                    $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                    $messageReq = "Liste des programmations de la période du " . $debut . " au " . $fin . $fournisseur;

                    break;
                case 'NON':
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereIn('statut', ['Valider', 'Livrer'])
                        ->where('imprimer', '1')
                        ->whereBetween('dateprogrammer', [$debut, $fin])
                        ->whereNull('dateSortie')
                        ->orderByDesc('code')->get();
                    $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                    $messageReq = "Liste des programmations de la période du " . $debut . " au " . $fin . $fournisseur;

                    break;
            }
        } else {
            switch ($request->option) {
                case 'Tous':
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereIn('statut', ['Valider', 'Livrer'])
                        ->where('imprimer', '1')
                        ->orderByDesc('code')->get();
                    $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                    $messageReq = "Liste des programmations " . $fournisseur;
                    break;
                case 'OUI':
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereIn('statut', ['Valider', 'Livrer'])
                        ->where('imprimer', '1')
                        ->whereNotNull('dateSortie')
                        ->orderByDesc('code')->get();
                    $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                    $messageReq = "Liste des camions chargés " . $fournisseur;
                    break;
                case 'NON':
                    $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                        ->whereIn('statut', ['Valider', 'Livrer'])
                        ->where('imprimer', '1')
                        ->whereNull('dateSortie')
                        ->orderByDesc('code')->get();
                    $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                    $messageReq = "Liste des camions non chargés " . $fournisseur;
                    break;
            }
        }
        // $programmations = collect($programmations);
        // $programmations = $programmations->chunk(10);
        // $programmations = $programmations->all();

        // dd($programmations);


        return redirect()->route('livraisons.suivicamion')->with([
            'resultat' => $programmations,
            'request' => $request->all(),
            'messageReq' => $messageReq
        ])->withInput();
    }

    function suivichauffeurForm()
    {
        $fournisseurs = Fournisseur::all();
        $chauffeurs = Chauffeur::all();
        return view('livraisons.suiviChauffeur', compact('fournisseurs', 'chauffeurs'));
    }

    public function suiviChauffeur(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $repre = $user->representant;
        $zones = $repre->zones;
        $boncommandesV = BonCommande::whereIn('statut', ['Valider', 'Programmer', 'Livrer', 'Annuler'])->pluck('id');
        $detailboncommande = DetailBonCommande::whereIn('bon_commande_id', $boncommandesV)->pluck('id');
        $fournisseur = Fournisseur::find($request->fournisseur);
        $chauffeur = Chauffeur::find($request->chauffeur);

        if ($request->debut && $request->fin) {
            session(['debut' => $request->debut]);
            session(['fin' => $request->fin]);
            session(['option' => $request->option]);
            session(['fournisseur' => $request->fournisseur]);
            session(['chauffeur' => $request->chauffeur]);
            if ($chauffeur) {
                switch ($request->option) {
                    case 'Tous':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->where('chauffeur_id', $chauffeur->id)
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des programmations de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                    case 'OUI':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->where('chauffeur_id', $chauffeur->id)
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->whereNotNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions chargés de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                    case 'NON':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->where('chauffeur_id', $chauffeur->id)
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->whereNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions non chargés de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                }
            } else {
                switch ($request->option) {
                    case 'Tous':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des programmations de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                    case 'OUI':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->whereNotNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions chargés de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                    case 'NON':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->whereNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions non chargés de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                }
            }
        } else {
            if ($chauffeur) {
                switch ($request->option) {
                    case 'Tous':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->where('chauffeur_id', $chauffeur->id)
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des programmations de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                    case 'OUI':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->where('chauffeur_id', $chauffeur->id)
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->whereNotNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions chargés de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                    case 'NON':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->where('chauffeur_id', $chauffeur->id)
                            ->whereBetween('dateprogrammer', [$request->debut, $request->fin])
                            ->whereNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions non chargés de la période du " . date_format(date_create($request->debut), 'd/m/y') . " au " . date_format(date_create($request->fin), 'd/m/Y') . $fournisseur;
                        break;
                }
            } else {
                switch ($request->option) {
                    case 'Tous':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des programmations " . $fournisseur;
                        break;
                    case 'OUI':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->whereNotNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions chargés " . $fournisseur;
                        break;
                    case 'NON':
                        $programmations = Programmation::whereIn('detail_bon_commande_id', $detailboncommande)
                            ->whereIn('statut', ['Valider', 'Livrer'])
                            ->where('imprimer', '1')
                            ->whereNull('dateSortie')
                            ->orderByDesc('code')->get();
                        $fournisseur = $fournisseur ? " du fournisseur " . $fournisseur->raisonSociale : '';
                        $messageReq = "Liste des camions non chargés " . $fournisseur;
                        break;
                }
            }
        }
        return redirect()->route('livraisons.suivichauffeur')->with([
            'resultat' => $programmations,
            'request' => $request->all(),
            'messageReq' => $messageReq
        ])->withInput();
    }

    public function dateSortie(DetailBonCommande $detailboncommande, Programmation $programmation)
    {
        return view('programmations.Sortie', compact('programmation', 'detailboncommande'));
    }

    public function postDateSortie(Request $request, DetailBonCommande $detailboncommande, Programmation $programmation)
    {

        $validator = Validator::make($request->all(), [
            'dateSortie' => ['required'],
        ]);
        if ($validator->fails()) {
            Session()->flash('alert', 'Veuillez revoir la date de sortie.');
            return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id])->withErrors($validator->errors())->withInput();
        }
        if ($programmation->dateSortie) {
            Session()->flash('alert', 'Attention La Date de sortie existe déjà !');
            return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id])->withErrors($validator->errors())->withInput();
        }

        if ($programmation->dateSortie != NULL) {
            Session()->flash('alert', 'Attention La Date de sortie existe déjà !');
            return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id])->withErrors($validator->errors())->withInput();
        }
        $zone = $programmation->zone;
        if (($programmation->statut == 'Valider') || ($programmation->statut == 'Livrer')) {
            $programmation->dateSortie = $request->dateSortie;
            if ($programmation->update()) {

                $programme = DB::select("
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
                ", [$programmation->id,]);
                $destinataire = [
                    'nom' => $zone->representant->nom . ' ' . $zone->representant->prenom,
                    'email' => $zone->representant->email
                ];
                $subject = 'SORTIE DU CAMION DE PROGRAMMATION N° ' . $programmation->code . ' DU ' . date_format(date_create($programmation->dateprogrammer), 'd/m/Y');
                $message_html = "<p style='color: green'>Nous vous informons que la programmation ci-dessous  pour votre zone vient de Sortie de chez le Fournisseur. Merci de Contacter Le chauffeur.</p>";
                $lienAction = route('programmations.index');
                $mail = new NotificateurProgrammationMail($destinataire, $subject, $message_html, $programmation->avaliseur->email ? [$programmation->avaliseur->email] : [], $programme[0], $lienAction);
                Mail::send($mail);
                Session()->flash('message', 'Votre date de sortie a été enregistrée avec succès !');
                return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id]);
            }
            return redirect()->route('programmations.create', ['detailboncommande' => $detailboncommande->id])->withErrors($validator->errors())->withInput();
        }
    }
}
