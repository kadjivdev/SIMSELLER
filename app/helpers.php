<?php

use App\Models\Vente;

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

    if (!$vente->traited_date) {
        return null;
    }
    $date = $vente->traited_date ? date("d/m/Y H:m:s", strtotime($vente->traited_date)) : null;
    return $date;
}
