SELECT 
    CURTIME() AS `heureSysteme`,CURDATE() AS `dateSysteme`,V.`code` AS `code`,V.`id` AS `id`,V.`date` AS `dateVente`,V.`created_at` AS `dateCreate`,
    V.`filleuls` AS `filleuls`,V.`date_traitement` AS `date_traitement`,V.`date_comptabilisation` AS `date_comptabilisation`,V.`qteTotal` AS `qte`,
    V.`pu` AS `pvr`,V.`prix_TTC` AS `prixTTC`,C.`raisonSociale` AS `clients`,C.`ifu` AS `ifu`,BC.`dateBon` AS `dateAchat`,PROD.`libelle` AS `produit`,F.`sigle` AS `FRS`,
    ROUND((V.`prix_TTC` / V.`taux_aib`),2) AS `PrixHT`,ROUND(((V.`prix_TTC` / V.`taux_aib`) * 1.18),2) AS `PrixBruite`,V.`taux_aib` AS `taux_aib`,
    ROUND(((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`),2) AS `NetHT`,ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) * 0.18),2) AS `TVA`,
(CASE
     WHEN (ROUND(V.`taux_aib`,2) = 1.18) THEN 0 
     WHEN (ROUND(V.`taux_aib`,2) = 1.19) THEN 
        ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) / 100),2)
     WHEN (ROUND(V.`taux_aib`,2) = 1.23) THEN 
        ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) * 0.05),2) 
END) AS `AIB`,
(CASE 
    WHEN (ROUND(V.`taux_aib`,2) = 1.18) THEN 
        ROUND(((0 + ROUND(((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`),2)) + ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) * 0.18),2)),2) 
    WHEN (ROUND(V.`taux_aib`,2) = 1.19) THEN 
        ROUND(((ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) / 100),2) + ROUND(((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`),2)) + ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) * 0.18),2)),2) 
    WHEN (ROUND(V.`taux_aib`,2) = 1.23) THEN 
        ROUND(((ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) * 0.05),2) + ROUND(((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`),2)) + ROUND((((V.`prix_TTC` / V.`taux_aib`) * V.`qteTotal`) * 0.18),2)),2) 
END) AS `TTC` 
FROM 
    `ventes` AS V, 
    `commande_clients` AS CC, 
    `clients` AS C, 
    `produits` AS PROD, 
    `vendus` AS VDU, 
    `programmations` AS PRG, 
    `bon_commandes` AS BC, 
    `detail_bon_commandes` AS DBC, 
    `fournisseurs` AS F 
WHERE V.`commande_client_id` = CC.`id` 
    AND CC.`client_id` = C.`id` 
    AND V.`id` = VDU.`vente_id` 
    AND VDU.`programmation_id` = PRG.`id` 
    AND PRG.`detail_bon_commande_id` = DBC.`id` 
    AND DBC.`produit_id` = PROD.`id` 
    AND DBC.`bon_commande_id` = BC.`id` 
    AND BC.`fournisseur_id` = F.`id`
