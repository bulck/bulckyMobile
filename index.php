<!DOCTYPE html>

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
    
    // Recherche dans config.php robuste
    // A la place de $a['b']['c'] utiliser : 
    // ConfigPHP($a, 'default', 'b', 'c');
    function ConfigPHP($ar, $default){
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $aritterator = $ar;
        for($i = 2; $i < $numargs; $i++){
            if (isset($aritterator[$arg_list[$i]]) || array_key_exists($arg_list[$i], $aritterator)){
                $aritterator = $aritterator[$arg_list[$i]];
            }else{
                return($default);
            }
        }
        return($aritterator);
    }

    // On vient lire le fichier de param_ini
    $param_ini = parse_ini_file("param.ini",true);

?>


<html>
    <head>

        <title><?php echo ConfigPHP($GLOBALS,"Irrigation",'CONFIG','nom'); ?></title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes">
        
        <link rel="icon" type="image/x-icon" href="img/favicon.ico">
        <meta name="msapplication-TileColor" content="#ffffff" />
        <meta name="msapplication-TileImage" content="img/water_drop_144.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-icon" href="img/water_drop_180.png">
        <link rel="apple-touch-icon" sizes="76x76" href="img/water_drop_76.png">
        <link rel="apple-touch-icon" sizes="120x120" href="img/water_drop_120.png">
        <link rel="apple-touch-icon" sizes="152x152" href="img/water_drop_152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="img/water_drop_180.png">
        <link rel="apple-touch-startup-image" href="img/water_drop_320.png">
        <link rel="icon" href="img/water_drop_32.png" sizes="32x32">
        
        <link type="text/css" href="css/layout.css" rel="stylesheet" />
        <link type="text/css" href="css/large_layout.css" rel="stylesheet" media="(min-width: 900px)" />
        <link type="text/css" href="css/jquery.mmenu.widescreen.css" type="text/css" rel="stylesheet" media="(min-width: 900px)" />

        <!-- Include jQuery.mmenu .css files -->
        <link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery.mmenu.all.css" rel="stylesheet" />
        <link type="text/css" href="css/jquery.mmenu.fullscreen.css" rel="stylesheet" />

        <!-- Include jQuery and the jQuery.mmenu .js files -->
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.mmenu.min.all.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript" src="js/mobile.js"></script>

        <!-- Fire the plugin onDocumentReady -->
        <script type="text/javascript">
            $(function( $ ) {
                $("#menu").mmenu({
                    extensions: [
                        "border-none",
                        "effect-zoom-menu",
                        "effect-zoom-panels",
                        "pageshadow",
                        "theme-dark",
                        "widescreen"
                    ]
                });

                // On charge la conf 
                loadConf();

                // On vient lire la valeur des capteurs 
                readSensors(0);

            });

        </script>
    </head>
    <body>
        <div id="page">
            <div class="header fixed">
                <a href="#menu" id="trigger_menu"></a>
                <p id="texte_info"><?php echo ConfigPHP($GLOBALS,"Irrigation",'CONFIG','nom'); ?></p>
            </div>
            <div class="content" >

            
                <div id="first_view" class="conf_section" style="display:block;" >
                    <p class="title_page">Bienvenu(e) sur l'interface de configuration de <?php echo ConfigPHP($GLOBALS,"Irrigation",'CONFIG','nom'); ?></p>
                    <p>Vous pouvez configurer : </p>
                    <ul>
                        <li><a href="#" onclick='displayBlock("param_conf");' ><i class="fa fa-cogs"></i>Configuration générale</a></li>
                    <?php
                        // On affiche le titre pour les zones
                        foreach ($GLOBALS['IRRIGATION'] as $nom_zone => $zone)
                        {
                            // On affiche un titre pour la cuve
                            $strname = strtoupper(str_replace(" ", "", $nom_zone));
                            ?>
                                <li><a href="#" onclick='displayBlock("cuve_conf_<?php echo $strname ;?>");'><i class="fa fa-database"></i>Cuve</a></li>
                            <?php  
                            
                            // On affiche le titre pour les plateformes
                            foreach ($zone["plateforme"] as $nom_plateforme => $plateforme)
                            {
                                $strname = strtoupper(str_replace(" ", "", $nom_plateforme));
                                ?>
                                    <li><a href="#" onclick='displayBlock("plateforme_conf_<?php echo $strname ;?>");'>PF <?php echo $nom_plateforme ;?></a></li>
                                <?php  
                            }
                        }
                    ?>
                    </ul>
                </div>
                
                <!-- Page de configuration -->
                <div id="param_conf" class="conf_section">
           <p class="title_page">Configuration générale</p>
                    <table class="center" >
                        <tr>
                            <td>Irrigation activée :</td>
                            <td>
                                <input id="IRRIGATION_ACTIF" type="checkbox" class="ios8-switch ios8-switch-lg" onclick="changeVal('PARAM','IRRIGATION_ACTIF',this.checked);" <?php if (ParamIni("PARAM","IRRIGATION_ACTIF","true") == "true") {echo "checked" ;}?> />
                                <label for="IRRIGATION_ACTIF"></label>
                            </td>
                        </tr>
                        <tr>
                            <td>Nettoyage gouteur actif :</td>
                            <td>
                                <input id="NETTOYAGE_GOUTEUR_ACTIF" type="checkbox" class="ios8-switch ios8-switch-lg" onclick="changeVal('PARAM','NETTOYAGE_GOUTEUR_ACTIF',this.checked);" <?php if (ParamIni("PARAM","NETTOYAGE_GOUTEUR_ACTIF","true") == "true") {echo "checked" ;}?> />
                                <label for="NETTOYAGE_GOUTEUR_ACTIF"></label>
                            </td>
                        </tr>
                        <tr>
                            <td>Nettoyage gouteurs :</td>
                            <td>
                                <select id="price-from" onchange="changeVal('PARAM','NETTOYAGE_GOUTEUR',this.value);" style="display:inline" >
                                    <option value="10"   <?php if (ParamIni("PARAM","NETTOYAGE_GOUTEUR","100") == "10")  {echo "selected";} ?>   >1 cycle sur 10</option>
                                    <option value="100"  <?php if (ParamIni("PARAM","NETTOYAGE_GOUTEUR","100") == "100") {echo "selected";} ?>  >1 cycle sur 100</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Supresseur activé :</td>
                            <td>
                                <input id="SURPRESSEUR_ACTIF" type="checkbox" class="ios8-switch ios8-switch-lg" onclick="changeVal('PARAM','SURPRESSEUR_ACTIF',this.checked);" <?php if (ParamIni("PARAM","SURPRESSEUR_ACTIF","true") == "true") {echo "checked" ;}?> />
                                <label for="SURPRESSEUR_ACTIF"></label>
                            </td>
                        </tr>
                    </table>
                    <a href="#" onclick='saveConf();' class="HrefBtnApply" ><i class="btnApply fa fa-arrow-circle-right"></i>Appliquer la configuration</a>
                </div> 

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
                        
                        $capteurNiveau  = $zone['capteur']['niveau_cuve']['numero'];
                        $capteurEC_cuve = $zone['capteur']['EC_cuve']['numero'];
                        
                        ?>
                            <div id="cuve_conf_<?php echo $zoneName ;?>"  class="conf_section">
                                <!--
                                <li>EC : <p style="display:inline">3.15</p></li>
                                <li>Température : <p style="display:inline">25°C</p></li>
                                <li>Humidité : <p style="display:inline">75%</p></li>
                                -->
                                <p class="title_page">Graphique</p>
                                <input type="button" value="&#xf1fe;" onclick='google.charts.setOnLoadCallback(function() {drawSensor("<?php echo $capteurNiveau ; ?>","Niveau <?php echo $nom_zone ;?>","<?php echo $capteurEC_cuve ; ?>","EC","","");});displayBlock("display_graph");' />
                                
                                <p class="title_page">Configuration cuve</p>

                                <p class="title_subpage">Dosage engrais 1</p>
                                <input type="button" value="&#xf146;" onclick='upVal("CUVE", "<?php echo $engrais1 ;?>", -0.1, "ml/min");' />
                                <p id="<?php echo "CUVE_" . $engrais1 ;?>" style="display:inline"><?php echo ParamIni("CUVE",$engrais1,"5");?> ml/min</p> 
                                <input type="button" value="&#xf0fe;" onclick='upVal("CUVE", "<?php echo $engrais1 ;?>", 0.1, "ml/min");' />
                                <p class="title_subpage">Dosage engrais 2</p>
                                <input type="button" value="&#xf146;" onclick='upVal("CUVE", "<?php echo $engrais2 ;?>", -0.1, "ml/min");' />
                                <p id="<?php echo "CUVE_" . $engrais2 ;?>" style="display:inline"><?php echo ParamIni("CUVE",$engrais2,"5");?> ml/min</p> 
                                <input type="button" value="&#xf0fe;" onclick='upVal("CUVE", "<?php echo $engrais2 ;?>", 0.1, "ml/min");' />
                                <p class="title_subpage">Dosage engrais 3</p>
                                <input type="button" value="&#xf146;" onclick='upVal("CUVE", "<?php echo $engrais3 ;?>", -0.1, "ml/min");' />
                                <p id="<?php echo "CUVE_" . $engrais3 ;?>" style="display:inline"><?php echo ParamIni("CUVE",$engrais3,"5");?> ml/min</p> 
                                <input type="button" value="&#xf0fe;" onclick='upVal("CUVE", "<?php echo $engrais3 ;?>", 0.1, "ml/min");' />

                                <p class="title_subpage">Volume injection engrais :</p>
                                <select id="temps_ajout_engrais" >
                                    <option value="3" >1 ml</option>
                                    <option value="12" >5 ml</option>
                                    <option value="24" selected>10 ml</option>
                                    <option value="48" >20 ml</option>
                                    <option value="120" >50 ml</option>
                                    <option value="240" >100 ml</option>
                                </select>
                                <br />
                                <input type="button" value="Injecter engrais 1" onclick='setPlug(document.getElementById("temps_ajout_engrais").value,"on", "<?php echo $zone["prise"]["engrais1"] ;?>", 0);' />
                                <br />
                                <input type="button" value="Injecter engrais 2" onclick='setPlug(document.getElementById("temps_ajout_engrais").value,"on", "<?php echo $zone["prise"]["engrais2"] ;?>", 0);' />
                                <br />
                                <input type="button" value="Injecter engrais 3" onclick='setPlug(document.getElementById("temps_ajout_engrais").value,"on", "<?php echo $zone["prise"]["engrais3"] ;?>", 0);' />
                                <p class="title_subpage">Actions</p>
                                <input type="button" value="Purge de la cuve" onclick='purgeCuve("<?php echo $ZoneIndex ;?>");' />
                                <br />

                                <p class="title_subpage">Configuration de la cuve</p> 
                                <table class="center">
                                    <tr>
                                        <td>Engrais 1 actif : </td>
                                        <td>
                                            <input id="<?php echo $engrais1actif ;?>" type="checkbox" class="ios8-switch ios8-switch-lg" onclick='changeVal("CUVE", "<?php echo $engrais1actif ;?>", this.checked);' <?php if (ParamIni("CUVE",$engrais1actif,"false") == "true") {echo "checked" ;}?> />
                                            <label for="<?php echo $engrais1actif ;?>"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Engrais 2 actif : </td>
                                        <td>
                                            <input id="<?php echo $engrais2actif ;?>" type="checkbox" class="ios8-switch ios8-switch-lg" onclick='changeVal("CUVE", "<?php echo $engrais2actif ;?>", this.checked);' <?php if (ParamIni("CUVE",$engrais2actif,"false") == "true") {echo "checked" ;}?> />
                                            <label for="<?php echo $engrais2actif ;?>"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Engrais 3 actif : </td>
                                        <td>
                                            <input id="<?php echo $engrais3actif ;?>" type="checkbox" class="ios8-switch ios8-switch-lg" onclick='changeVal("CUVE", "<?php echo $engrais3actif ;?>", this.checked);' <?php if (ParamIni("CUVE",$engrais3actif,"false") == "true") {echo "checked" ;}?> />
                                            <label for="<?php echo $engrais3actif ;?>"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Remplissage cuve activé :</td>
                                        <td>
                                            <input id="<?php echo $remplissageActif ;?>" type="checkbox" class="ios8-switch ios8-switch-lg" onclick='changeVal("CUVE", "<?php echo $remplissageActif ;?>", this.checked);' <?php if (ParamIni("CUVE",$remplissageActif,"true") == "true") {echo "checked" ;}?> />
                                            <label for="<?php echo $remplissageActif ;?>"></label>
                                        </td>
                                    </tr>
                                </table>
                                <a href="#" onclick='saveConf();' class="HrefBtnApply" ><i class="btnApply fa fa-arrow-circle-right"></i>Appliquer la configuration</a>
                            </div>
                        <?php
                        $ZoneIndex++;
                    }
                ?>

                <!-- Plateforme parts -->
                <?php
                    foreach ($GLOBALS['IRRIGATION'] as $nom_zone => $zone)
                    {
                        $capteurNiveau  = $zone['capteur']['niveau_cuve']['numero'];
                        // On affiche le titre pour les plateformes
                        foreach ($zone["plateforme"] as $nom_plateforme => $plateforme)
                        {

                            $pfName = strtoupper(str_replace(" ", "", $nom_plateforme));
                            
                            // On calcul le maximum de l/h max 
                            $nbLigne = count($plateforme["ligne"]);
                            $lhMax = round($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'] / $nbLigne , 1);
                            $capteurPressionPompe  = $plateforme['capteur']['pression_pompe']['numero'];
                            ?>
                                <div id="plateforme_conf_<?php echo $pfName ;?>" class="conf_section">
                                    <?php
                                        foreach ($plateforme["ligne"] as $nom_ligne => $ligne) 
                                        {
                                            $ligneName = strtoupper(str_replace(" ", "", $nom_ligne));
                                            
                                            // On intitilialise les valeurs si elles n'existent pas
                                            $matin = $pfName . "_" . $ligneName . "_MATIN";
                                            $amidi = $pfName . "_" . $ligneName . "_APRESMIDI";
                                            $soir = $pfName . "_" . $ligneName . "_SOIR";
                                            $active = $pfName . "_" . $ligneName . "_ACTIVE";
                                            
                                            $capteurPressionLigne = $ligne['capteur']['pression']['numero'];
                                            
                                            ?>

                                                
                                
                                                <p class="title_subpage"></p>
                                                <table class="center" >
                                                    <tr>
                                                        <td colspan="3">Ligne <?php echo $ligneName ;?> (l/h/membrane)</td>
                                                        <td><input type="button" value="&#xf1fe;" onclick='google.charts.setOnLoadCallback(function() {drawSensor("<?php echo $capteurNiveau ; ?>","Niveau <?php echo $nom_zone ; ?>","<?php echo $capteurPressionPompe ; ?>","Pression pompe","<?php echo $capteurPressionLigne ; ?>","Pression ligne <?php echo $ligneName ;?>");});displayBlock("display_graph");' /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Matin :</td>
                                                        <td><input type="button" value="&#xf146;"   onclick='upVal("LIGNE", "<?php echo $matin ;?>", -0.1, "l/h/m", 100);upValTxtLigne ("<?php echo $pfName ;?>" , <?php echo $nbLigne ;?> , "<?php echo $matin ;?>", "<?php echo $GLOBALS['CONFIG']['debit_gouteur'] ;?>" , "<?php echo $GLOBALS['CONFIG']['gouteur_membrane'] ;?>");' /></td>
                                                        <td><p id="<?php echo "LIGNE_" . $matin ;?>" style="display:inline"><?php echo ParamIni("LIGNE",$matin,"1.5") ;?> l/h/m</p></td>
                                                        <td><input type="button" value="&#xf0fe;"   onclick='upVal("LIGNE", "<?php echo $matin ;?>", 0.1, "l/h/m", <?php echo $lhMax ;?>);upValTxtLigne ("<?php echo $pfName ;?>" , <?php echo $nbLigne ;?> , "<?php echo $matin ;?>", "<?php echo $GLOBALS['CONFIG']['debit_gouteur'] ;?>" , "<?php echo $GLOBALS['CONFIG']['gouteur_membrane'] ;?>");' /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Après Midi :</td>
                                                        <td><input type="button" value="&#xf146;"  onclick='upVal("LIGNE", "<?php echo $amidi ;?>", -0.1, "l/h/m", 100);upValTxtLigne ("<?php echo $pfName ;?>" , <?php echo $nbLigne ;?> , "<?php echo $amidi ;?>", "<?php echo $GLOBALS['CONFIG']['debit_gouteur'] ;?>" , "<?php echo $GLOBALS['CONFIG']['gouteur_membrane'] ;?>");' /></td>
                                                        <td><p id="<?php echo "LIGNE_" . $amidi ;?>" style="display:inline"><?php echo ParamIni("LIGNE",$amidi,"1.5");?> l/h/m</p></td>
                                                        <td><input type="button" value="&#xf0fe;" onclick='upVal("LIGNE", "<?php echo $amidi ;?>", 0.1, "l/h/m", <?php echo $lhMax ;?>);upValTxtLigne ("<?php echo $pfName ;?>" , <?php echo $nbLigne ;?> , "<?php echo $amidi ;?>", "<?php echo $GLOBALS['CONFIG']['debit_gouteur'] ;?>" , "<?php echo $GLOBALS['CONFIG']['gouteur_membrane'] ;?>");' /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Soir :</td>
                                                        <td><input type="button" value="&#xf146;"  onclick='upVal("LIGNE", "<?php echo $soir ;?>", -0.1, "l/h/m", 100);upValTxtLigne ("<?php echo $pfName ;?>" , <?php echo $nbLigne ;?> , "<?php echo $soir ;?>", "<?php echo $GLOBALS['CONFIG']['debit_gouteur'] ;?>" , "<?php echo $GLOBALS['CONFIG']['gouteur_membrane'] ;?>");' /></td>
                                                        <td><p id="<?php echo "LIGNE_" . $soir ;?>" style="display:inline"><?php echo ParamIni("LIGNE",$soir,"1.5");?> l/h/m</p></td>
                                                        <td><input type="button" value="&#xf0fe;" onclick='upVal("LIGNE", "<?php echo $soir ;?>", 0.1, "l/h/m", <?php echo $lhMax ;?>);upValTxtLigne ("<?php echo $pfName ;?>" , <?php echo $nbLigne ;?> , "<?php echo $soir ;?>", "<?php echo $GLOBALS['CONFIG']['debit_gouteur'] ;?>" , "<?php echo $GLOBALS['CONFIG']['gouteur_membrane'] ;?>");' /></td>
                                                    </tr>
                                                </table>
                                            <?php
                                        }
                                    ?>
                                    <p class="title_subpage">Configuration des lignes</p>
                                    <table class="center">
                                    <?php
                                    foreach ($plateforme["ligne"] as $nom_ligne => $ligne) 
                                    {
                                        $ligneName = strtoupper(str_replace(" ", "", $nom_ligne));
                                        $active = $pfName . "_" . $ligneName . "_ACTIVE";
                                        ?>
                                            <tr>
                                                <td>Activation ligne <?php echo $ligneName ;?> : </td>
                                                <td>
                                                    <input id="<?php echo $active ;?>" type="checkbox" class="ios8-switch ios8-switch-lg" onclick='changeVal("LIGNE", "<?php echo $active ;?>", this.checked);' <?php if (ParamIni("LIGNE",$active,"true") == "true") {echo "checked" ;}?> />
                                                    <label for="<?php echo $active ;?>"></label>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                    ?>
                                        <tr>
                                            <td>Temps cycle :</td>
                                            <td>
                                                <select id="temps_cycle_<?php echo $pfName ;?>" onchange="changeVal('LIGNE','<?php echo $pfName ;?>_TEMPS_CYCLE',this.value);" style="display:inline" >
                                                    <option value="240"  <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "240")  {echo "selected";} ?> >2 minutes</option>
                                                    <option value="300"  <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "300")  {echo "selected";} ?> >5 minutes</option>
                                                    <option value="600"  <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "600")  {echo "selected";} ?> >10 minutes</option>
                                                    <option value="900"  <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "900")  {echo "selected";} ?> >15 minutes</option>
                                                    <option value="1200" <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "1200") {echo "selected";} ?> >20 minutes</option>
                                                    <option value="3600" <?php if (ParamIni("LIGNE",$pfName . "_TEMPS_CYCLE","300") == "3600") {echo "selected";} ?> >60 minutes</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <a href="#" onclick='saveConf();' class="HrefBtnApply"><i class="btnApply fa fa-arrow-circle-right"></i>Appliquer la configuration</a>
                                    <br />
                                    <p class="title_subpage">Test des lignes</p>
                                    <table class="center" >
                                        <tr>
                                            <td>Temps de test :</td>
                                            <td colspan="2">
                                                <select id="temps_test_cyle" style="display:inline" >
                                                    <option value="30" >30 secondes</option>
                                                    <option value="60" selected>1 minute</option>
                                                    <option value="120" >2 minutes</option>
                                                    <option value="600" >10 minutes</option>
                                                    <option value="86400" >1 journée</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php
                                    foreach ($plateforme["ligne"] as $nom_ligne => $ligne) 
                                    {
                                        $ligneName = strtoupper(str_replace(" ", "", $nom_ligne));
                                        ?>
                                            <tr>
                                                <td>Ligne <?php echo $ligneName ;?> (Pompe + EV) :</td>
                                                <td><input type="button" value="&#xf144;" onclick='setPlug(document.getElementById("temps_test_cyle").value,"on", <?php echo $ligne["prise"] ;?>,<?php echo $plateforme["prise"]["pompe"] ;?>);' /></td>
                                                <td><input type="button" value="&#xf28d;" onclick='setPlug(document.getElementById("temps_test_cyle").value,"off", <?php echo $ligne["prise"] ;?>,<?php echo $plateforme["prise"]["pompe"] ;?>);' /></td>
                                            <tr />
                                        <?php
                                    }
                                    ?>
                                    </table>
                                </div>
                            <?php
                        }
                    }
                ?>

                <!-- Pilotage prise -->
                <div id="debug_pilotage"  class="conf_section">
                    <table class="center">
                        <tr>
                            <td><input id="btn_reload_plug" type="button" value="&#xf0e2;" onclick='readPlugs(0);' /></td>
                            <td colspan="2"><input id="btn_reload_periodic_plug" type="button" value="&#xf021;" onclick='readPlugs(30);' /></td>
                        </tr>
                        <tr>
                            <td>Temps de test :</td>
                            <td colspan="2">
                                <select id="temps_test_cyle_plug" >
                                    <option value="30" >30 secondes</option>
                                    <option value="60" selected>1 minute</option>
                                    <option value="120" >2 minutes</option>
                                    <option value="600" >10 minutes</option>
                                    <option value="1200" >20 minutes</option>
                                    <option value="3600" >1 heure</option>
                                    <option value="86400" >1 journée</option>
                                </select>
                            </td>
                        </tr>
                        <?php 
                            
                            foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {
                                
                                // On ajoute les prises engrais, purge , remplissage
                                foreach ($zone["prise"] as $prise_nom => $numero) {
                                    $outPrise[$numero] = $prise_nom;
                                }
                                foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {
                                    // Pompe 
                                    foreach ($plateforme["prise"] as $prise_nom => $numero) {
                                        $outPrise[$numero] = "PF " . $plateforme_nom . " " . $prise_nom;
                                    }
                                    foreach ($plateforme["ligne"] as $ligne_numero => $ligne) {
                                        $outPrise[$ligne["prise"]] = " EV Ligne " . $ligne_numero;
                                    }
                                }
                            }
                            ksort($outPrise);
                            foreach ($outPrise as $numero => $nom) {
                                ?>
                                <tr>
                                    <td><i id="plug_<?php echo $numero ;?>" class="fa fa-question-circle"></i><?php echo $numero . " : " . $nom ;?> :</td>
                                    <td><input                          type="button" value="&#xf144;"  onclick='setPlug(document.getElementById("temps_test_cyle_plug").value,"on", <?php echo $numero ;?>,0);' /></td>
                                    <td><input style="float: right;" type="button" value="&#xf28d;" onclick='setPlug(document.getElementById("temps_test_cyle_plug").value,"off", <?php echo $numero ;?>,0);' /></td>
                                <tr>
                                <?php 
                            }
                        ?>
                    </table>
                </div>

                <!-- Valeur des capteurs -->
                <div id="sensors"  class="conf_section" >
                    <table class="center">
                        <tr>
                            <td colspan="2"><input id="btn_reload_sensor" type="button" value="&#xf0e2;" onclick='readSensors(0);' /></td>
                            <td colspan="2"><input id="btn_reload_periodic_sensor" type="button" value="&#xf021;" onclick='readSensors(30);' /></td>
                        </tr>
                        <tr>
                            <th>Numéro</th><th>Nom</th><th colspan="2">Valeur</th>
                        </tr>

                    <?php 
                        foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {
                            foreach ($zone["capteur"] as $capteur_nom => $capteur) {
                                $outCapteur[$capteur["numero"]] = $capteur_nom;
                            }
                            foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {
                                foreach ($plateforme["capteur"] as $capteur_nom => $capteur) {
                                    $outCapteur[$capteur["numero"]] = $capteur_nom;
                                }
                                foreach ($plateforme["ligne"] as $ligne_numero => $ligne) {
                                    
                                    // On ajoute un détecteur de pression par ligne
                                    foreach ($ligne["capteur"] as $capteur_nom => $capteur) {
                                        $outCapteur[$capteur["numero"]] = $capteur_nom . " ligne " . $ligne_numero ;
                                    }
                                }
                            }
                        }
                        ksort($outCapteur);
                        foreach ($outCapteur as $numero => $nom) {
                            ?>
                            <tr>
                                <td>Capteur <?php echo $numero;?></td>
                                <td><?php echo $nom ;?></td>
                                <td id="sensor_<?php echo $numero ;?>"></td>
                                <td><input style="float: right;" type="button" value="&#xf1fe;" onclick='google.charts.setOnLoadCallback(function() {drawSensor("<?php echo $numero ; ?>","<?php echo $nom ;?>","","","","");});displayBlock("display_graph");' /></td>
                            </tr>
                            <?php 
                        }
                        
                    ?>
                    </table>
                </div>
                
                <div id="display_graph"  class="conf_section">
                    <div id="chart_div"></div>
                </div>
                
                <!-- subpanel for verbose -->
                <div id="param_verbose"  class="conf_section">
                    <p class="title_page">Configuration de la verbosité</p>
                    <table class="center"> 
                        <tr>
                            <td>Verbose Server :</td>
                            <td>
                                <select id="verbose_server" onchange="changeVal('PARAM','VERBOSE_SERVER',this.value);" style="display:inline" >
                                    <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "debug") {echo "selected";} ?>   >debug</option>
                                    <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "info") {echo "selected";} ?>    >info</option>
                                    <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "warning") {echo "selected";} ?> >warning</option>
                                    <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_SERVER","info") == "error") {echo "selected";} ?>   >error</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Verbose Sensor :</td>
                            <td>
                                <select id="verbose_acqsensor" onchange="changeVal('PARAM','VERBOSE_ACQSENSOR',this.value);" style="display:inline" >
                                    <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "debug")   {echo "selected";} ?>   >debug</option>
                                    <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "info")    {echo "selected";} ?>    >info</option>
                                    <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "warning") {echo "selected";} ?> >warning</option>
                                    <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_ACQSENSOR","info") == "error")   {echo "selected";} ?>   >error</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Verbose Plug :</td>
                            <td>
                                <select id="verbose_plug" onchange="changeVal('PARAM','VERBOSE_PLUG',this.value);" style="display:inline" >
                                    <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "debug") {echo "selected";} ?>   >debug</option>
                                    <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "info") {echo "selected";} ?>    >info</option>
                                    <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "warning") {echo "selected";} ?> >warning</option>
                                    <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_PLUG","info") == "error") {echo "selected";} ?>   >error</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Verbose SLF :</td>
                            <td>
                                <select id="verbose_SLF" onchange="changeVal('PARAM','VERBOSE_SLF',this.value);" style="display:inline" >
                                    <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "debug")   {echo "selected";} ?>   >debug</option>
                                    <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "info")    {echo "selected";} ?>    >info</option>
                                    <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "warning") {echo "selected";} ?> >warning</option>
                                    <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_SLF","debug") == "error")   {echo "selected";} ?>   >error</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Verbose Histo :</td>
                            <td>
                                <select id="verbose_SLF" onchange="changeVal('PARAM','VERBOSE_HISTO',this.value);" style="display:inline" >
                                    <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_HISTO","info") == "debug")  {echo "selected";} ?>  >debug</option>
                                    <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_HISTO","info") == "info")   {echo "selected";} ?>  >info</option>
                                    <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_HISTO","info") == "warning") {echo "selected";} ?> >warning</option>
                                    <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_HISTO","info") == "error")  {echo "selected";} ?>  >error</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Verbose Mail :</td>
                            <td>
                                <select id="verbose_SLF" onchange="changeVal('PARAM','VERBOSE_MAIL',this.value);" style="display:inline" >
                                    <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_MAIL","info") == "debug")   {echo "selected";} ?> >debug</option>
                                    <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_MAIL","info") == "info")    {echo "selected";} ?> >info</option>
                                    <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_MAIL","info") == "warning") {echo "selected";} ?> >warning</option>
                                    <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_MAIL","info") == "error")   {echo "selected";} ?> >error</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Verbose Supervision :</td>
                            <td>
                                <select id="verbose_SLF" onchange="changeVal('PARAM','VERBOSE_SUPERVISION',this.value);" style="display:inline" >
                                    <option value="debug"   <?php if (ParamIni("PARAM","VERBOSE_SUPERVISION","info") == "debug")   {echo "selected";} ?> >debug</option>
                                    <option value="info"    <?php if (ParamIni("PARAM","VERBOSE_SUPERVISION","info") == "info")    {echo "selected";} ?> >info</option>
                                    <option value="warning" <?php if (ParamIni("PARAM","VERBOSE_SUPERVISION","info") == "warning") {echo "selected";} ?> >warning</option>
                                    <option value="error"   <?php if (ParamIni("PARAM","VERBOSE_SUPERVISION","info") == "error")   {echo "selected";} ?> >error</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                    
                <!-- Action de configuration -->
                <div id="conf_action" class="conf_section">
            <p class="title_page">Actions de configuration</p>
                    <input type="button" value="Mise à jour Bulckyface" onclick="rpi_update('bulckyface');" />
                    <br />
                    <input type="button" value="Mise à jour Bulckypi" onclick="rpi_update('bulckypi');" />

                    <p class="title_page">Appliquer une conf template</p>
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
                </div>
                
                <!-- Configuration des mails -->
                <div id="conf_mail" class="conf_section">
                    <p class="title_page">Configuration des mails</p>
                    <span>Nom d'utilisateur :</span>
                    <input type="text" id="conf_mail_username" ><br />
                    <input type="button" value="Sauvegarder utilisateur" onclick="changeVal('PARAM','MAIL_USERNAME',document.getElementById('conf_mail_username').value);" />
                    <br /><br />
                    <span>Mot de passe :</span>
                    <input type="password" id="conf_mail_password" ><br />
                    <input type="button" value="Sauvegarder MDP" onclick="changeVal('PARAM','MAIL_PASSWORD',document.getElementById('conf_mail_password').value);" />
                    <br /><br />
                    <span>Server SMTP :</span>
                    <select id="conf_mail_Port" onchange="changeVal('PARAM','MAIL_SMTP',this.value);" style="display:inline" >
                        <option value="smtp.gmail.com"              <?php if (ParamIni("PARAM","MAIL_SMTP","smtp.gmail.com") == "smtp.gmail.com")             {echo "selected";} ?>  >smtp.gmail.com</option>
                        <option value="mail.greenbox-botanic.com"   <?php if (ParamIni("PARAM","MAIL_SMTP","smtp.gmail.com") == "mail.greenbox-botanic.com")  {echo "selected";} ?>  >mail.greenbox-botanic.com</option>
                    </select>
                    <br />
                    <span>Port :</span>
                    <select id="conf_mail_Port" onchange="changeVal('PARAM','MAIL_PORT',this.value);" style="display:inline" >
                        <option value="587"   <?php if (ParamIni("PARAM","MAIL_PORT","35") == "587") {echo "selected";} ?>  >587</option>
                        <option value="26"    <?php if (ParamIni("PARAM","MAIL_PORT","35") == "26")  {echo "selected";} ?>  >26</option>
                    </select>
                    <br />
                    <span>SSL :</span>
                    <select id="conf_mail_SSL" onchange="changeVal('PARAM','MAIL_SSL',this.value);" style="display:inline" >
                        <option value="true"   <?php if (ParamIni("PARAM","MAIL_SSL","false") == "true") {echo "selected";} ?>   >Oui</option>
                        <option value="false"  <?php if (ParamIni("PARAM","MAIL_SSL","false") == "flase") {echo "selected";} ?>  >Non</option>
                    </select>
                </div>
                
            </div>
        </div>
        
        <!-- The menu -->
        <nav id="menu">

            <div id="app">
                <ul>
                    <li><label>Configuration</label></li>
                    <li><a href="#" onclick='saveConf();' class="HrefBtnApply"><i class="btnApply fa fa-arrow-circle-right"></i>Appliquer la configuration</a></li>
                    <li><a href="#" onclick='displayBlock("param_conf");' ><i class="fa fa-cogs"></i>Configuration générale</a></li>
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
                                <li><a href="#" onclick='displayBlock("cuve_conf_<?php echo $strname ;?>");'><i class="fa fa-database"></i>Cuve</a></li>
                            <?php  
                            
                            // On affiche le titre pour les plateformes
                            foreach ($zone["plateforme"] as $nom_plateforme => $plateforme)
                            {
                                $strname = strtoupper(str_replace(" ", "", $nom_plateforme));
                                ?>
                                    <li><a href="#" onclick='displayBlock("plateforme_conf_<?php echo $strname ;?>");'>PF <?php echo $nom_plateforme ;?></a></li>
                                <?php  
                            }
                        }
                    ?>
                    <li><label>Debug</label></li>
                    <li><a href="#" onclick='displayBlock("debug_pilotage");' ><i class="fa fa-power-off"></i>Pilotage</a></li>
                    <li><a href="#" onclick='displayBlock("sensors");' ><i class="fa fa-tachometer"></i>Capteurs</a></li>
                    <li><a href="#param_debug" class="mm-arrow"><i class="fa fa-file-code-o"></i>Paramètres avancées</a></li>
                </ul>

                <!-- subpanel for debug -->
                <div id="param_debug"  class="Panel">
                    <ul>
                        <li><a href="#" onclick='displayBlock("param_verbose");' ><i class="fa fa-sort-amount-asc"></i>Verbose</a></li>
                        <li><a href="#" onclick='displayBlock("conf_mail");'     ><i class="fa fa-envelope-o"></i>Mail</a></li>
                        <li><a href="#" onclick='displayBlock("conf_action");'   ><i class="fa fa-terminal"></i>Action</a></li>
                        <li><a href="#" onclick='saveConf();' class="HrefBtnApply"><i class="btnApply fa fa-arrow-circle-right"></i>Appliquer la configuration</a></li>
                    </ul>
                </div>

            </div>
        </nav>
    </body>
</html>
