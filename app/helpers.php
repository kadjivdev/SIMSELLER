<?php

use App\Models\Client;
use App\Models\Vente;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;

function IsThisVenteUpdateDemandeOnceMade($vente)
{
    $demand = $vente->_updateDemandes->last();
    if ($demand) {
        if ($demand->demandeur == auth()->user()->id) {
            return true;
        }

        ####__else
        return false;
    }
}

function IsThisVenteUpdateDemandeAlreadyValidated($vente)
{
    $demand = $vente->_updateDemandes->last();
    if ($demand) {
        if ($demand->demandeur == auth()->user()->id) {
            ###___SI LA DEMANDE EST VALIDEE
            if ($demand->valide) {
                return true;
            }

            ####__else
            return false;
        }
    }
    ####__else
    return false;
}

function IsThisVenteUpdateDemandeAlreadyModified($vente)
{
    $demand = $vente->_updateDemandes->last();
    if ($demand) {
        if ($demand->demandeur == auth()->user()->id) {
            ###___SI LA DEMANDE EST MODIFIEE
            if ($demand->modified) {
                return true;
            }

            ####__else
            return false;
        }
    }
    ####__else
    return false;
}

function GetVenteUpdatedDate($vente)
{
    $last_update = $vente->_updateDemandes->last();

    if ($last_update) {
       
        $date = date("d/m/Y H:m:s", strtotime($last_update->created_at));
        return $date;
    }

    return null;
}


###############======== DELETE VENTE ===========#########
function IsThisVenteDeleteDemandeOnceMade($vente)
{
    $demand = $vente->_deleteDemandes->last();
    if ($demand) {
        if ($demand->demandeur == auth()->user()->id) {
            return true;
        }
    }
    ####__else
    return false;
}

function IsThisVenteDeleteDemandeAlreadyValidated($vente)
{
    $demand = $vente->_deleteDemandes->last();
    if ($demand) {
        if ($demand->demandeur == auth()->user()->id) {
            ###___SI LA DEMANDE EST VALIDEE
            if ($demand->valide) {
                return true;
            }

            ####__else
            return false;
        }
    }

    ####__else
    return false;
}

function IsThisVenteDeleteDemandeAlreadyModified($vente)
{
    $demand = $vente->_deleteDemandes->last();
    if ($demand) {
        if ($demand->demandeur == auth()->user()->id) {
            ###___SI LA DEMANDE EST SUPPRIMEE
            if ($demand->deleted) {
                return true;
            }

            ####__else
            return false;
        }
    }
    ####__else
    return false;
}

function GetVenteTraitedDateViaCode($venteCode)
{
    $vente = Vente::where("code", $venteCode)->first();

    if ($vente) {
        if (!$vente->traited_date) {
            return null;
        }
        $date = $vente->traited_date ? date("d/m/Y H:m:s", strtotime($vente->traited_date)) : null;
        return $date;
    }

    return null;
}

function GetVenteDeleteDate($vente)
{
    $last_delete = $vente->_deleteDemandes->last();

    if ($last_delete) {
       
        $date = date("d/m/Y H:m:s", strtotime($last_delete->created_at));
        return $date;
    }

    return null;
}

###___Verifions si le client a une dette à regler
function IsClientHasADebt($clientId)
{
    $client = Client::find($clientId);
    // dd($client);
    if (!$client->debit_old || $client->debit_old == 0) {
        return false;
    }

    return true;
}

###___RECUPERATION DU RESTE A SOLDER D'UN CLIENT DE L'ANCIEN SYSTEME
function ClientDebtReste($clientOld)
{
    $client = Client::where(["raisonSociale" => $clientOld->nomUP])->first();

    if (!$client) {
        return 0;
    }

    return $client->debit_old ? $client->debit_old : 0;
}

###___RECUPERATION DU RESTE A SOLDER D'UN CLIENT DU NOUVEAU SYSTEME
function _ClientDebtReste($client)
{
    if (!$client) {
        return 0;
    }

    return $client->debit_old ? $client->debit_old : 0;
}


###___SOMME DE DEUX NOMBRE
function Somm($a, $b)
{
    // dd($a,$b);
    return number_format($a + $b,'0', '', ' ');
}
