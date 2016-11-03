<?php

$GLOBALS['CONFIG'] = array(
    'debit_gouteur' => "12",
    'gouteur_membrane' => "2",
    'nom' => "Config Test"
);

$GLOBALS['SURPRESSEUR'] = array(
    'IP' => "localhost",
    'prise' => 24,
    'puissance' => 850
);

// Pour les capteurs :
//  - Dans le cas d'un MCP , index représente le numéro du boitier
$GLOBALS['IRRIGATION'] = array(
    'Sud Est' => array (
        'parametres' => array (
            'IP'   => 'localhost'
        ),
        'capteur'   => array (
            'temperature'        => array (
                'numero'      => 1,
                'type'        => "I2C",
                'index'       => 0
            ),
            'humidite'        => array (
                'numero'      => 2,
                'type'        => "I2C",
                'index'       => 1
            ),
            'niveau_cuve' => array (
                'numero'      => 3,
                'type'        => "MCP230XX",
                'index'       => 7,
                'nbinput'       => "3",
                'input,1'       => "13",
                'value,1'       => 20,
                'input,2'       => "14",
                'value,2'       => 10,
                'input,3'       => "15",
                'value,3'       => 5
            ),
            'EC_cuve'     => array (
                'numero'      => 4,
                'type'        => "EC",
                'index'       => 1,
                'comPort'     => "/dev/ttyUSB0",
                'version'     => "EC3.0"
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
                        'numero'      => 5,
                        'type'        => "ADS1015",
                        'index'       => 0,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                 'ligne' => array (
                     "1" => array(
                        'prise' => 1,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 6,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'     => 1,
                                'input,1'     => 1,
                                'value,1'     => 1
                            )
                        ),
                        'longueur' => 50
                     ),
                     "2" => array(
                        'prise' => 2,
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
                        'longueur' => 50
                     ),
                     "3" => array(
                        'prise' => 3,
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
                        'longueur' => 50
                     ),
                     "4" => array(
                        'prise' => 4,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 9,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                        'longueur' => 50
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
                        'numero'      => 10,
                        'type'        => "ADS1015",
                        'index'       => 0,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'ligne' => array (
                    "5" => array(
                        'prise' => 5,
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
                        'longueur' => 50
                    ),
                    "6" => array(
                        'prise' => 6,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 12,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                        'longueur' => 50
                    ),
                    "7" => array(
                        'prise' => 7,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 13,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                        'longueur' => 50
                    ),
                    "8" => array(
                        'prise' => 8,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 14,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                        'longueur' => 50
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
                        'numero'      => 15,
                        'type'        => "ADS1015",
                        'index'       => 0,
                        'input'       => "1",
                        'min'         => "0",
                        'max'         => "10"
                    ),
                ),
                'ligne' => array (
                    "9" => array(
                        'prise' => 9,
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
                        'longueur' => 50
                    ),
                    "10" => array(
                        'prise' => 10,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 17,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                        'longueur' => 50
                    ),
                    "11" => array(
                        'prise' => 11,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 18,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                        'longueur' => 50
                    ),
                    "12" => array(
                        'prise' => 12,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 19,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 1,
                                'value,1'       => 1
                            )
                        ),
                        'longueur' => 50
                    )
                )
             )
        )
    )
);
                            
?>