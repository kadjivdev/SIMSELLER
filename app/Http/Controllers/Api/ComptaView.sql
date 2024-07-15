CREATE VIEW export_comptabilite	AS
SELECT
    CURTIME() AS `heureSysteme`, CURDATE() AS `dateSysteme`,
    `ventes`.`code` AS `code`, `ventes`.`id` AS `id`,
    `ventes`.`date` AS `dateVente`,`ventes`.`created_at` AS `dateCreate`, `ventes`.`filleuls` AS `filleuls`, `ventes`.`date_traitement` AS `date_traitement`, `ventes`.`date_comptabilisation` AS `date_comptabilisation`, `ventes`.`qteTotal` AS `qte`, `ventes`.`pu` AS `pvr`, `ventes`.`prix_TTC` AS `prixTTC`, `clients`.`raisonSociale` AS `clients`, `clients`.`ifu` AS `ifu`, `bon_commandes`.`dateBon` AS `dateAchat`, `produits`.`libelle` AS `produit`, `fournisseurs`.`sigle` AS `FRS`, ROUND(
        (
            `ventes`.`prix_TTC` / `ventes`.`taux_aib`
        ),
        2
    ) AS `PrixHT`,
    ROUND(
        (
            (
                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
            ) * 1.18
        ),
        2
    ) AS `PrixBruite`,
    ROUND(
        (
            (
                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
            ) * `ventes`.`qteTotal`
        ),
        2
    ) AS `NetHT`,
    ROUND(
        (
            (
                (
                    `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                ) * `ventes`.`qteTotal`
            ) * 0.18
        ),
        2
    ) AS `TVA`,
    `ventes`.`taux_aib` AS `taux_aib`,
    (
        CASE WHEN(
            ROUND(
                `ventes`.`taux_aib`,
                2
            ) = 1.18
        ) THEN 0 WHEN(
            ROUND(
                `ventes`.`taux_aib`,
                2
            ) = 1.19
        ) THEN ROUND(
            (
                (
                    (
                        `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                    ) * `ventes`.`qteTotal`
                ) / 100
            ),
            2
        ) WHEN(
            ROUND(
                `ventes`.`taux_aib`,
                2
            ) = 1.23
        ) THEN ROUND(
            (
                (
                    (
                        `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                    ) * `ventes`.`qteTotal`
                ) * 0.05
            ),
            2
        )
    END
    ) AS `AIB`,
    (
        CASE WHEN(
            ROUND(
                `ventes`.`taux_aib`,
                2
            ) = 1.18
        ) THEN ROUND(
            (
                (
                    0 + ROUND(
                        (
                            (
                                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                            ) * `ventes`.`qteTotal`
                        ),
                        2
                    )
                ) + ROUND(
                    (
                        (
                            (
                                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                            ) * `ventes`.`qteTotal`
                        ) * 0.18
                    ),
                    2
                )
            ),
            2
        ) WHEN(
            ROUND(
                `ventes`.`taux_aib`,
                2
            ) = 1.19
        ) THEN ROUND(
            (
                (
                    ROUND(
                        (
                            (
                                (
                                    `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                                ) * `ventes`.`qteTotal`
                            ) / 100
                        ),
                        2
                    ) + ROUND(
                        (
                            (
                                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                            ) * `ventes`.`qteTotal`
                        ),
                        2
                    )
                ) + ROUND(
                    (
                        (
                            (
                                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                            ) * `ventes`.`qteTotal`
                        ) * 0.18
                    ),
                    2
                )
            ),
            2
        ) WHEN(
            ROUND(
                `ventes`.`taux_aib`,
                2
            ) = 1.23
        ) THEN ROUND(
            (
                (
                    ROUND(
                        (
                            (
                                (
                                    `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                                ) * `ventes`.`qteTotal`
                            ) * 0.05
                        ),
                        2
                    ) + ROUND(
                        (
                            (
                                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                            ) * `ventes`.`qteTotal`
                        ),
                        2
                    )
                ) + ROUND(
                    (
                        (
                            (
                                `ventes`.`prix_TTC` / `ventes`.`taux_aib`
                            ) * `ventes`.`qteTotal`
                        ) * 0.18
                    ),
                    2
                )
            ),
            2
        )
    END
    ) AS `TTC`
FROM
    (
        (
            (
                (
                    (
                        (
                            (
                                (
                                    `ventes`
                                LEFT JOIN `commande_clients` ON
                                    (
                                        (
                                            `ventes`.`commande_client_id` = `commande_clients`.`id`
                                        )
                                    )
                                )
                            LEFT JOIN `produits` ON
                                (
                                    (
                                        `ventes`.`produit_id` = `produits`.`id`
                                    )
                                )
                            )
                        LEFT JOIN `clients` ON
                            (
                                (
                                    `commande_clients`.`client_id` = `clients`.`id`
                                )
                            )
                        )
                    LEFT JOIN `vendus` ON
                        (
                            (
                                `ventes`.`id` = `vendus`.`vente_id`
                            )
                        )
                    )
                LEFT JOIN `programmations` ON
                    (
                        (
                            `vendus`.`programmation_id` = `programmations`.`id`
                        )
                    )
                )
            LEFT JOIN `detail_bon_commandes` ON
                (
                    (
                        `programmations`.`detail_bon_commande_id` = `detail_bon_commandes`.`bon_commande_id`
                    )
                )
            )
        LEFT JOIN `bon_commandes` ON
            (
                (
                    `detail_bon_commandes`.`bon_commande_id` = `bon_commandes`.`id`
                )
            )
        )
    LEFT JOIN `fournisseurs` ON
        (
            (
                `bon_commandes`.`fournisseur_id` = `fournisseurs`.`id`
            )
        )
    );