<!DOCTYPE html>
<html>
   <head>

      <title>Irrigation</title>
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
                color: rgba(255, 255, 255, 0.8) ;
			}
			.mm-navbar-top-1 > * {
				display: inline-block;
				vertical-align: middle;
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
            input[type=range]:-webkit-slider-thumb {
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
			.mm-navbar-bottom-1 p {
				color: rgba(255, 255, 255, 0.8) !important;
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
                    height 	: 1,
                    position : "bottom",
                    content : [ 
                        '<p id="texte_info" ></p>'
                    ]
                }/*,
                navbars		: {
                    height 	: 4,
                    content : [ 
                        '<a href="#/" class="fa fa-phone" class="temperature_zone" ></a>',
                        '<img src="img/shortlogo2.png" class="image_header" />',
                        '<a href="#/" class="fa fa-envelope" class="humidity_zone" ></a>'
                    ]
                }*/
            });
            
            // On charge la conf 
            loadConf();
            
            // On vient lire la valeur des capteurs 
            readSensors();
            
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
            
            function ParamIni($key1, $key2, $default) {
                global $param_ini;
                if (array_key_exists($key2, $param_ini[$key1])) {
                    return $param_ini[$key1][$key2];
                }
                return $default;
            }

            // On vient lire le fichier de param_ini
            $param_ini = parse_ini_file("param.ini",true);

        ?>

        <!-- The menu -->
        <nav id="menu" style="min-height: 100vh;">

            <div id="app">
                <ul>
                    <li><label>Configuration</label></li>
                    <li><a href="#" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a></li>
                    <li><a href="#param_conf" ><i class="fa fa-cogs"></i>Configuration générale</a></li>
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
                                <li><a href="#cuve_conf_<?php echo $strname ;?>" class="mm-arrow"><i class="fa fa-database"></i>Cuve</a></li>
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
                    <li><a href="#debug_pilotage" ><i class="fa fa-power-off"></i>Pilotage</a></li>
                    <li><a href="#sensors" ><i class="fa fa-tachometer"></i>Capteurs</a></li>
                    <li><a href="#param_debug" ><i class="fa fa-file-code-o"></i>Paramètres avancées</a></li>
                </ul>

                <!-- Pour les cuves -->
                <?php
                    $ZoneIndex = 0;
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
                        $remplissageActif  = $zoneName . "_REMPLISSAGE_ACTIF";

                        // 25 mL / min  

                        ?>
                            <div id="cuve_conf_<?php echo $zoneName ;?>" class="Panel">
                                <ul>
                                    <!--
                                    <li>EC : <p style="display:inline">3.15</p></li>
                                    <li>Température : <p style="display:inline">25°C</p></li>
                                    <li>Humidité : <p style="display:inline">75%</p></li>
                                    -->
                                    <li>
                                        <a href="#" >Engrais 1 :</a>
                                        <input type="button" value="-" onclick='upVal("CUVE", "<?php echo $engrais1 ;?>", -0.1, "ml/min");' />
                                        <p id="<?php echo "CUVE_" . $engrais1 ;?>" style="display:inline"><?php echo ParamIni("CUVE",$engrais1,"5");?> ml/min</p> 
                                        <input type="button" value="+" onclick='upVal("CUVE", "<?php echo $engrais1 ;?>", 0.1, "ml/min");' />
                                    </li>
                                    <li>
                                        <a href="#" >Engrais 2 :</a>
                                        <input type="button" value="-" onclick='upVal("CUVE", "<?php echo $engrais2 ;?>", -0.1, "ml/min");' />
                                        <p id="<?php echo "CUVE_" . $engrais2 ;?>" style="display:inline"><?php echo ParamIni("CUVE",$engrais2,"5");?> ml/min</p> 
                                        <input type="button" value="+" onclick='upVal("CUVE", "<?php echo $engrais2 ;?>", 0.1, "ml/min");' />
                                    </li>
                                    <li>
                                        <a href="#" >Engrais 3 :</a>
                                        <input type="button" value="-" onclick='upVal("CUVE", "<?php echo $engrais3 ;?>", -0.1, "ml/min");' />
                                        <p id="<?php echo "CUVE_" . $engrais3 ;?>" style="display:inline"><?php echo ParamIni("CUVE",$engrais3,"5");?> ml/min</p> 
                                        <input type="button" value="+" onclick='upVal("CUVE", "<?php echo $engrais3 ;?>", 0.1, "ml/min");' />
                                    </li>
                                    <li>
                                        <a href="#" >Action :</a>
                                        <input type="button" value="Purge de la cuve" onclick='purgeCuve("<?php echo $ZoneIndex ;?>");' />
                                        <br />
                                        <input type="button" value="Injecter 25 mL de l'engrais 1" onclick='setPlug(60, "<?php echo $zone["prise"]["engrais1"] ;?>", 0);' />
                                        <br />
                                        <input type="button" value="Injecter 25 mL de l'engrais 2" onclick='setPlug(60, "<?php echo $zone["prise"]["engrais2"] ;?>", 0);' />
                                        <br />
                                        <input type="button" value="Injecter 25 mL de l'engrais 3" onclick='setPlug(60, "<?php echo $zone["prise"]["engrais3"] ;?>", 0);' />
                                    </li>
                                    <li>
                                        <span>Engrais 1 Actif : </span>
                                        <input type="checkbox" class="Toggle" onclick='changeVal("CUVE", "<?php echo $engrais1actif ;?>", this.checked);' <?php if (ParamIni("CUVE",$engrais1actif,"false") == "true") {echo "checked" ;}?> />
                                    </li>
                                    <li>
                                        <span>Engrais 2 Actif : </span>
                                        <input type="checkbox" class="Toggle" onclick='changeVal("CUVE", "<?php echo $engrais1actif ;?>", this.checked);' <?php if (ParamIni("CUVE",$engrais2actif,"false") == "true") {echo "checked" ;}?> />
                                    </li>
                                    <li>
                                        <span>Engrais 3 Actif : </span>
                                        <input type="checkbox" class="Toggle" onclick='changeVal("CUVE", "<?php echo $engrais1actif ;?>", this.checked);' <?php if (ParamIni("CUVE",$engrais3actif,"false") == "true") {echo "checked" ;}?> />
                                    </li>
                                    <li>
                                        <span>Remplissage cuve activé :</span>
                                        <input type="checkbox" class="Toggle" onclick='changeVal("CUVE", "<?php echo $remplissageActif ;?>", this.checked);' <?php if (ParamIni("CUVE",$remplissageActif,"true") == "true") {echo "checked" ;}?> />
                                     </li>
                                </ul>
                                <li>
                                    <a href="#" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a>
                                </li>
                            </div>
                        <?php
                        $ZoneIndex++;
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
                                                $amidi = $pfName . "_" . $ligneName . "_APRESMIDI";
                                                $soir = $pfName . "_" . $ligneName . "_SOIR";
                                                $active = $pfName . "_" . $ligneName . "_ACTIVE";
                                                
                                                ?>
                                                <li>
                                                    <a href="#" >Ligne <?php echo $ligneName ;?> (l/h/membrane) :</a>
                                                    <table >
                                                        <tr>
                                                            <td>Matin :</td>
                                                            <td><input type="button" value="-"   onclick='upVal("LIGNE", "<?php echo $matin ;?>", -0.1, "l/h/m", 100);' /></td>
                                                            <td><p id="<?php echo "LIGNE_" . $matin ;?>" style="display:inline"><?php echo ParamIni("LIGNE",$matin,"1.5") ;?> l/h/m</p></td>
                                                            <td><input type="button" value="+"   onclick='upVal("LIGNE", "<?php echo $matin ;?>", 0.1, "l/h/m", <?php echo $lhMax ;?>);' /></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Après Midi :</td>
                                                            <td><input type="button" value="-"  onclick='upVal("LIGNE", "<?php echo $amidi ;?>", -0.1, "l/h/m", 100);' /></td>
                                                            <td><p id="<?php echo "LIGNE_" . $amidi ;?>" style="display:inline"><?php echo ParamIni("LIGNE",$amidi,"1.5");?> l/h/m</p></td>
                                                            <td><input type="button" value="+" onclick='upVal("LIGNE", "<?php echo $amidi ;?>", 0.1, "l/h/m", <?php echo $lhMax ;?>);' /></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Soir :</td>
                                                            <td><input type="button" value="-"  onclick='upVal("LIGNE", "<?php echo $soir ;?>", -0.1, "l/h/m", 100);' /></td>
                                                            <td><p id="<?php echo "LIGNE_" . $soir ;?>" style="display:inline"><?php echo ParamIni("LIGNE",$soir,"1.5");?> l/h/m</p></td>
                                                            <td><input type="button" value="+" onclick='upVal("LIGNE", "<?php echo $soir ;?>", 0.1, "l/h/m", <?php echo $lhMax ;?>);' /></td>
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
                                                    <input type="checkbox" class="Toggle" onclick='changeVal("LIGNE", "<?php echo $active ;?>", this.checked);' <?php if (ParamIni("LIGNE",$active,"true") == "true") {echo "checked" ;}?> />
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
                                            <span>Temps cycle :</span>
                                            <select id="temps_cycle_<?php echo $pfName ;?>" onchange="changeVal('LIGNE','<?php echo $pfName ;?>_TEMPS_CYCLE',this.value);" style="display:inline" >
                                                <option value="240"  <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "240") {echo "selected";} ?> >2 minutes</option>
                                                <option value="300"  <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "300") {echo "selected";} ?> >5 minutes</option>
                                                <option value="600"  <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "600") {echo "selected";} ?> >10 minutes</option>
                                            </select>
                                        </li>
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
                                <option value="10"   <?php if (ParamIni("PARAM","NETTOYAGE_GOUTEUR","100") == "10")  {echo "selected";} ?>   >1 cycle sur 10</option>
                                <option value="100"  <?php if (ParamIni("PARAM","NETTOYAGE_GOUTEUR","100") == "100") {echo "selected";} ?>  >1 cycle sur 100</option>
                            </select>
                        </li>
                        <li>
                            <span>Nettoyage gouteur actif :</span>
                            <input type="checkbox" class="Toggle" onclick="savParam('NETTOYAGE_GOUTEUR_ACTIF',this.checked);" <?php if (ParamIni("PARAM","NETTOYAGE_GOUTEUR_ACTIF","true") == "true") {echo "checked" ;}?> />
                        </li>
                        <li>
                            <span>Irrigation activée :</span>
                            <input type="checkbox" class="Toggle" onclick="savParam('IRRIGATION_ACTIF',this.checked);" <?php if (ParamIni("PARAM","IRRIGATION_ACTIF","true") == "true") {echo "checked" ;}?> />
                        </li>
                        <li>
                            <span>Supresseur activé :</span>
                            <input type="checkbox" class="Toggle" onclick="savParam('SURPRESSEUR_ACTIF',this.checked);" <?php if (ParamIni("PARAM","SURPRESSEUR_ACTIF","true") == "true") {echo "checked" ;}?> />
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
                            <select id="verbose_server" onchange="savParam('VERBOSE_SERVER',this.value);" style="display:inline" >
                                <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "debug") {echo "selected";} ?>   >debug</option>
                                <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "info") {echo "selected";} ?>    >info</option>
                                <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "warning") {echo "selected";} ?> >warning</option>
                                <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "error") {echo "selected";} ?>   >error</option>
                            </select>
                        </li>
                        <li>
                            <span>Verbose Sensor :</span>
                        <select id="verbose_acqsensor" onchange="savParam('VERBOSE_ACQSENSOR',this.value);" style="display:inline" >
                            <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "debug")   {echo "selected";} ?>   >debug</option>
                            <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "info")    {echo "selected";} ?>    >info</option>
                            <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "warning") {echo "selected";} ?> >warning</option>
                            <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "error")   {echo "selected";} ?>   >error</option>
                        </select>
                        </li>
                        <li>
                            <span>Verbose Plug :</span>
                            <select id="verbose_plug" onchange="savParam('VERBOSE_PLUG',this.value);" style="display:inline" >
                                <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "debug") {echo "selected";} ?>   >debug</option>
                                <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "info") {echo "selected";} ?>    >info</option>
                                <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "warning") {echo "selected";} ?> >warning</option>
                                <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "error") {echo "selected";} ?>   >error</option>
                            </select>
                        </li>
                        <li>
                            <span>Verbose SLF :</span>
                            <select id="verbose_SLF" onchange="savParam('VERBOSE_SLF',this.value);" style="display:inline" >
                                <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "debug") {echo "selected";} ?>   >debug</option>
                                <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "info") {echo "selected";} ?>    >info</option>
                                <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "warning") {echo "selected";} ?> >warning</option>
                                <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "error") {echo "selected";} ?>   >error</option>
                            </select>
                        </li>
                        <li><a href="#conf_mail" ><i class="fa fa-envelope-o"></i>Mail</a></li>
                        <li><a href="#conf_action" ><i class="fa fa-terminal"></i>Action</a></li>
                        <li>
                            <span>Appliquer une conf template :</span>
                            <select id="conf_template" style="display:inline" >
                                <?php
                                    // Liste des configs
                                    $configDispos = glob('default_cnf/*.{php}', GLOB_BRACE);
                                    foreach ($configDispos as $configDispo)
                                    {
                                        ?>
                                            <option value="<?php echo basename($configDispo) ;?>"  ><?php echo basename($configDispo) ;?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                            <input type="button" value="Appliquer" onclick='loadTemplateConf(document.getElementById("conf_template").value);' />
                            <br />
                        </li>
                        <li>
                            <a href="#" onclick='saveConf();' ><i class="fa fa-arrow-circle-right"></i>Appliquer</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Pilotage prise -->
                <div id="debug_pilotage" class="Panel">
                    <ul>
                        <?php 
                            for ($i = 1; $i <= 25; $i++) {
                        ?>
                            <li>
                                <input type="button" value="ON sortie <?php echo $i ;?> pendant 30s" onclick='setPlug(30, <?php echo $i ;?>,0);' />
                                <br />
                            </li>
                        <?php 
                            }
                        ?>
                    </ul>
                </div>

                <!-- Valeur des capteurs -->
                <div id="sensors" class="Panel">
                    <ul>
                        <li>
                            <input type="button" value="Recharger les valeurs" onclick='readSensors();' />
                            <br />
                        </li>
                        <li>
                            <table >
                                <tr>
                                    <th>Numéro</th><th>Nom</th><th>Valeur</th>
                                </tr>

                        <?php 
                            foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {
                                foreach ($zone["capteur"] as $capteur_nom => $capteur) {

                                ?>
                                    <tr>
                                        <td>Capteur <?php echo $capteur["numero"];?></td><td><?php echo $capteur_nom ;?></td><td id="sensor_<?php echo $capteur["numero"] ;?>"></td>
                                    </tr>
                                <?php
                                }

                                foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {

                                    foreach ($plateforme["capteur"] as $capteur_nom => $capteur) {
                                        ?>
                                            <tr>
                                                <td>Capteur <?php echo $capteur["numero"];?></td><td><?php echo $capteur_nom ;?></td><td id="sensor_<?php echo $capteur["numero"] ;?>"></td>
                                            </tr>
                                        <?php
                                    }

                                    
                                    foreach ($plateforme["ligne"] as $ligne_numero => $ligne) {
                                        
                                        // On ajoute un détecteur de pression par ligne
                                        foreach ($ligne["capteur"] as $capteur_nom => $capteur) {
                                        ?>
                                            <tr>
                                                <td>Capteur <?php echo $capteur["numero"];?></td><td><?php echo $capteur_nom . " ligne " . $ligne_numero ;?></td><td id="sensor_<?php echo $capteur["numero"] ;?>"></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                }
                            }
                        ?>
                            </table>
                        </li>
                    </ul>
                </div>

                <!-- Configuration des mails -->
                <div id="conf_mail" class="Panel">
                    <ul>
                        <li>
                            <span>Nom d'utilisateur :</span>
                            <input type="text" id="conf_mail_username" >
                            <input type="button" value="Sauvegarder utilisateur" onclick="savParam('MAIL_USERNAME',document.getElementById('conf_mail_username').value);" />
                        </li>
                        <li>
                            <span>Mot de passe :</span>
                            <input type="password" id="conf_mail_password" ><br />
                            <input type="button" value="Sauvegarder MDP" onclick="savParam('MAIL_PASSWORD',document.getElementById('conf_mail_password').value);" />
                        </li>
                        <li>
                            <span>Server SMTP :</span>
                            <select id="conf_mail_Port" onchange="savParam('MAIL_SMTP',this.value);" style="display:inline" >
                                <option value="smtp.gmail.com"              <?php if (ParamIni("PARAM","MAIL_SMTP","smtp.gmail.com") == "smtp.gmail.com")             {echo "selected";} ?>  >smtp.gmail.com</option>
                                <option value="mail.greenbox-botanic.com"   <?php if (ParamIni("PARAM","MAIL_SMTP","smtp.gmail.com") == "mail.greenbox-botanic.com")  {echo "selected";} ?>  >mail.greenbox-botanic.com</option>
                            </select>
                        </li>
                        <li>
                            <span>Port :</span>
                            <select id="conf_mail_Port" onchange="savParam('MAIL_PORT',this.value);" style="display:inline" >
                                <option value="587"   <?php if (ParamIni("PARAM","MAIL_PORT","35") == "587") {echo "selected";} ?>  >587</option>
                                <option value="26"    <?php if (ParamIni("PARAM","MAIL_PORT","35") == "26")  {echo "selected";} ?>  >26</option>
                            </select>
                        </li>
                        <li>
                            <span>SSL :</span>
                            <select id="conf_mail_SSL" onchange="savParam('MAIL_SSL',this.value);" style="display:inline" >
                                <option value="true"   <?php if (ParamIni("PARAM","MAIL_SSL","false") == "true") {echo "selected";} ?>   >Oui</option>
                                <option value="false"  <?php if (ParamIni("PARAM","MAIL_SSL","false") == "flase") {echo "selected";} ?>  >Non</option>
                            </select>
                        </li>
                    </ul>
                </div>

                <!-- Action de configuration -->
                <div id="conf_action" class="Panel">
                    <ul>
                        <li>
                            <input type="button" value="Mise à jour Bulckyface" onclick="rpi_update('bulckyface');" />
                        </li>
                        <li>
                            <input type="button" value="Mise à jour Bulckypi" onclick="rpi_update('bulckypi');" />
                        </li>
                    </ul>
                </div>                

            </div>
        </nav>

    </body>
</html>