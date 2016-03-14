<?php

$GLOBALS['SURPRESSEUR'] = array(
    'IP' => "localhost",
    'prise' => 17
);

$GLOBALS['IRRIGATION'] = array(
    'Sud Est' => array (
        'parametres' => array (
            'IP'   => 'localhost'
        ),
        'capteur'   => array (
            'EC_cuve'    => 2
        ),
        'prise'   => array (
            "engrais1" => 14,
            "engrais2" => 15,
            "engrais3" => 16,
            "purge" => 8,
            "remplissage" => 9
        ),
        'plateforme' => array (
            'Est' => array(
                'prise'   => array (
                    "pompe"             => 12,
                    'EV_eauclaire'      => 10,
                    'Afficheur_arret'   => 2
                ),
                'capteur'   => array (
                    'tor_boutonarret'    => 2, 
                    'pression_pompe'    => 2
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                 'ligne' => array (
                     "1" => array(
                        'prise' => 6
                     ),
                     "2" => array(
                        'prise' => 7
                     )
                 )
            ),
            'Sud' => array(
                'prise'   => array (
                    "pompe"             => 12,
                    'EV_eauclaire'      => 10,
                    'Afficheur_arret'   => 2
                ),
                'capteur'   => array (
                    'tor_boutonarret'    => 2, 
                    'pression_pompe'    => 2
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                'ligne' => array (
                    "3" => array(
                        'prise' => 3
                    ),
                    "4" => array(
                        'prise' => 4
                    ),
                    "5" => array(
                        'prise' => 5
                    )
                )
             )
        )
    ),
    'Nord Ouest' => array (
        'parametres' => array (
            'IP'   => 'localhost'
        ),
        'capteur'   => array (
            'EC_cuve'    => 2
        ),
        'prise'   => array (
            "engrais1" => 3,
            "engrais2" => 3,
            "engrais3" => 3,
            "purge" => 8,
            "remplissage" => 9 
        ),
        'plateforme' => array (
            'Nord' => array(
                'prise'   => array (
                    "pompe"             => 12,
                    'EV_eauclaire'      => 10,
                    'Afficheur_arret'   => 2
                ),
                'capteur'   => array (
                    'tor_boutonarret'    => 2, 
                    'pression_pompe'    => 2
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                 'ligne' => array (
                     "10" => array(
                        'prise' => 3
                     ),
                     "11" => array(
                        'prise' => 3
                     )
                 )
            ),
            'Ouest' => array(
                'prise'   => array (
                    "pompe"             => 12,
                    'EV_eauclaire'      => 10,
                    'Afficheur_arret'   => 2
                ),
                'capteur'   => array (
                    'tor_boutonarret'    => 2, 
                    'pression_pompe'    => 2
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                 'ligne' => array (
                     "6" => array(
                        'prise' => 3
                     ),
                     "7" => array(
                        'prise' => 3
                     ),
                     "8" => array(
                        'prise' => 3
                     ),
                     "9" => array(
                        'prise' => 3
                     )
                 )
            )
        )
    )
);
                            
?>