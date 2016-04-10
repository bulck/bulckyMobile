<?php

$GLOBALS['CONFIG'] = array(
    'debit_gouteur' => "12",
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
                'index'       => 7,
                'nbinput'       => "3",
                'input,1'       => "13",
                'value,1'       => "5",
                'input,2'       => "14",
                'value,2'       => "10",
                'input,3'       => "15",
                'value,3'       => "20"
            ),
            'EC_cuve'     => array (
                'numero'      => 3,
                'type'        => "USBSERIAL",
                'index'       => 1
            ),
        ),
        'prise'   => array (
            "engrais1" => 21,
            "engrais2" => 22,
            "engrais3" => 23,
            "purge"    => 13,
            "remplissage" => 14
        ),
        'plateforme' => array (
            'Ouest' => array(
                'prise'   => array (
                    "pompe"             => 18,
                    'EV_eauclaire'      => 15
                ),
                'capteur'   => array (
                    'pression_pompe'     => array (
                        'numero'      => 4,
                        'type'        => "ADS1015",
                        'index'       => 0,
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
                        'prise' => 1,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 5,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'     => 1,
                                'input,1'     => 1,
                                'value,1'     => 1
                            )
                        ),
                     ),
                     "2" => array(
                        'prise' => 2,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 6,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                     ),
                     "3" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 7,
                                'type'        => "MCP230XX",
                                'index'       => 7,
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
                                'numero'      => 8,
                                'type'        => "MCP230XX",
                                'index'       => 7,
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
                    "pompe"             => 19,
                    'EV_eauclaire'      => 16
                ),
                'capteur'   => array (
                    'pression_pompe'     => array (
                        'numero'      => 9,
                        'type'        => "ADS1015",
                        'index'       => 0,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                'ligne' => array (
                    "5" => array(
                        'prise' => 5,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 10,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                    ),
                    "6" => array(
                        'prise' => 6,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 11,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                    ),
                    "7" => array(
                        'prise' => 7,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 12,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        )
                    ),
                    "8" => array(
                        'prise' => 8,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 13,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        )
                    )
                )
             ),
            'Loin' => array(
                'prise'   => array (
                    "pompe"             => 20,
                    'EV_eauclaire'      => 17
                ),
                'capteur'   => array (
                    'pression_pompe'     => array (
                        'numero'      => 11,
                        'type'        => "ADS1015",
                        'index'       => 0,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'parametre'   => array (
                    'temps_cycle' => 240
                ),
                'ligne' => array (
                    "9" => array(
                        'prise' => 9,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 15,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                    ),
                    "10" => array(
                        'prise' => 10,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 16,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                    ),
                    "11" => array(
                        'prise' => 11,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 17,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        )
                    ),
                    "12" => array(
                        'prise' => 12,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 18,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        )
                    )
                )
             )
        )
    )
);
                            
?>