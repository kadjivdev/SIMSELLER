<?php

use App\Http\Controllers\Api\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\BonCommandeController;
use App\Http\Controllers\Api\CommanderController;
use \App\Http\Controllers\Api\DetailBonCommandeController;
use \App\Http\Controllers\Api\FournisseurController;
use \App\Http\Controllers\Api\ProgrammeController;
use App\Http\Controllers\Api\ReglementController;
use App\Http\Controllers\Api\TypeCommandeController;
use App\Http\Controllers\VenteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Detail Bon de commande router

Route::prefix('detailboncommandes')->group(function () {

    Route::controller(DetailBonCommandeController::class)->group(function () {

        Route::post('/store', 'store');


        Route::post('/update', 'update');


        Route::get('/details/{cde_id}', 'detailCommande')->where(['cde_id' => '[0-9]+']);


        Route::get('/listedetails/{boncommande}', 'listedetail')->where(['cde_id' => '[0-9]+']);


        Route::get('/destroy/{detailcommande}', 'destroy');


        Route::get('/empty/{bon_commande_id}', 'empty');
    });
});

// Bon de commande router

Route::prefix('boncommandes')->group(function () {

    Route::controller(BonCommandeController::class)->group(function () {

        Route::post('/store', 'store');

        Route::post('/update', 'update');

        Route::get('/update-date-traitement/{boncommandes}/{date}', 'dateTraitement');
    });
});


// Fournisseur router

Route::prefix('produits')->group(function () {

    Route::controller(FournisseurController::class)->group(function () {

        Route::get('/liste/{fournisseur}', 'liste');
    });
});


Route::prefix('programmation')->group(function () {
    Route::controller(ProgrammeController::class)->group(function () {
        Route::get('chauffeur/{camion}', 'getChauffeurByCamionId');
        Route::get('livraison/{programation}', 'getProgrammationById');
        Route::get('livraison/bl/{programmation}/{bl}/{user}', 'bordLiv');
        Route::get('detail-transfert/{programmation}', 'getdetailTransfert');
        Route::post('dateSortie/{programmation}', 'dateSortie')->name('programmations.dateSortie');
    });
    Route::controller(ProgrammeController::class)->group(function () {
        Route::get('produits/{produit}/{user}', 'getProgrammationByProduitId');
        Route::get('stock/{programmation}', 'getStockDisponible');
    });
});

Route::prefix('reglement')->group(function () {
    Route::controller(ReglementController::class)->group(function () {
        Route::get('compte-solde/{vente}/{user}', 'getSoldeCompte');
    });
});
//Commande liste client

Route::prefix('commandeclients')->group(function () {
    Route::controller(TypeCommandeController::class)->group(function () {
        Route::get('typecommande/{client}/{commandeClient?}', 'getTypeCommandeByClientId');
    });
});


// Detail Bon de commande router

Route::prefix('commanders')->group(function () {

    Route::controller(CommanderController::class)->group(function () {


        Route::post('/store', 'store');


        Route::post('/update', 'update');


        Route::get('/details/{cde_id}', 'commander')->where(['cde_id' => '[0-9]+']);


        Route::get('/listedetails/{boncommande}', 'listedetail')->where(['cde_id' => '[0-9]+']);


        Route::get('/destroy/{commander}', 'destroy');


        Route::get('/empty/{bon_commande_id}', 'empty');
    });
});

Route::prefix('ventes')->group(function () {
    Route::controller(VenteController::class)->group(function () {
        Route::get('/cltpayeur/{client}', 'cltpayeur')->name('ventes.cltpayeur');
        Route::get('/envoieComptabilite/{ventes}', 'cltpayeur')->name('ventes.envoieComptabilite');
        Route::get('/show/{vente}', 'showVente')->name('ventes.showVente');
        Route::get('/detailVente/{vente}', 'detailVente')->name('ventes.detailVente');
    });
});
Route::post('liste-produit/{fournisseur}', [ProgrammeController::class, 'getProduitFournisseur']);
Route::post('update-date-sortie/{programmation}', [ProgrammeController::class, 'insertDate']);
Route::post('comptabiliser/{vente}', [ProgrammeController::class, 'comptabilise']);
Route::get('client/liste', [ClientController::class, 'index']);

Route::prefix('client')->group(function () {
    Route::controller(ClientController::class)->group(function () {
        Route::get('/{id}/retrieve', 'Retrieve');
    });
});
