<?php

function IsThisVenteUpdateDemandeOnceMade($vente)
{
    foreach ($vente->_updateDemandes as $demand) {
        if ($demand->demandeur == auth()->user()->id) {
            return true;
        }

        ####__else
        return false;
    }
}

function IsThisVenteUpdateDemandeAlreadyValidated($vente)
{
    foreach ($vente->_updateDemandes as $demand) {
        if ($demand->demandeur == auth()->user()->id) {
            ###___SI LA DEMANDE EST VALIDEE
            if ($demand->valide) {
                return true;
            }

            ####__else
            return false;
        }
        ####__else
        return false;
    }
}

function IsThisVenteUpdateDemandeAlreadyModified($vente)
{
    foreach ($vente->_updateDemandes as $demand) {
        if ($demand->demandeur == auth()->user()->id) {
            ###___SI LA DEMANDE EST VALIDEE
            if ($demand->modified) {
                return true;
            }

            ####__else
            return false;
        }
        ####__else
        return false;
    }
}
