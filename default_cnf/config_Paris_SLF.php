<?php

$GLOBALS['CONFIG'] = array(
    'debit_gouteur' => "12",
    'gouteur_membrane' => "2",
    'nom' => "SLF Paris"
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
            'temperature'        => array (
                'numero'      => 1,
                'type'        => "I2C",
                'index'       => 1
            ),
            'humidite'        => array (
                'numero'      => 2,
                'type'        => "I2C",
                'index'       => 2
            ),
            'niveau_cuve' => array (
                'numero'      => 3,
                'type'        => "MCP230XX",
                'index'       => 7,
                'nbinput'       => "3",
                'input,1'       => "4",
                'value,1'       => "20",
                'input,2'       => "5",
                'value,2'       => "10",
                'input,3'       => "6",
                'value,3'       => "5",
            ),
            'EC_cuve'     => array (
                'numero'      => 4,
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
                        'numero'      => 5,
                        'type'        => "ADS1015",
                        'index'       => 0,
                        'input'       => "1",
                        'min'         => "-4",
                        'max'         => "16"
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
                     ),
                     "2" => array(
                        'prise' => 2,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 7,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 2,
                                'value,1'       => 1
                            )
                        ),
                     ),
                     "3" => array(
                        'prise' => 3,
                        'capteur'   => array (
                            'pression'    =>  array (
                                'numero'      => 8,
                                'type'        => "MCP230XX",
                                'index'       => 7,
                                'nbinput'       => 1,
                                'input,1'       => 3,
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