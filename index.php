<!DOCTYPE html>
<html>
   <head>

      <title>Live !</title>
      <meta charset="utf-8" />
      <meta content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" name="viewport">
      <link type="text/css" href="css/layout.css" rel="stylesheet" />

      <!-- Include jQuery.mmenu .css files -->
      <link type="text/css" href="css/jquery.mmenu.all.css" rel="stylesheet" />
      <link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />
      <link type="text/css" href="css/jquery.mmenu.fullscreen.css" rel="stylesheet" />

      <!-- Include jQuery and the jQuery.mmenu .js files -->
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <script type="text/javascript" src="js/jquery.mmenu.min.all.js"></script>
      <script type="text/javascript" src="js/mobile.js"></script>

		<style type="text/css">

			.mm-menu {
				background: #220011 !important;
			}
			.mm-navbar-top-1 {
				text-align: center;
				position: relative;
				border-bottom: none;
			}
			.mm-navbar-top-1:before {
				content: "";
				display: inline-block;
				vertical-align: middle;
				height: 100%;
				width: 1px;
			}
			.mm-navbar-top-1 > * {
				display: inline-block;
				vertical-align: middle;
			}
			.mm-navbar-top-1 img {
				border: 1px solid rgba(255, 255, 255, 0.6);
				border-radius: 60px;
				width: 60px;
				height: 60px;
				padding: 10px;
				margin: 0 10px;
			}
			.mm-navbar-top-1 a {
				border: 1px solid rgba(255, 255, 255, 0.6);
				border-radius: 40px;
				color: rgba(255, 255, 255, 0.6) !important;
				font-size: 16px !important;
				line-height: 40px;
				width: 40px;
				height: 40px;
				padding: 0;
			}
			.mm-navbar-top-1 a:hover {
				border-color: #fff;
				color: #555 !important;
			}
            input[type=range]::-webkit-slider-thumb {
              -webkit-appearance: none;
              width: 100%; height: 44px;
              background: #fdfdfd; background: -moz-linear-gradient(top, #fdfdfd 0%, #bebebe 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fdfdfd), color-stop(100%,#bebebe)); background: -webkit-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); background: -o-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); background: -ms-linear-gradient(top, #fdfdfd 0%,#bebebe 100%); background: linear-gradient(to bottom, #fdfdfd 0%,#bebebe 100%);
              border: 1px solid #bbb;
              -webkit-border-radius: 22px; -moz-border-radius: 22px; border-radius: 22px;
            }
            input[type=button] {
                background-color:#44c767;
                -moz-border-radius:28px;
                -webkit-border-radius:28px;
                border-radius:28px;
                border:1px solid #18ab29;
                display:inline-block;
                cursor:pointer;
                color:#ffffff;
                font-family:Arial;
                font-size:17px;
                padding:8px 17px;
                text-decoration:none;
            }
            
		</style>
        
      <!-- Fire the plugin onDocumentReady -->
      <script type="text/javascript">
         jQuery(document).ready(function( $ ) {
            $("#menu").mmenu({
                offCanvas: false,
                extensions: [
                    "border-none",
                    "effect-zoom-menu",
                    "effect-zoom-panels",
                    "pageshadow",
                    "theme-dark",
                    "fullscreen"
                ],
                navbar 		: {
                    add:true
                },
                navbars		: {
                    height 	: 4,
                    content : [ 
                        '<a href="#/" class="fa fa-phone" class="temperature_zone" ></a>',
                        '<img src="img/shortlogo2.png" class="image_header" />',
                        '<a href="#/" class="fa fa-envelope" class="humidity_zone" ></a>'
                    ]
                }
            });
            
            // On charge la conf 
            loadConf();
            
         });
      </script>

    </head>
    <body>

      <!-- The page -->
      <div class="page">
         <div class="header">
            <a href="#menu"></a>
         </div>
         <div class="content">
         </div>
      </div>

        
        <?php 

            // On charge le fichier de configuration 
            require_once 'config.php';
            
            // On vient lire le fichier de param_ini
            $param_ini = parse_ini_file("param.ini",true);

        ?>

        <!-- The menu -->
        <nav id="menu" style="min-height: 100vh;">

            <div id="app">
                <ul>
                    <li><label>Configuration</label></li>
                    <li><a href="#conf_application" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a></li>
                    <li><a href="#param_conf" ></i>Configuration générale</a></li>
                    <?php
                        // On affiche le titre pour les zones
                        foreach ($GLOBALS['IRRIGATION'] as $nom_zone => $zone)
                        {
                            ?>
                                <li><label>Zone <?php echo $nom_zone ;?></label></li>
                            <?php
                            
                            // On affiche un titre pour la cuve
                            $strname = strtoupper(str_replace(" ", "", $nom_zone));
                            ?>
                                <li><a href="#cuve_conf_<?php echo $strname ;?>" class="mm-arrow">Cuve</a></li>
                            <?php  
                            
                            // On affiche le titre pour les plateformes
                            foreach ($zone["plateforme"] as $nom_plateforme => $plateforme)
                            {
                                $strname = strtoupper(str_replace(" ", "", $nom_plateforme));
                                ?>
                                    <li><a href="#plateforme_conf_<?php echo $strname ;?>" class="mm-arrow">PF <?php echo $nom_plateforme ;?></a></li>
                                <?php  
                            }
                        }
                    ?>
                    <li><label>Debug</label></li>
                    <li><a href="#param_debug" >></i>Paramètres avancées</a></li>
                </ul>

                
                <!-- Pour les cuves -->
                <?php
                    foreach ($GLOBALS['IRRIGATION'] as $nom_zone => $zone)
                    {
                        // On affiche le titre pour les plateformes
                        $zoneName = strtoupper(str_replace(" ", "", $nom_zone));
                        
                        // On calcul le nom des param_inis
                        $engrais1       = $zoneName . "_ENGRAIS_1";
                        $engrais2       = $zoneName . "_ENGRAIS_2";
                        $engrais3       = $zoneName . "_ENGRAIS_3";
                        $engrais1actif  = $zoneName . "_ENGRAIS_ACTIF_1";
                        $engrais2actif  = $zoneName . "_ENGRAIS_ACTIF_2";
                        $engrais3actif  = $zoneName . "_ENGRAIS_ACTIF_3";
                        
                        // 25 mL / min  
                        
                        ?>
                            <div id="cuve_conf_<?php echo $zoneName ;?>" class="Panel">
                                <ul>
                                    <li>EC : <p style="display:inline">3.15</p></li>
                                    <li>Température : <p style="display:inline">25°C</p></li>
                                    <li>Humidité : <p style="display:inline">75%</p></li>
                                    <li>
                                        <a href="#" >Engrais 1 :</a>
                                        <input type="button" value="-" onclick='upVal("CUVE", "<?php echo $engrais1 ;?>", -1, "ml/min");' />
                                        <p id="<?php echo "CUVE_" . $engrais1 ;?>" style="display:inline"><?php echo $param_ini["CUVE"][$engrais1] ;?> ml/min</p> 
                                        <input type="button" value="+" onclick='upVal("CUVE", "<?php echo $engrais1 ;?>", 1, "ml/min");' />
                                    </li>
                                    <li>
                                        <a href="#" >Engrais 2 :</a>
                                        <input type="button" value="-" onclick='upVal("CUVE", "<?php echo $engrais2 ;?>", -1, "ml/min");' />
                                        <p id="<?php echo "CUVE_" . $engrais2 ;?>" style="display:inline"><?php echo $param_ini["CUVE"][$engrais2] ;?> ml/min</p> 
                                        <input type="button" value="+" onclick='upVal("CUVE", "<?php echo $engrais2 ;?>", 1, "ml/min");' />
                                    </li>
                                    <li>
                                        <a href="#" >Engrais 3 :</a>
                                        <input type="button" value="-" onclick='upVal("CUVE", "<?php echo $engrais3 ;?>", -1, "ml/min");' />
                                        <p id="<?php echo "CUVE_" . $engrais3 ;?>" style="display:inline"><?php echo $param_ini["CUVE"][$engrais3] ;?> ml/min</p> 
                                        <input type="button" value="+" onclick='upVal("CUVE", "<?php echo $engrais3 ;?>", 1, "ml/min");' />
                                    </li>
                                    <li>
                                        <a href="#" >Action :</a>
                                        <input type="button" value="Purge de la cuve" onclick='setPlug(60, "<?php echo $zone["prise"]["purge"] ;?>", 0);' />
                                        <br />
                                        <input type="button" value="Injecter 25 mL de l'engrais 1" onclick='setPlug(60, "<?php echo $zone["prise"]["engrais1"] ;?>", 0);' />
                                        <br />
                                        <input type="button" value="Injecter 25 mL de l'engrais 2" onclick='setPlug(60, "<?php echo $zone["prise"]["engrais2"] ;?>", 0);' />
                                        <br />
                                        <input type="button" value="Injecter 25 mL de l'engrais 3" onclick='setPlug(60, "<?php echo $zone["prise"]["engrais3"] ;?>", 0);' />
                                    </li>
                                    <li>
                                        <span>Engrais 1 Actif : </span>
                                        <input type="checkbox" class="Toggle" onclick='changeVal("CUVE", "<?php echo $engrais1actif ;?>", this.checked);' <?php if ($param_ini["CUVE"][$engrais1actif] == "true") {echo "checked" ;}?> />
                                    </li>
                                    <li>
                                        <span>Engrais 2 Actif : </span>
                                        <input type="checkbox" class="Toggle" onclick='changeVal("CUVE", "<?php echo $engrais1actif ;?>", this.checked);' <?php if ($param_ini["CUVE"][$engrais1actif] == "true") {echo "checked" ;}?> />
                                    </li>
                                    <li>
                                        <span>Engrais 3 Actif : </span>
                                        <input type="checkbox" class="Toggle" onclick='changeVal("CUVE", "<?php echo $engrais1actif ;?>", this.checked);' <?php if ($param_ini["CUVE"][$engrais1actif] == "true") {echo "checked" ;}?> />
                                    </li>
                                </ul>
                                <li>
                                    <a href="#" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a>
                                </li>
                            </div>
                        <?php
                    }
                ?>
                
                <!-- Plateforme parts -->
                <?php
                    foreach ($GLOBALS['IRRIGATION'] as $nom_zone => $zone)
                    {
                        // On affiche le titre pour les plateformes
                        foreach ($zone["plateforme"] as $nom_plateforme => $plateforme)
                        {

                            $pfName = strtoupper(str_replace(" ", "", $nom_plateforme));
                            
                            // On calcul le maximum de l/h max 
                            $lhMax = round($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'] / count($plateforme["ligne"]) , 1);
                            ?>
                                <div id="plateforme_conf_<?php echo $pfName ;?>" class="Panel">
                                    <ul>
                                        <?php
                                            foreach ($plateforme["ligne"] as $nom_ligne => $ligne) 
                                            {
                                                $ligneName = strtoupper(str_replace(" ", "", $nom_ligne));
                                                
                                                // On intitilialise les valeurs si elles n'existent pas
                                                $matin = $pfName . "_" . $ligneName . "_MATIN";
                                                if (!array_key_exists($matin, $param_ini["LIGNE"]) ) {
                                                    $param_ini["LIGNE"][$matin] = 1.5;
                                                }
                                                $amidi = $pfName . "_" . $ligneName . "_APRESMIDI";
                                                if (!array_key_exists($amidi, $param_ini["LIGNE"]) ) {
                                                    $param_ini["LIGNE"][$amidi] = 1.5;
                                                }
                                                $soir = $pfName . "_" . $ligneName . "_SOIR";
                                                if (!array_key_exists($soir, $param_ini["LIGNE"]) ) {
                                                    $param_ini["LIGNE"][$soir] = 1.5;
                                                }
                                                $active = $pfName . "_" . $ligneName . "_ACTIVE";
                                                if (!array_key_exists($active, $param_ini["LIGNE"]) ) {
                                                    $param_ini["LIGNE"][$active] = 1;
                                                }
                                                
                                                ?>
                                                <li>
                                                    <a href="#" >Ligne <?php echo $ligneName ;?> :</a>
                                                    <table >
                                                        <tr>
                                                            <td>Matin :</td>
                                                            <td><input type="button" value="-"  onclick='upVal("LIGNE", "<?php echo $matin ;?>", -0.1, "l/h/membrane", 100);' /></td>
                                                            <td><p id="<?php echo "LIGNE_" . $matin ;?>" style="display:inline"><?php echo $param_ini["LIGNE"][$matin] ;?> l/h/membrane</p></td>
                                                            <td><input type="button" value="+" onclick='upVal("LIGNE", "<?php echo $matin ;?>", 0.1, "l/h/membrane", <?php echo $lhMax ;?>);' /></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Après Midi :</td>
                                                            <td><input type="button" value="-"  onclick='upVal("LIGNE", "<?php echo $amidi ;?>", -0.1, "l/h/membrane", 100);' /></td>
                                                            <td><p id="<?php echo "LIGNE_" . $amidi ;?>" style="display:inline"><?php echo $param_ini["LIGNE"][$amidi] ;?> l/h/membrane</p></td>
                                                            <td><input type="button" value="+" onclick='upVal("LIGNE", "<?php echo $amidi ;?>", 0.1, "l/h/membrane", <?php echo $lhMax ;?>);' /></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Soir :</td>
                                                            <td><input type="button" value="-"  onclick='upVal("LIGNE", "<?php echo $soir ;?>", -0.1, "l/h/membrane", 100);' /></td>
                                                            <td><p id="<?php echo "LIGNE_" . $soir ;?>" style="display:inline"><?php echo $param_ini["LIGNE"][$soir] ;?> l/h/membrane</p></td>
                                                            <td><input type="button" value="+" onclick='upVal("LIGNE", "<?php echo $soir ;?>", 0.1, "l/h/membrane", <?php echo $lhMax ;?>);' /></td>
                                                        </tr>
                                                    </table>
                                                </li>
                                                <?php
                                            }
                                            foreach ($plateforme["ligne"] as $nom_ligne => $ligne) 
                                            {
                                                $ligneName = strtoupper(str_replace(" ", "", $nom_ligne));
                                                ?>
                                                <li>
                                                    <span>Activation ligne <?php echo $ligneName ;?> : </span>
                                                    <input type="checkbox" class="Toggle" onclick='changeVal("LIGNE", "<?php echo $active ;?>", this.checked);' <?php if ($param_ini["LIGNE"][$active] == "true") {echo "checked" ;}?> />
                                                </li> 
                                                <?php
                                            }
                                            foreach ($plateforme["ligne"] as $nom_ligne => $ligne) 
                                            {
                                                $ligneName = strtoupper(str_replace(" ", "", $nom_ligne));
                                                ?>
                                                <li>
                                                    <input type="button" value="ON ligne <?php echo $ligneName ;?> pendant 60s" onclick='setPlug(60, <?php echo $ligne["prise"] ;?>,<?php echo $plateforme["pompe_prise"] ;?>);' />
                                                    <br />
                                                </li>
                                                <?php
                                            }
                                        ?>
                                        <li>
                                            <a href="#" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a>
                                        </li>
                                    </ul>
                                </div>
                            <?php
                        }
                    }
                ?>

                <!-- subpanel for conf -->
                <div id="param_conf" class="Panel">
                    <ul>
                        <li>
                            <span>Nettoyage gouteurs :</span>
                            <select id="price-from" onchange="savParam('NETTOYAGE_GOUTEUR',this.value);" style="display:inline" >
                                <option value="10"   <?php if ($param_ini["PARAM"]["NETTOYAGE_GOUTEUR"] == "10") {echo "selected";} ?>   >1 cycle sur 10</option>
                                <option value="100"  <?php if ($param_ini["PARAM"]["NETTOYAGE_GOUTEUR"] == "100") {echo "selected";} ?>  >1 cycle sur 100</option>
                            </select>
                        </li>
                        <li>
                            <a href="#" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a>
                        </li>
                    </ul>
                </div> 

                <!-- subpanel for debug -->
                <div id="param_debug" class="Panel">
                    <ul>
                        <li>
                            <span>Verbose Server :</span>
                            <select id="price-from" onchange="savParam('VERBOSE_SERVER',this.value);" style="display:inline" >
                                <option value="debug"   <?php if ($param_ini["PARAM"]["VERBOSE_SERVER"] == "debug") {echo "selected";} ?>   >debug</option>
                                <option value="info"    <?php if ($param_ini["PARAM"]["VERBOSE_SERVER"] == "info") {echo "selected";} ?>    >info</option>
                                <option value="warning" <?php if ($param_ini["PARAM"]["VERBOSE_SERVER"] == "warning") {echo "selected";} ?> >warning</option>
                                <option value="error"   <?php if ($param_ini["PARAM"]["VERBOSE_SERVER"] == "error") {echo "selected";} ?>   >error</option>
                            </select>
                        </li>
                        <li>
                            <span>Verbose Sensor :</span>
                        <select id="price-from" onchange="savParam('VERBOSE_ACQSENSOR',this.value);" style="display:inline" >
                            <option value="debug"   <?php if ($param_ini["PARAM"]["VERBOSE_ACQSENSOR"] == "debug")   {echo "selected";} ?>   >debug</option>
                            <option value="info"    <?php if ($param_ini["PARAM"]["VERBOSE_ACQSENSOR"] == "info")    {echo "selected";} ?>    >info</option>
                            <option value="warning" <?php if ($param_ini["PARAM"]["VERBOSE_ACQSENSOR"] == "warning") {echo "selected";} ?> >warning</option>
                            <option value="error"   <?php if ($param_ini["PARAM"]["VERBOSE_ACQSENSOR"] == "error")   {echo "selected";} ?>   >error</option>
                        </select>
                        </li>
                        <li>
                            <span>Verbose Plug :</span>
                            <select id="price-from" onchange="savParam('VERBOSE_PLUG',this.value);" style="display:inline" >
                                <option value="debug"   <?php if ($param_ini["PARAM"]["VERBOSE_PLUG"] == "debug") {echo "selected";} ?>   >debug</option>
                                <option value="info"    <?php if ($param_ini["PARAM"]["VERBOSE_PLUG"] == "info") {echo "selected";} ?>    >info</option>
                                <option value="warning" <?php if ($param_ini["PARAM"]["VERBOSE_PLUG"] == "warning") {echo "selected";} ?> >warning</option>
                                <option value="error"   <?php if ($param_ini["PARAM"]["VERBOSE_PLUG"] == "error") {echo "selected";} ?>   >error</option>
                            </select>
                        </li>
                        <li>
                            <span>Verbose SLF :</span>
                            <select id="price-from" onchange="savParam('VERBOSE_SLF',this.value);" style="display:inline" >
                                <option value="debug"   <?php if ($param_ini["PARAM"]["VERBOSE_SLF"] == "debug") {echo "selected";} ?>   >debug</option>
                                <option value="info"    <?php if ($param_ini["PARAM"]["VERBOSE_SLF"] == "info") {echo "selected";} ?>    >info</option>
                                <option value="warning" <?php if ($param_ini["PARAM"]["VERBOSE_SLF"] == "warning") {echo "selected";} ?> >warning</option>
                                <option value="error"   <?php if ($param_ini["PARAM"]["VERBOSE_SLF"] == "error") {echo "selected";} ?>   >error</option>
                            </select>
                        </li>
                        <li>
                            <a href="#" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a>
                        </li>
                    </ul>
                </div>
                
                <!-- subpanel for debug -->
                <div id="conf_application" class="Panel">
                    <ul>
                        <li>
                            <span>En cours d'application :</span>
                        </li>
                    </ul>
                </div>
                
            </div>
        </nav>

    </body>
</html>