<?php

use App\Http\Controllers\ControleVenteContreller;
use App\Http\Controllers\CompteClientController;
use App\Http\Controllers\EcheanceCreditController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\ReglementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\AvoirController;
use App\Http\Controllers\BanqueController;
use App\Http\Controllers\CamionController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\AvaliseurController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TelephoneController;
use App\Http\Controllers\DetailRecuController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\FournisseurController;
use \App\Http\Controllers\TypeProduitController;
use App\Http\Controllers\RepresentantController;
use App\Http\Controllers\TypeDocumentController;
use \App\Http\Controllers\TypeCommandeController;
use App\Http\Controllers\InterlocuteurController;
use App\Http\Controllers\ProgrammationController;
use App\Http\Controllers\TypeAvaliseurController;
use App\Http\Controllers\AccuseDocumentController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Api\ProgrammeController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\CommercialiserController;
use \App\Http\Controllers\TypeDetailRecuController;
use App\Http\Controllers\VisiteTechniqueController;
use \App\Http\Controllers\DetailBonCommandeController;
use App\Http\Controllers\CompagnieAssuranceController;
use App\Http\Controllers\CategorieFournisseurController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\TypeClientController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\clientsController;
use App\Http\Controllers\CommandeClientController;
use App\Http\Controllers\CommanderController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\VenduController;
use App\Models\User;
use Database\Factories\AgentFactory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'pwd'])->group(function () {
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');


    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Detail Bon de commande router
    // Assurance router

    Route::prefix('fichiers')->group(function () {

        Route::controller(AssuranceController::class)->group(function () {

            Route::get('/camion/assurances/index/{id}', 'index')->name('assurances.index');

            Route::get('/camion/assurances/show/{id}', 'show')->name('assurances.show');

            Route::get('/camion/assurances/create/{id}', 'create')->name('assurances.create');

            Route::post('/camion/assurances/store', 'store')->name('assurances.store');

            Route::post('/camion/assurances/edit/logo', 'logo')->name('assurances.logo');

            Route::get('/camion/assurances/edit/{camion_id}/{assurance_id}', 'edit')->name('assurances.edit');

            Route::post('/camion/assurances/update', 'update')->name('assurances.update');

            Route::get('/camion/assurances/delete/{camion_id}/{assurance_id}', 'delete')->name('assurances.delete');

            Route::get('/camion/assurances/destroy/{id}', 'destroy')->name('assurances.destroy');
        });
    });

    // Visite Technique router

    Route::prefix('fichiers')->group(function () {

        Route::controller(VisiteTechniqueController::class)->group(function () {

            Route::get('/camion/visitetechniques/index/{id}', 'index')->name('visitetechniques.index');

            Route::get('/camion/visitetechniques/show/{id}', 'show')->name('visitetechniques.show');

            Route::get('/camion/visitetechniques/create/{id}', 'create')->name('visitetechniques.create');

            Route::post('/camion/visitetechniques/store', 'store')->name('visitetechniques.store');

            Route::post('/camion/visitetechniques/edit/logo', 'logo')->name('visitetechniques.logo');

            Route::get('/camion/visitetechniques/edit/{camion_id}/{visitetechnique_id}', 'edit')->name('visitetechniques.edit');

            Route::post('/camion/visitetechniques/update', 'update')->name('visitetechniques.update');

            Route::get('/camion/visitetechniques/delete/{camion_id}/{visitetechnique_id}', 'delete')->name('visitetechniques.delete');

            Route::get('/destroy/{id}', 'destroy')->name('visitetechniques.destroy');
        });
    });

    // Vente router
    Route::prefix('ventes')->group(function () {
        Route::controller(VenteController::class)->group(function () {

            Route::get('/index', 'index')->name('ventes.index');

            Route::match(['GET', 'POST'], '/indexCreate', 'indexCreate')->name('ventes.indexCreate');

            Route::get('/indexControlle', 'indexControlle')->name('ventes.indexControlle');

            Route::get('/create', 'create')->name('ventes.create');

            Route::post('/store/{vente?}', 'store')->where(['ventes' => '[0-9]+'])->name('ventes.store');

            Route::post('/update/{vente}', 'update')->name('ventes.update');

            Route::get('/show/{vente}', 'show')->name('ventes.show');

            Route::get('/askUpdate', 'askUpdate')->name('ventes.askUpdate');

            Route::get('/show/print/{vente}', 'show_print')->name('ventes.show-print');

            Route::post('/demandeVente', 'demandeVente')->name('ventes.demandeVente');

            Route::post('/valider/{vente}', 'validationVente')->name('ventes.valider');

            Route::post('/initialise/{vente}', 'initVente')->name('ventes.init');

            Route::get('/invalider/{vente}', 'invalider')->name('ventes.invalider');

            Route::post('/post-invalider/{vente}', 'posteInvalider')->name('ventes.postinvalider');

            Route::get('/edit/{vente}/{vendu?}', 'edit')->where(['ventes' => '[0-9]+'])->name('ventes.edit');

            Route::get('/delete/{vente}', 'delete')->name('ventes.delete');

            Route::get('/destroy/{vente}', 'destroy')->name('ventes.destroy');

            ###_____UPDATE DE VENTE
            Route::match(["GET", "POST"], '/askUpdateVente/{vente?}', 'askUpdateVente')->name('ventes.askUpdateVente');
            Route::post('/updateVente/{vente?}', '_updateVente')->name('ventes.updateVente');
            Route::match(['GET', "POST"], '/ventes/validation/', 'Validation')->name('ventes.validation');
            Route::delete('/ventes/deleteVenteUpdate/{demand}', 'DeleteDemandVenteUpdate')->name('ventes.DeleteDemandVenteUpdate');

            ###_____DELETE DE VENTE
            Route::match(["GET", "POST"], '/askDeleteVente/{vente?}', 'askDeleteVente')->name('ventes.askDeleteVente');
            Route::post('/deleteVente/{vente?}', '_deleteVente')->name('ventes.deleteVente');
            Route::match(['GET', "POST"], '/ventes/deleteValidation/', 'venteDeleteValidation')->name('ventes.deleteValidation');
            Route::delete('/ventes/deleteVenteDelete/{demand}', 'DeleteDemandVenteDelete')->name('ventes.DeleteDemandVenteDelete');
        });
    });

    Route::prefix('comptabilite')->group(function () {
        Route::controller(VenteController::class)->group(function () {
            Route::get('/vente-a-comptabilise/{vente}', 'aComptabiliser')->name('ventes.aComptabiliser');
            Route::get('/vente-a-envoyer-comptabilise', 'venteAEnvoyerComptabiliser')->name('ventes.venteAEnvoyerComptabiliser');
            Route::post('/vente-envoyer', 'envoieComptabilite')->name('ventes.envoieComptabilite');

            Route::get('/vente-a-comptabilise', 'getVenteAComptabiliser')->name('ventes.venteAComptabiliser');
            Route::get('/vente-a-comptabilise-deleted', 'getVenteAComptabiliserDeleted')->name('ventes.venteAComptabiliserDeleted');
            Route::get('/vente-a-comptabilise-updated', 'getVenteAComptabiliserUpdated')->name('ventes.venteAComptabiliserUpdated');

            Route::post('/vente-a-comptabilise', 'postVenteAComptabiliser')->name('ventes.postVenteAComptabiliser');
            Route::post('/vente-a-deleted', 'postVenteAComptabiliserDeleted')->name('ventes.postVenteAComptabiliserDeleted');
            Route::post('/vente-a-updated', 'postVenteAComptabiliserUpdated')->name('ventes.postVenteAComptabiliserUpdated');

            Route::get('/vente-a-comptabilises', 'postVenteAComptabiliser')->name('ventes.getPostVenteAComptabiliser');
            Route::get('/vente-a-traiter/{vente}', 'ventATraiter')->name('ventes.ventATraiter');
            Route::post('/reglement-en-attente/{vente}', 'reglement-en-attente')->name('ventes.reglementEnAttente');
            Route::post('/traiter-vente/{vente}', 'traiterVente')->name('ventes.traiter');
            Route::post('/filleul', 'filleul')->name('ventes.filleulfictive');
            Route::get('/des-vente-traiter', 'listeDesTraitementPeriode')->name('ventes.listeDesTraitementPeriode');
            Route::get('/export/{debut}/{fin}/{filtre}', 'export')->name('ventes.export');
            Route::get('/ReExport/{debut}/{fin}/{filtre}', 'ReExport')->name('ventes.ReExport');
            Route::get('/viewVenteTraiter', 'viewVenteTraiter')->name('ventes.viewVenteTraiter');
            Route::get('/viewVenteComptabiliser', 'viewVenteComptabiliser')->name('ventes.viewVenteComptabiliser');
            Route::post('/postexport', 'postexport')->name('ventes.postexport');
            Route::post('/postDejaExport', 'postDejaExport')->name('ventes.postDejaExport');
            Route::post('/listes-traitement-vente', 'postListeDesTraitementPeriode')->name('ventes.postListeDesTraitementPeriode');
        });
    });
    // Vendu

    Route::prefix('ventes')->group(function () {

        Route::controller(VenduController::class)->group(function () {

            Route::get('/vendus/index', 'index')->name('vendus.index');

            Route::get('/vendus/create/{vente}/{vendu?}', 'create')->name('vendus.create');

            Route::post('/vendus/store/{vente}/{vendu?}', 'store')->where(['ventes' => '[0-9]+'])->name('vendus.store');

            Route::post('/vendus/destroy/{vendu}', 'destroy')->name('vendus.destroy');
        });
    });

    // Commande client router

    Route::prefix('commandeclients')->group(function () {

        Route::controller(CommandeClientController::class)->group(function () {

            Route::get('/index', 'index')->name('commandeclients.index');

            Route::get('/create/{commandeclient?}', 'create')->name('commandeclients.create');

            Route::post('/store/{commandeclient?}', 'store')->where(['commandeclient' => '[0-9]+'])->name('commandeclients.store');

            Route::post('/update/{commandeclient}', 'update')->name('commandeclients.update');

            Route::get('/show/{commandeclient}', 'show')->name('commandeclients.show');

            Route::get('/show/print/{commandeclient}', 'show_print')->name('commandeclients.show-print');

            Route::get('/annulation/{commandeclient}', 'annulation')->name('commandeclients.annulation');

            Route::get('/annuler/{commandeclient}', 'annuler')->name('commandeclients.annuler');

            Route::get('/invalider/{commandeclient}', 'invalider')->name('commandeclients.invalider');

            Route::get('/edit/{commandeclient}/{commander?}', 'edit')->where(['commandeclient' => '[0-9]+'])->name('commandeclients.edit');

            Route::get('/delete/{commandeclient}', 'delete')->name('commandeclients.delete');

            Route::get('/destroy/{commandeclient}', 'destroy')->name('commandeclients.destroy');

            Route::post('/valider/{client}', 'validerCommande')->name('commandeclients.valider');
        });
    });


    // Bon de commande router
    Route::prefix('boncommandes')->group(function () {

        Route::controller(BonCommandeController::class)->group(function () {

            Route::get('/index', 'index')->name('boncommandes.index');

            Route::get('/create/{boncommandes?}', 'create')->name('boncommandes.create');

            Route::post('/store/{boncommandes?}', 'store')->where(['boncommande' => '[0-9]+'])->name('boncommandes.store');

            Route::post('/update/{boncommande}', 'update')->name('boncommandes.update');

            Route::get('/show/{boncommande}', 'show')->name('boncommandes.show');
            Route::get('/show/print/{boncommande}', 'pdf')->name('boncommandes.show-print');
            Route::get('/valider/{boncommande}', 'valider')->name('boncommandes.valider');
            Route::get('/invalider/{boncommande}', 'invalider')->name('boncommandes.invalider');
            Route::get('/edit/{boncommande}/{detailboncommande?}', 'edit')->where(['boncommande' => '[0-9]+'])->name('boncommandes.edit');
            Route::get('/delete/{boncommande}', 'delete')->name('boncommandes.delete');
            Route::get('/destroy/{boncommande}', 'destroy')->name('boncommandes.destroy');
            Route::get('/etat', 'etat')->name('boncommandes.etat');
            Route::get('/envoyer-bon-de-commande/{boncommandes}', 'envoyerCommande')->name('boncommandes.envoyer');
            Route::get('/retouner-bon-de-commande/{boncommandes}', 'retournerCommande')->name('boncommandes.retourner');
            Route::post('/envoyer-bon-de-commande/{boncommandes}', 'postEnvoyerCommande')->name('boncommandes.postenvoyer');
            Route::post('/retouner-bon-de-commande/{boncommandes}', 'postRetournerCommande')->name('boncommandes.postretourner');
        });
    });


    // Programmation router

    Route::prefix('programmations')->group(function () {

        Route::controller(ProgrammationController::class)->group(function () {

            Route::get('/index', 'index')->name('programmations.index');

            Route::get('/show/{detailboncommande}/{programmation?}/{total}', 'show')->name('programmations.show');

            Route::get('/allvalidate/{detailboncommande}/{programmation}', 'allvalidate')->name('programmations.allvalidate');

            Route::get('/create/{detailboncommande}/{programmation?}', 'create')->name('programmations.create');

            Route::post('/store/{detailboncommande}/{programmation?}', 'store')->name('programmations.store');

            Route::get('/edit/{programmation}', 'edit')->name('programmations.edit');

            Route::post('/update/{detailboncommande}/{programmation?}', 'update')->name('programmations.update');

            Route::get('/delete/{programmation}', 'delete')->name('programmations.delete');

            Route::get('/destroy/{detailboncommande}/{programmation?}', 'destroy')->name('programmations.destroy');
            Route::get('/deition', 'editionProgramme')->name('programmations.edition');
            Route::post('/post-deition', 'postEditionProgramme')->name('programmations.postEdition');
            Route::get('/post-impriession/{debut}/{fin}/{fournisseur}', 'postImpression')->name('programmations.postImpression');
            Route::get('/progrmme-impriession/{debut}/{fin}/{fournisseur}', 'confirmationImpression')->name('programmations.impression');
        });
    });

    // PROGRAMMATION
    Route::prefix('programmation')->group(function () {
        Route::controller(ProgrammeController::class)->group(function () {
            Route::post('livraison/bl/{programmation}/{user}', 'bordLivViaPost');
            Route::get('livraison/{programation}', 'getProgrammationById_redirect');
        });
    });

    // Livraison router
    Route::prefix('livraisons')->group(function () {
        // Route::controller(ProgrammeController::class)->group(function () {
        //     Route::get('livraison/{programation}', 'getProgrammationById_redirect')->name("livraisons.getTransfertProgramme");
        // });

        Route::controller(LivraisonController::class)->group(function () {

            Route::get('/index', 'index')->name('livraisons.index');
            Route::get('/indexpartielle', 'indexpartielle')->name('livraisons.indexpartielle');
            Route::get('/suivi-camion', 'suiviSortieForm')->name('livraisons.suivicamion');
            Route::post('/suivi-camion', 'suiviSortie')->name('livraisons.postSuivicamion');
            Route::get('/suivi-camions', 'suiviSortie')->name('livraisons.getSuivicamion');

            Route::get('/suivi-chauffeur', 'suivichauffeurForm')->name('livraisons.suivichauffeur');
            Route::post('/suivi-chauffeur', 'suiviChauffeur')->name('livraisons.postSuivichauffeur');
            Route::get('/suivi-chauffeurs', 'suiviChauffeur')->name('livraisons.getSuivichauffeur');

            Route::get('/show/{programmation?}/{total}', 'show')->name('livraisons.show');

            Route::get('/annulation/{programmation}', 'annulation')->name('livraisons.annulation');

            Route::get('/create/{programmation}', 'create')->name('livraisons.create');

            Route::get('/cloturer/{programmation}', 'cloturer')->name('livraisons.cloturer');

            Route::post('/store/{programmation}', 'store')->name('livraisons.store');

            Route::get('/edit/{programmation}', 'edit')->name('livraisons.edit');

            Route::post('/update/{programmation}', 'update')->name('livraisons.update');
            // Route::post('/transfert', 'transfertLivraison')->name('livraisons.transfert');

            Route::get('/transfert/{programmation}', 'getTransfertPage')->name('livraisons.getTransfert');
            Route::post('/transfert', 'transfertLivraison_redirect')->name('livraisons.transfert');


            Route::get('/delete/{programmation}', 'delete')->name('livraisons.delete');

            Route::get('/destroy/{detailboncommande}/{programmation?}', 'destroy')->name('livraisons.destroy');

            Route::get('/datesortie/{detailboncommande}/{programmation}', 'dateSortie')->name('programmations.dateSortie');
            Route::post('/datesortie/{detailboncommande}/{programmation}', 'postDateSortie')->name('programmations.postDateSortie');
        });
    });


    //Produit commander par un client

    Route::prefix('commandeclients')->group(function () {

        Route::controller(CommanderController::class)->group(function () {

            //Route::get('/index', 'index')->name('boncommandes.index');

            Route::post('/commanders/store/{commandeclient}/{commander?}', 'store')->where(['commandeclient' => '[0-9]+'])->name('commanders.store');


            Route::get('/commanders/show/{id}', 'show')->name('commanders.show');

            Route::get('/commanders/create', 'create')->name('commanders.create');

            Route::get('/commanders/edit/{id}', 'edit')->name('commanders.edit');

            Route::post('/commanders/destroy/{id}', 'destroy')->name('commanders.destroy');

            //Route::post('/store/{detailboncommandes?}', 'store')->name('detailboncommandes.store');

        });
    });


    //Detail bon de commande
    Route::prefix('boncommandes')->group(function () {
        Route::controller(DetailBonCommandeController::class)->group(function () {
            //Route::get('/index', 'index')->name('boncommandes.index');

            Route::post('/detailboncommandes/store/{boncommande}/{detailboncommandes?}', 'store')->where(['boncommande' => '[0-9]+'])->name('detailboncommandes.store');

            Route::get('/detailboncommandes/show/{id}', 'show')->name('detailboncommandes.show');

            Route::get('/detailboncommandes/create', 'create')->name('detailboncommandes.create');

            Route::get('/detailboncommandes/edit/{id}', 'edit')->name('detailboncommandes.edit');

            Route::get('/detailboncommandes/delete/{id}', 'delete')->name('detailboncommandes.delete');

            //Route::post('/store/{detailboncommandes?}', 'store')->name('detailboncommandes.store');

        });
    });

    // Produit router
    Route::prefix('fichiers')->group(function () {

        Route::controller(ProduitController::class)->group(function () {

            Route::get('/produits/index', 'index')->name('produits.index');

            Route::get('/produits/show/{id}', 'show')->name('produits.show');

            Route::get('/produits/create', 'create')->name('produits.create');

            Route::post('/produits/store', 'store')->name('produits.store');

            Route::post('/produits/edit/photo', 'photo')->name('produits.photo');

            Route::get('/produits/edit/{id}', 'edit')->name('produits.edit');

            Route::post('/produits/update', 'update')->name('produits.update');

            Route::get('/produits/delete/{id}', 'delete')->name('produits.delete');

            Route::get('/produits/destroy/{id}', 'destroy')->name('produits.destroy');
        });
    });

    Route::prefix('edition')->group(function () {
        Route::controller(EditionController::class)->group(function () {
            Route::get('/point-stock', 'pointStock')->name('edition.stock');
            Route::post('/point-stock', 'postPointStock')->name('edition.postPointStock');

            Route::get('/point-stock-valider', 'pointStockValider')->name('edition.stockValider');
            Route::post('/point-stock-valider', 'postPointStockValider')->name('edition.postPointStockValider');

            Route::get('/point-Solde', 'pointSolde')->name('edition.solde');
            Route::post('/point-Solde', 'postPointSolde')->name('edition.postPointSolde');

            Route::get('/etat-compte', 'etatCompte')->name('edition.etatCompte');
            Route::post('/etat-compte', 'postetatCompte')->name('edition.postEtatCompte');

            Route::get('/etat-livraison-commande', 'etatLivCde')->name('edition.etatlivraisoncde');
            Route::post('/etat-livraison-commande', 'postEtatLivCde')->name('edition.postetatlivraisoncde');

            Route::get('/etat-vente-periode', 'etatEtatVentePeriode')->name('edition.etatventeperiode');
            Route::post('/etat-vente-periode', 'postEtatVentePeriode')->name('edition.postetatventepeirode');

            Route::get('/etat-camion-programmation-periode', 'etatCaProgPeriode')->name('edition.etatCaProgPeriode');
            Route::post('/etat-camion-programmation-periode', 'postEtatCaProgPeriode')->name('edition.postEtatCaProgPeriode');

            Route::get('/etat-reglement-periode', 'etatReglementPeriode')->name('edition.etatReglementperiode');
            Route::post('/etat-reglement-periode', 'postEtatReglementPeriode')->name('edition.postEtatReglementPeriode');

            Route::get('/etat-reglement-periode-Rep', 'etatReglementPeriodeRep')->name('edition.etatReglementperiodeRep');
            Route::post('/etat-reglement-periode-Rep', 'postEtatReglementPeriodeRep')->name('edition.postEtatReglementPeriodeRep');

            Route::get('/etat-livraison-periode', 'etatLivraisonPeriode')->name('edition.etatLivraisonPeriode');
            Route::post('/etat-livraison-periode', 'postEtatLivraisonPeriode')->name('edition.postEtatLivraisonPeriode');

            Route::get('/etat-gene-periode', 'EtatGenePeriode')->name('edition.EtatGenePeriode');
            Route::post('/etat-gene-periode', 'postEtatGenePeriode')->name('edition.postEtatGenePeriode');


            Route::get('/Vente-camion', 'VenteCamion')->name('edition.VenteCamion');
            Route::post('/vente-camion', 'postVenteCamion')->name('edition.postVenteCamion');

            Route::get('/recouvrement', 'creditARecouvrir')->name('edition.revouvrement');

            // GESTION DES APPROVISIONNEMENTS
            Route::match(["GET", "POST"], '/etat-compte-approvisionnement', 'CompteApprovisionnement')->name('edition.compteApprovisionnement');

            // RESTORER LES VENTES SUPPRIMEES AU SOLDE DU CLIENT
            Route::get('/{vente}/{client}/restoreVenteDeleted', 'RestoreVenteDeleted')->name('edition.RestoreVenteDeleted');
        });
    });

    // Fournisseur router
    Route::prefix('fichiers')->group(function () {

        Route::controller(FournisseurController::class)->group(function () {

            Route::get('/fournisseurs/index', 'index')->name('fournisseurs.index');

            Route::get('/fournisseurs/show/{id}', 'show')->name('fournisseurs.show');

            Route::get('/fournisseurs/create', 'create')->name('fournisseurs.create');

            Route::post('/fournisseurs/store', 'store')->name('fournisseurs.store');

            Route::post('/fournisseurs/edit/logo', 'logo')->name('fournisseurs.logo');

            Route::get('/fournisseurs/edit/{id}', 'edit')->name('fournisseurs.edit');

            Route::post('/fournisseurs/update', 'update')->name('fournisseurs.update');

            Route::get('/fournisseurs/delete/{id}', 'delete')->name('fournisseurs.delete');

            Route::get('/fournisseurs/destroy/{id}', 'destroy')->name('fournisseurs.destroy');
        });
    });


    // Camion router

    Route::prefix('fichiers')->group(function () {

        Route::controller(CamionController::class)->group(function () {

            Route::get('/camions/index', 'index')->name('camions.index');

            Route::get('/camions/show/{id}', 'show')->name('camions.show');

            Route::get('/camions/create', 'create')->name('camions.create');

            Route::post('/camions/store', 'store')->name('camions.store');

            Route::get('/camions/addPhoto{id}', 'addPhoto')->name('camions.addPhoto');

            Route::post('/camions/photo', 'photo')->name('camions.photo');

            Route::get('/camions/edit/{id}', 'edit')->name('camions.edit');

            Route::post('/camions/update', 'update')->name('camions.update');

            Route::get('/camions/delete/{id}', 'delete')->name('camions.delete');

            Route::get('/camions/delete/{id}', 'delete')->name('camions.delete');

            Route::get('/camions/destroy/{id}', 'destroy')->name('camions.destroy');
        });
    });


    // Chauffeur router

    Route::prefix('fichiers')->group(function () {

        Route::controller(ChauffeurController::class)->group(function () {

            Route::get('/chauffeurs/index', 'index')->name('chauffeurs.index');

            Route::get('/chauffeurs/show/{id}', 'show')->name('chauffeurs.show');

            Route::get('/chauffeurs/create', 'create')->name('chauffeurs.create');

            Route::post('/chauffeurs/store', 'store')->name('chauffeurs.store');

            Route::get('/chauffeurs/addPhoto{id}', 'addPhoto')->name('chauffeurs.addPhoto');

            Route::post('/chauffeurs/photo', 'photo')->name('chauffeurs.photo');

            Route::get('/chauffeurs/edit/{id}', 'edit')->name('chauffeurs.edit');

            Route::post('/chauffeurs/update', 'update')->name('chauffeurs.update');

            Route::get('/chauffeurs/delete/{id}', 'delete')->name('chauffeurs.delete');

            Route::get('/chauffeurs/destroy/{id}', 'destroy')->name('chauffeurs.destroy');
        });
    });


    // Avaliseur router

    Route::prefix('fichiers')->group(function () {

        Route::controller(AvaliseurController::class)->group(function () {

            Route::get('/avaliseurs/index', 'index')->name('avaliseurs.index');

            Route::get('/avaliseurs/show/{avaliseur}', 'show')->name('avaliseurs.show');

            Route::get('/avaliseurs/create', 'create')->name('avaliseurs.create');

            Route::post('/avaliseurs/store', 'store')->name('avaliseurs.store');

            Route::get('/avaliseurs/addPhoto{id}', 'addPhoto')->name('avaliseurs.addPhoto');

            Route::post('/avaliseurs/photo', 'photo')->name('avaliseurs.photo');

            Route::get('/avaliseurs/edit/{avaliseur}', 'edit')->name('avaliseurs.edit');

            Route::post('/avaliseurs/update/{avaliseur}', 'update')->name('avaliseurs.update');

            Route::get('/avaliseurs/delete/{avaliseur}', 'delete')->name('avaliseurs.delete');

            Route::get('/avaliseurs/destroy/{avaliseur}', 'destroy')->name('avaliseurs.destroy');
            Route::get('/avaliseurs/camion/{avaliseur}', 'camions')->name('avaliseurs.camions');
        });
    });


    // Accuse document router

    Route::prefix('boncommandes')->group(function () {

        Route::controller(AccuseDocumentController::class)->group(function () {

            Route::get('/accusedocuments/index', 'indexAll')->name('accusedocuments.indexAll');

            Route::get('/accusedocuments/index/{boncommandes}', 'index')->name('accusedocuments.index');

            Route::get('/accusedocuments/show/{boncommande}/{accusedocument}', 'show')->name('accusedocuments.show');

            Route::get('/accusedocuments/create/{boncommande}', 'create')->name('accusedocuments.create');

            Route::post('/accusedocuments/store/{boncommande}', 'store')->name('accusedocuments.store');

            Route::get('/accusedocuments/edit/{boncommande}/{accusedocument}', 'edit')->name('accusedocuments.edit');

            Route::post('/accusedocuments/update/{boncommande}/{accusedocument}', 'update')->name('accusedocuments.update');

            Route::get('/accusedocuments/delete/{boncommande}/{accusedocument}', 'delete')->name('accusedocuments.delete');

            Route::get('/accusedocuments/destroy/{boncommande}/{accusedocument}', 'destroy')->name('accusedocuments.destroy');
        });
    });


    // Reçu router

    Route::prefix('boncommandes')->group(function () {

        Route::controller(RecuController::class)->group(function () {

            Route::get('/recus/index/{boncommandes}', 'index')->name('recus.index');

            Route::get('/recus/show/{boncommande}/{recu}', 'show')->name('recus.show');

            Route::get('/recus/create/{boncommande}', 'create')->name('recus.create');

            Route::post('/recus/store/{boncommande}', 'store')->name('recus.store');

            Route::get('/recus/edit/{boncommande}/{recu}', 'edit')->name('recus.edit');

            Route::post('/recus/update/{boncommande}/{recu}', 'update')->name('recus.update');

            Route::get('/recus/delete/{boncommande}/{recu}', 'delete')->name('recus.delete');

            Route::get('/recus/destroy/{boncommande}/{recu}', 'destroy')->name('recus.destroy');
        });
    });



    // Détail Reçu router

    Route::prefix('boncommandes')->group(function () {

        Route::controller(DetailRecuController::class)->group(function () {

            Route::get('/recus/detailrecus/index/{recu}', 'index')->name('detailrecus.index');

            Route::get('/recus/detailrecus/show/{recu}/{detailrecu}', 'show')->name('detailrecus.show');

            Route::get('/recus/detailrecus/create/{recu}', 'create')->name('detailrecus.create');

            Route::post('/recus/detailrecus/store/{recu}', 'store')->name('detailrecus.store');

            Route::get('/recus/detailrecus/edit/{recu}/{detailrecu}', 'edit')->name('detailrecus.edit');

            Route::post('/recus/detailrecus/update/{recu}/{detailrecu}', 'update')->name('detailrecus.update');

            Route::get('/recus/detailrecus/delete/{recu}/{detailrecu}', 'delete')->name('detailrecus.delete');

            Route::get('/recus/detailrecus/destroy/{recu}/{detailrecu}', 'destroy')->name('detailrecus.destroy');
        });
    });



    // Interlocuteur router

    Route::prefix('interlocuteurs')->group(function () {

        Route::controller(InterlocuteurController::class)->group(function () {

            Route::get('/index', 'index')->name('interlocuteurs.index');

            Route::get('/show/{id}', 'show')->name('interlocuteurs.show');

            Route::get('/create', 'create')->name('interlocuteurs.create');

            Route::post('/store', 'store')->name('interlocuteurs.store');

            Route::post('/edit/logo', 'logo')->name('interlocuteurs.logo');

            Route::get('/edit/{id}', 'edit')->name('interlocuteurs.edit');

            Route::post('/update', 'update')->name('interlocuteurs.update');

            Route::get('/delete/{id}', 'delete')->name('interlocuteurs.delete');

            Route::get('/destroy/{id}', 'destroy')->name('interlocuteurs.destroy');
        });
    });


    // Téléphone router

    Route::prefix('telephones')->group(function () {

        Route::controller(TelephoneController::class)->group(function () {

            Route::get('/index', 'index')->name('telephones.index');

            Route::get('/show/{id}', 'show')->name('telephones.show');

            Route::get('/create', 'create')->name('telephones.create');

            Route::post('/store', 'store')->name('telephones.store');

            Route::post('/edit/logo', 'logo')->name('telephones.logo');

            Route::get('/edit/{id}', 'edit')->name('telephones.edit');

            Route::post('/update', 'update')->name('telephones.update');

            Route::get('/delete/{id}', 'delete')->name('telephones.delete');

            Route::get('/destroy/{id}', 'destroy')->name('telephones.destroy');
        });
    });



    // Banque router

    Route::prefix('fichiers')->group(function () {

        Route::controller(BanqueController::class)->group(function () {

            Route::get('/banques/index', 'index')->name('banques.index');

            Route::get('/banques/show', 'show')->name('banques.show');

            Route::get('/banques/create', 'create')->name('banques.create');

            Route::post('/banques/store', 'store')->name('banques.store');

            Route::get('/banques/edit/{id}', 'edit')->name('banques.edit');

            Route::post('/banques/update', 'update')->name('banques.update');

            Route::get('/banques/delete/{id}', 'delete')->name('banques.delete');

            Route::get('/banques/destroy/{id}', 'destroy')->name('banques.destroy');
        });
    });


    // Commercialiser router

    Route::prefix('commercialisers')->group(function () {

        Route::controller(CommercialiserController::class)->group(function () {

            Route::get('/index', 'index')->name('commercialisers.index');

            Route::get('/show', 'show')->name('commercialisers.show');

            Route::get('/create', 'create')->name('commercialisers.create');

            Route::post('/store', 'store')->name('commercialisers.store');

            Route::get('/edit/{id}', 'edit')->name('commercialisers.edit');

            Route::post('/update', 'update')->name('commercialisers.update');

            Route::get('/delete/{fournisseur_id}/{produit_id}', 'delete')->name('commercialisers.delete');

            Route::get('/destroy/{fournisseur_id}/{produit_id}', 'destroy')->name('commercialisers.destroy');
        });
    });


    // Categorie Fournisseurs router

    Route::prefix('configurations')->group(function () {

        Route::controller(CategorieFournisseurController::class)->group(function () {

            Route::get('/categoriefournisseurs/index', 'index')->name('categoriefournisseurs.index');

            Route::get('/categoriefournisseurs/show', 'show')->name('categoriefournisseurs.show');

            Route::get('/categoriefournisseurs/create', 'create')->name('categoriefournisseurs.create');

            Route::post('/categoriefournisseurs/store', 'store')->name('categoriefournisseurs.store');

            Route::get('/categoriefournisseurs/edit/{id}', 'edit')->name('categoriefournisseurs.edit');

            Route::post('/categoriefournisseurs/update', 'update')->name('categoriefournisseurs.update');

            Route::get('/categoriefournisseurs/delete/{id}', 'delete')->name('categoriefournisseurs.delete');

            Route::get('/categoriefournisseurs/destroy/{id}', 'destroy')->name('categoriefournisseurs.destroy');
        });
    });


    // Type commande router

    Route::prefix('configurations')->group(function () {

        Route::controller(TypeCommandeController::class)->group(function () {

            Route::get('/typecommandes/index', 'index')->name('typecommandes.index');

            Route::get('/typecommandes/show', 'show')->name('typecommandes.show');

            Route::get('/typecommandes/create', 'create')->name('typecommandes.create');

            Route::post('/typecommandes/store', 'store')->name('typecommandes.store');

            Route::get('/typecommandes/edit/{id}', 'edit')->name('typecommandes.edit');

            Route::post('/typecommandes/update', 'update')->name('typecommandes.update');

            Route::get('/typecommandes/delete/{id}', 'delete')->name('typecommandes.delete');

            Route::get('/typecommandes/destroy/{id}', 'destroy')->name('typecommandes.destroy');
        });
    });


    // Type client router

    Route::prefix('configurations')->group(function () {

        Route::controller(TypeClientController::class)->group(function () {

            Route::get('/typeclients/index', 'index')->name('typeclients.index');

            Route::get('/typeclients/show', 'show')->name('typeclients.show');

            Route::get('/typeclients/create', 'create')->name('typeclients.create');

            Route::post('/typeclients/store', 'store')->name('typeclients.store');

            Route::get('/typeclients/edit/{typeclient}', 'edit')->name('typeclients.edit');

            Route::post('/typeclients/update/{typeclient}', 'update')->name('typeclients.update');

            Route::get('/typeclients/delete/{typeclient}', 'delete')->name('typeclients.delete');

            Route::get('/typeclients/destroy/{typeclient}', 'destroy')->name('typeclients.destroy');
        });
    });



    // Type Detail Recu router
    Route::prefix('configurations')->group(function () {

        Route::controller(TypeDetailRecuController::class)->group(function () {

            Route::get('/typedetailrecus/index', 'index')->name('typedetailrecus.index');

            Route::get('/typedetailrecus/show', 'show')->name('typedetailrecus.show');

            Route::get('/typedetailrecus/create', 'create')->name('typedetailrecus.create');

            Route::post('/typedetailrecus/store', 'store')->name('typedetailrecus.store');

            Route::get('/typedetailrecus/edit/{id}', 'edit')->name('typedetailrecus.edit');

            Route::post('/typedetailrecus/update', 'update')->name('typedetailrecus.update');

            Route::get('/typedetailrecus/delete/{id}', 'delete')->name('typedetailrecus.delete');

            Route::get('/typedetailrecus/destroy/{id}', 'destroy')->name('typedetailrecus.destroy');
        });
    });


    // Type produit router

    Route::prefix('configurations')->group(function () {

        Route::controller(TypeProduitController::class)->group(function () {

            Route::get('/typeproduits/index', 'index')->name('typeproduits.index');

            Route::get('/typeproduits/show', 'show')->name('typeproduits.show');

            Route::get('/typeproduits/create', 'create')->name('typeproduits.create');

            Route::post('/typeproduits/store', 'store')->name('typeproduits.store');

            Route::get('/typeproduits/edit/{typeproduit}', 'edit')->name('typeproduits.edit');

            Route::post('/typeproduits/update/{typeproduit}', 'update')->name('typeproduits.update');

            Route::get('/typeproduits/delete/{typeproduit}', 'delete')->name('typeproduits.delete');

            Route::get('/typeproduits/destroy/{typeproduit}', 'destroy')->name('typeproduits.destroy');
        });
    });


    // Type document router

    Route::prefix('configurations')->group(function () {

        Route::controller(TypeDocumentController::class)->group(function () {

            Route::get('/typedocuments/index', 'index')->name('typedocuments.index');

            Route::get('/typedocuments/show', 'show')->name('typedocuments.show');

            Route::get('/typedocuments/create', 'create')->name('typedocuments.create');

            Route::post('/typedocuments/store', 'store')->name('typedocuments.store');

            Route::get('/typedocuments/edit/{typedocument}', 'edit')->name('typedocuments.edit');

            Route::post('/typedocuments/update/{typedocument}', 'update')->name('typedocuments.update');

            Route::get('/typedocuments/delete/{typedocument}', 'delete')->name('typedocuments.delete');

            Route::get('/typedocuments/destroy/{typedocument}', 'destroy')->name('typedocuments.destroy');
        });
    });


    // Type avaliseur router

    Route::prefix('configurations')->group(function () {

        Route::controller(TypeAvaliseurController::class)->group(function () {

            Route::get('/typeavaliseurs/index', 'index')->name('typeavaliseurs.index');

            Route::get('/typeavaliseurs/show', 'show')->name('typeavaliseurs.show');

            Route::get('/typeavaliseurs/create', 'create')->name('typeavaliseurs.create');

            Route::post('/typeavaliseurs/store', 'store')->name('typeavaliseurs.store');

            Route::get('/typeavaliseurs/edit/{typeavaliseur}', 'edit')->name('typeavaliseurs.edit');

            Route::post('/typeavaliseurs/update/{typeavaliseur}', 'update')->name('typeavaliseurs.update');

            Route::get('/typeavaliseurs/delete/{typeavaliseur}', 'delete')->name('typeavaliseurs.delete');

            Route::get('/typeavaliseurs/destroy/{typeavaliseur}', 'destroy')->name('typeavaliseurs.destroy');
        });
    });


    // Compagnie assurance router

    Route::prefix('configurations')->group(function () {

        Route::controller(CompagnieAssuranceController::class)->group(function () {

            Route::get('/compagnieassurances/index', 'index')->name('compagnieassurances.index');

            Route::get('/compagnieassurances/show', 'show')->name('compagnieassurances.show');

            Route::get('/compagnieassurances/create', 'create')->name('compagnieassurances.create');

            Route::post('/compagnieassurances/store', 'store')->name('compagnieassurances.store');

            Route::get('/compagnieassurances/edit/{compagnieassurance}', 'edit')->name('compagnieassurances.edit');

            Route::post('/compagnieassurances/update/{compagnieassurance}', 'update')->name('compagnieassurances.update');

            Route::get('/compagnieassurances/delete/{compagnieassurance}', 'delete')->name('compagnieassurances.delete');

            Route::get('/compagnieassurances/destroy/{compagnieassurance}', 'destroy')->name('compagnieassurances.destroy');
        });
    });


    // Marque
    Route::prefix('configurations')->group(function () {

        Route::controller(MarqueController::class)->group(function () {

            Route::get('/marques/index', 'index')->name('marques.index');

            Route::get('/marques/show', 'show')->name('marques.show');

            Route::get('/marques/create', 'create')->name('marques.create');

            Route::post('/marques/store', 'store')->name('marques.store');

            Route::get('/marques/edit/{marque}', 'edit')->name('marques.edit');

            Route::post('/marques/update/{marque}', 'update')->name('marques.update');

            Route::get('/marques/delete/{marque}', 'delete')->name('marques.delete');

            Route::get('/marques/destroy/{marque}', 'destroy')->name('marques.destroy');
        });
    });



    // Compte router

    Route::prefix('fichiers')->group(function () {

        Route::controller(CompteController::class)->group(function () {

            Route::get('/banque/comptes/index/{banque}', 'index')->name('comptes.index');

            Route::get('/banque/comptes/show', 'show')->name('comptes.show');

            Route::get('/banque/comptes/create/{banque}', 'create')->name('comptes.create');

            Route::post('/banque/comptes/store/{banque}', 'store')->name('comptes.store');

            Route::get('/banque/comptes/edit/{banque}/{compte}', 'edit')->name('comptes.edit');

            Route::post('/banque/comptes/update/{banque}/{compte}', 'update')->name('comptes.update');

            Route::get('/banque/comptes/delete/{banque}/{compte}', 'delete')->name('comptes.delete');

            Route::get('/banque/comptes/destroy/{banque}/{compte}', 'destroy')->name('comptes.destroy');
        });
    });


    // Representants router

    Route::prefix('fichiers')->group(function () {

        Route::controller(RepresentantController::class)->group(function () {

            Route::get('/representants/index', 'index')->name('representants.index');

            Route::get('/representants/show', 'show')->name('representants.show');

            Route::get('/representants/create', 'create')->name('representants.create');

            Route::post('/representants/store', 'store')->name('representants.store');

            Route::get('/representants/edit/{representant}', 'edit')->name('representants.edit');

            Route::get('/representants/zone/{representant}', 'zone')->name('representants.zone');

            Route::post('/representants/update/{representant}', 'update')->name('representants.update');

            Route::get('/representants/delete/{representant}', 'delete')->name('representants.delete');

            Route::get('/representants/destroy/{representant}', 'destroy')->name('representants.destroy');
        });
    });


    // Client router

    Route::prefix('fichiers')->group(function () {

        Route::controller(ClientController::class)->group(function () {

            Route::get('/clients/index', 'index')->name('clients.index');
            Route::get('/clients/oldcli', 'oldSoldeClient')->name('clients.oldSoldeClient');

            Route::get('/clients/show', 'show')->name('clients.show');

            Route::get('/clients/create', 'create')->name('clients.create');

            Route::post('/clients/store/{typeclient}', 'store')->name('clients.store');

            Route::get('/clients/edit/{client}', 'edit')->name('clients.edit');

            Route::get('/clients/zone/{client}', 'zone')->name('clients.zone');

            Route::post('/clients/update/{typeclient}/{client}', 'update')->name('clients.update');

            Route::get('/clients/delete/{client}', 'delete')->name('clients.delete');

            Route::get('/clients/destroy/{client}', 'destroy')->name('clients.destroy');

            Route::get('/clients/sommeil/{client}', 'sommeil')->name('clients.sommeil');

            Route::get('/clients/reveil/{client}', 'reveil')->name('clients.reveil');

            Route::get('/clients/achatClient/{client}', 'achatClient')->name('clients.achatClient');
        });
    });


    // Representants router

    Route::prefix('configurations')->group(function () {

        Route::controller(DepartementController::class)->group(function () {

            Route::get('/departements/index', 'index')->name('departements.index');

            Route::get('/departements/show', 'show')->name('departements.show');

            Route::get('/departements/create', 'create')->name('departements.create');

            Route::post('/departements/store', 'store')->name('departements.store');

            Route::get('/departements/edit/{departement}', 'edit')->name('departements.edit');

            Route::get('/departements/zone/{departement}', 'zone')->name('departements.zone');

            Route::post('/departements/update/{departement}', 'update')->name('departements.update');

            Route::get('/departements/delete/{departement}', 'delete')->name('departements.delete');

            Route::get('/departements/destroy/{departement}', 'destroy')->name('departements.destroy');
        });
    });


    // Zones router

    Route::prefix('fichiers')->group(function () {

        Route::controller(ZoneController::class)->group(function () {

            Route::get('/zones/index', 'index')->name('zones.index');

            Route::get('/zones/show', 'show')->name('zones.show');

            Route::get('/zones/create', 'create')->name('zones.create');

            Route::post('/zones/store', 'store')->name('zones.store');

            Route::get('/zones/edit/{zone}', 'edit')->name('zones.edit');

            Route::post('/zones/update/{zone}', 'update')->name('zones.update');

            Route::get('/zones/delete/{zone}', 'delete')->name('zones.delete');

            Route::get('/zones/destroy/{zone}', 'destroy')->name('zones.destroy');
        });
    });


    Route::prefix('admin')->group(function () {
        Route::controller(UserController::class)->group(function () {

            Route::get('/users/index', 'index')->name('users.index');

            Route::get('/users/show', 'show')->name('users.show');

            Route::get('/users/create', 'create')->name('users.create');

            Route::post('/users/store', 'store')->name('users.store');

            Route::get('/users/edit/{user}', 'edit')->name('users.edit');

            Route::post('/users/update/{user}', 'update')->name('users.update');

            Route::get('/users/delete/{user}', 'delete')->name('users.delete');

            Route::get('/users/destroy/{user}', 'destroy')->name('users.destroy');
        });
    });

    Route::prefix('admin')->group(function () {

        Route::controller(RoleController::class)->group(function () {

            Route::get('/roles/index', 'index')->name('roles.index');

            Route::get('/roles/show/{role}', 'show')->name('roles.show');

            Route::get('/roles/create', 'create')->name('roles.create');

            Route::post('/roles/store', 'store')->name('roles.store');

            Route::get('/roles/edit/{role}', 'edit')->name('roles.edit');

            Route::post('/roles/update/{role}', 'update')->name('roles.update');

            Route::get('/roles/delete/{role}', 'delete')->name('roles.delete');

            Route::get('/roles/destroy/{role}', 'destroy')->name('roles.destroy');
        });
    });


    Route::prefix('admin')->group(function () {

        Route::controller(AvoirController::class)->group(function () {

            Route::get('/avoirs/index/{user}', 'index')->name('avoirs.index');

            Route::get('/avoirs/show/{user}', 'show')->name('avoirs.show');

            Route::get('/avoirs/create/{user}', 'create')->name('avoirs.create');

            Route::post('/avoirs/store/{user}', 'store')->name('avoirs.store');

            Route::get('/avoirs/edit/{user}/{role}', 'edit')->name('avoirs.edit');

            Route::post('/avoirs/update/{user}/{role}', 'update')->name('avoirs.update');

            Route::get('/avoirs/delete/{user}/{role}', 'delete')->name('avoirs.delete');

            Route::get('/avoirs/destroy/{user}/{role}', 'destroy')->name('avoirs.destroy');
        });
    });

    Route::prefix('vente')->group(function () {
        Route::controller(ReglementController::class)->group(function () {

            Route::get('/reglement/{vente}', 'index')->name('reglements.index');

            Route::get('/reglement/{vente}/{reglement}', 'show')->name('reglements.show');

            Route::get('/regelement/create/{vente}', 'create')->name('reglements.create');
            Route::post('/regelement/validation/{vente}', 'validerReglement')->name('reglements.valider');

            Route::post('/reglement/store/{vente}', 'store')->name('reglements.store');

            Route::get('/reglement/edit/{vente}/{reglement}', 'edit')->name('reglements.edit');

            Route::post('/reglement/update/{vente}/{reglement}', 'update')->name('reglements.update');

            Route::get('/reglement/delete/{vente}/{reglement}', 'delete')->name('reglements.delete');

            Route::get('/reglement/destroy/{vente}/{reglement}', 'destroy')->name('reglements.destroy');
        });
        Route::controller(EcheanceCreditController::class)->group(function () {

            Route::get('/echeance/{vente}', 'index')->name('echeances.index');

            Route::get('/echeance/create/{vente}', 'create')->name('echeances.create');

            Route::post('/echeance/store/{vente}', 'store')->name('echeances.store');

            Route::get('/echeance/delete/{vente}/{echeance}', 'delete')->name('echeances.delete');

            Route::get('/echeance/destroy/{vente}/{echeance}', 'destroy')->name('echeances.destroy');

            Route::get('/echeance/edit/{vente}/{echeance}', 'edit')->name('echeances.edit');

            Route::post('/echeance/update/{vente}/{echeance}', 'update')->name('echeances.update');
        });
    });

    Route::prefix('compte-client')->group(function () {

        Route::controller(CompteClientController::class)->group(function () {

            Route::get('compte/{client}', 'show')->name('compteClient.show');

            Route::get('approvisionement-compte/{client}', 'createAppro')->name('compteClient.appro');

            Route::get('approvisionement-compte/delete/{mouvement}/{client}', 'delete')->name('compteClient.delete');

            Route::get('approvisionement-compte/destroy/{mouvement}/{client}', 'destroy')->name('compteClient.destroy');

            Route::post('approvisionement-compte/{client}', 'postAppro')->name('compteClient.postAppro');
        });
    });

    // Route::prefix('agent')->group( function () {

    //Route::resource('/agent', AgentController::class);
    Route::controller(AgentController::class)->group(function () {


        Route::get('/agent/index', 'index')->name('agent.index');

        Route::get('/agent/show', 'show')->name('agent.show');

        Route::get('/agent/create', 'create')->name('agent.create');

        Route::post('/agent/store', 'store')->name('agent.store');

        Route::get('/agent/edit/{agent}', 'edit')->name('agent.edit');

        Route::post('/agent/update/{agent}', 'update')->name('agent.update');

        Route::get('/agent/delete/{agent}', 'delete')->name('agent.delete');

        Route::get('/agent/destroy/{agent}', 'destroy')->name('agent.destroy');
        Route::get('/agent/client_Affecter/{agent}', 'client_Affecter')->name('agent.affecter');
    });
    
    //});
    Route::controller(clientsController::class)->group(function () {
        Route::get('newclient/index/', 'index')->name('newclient.index');
        Route::get('newclient/indexOld/', 'oldClients')->name('newclient.oldClients');
        Route::get('newclient/indexOldNotExistInTheNewSystem/', 'oldClientsNotInTheNewSystem')->name('newclient.oldClientsNotInTheNewSystem');

        Route::post('affect-to-zone/', 'AffectToZone')->name('newclient.AffectToZone');

        ####___REGLEMENT DES DETTES ANCIENNES
        Route::match(["GET","POST"],'newclient/reglement/{client?}', 'reglement')->name('newclient.reglement');
        Route::get('newclient/reglement/{client}/detail', 'reglementDetail')->name('newclient.reglementDetail');

        Route::get('newclient/data', 'data')->name('newclient.data');
        Route::get('newclient/create', 'create')->name('newclient.create');
        Route::get('newclient/show/{client}', 'show')->name('newclient.show');
        Route::get('newclient/edit/{client}', 'edit')->name('newclient.edit');
        Route::get('newclient/eligible/{client}', 'eligibleCredit')->name('newclient.eligible');
        Route::put('newclient/update/{client}', 'update')->name('newclient.update');
        Route::get('newclient/delete/{client}', 'delete')->name('newclient.delete');
        Route::get('newclient/destroy/{client}', 'destroy')->name('newclient.destroy');
        Route::post('newclient/store', 'store')->name('newclient.store');
        Route::post('newclient/affection', 'affectionAgent')->name('newclient.affectionAgent');
        Route::get('newclient/achatClient/{client}', 'achatClient')->name('newclient.achatClient');
    });

    Route::controller(ControleVenteContreller::class)->prefix('controle')->group(function () {
        Route::get('reglement-en-attente', 'index')->name('ctlventes.index');
        Route::get('reglement-en-attente/sur-compte', 'reglementSurCompte')->name('ctlventes.reglementSurCompte');
        Route::get('controler/{reglement}', 'controler')->name('ctlventes.create');
        Route::post('controler/{reglement}', 'validerControle')->name('ctlventes.validerControle');
        Route::post('controler/askUpdate/{reglement}', 'rejetReglement')->name('ctlventes.askUpdate');
    });
    Route::get('test-mail', [VenteController::class, 'testMail']);
});
require __DIR__ . '/auth.php';
