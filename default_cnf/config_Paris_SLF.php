<?php

$GLOBALS['CONFIG'] = array(
    'debit_gouteur' => "12",
    'gouteur_membrane' => "2",
);

$GLOBALS['SURPRESSEUR'] = array(
    'IP' => "localhost",
    'prise' => 8
);

// Pour les capteurs :
//  - Dans le cas d'un MCP , index représente le numéro du boitier
$GLOBALS['IRRIGATION'] = array(
    'Terrasse' => array (
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
                'index'       => 8,
                'nbinput'       => "3",
                'input,1'       => "4",
                'value,1'       => "5",
                'input,2'       => "5",
                'value,2'       => "5",
                'input,3'       => "6",
                'value,3'       => "5",
            ),
            'EC_cuve'     => array (
                'numero'      => 3,
                'type'        => "USBSERIAL",
                'index'       => 1
            ),
        ),
        'prise'   => array (
            "engrais1" => 9,
            "engrais2" => 10,
            "engrais3" => 11,
            "purge"    => 4,
            "remplissage" => 5
        ),
        'plateforme' => array (
            'Terrasse' => array(
                'prise'   => array (
                    "pompe"             => 7,
                    'EV_eauclaire'      => 6
                ),
                'capteur'   => array (
                    'pression_pompe'     => array (
                        'numero'      => 4,
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
                        'prise' => 1,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 5,
                                'type'        => "MCP230XX",
                                'index'       => 1,
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
                                'index'       => 1,
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
                                'index'       => 1,
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