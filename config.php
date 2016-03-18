<?php

$GLOBALS['CONFIG'] = array(
    'debit_gouteur' => "4",
    'gouteur_membrane' => "2",
);

$GLOBALS['SURPRESSEUR'] = array(
    'IP' => "localhost",
    'prise' => 17
);

// Pour les capteurs :
//  - Dans le cas d'un MCP , index représente le numéro du boitier
$GLOBALS['IRRIGATION'] = array(
    'Sud Est' => array (
        'parametres' => array (
            'IP'   => 'localhost'
        ),
        'capteur'   => array (
            't_rh'        => array (
                'numero'      => 1,
                'type'        => "I2C",
                'index'       => 1
            ),
            'niveau_cuve' => array (
                'numero'      => 2,
                'type'        => "MCP230XX",
                'index'       => 1,
                'nbinput'       => "4",
                'input,1'       => "1",
                'value,1'       => "5",
                'input,2'       => "2",
                'value,2'       => "5",
                'input,3'       => "3",
                'value,3'       => "5",
                'input,4'       => "4",
                'value,4'       => "5"
            ),
            'EC_cuve'     => array (
                'numero'      => 3,
                'type'        => "USBSERIAL",
                'index'       => 1
            ),
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
                    'tor_boutonarret'    =>  array (
                        'numero'      => 4,
                        'type'        => "MCP230XX",
                        'index'       => 1,
                        'nbinput'       => 1,
                        'input,1'       => 1,
                        'value,1'       => 1
                    ),
                    'pression_pompe'     => array (
                        'numero'      => 5,
                        'type'        => "ADS1015",
                        'index'       => 1,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                 'ligne' => array (
                     "1" => array(
                        'prise' => 6,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 6,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                     ),
                     "2" => array(
                        'prise' => 7,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 7,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
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
                    'tor_boutonarret'    =>  array (
                        'numero'      => 8,
                        'type'        => "MCP230XX",
                        'index'       => 1,
                        'nbinput'       => 1,
                        'input,1'       => 1,
                        'value,1'       => 1
                    ),
                    'pression_pompe'     => array (
                        'numero'      => 9,
                        'type'        => "ADS1015",
                        'index'       => 1,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                'ligne' => array (
                    "3" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 10,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                    ),
                    "4" => array(
                        'prise' => 4,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 11,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                    ),
                    "5" => array(
                        'prise' => 5,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 12,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                    )
                )
             )
        )
    ),
    'Nord Ouest' => array (
        'parametres' => array (
            'IP'   => '192.168.2.50'
        ),
        'capteur'   => array (
            't_rh'        => array (
                'numero'      => 1,
                'type'        => "I2C",
                'index'       => 1
            ),
            'niveau_cuve' => array (
                'numero'      => 2,
                'type'        => "MCP230XX",
                'index'       => 1,
                'nbinput'       => "4",
                'input,1'       => "1",
                'value,1'       => "5",
                'input,2'       => "2",
                'value,2'       => "5",
                'input,3'       => "3",
                'value,3'       => "5",
                'input,4'       => "4",
                'value,4'       => "5"
            ),
            'EC_cuve'     => array (
                'numero'      => 3,
                'type'        => "USBSERIAL",
                'index'       => 1
            ),
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
                    'tor_boutonarret'    =>  array (
                        'numero'      => 4,
                        'type'        => "MCP230XX",
                        'index'       => 1,
                        'nbinput'       => 1,
                        'input,1'       => 1,
                        'value,1'       => 1
                    ),
                    'pression_pompe'     => array (
                        'numero'      => 5,
                        'type'        => "ADS1015",
                        'index'       => 1,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                 'ligne' => array (
                     "10" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 6,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                     ),
                     "11" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 7,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
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
                    'tor_boutonarret'    =>  array (
                        'numero'      => 8,
                        'type'        => "MCP230XX",
                        'index'       => 1,
                        'nbinput'       => 1,
                        'input,1'       => 1,
                        'value,1'       => 1
                    ),
                    'pression_pompe'     => array (
                        'numero'      => 9,
                        'type'        => "ADS1015",
                        'index'       => 1,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                 'ligne' => array (
                     "6" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 10,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                     ),
                     "7" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 11,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                     ),
                     "8" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 12,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                     ),
                     "9" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 13,
                                'type'        => "MCP230XX",
                                'index'       => 1,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                     )
                 )
            )
        )
    )
);
                            
?>