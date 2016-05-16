<?php 

require_once 'config.php';

//Affiche la valeur d'une variable PHP, script appelé par Ajax par le fichier cultibox.js pour récupérer des informations
// stockées côté serveur:

$error=array();


function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = ""; 
    if ($has_sections) { 
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else if($elem2=="") $content .= $key2." = \n"; 
                else $content .= $key2." = \"".$elem2."\"\n"; 
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else if($elem=="") $content .= $key." = \n"; 
            else $content .= $key." = \"".$elem."\"\n"; 
        } 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    }

    $success = fwrite($handle, $content);
    fclose($handle); 

    return $success; 
}

// {{{ create_conf_XML()
// ROLE Used to creat a conf file
// IN      $file        Path for the conf file
// IN      $paramList       List of params
// IN      $tag         tag for start and end of the configuration
// RET true if we can, false else
function create_conf_XML($file, $paramList,$tag="conf") {

    // Check if directory exists
    if(!is_dir(dirname($file)))
        mkdir(dirname($file));

    // Open in write mode
    $fid = fopen($file,"w+");
    
    // Add header
    fwrite($fid,'<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>' . "\r\n");
    fwrite($fid,"<${tag}>". "\r\n");
    
    // Foreach param to write, add it to the file
    foreach ($paramList as $elemOfArray) {
        
        $str = "    <item ";
        
        foreach ($elemOfArray as $key => $value) {
            $str .= $key . '="' . $value . '" ';
        }
        
        $str .= "/>". "\r\n";
    
        fwrite($fid,$str);
    }

    // Add Footer
    fwrite($fid,"</${tag}>". "\r\n");
    
    // Close file
    fclose($fid);
    
    return true;
}
// }}}


function create_plugConf($dir, $nbPlug) {

    // Check if directory exists
    if(!is_dir(dirname($dir)))
        mkdir(dirname($dir));
    if(!is_dir(dirname($dir . "/plg")))
        mkdir(dirname($dir . "/plg"));
    if(!is_dir(dirname($dir . "/prg")))
        mkdir(dirname($dir . "/prg"));

    // Open in write mode
    $fidPlgA = fopen($dir . "/plg/pluga" ,"w+");
    
    // Add header
    fwrite($fidPlgA,$nbPlug . "\r\n");
    for ($i = 1; $i <= $nbPlug; $i++) {
        fwrite($fidPlgA,(3000 + ($i - 1) % 8 + intval(($i - 1) / 8) * 10) . "\r\n");
        
        // On cré le fichier de conf associé
        $fid = fopen($dir . "/plg/plug" . str_pad($i, 2, '0', STR_PAD_LEFT) ,"w+");
        
        fwrite($fid,"REG:N+000". "\r\n");
        fwrite($fid,"SEC:N+0000". "\r\n");
        fwrite($fid,"SEN:M100000". "\r\n");
        fwrite($fid,"STOL:000". "\r\n");
        
        fclose($fid);
    }

    // Close file
    fclose($fidPlgA);
    
    return true;
}
// }}}


function forcePlug($number,$time,$value) {

    $return_array = array();

	$err = 0;
	$return_array["status"] = "";
    try {
        switch(php_uname('s')) {
            case 'Windows NT':
                $return_array["status"] = exec('C:\Tcl\bin\tclsh.exe "C:\cultibox\bulckyCore\bulckyPi\getCommand.tcl" serverPlugUpdate localhost setGetRepere ' . $number . ' ' . $value . ' ' . $time,$ret,$err);
                break;
            default : 
                $return_array["status"] = exec('tclsh "/opt/bulckypi/bulckyPi/getCommand.tcl" serverPlugUpdate localhost setGetRepere ' . $number . ' ' . $value . ' ' . $time,$ret,$err);
                break;
        }
    } catch (Exception $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
        $return_array["status"] = $e->getMessage();
    }
	
	if ($err != 0 && $return_array["status"] == "") {
		$return_array["status"] = "Erreur pilotage prise " . $number;
	}

    return $return_array;
}

function xcopy($src, $dest) {
    if (!file_exists($dest)) {
        mkdir($dest);
    }
    foreach (scandir($src) as $file) {
        $srcfile = rtrim($src, '/') .'/'. $file;
        $destfile = rtrim($dest, '/') .'/'. $file;
        if (!is_readable($srcfile)) { continue; }
        if ($file != '.' && $file != '..') {
            if (is_dir($srcfile)) {
                if (!file_exists($destfile)) {
                    mkdir($destfile);
                }
                xcopy($srcfile, $destfile);
            } else {
                copy($srcfile, $destfile);
            }
        }
    }
}

// Cette fonction lit de façon robuste dans param.ini
function readInIni ($arr, $first, $second, $default) {
    
    if (array_key_exists($first, $arr)) {
        
        if (array_key_exists($second, $arr[$first])) {
            return $arr[$first][$second];
        } else {
            echo "param.ini ne contient pas $first -> $second (Défaut $default) " . "\r\n";
            return $default;
        }
        
    } else {
        echo "param.ini ne contient pas $first (Défaut $default) " . "\r\n";
        return $default;
    }
    
    
}

function generateConf ($path, $pathTmp, $userVar) {

    // Crée le reperoire temporaire
    $pathTemporaire = $pathTmp . "/conf_tmp" ;
    //$newPath = $path . "/test-cnf" ;
    
    if (!is_dir($pathTemporaire))   mkdir($pathTemporaire);
    
    // On change les parametres pour le lancement des modules
    $paramListCultipiStart[] = array ( 
        'name' => "serverLog",
        'waitAfterUS' => "1000",
        'pathexe' => "tclsh",
        'path' => "./serverLog/serverLog.tcl",
        'xmlconf' => "./serverLog/conf.xml",
    );
    $paramListCultipiStart[] = array ( 
        'name' => "serverAcqSensorV2",
        'waitAfterUS' => "100",
        'pathexe' => "tclsh",
        'path' => "./serverAcqSensorV2/serverAcqSensorV2.tcl",
        'xmlconf' => "./serverAcqSensorV2/conf.xml",
    );
    $paramListCultipiStart[] = array ( 
        'name' => "serverPlugUpdate",
        'waitAfterUS' => "100",
        'pathexe' => "tclsh",
        'path' => "./serverPlugUpdate/serverPlugUpdate.tcl",
        'xmlconf' => "./serverPlugUpdate/conf.xml",
    );
    $paramListCultipiStart[] = array ( 
        'name' => "serverMail",
        'waitAfterUS' => "100",
        'pathexe' => "tclsh",
        'path' => "./serverMail/serverMail.tcl",
        'xmlconf' => "./serverMail/conf.xml",
    );
    $irrigationActive = readInIni($userVar, 'PARAM', 'IRRIGATION_ACTIF' , "false");
    if ($irrigationActive != "false") {
        $paramListCultipiStart[] = array ( 
            'name' => "serverSupervision",
            'waitAfterUS' => "100",
            'pathexe' => "tclsh",
            'path' => "./serverSupervision/serverSupervision.tcl",
            'xmlconf' => "./serverSupervision/conf.xml",
        );
        
        $paramListCultipiStart[] = array ( 
            'name' => "serverSLF",
            'waitAfterUS' => "100",
            'pathexe' => "tclsh",
            'path' => "./serverSLF/serverSLF.tcl",
            'xmlconf' => "./serverSLF/conf.xml",
        );    
    }
    $paramListCultipiStart[] = array ( 
        'name' => "serverHisto",
        'waitAfterUS' => "1000",
        'pathexe' => "tclsh",
        'path' => "./serverHisto/serverHisto.tcl",
        'xmlconf' => "./serverHisto/conf.xml",
    );
   
    $paramListCultipiConf[] = array (
        "key" => "verbose",
        "level" => "warning"
    );
   
    if (!is_dir($pathTemporaire . "/bulckyPi")) mkdir($pathTemporaire . "/bulckyPi");

    create_conf_XML($pathTemporaire . "/bulckyPi/start.xml" , $paramListCultipiStart,"starts");
    create_conf_XML($pathTemporaire . "/bulckyPi/conf.xml" , $paramListCultipiConf);

    /*************************  Prise ***********************************/
    // On cherche le nombre de prise 
    $prisemax = 0;
    foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {
        
        // On ajoute les prises engrais, purge , remplissage
        foreach ($zone["prise"] as $prise_nom => $numero) {
            if ($numero > $prisemax) {
                $prisemax = $numero;
            }
        }
        foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {
            foreach ($plateforme["prise"] as $prise_nom => $numero) {
                if ($numero > $prisemax) {
                    $prisemax = $numero;
                }
            }
            foreach ($plateforme["ligne"] as $ligne_numero => $ligne) {
                if ($ligne["prise"] > $prisemax) {
                    $prisemax = $ligne["prise"];
                }
            }
        }
    }

    // Création des répertoires
    if (!is_dir($pathTemporaire . "/serverPlugUpdate")) mkdir($pathTemporaire . "/serverPlugUpdate");
    if (!is_dir($pathTemporaire . "/serverPlugUpdate/plg")) mkdir($pathTemporaire . "/serverPlugUpdate/plg");
    if (!is_dir($pathTemporaire . "/serverPlugUpdate/prg")) mkdir($pathTemporaire . "/serverPlugUpdate/prg");
    
    // Création du fichier pluga 
    create_plugConf($pathTemporaire . "/serverPlugUpdate" , $prisemax);
    
    // Création du fichier de conf
    // Add trace level
    $paramServerPlugUpdate = array (
        array (
            "key" => "verbose",
            "level" => "info"
        ),
        array (
            "key" => "wireless_freq_plug_update",
            "value" => "1"
        ),
        array (
            "key" => "alarm_activ",
            "value" => "0000"
        ),
        array (
            "key" => "alarm_value",
            "value" => "6000"
        ),
        array (
            "key" => "alarm_sensor",
            "value" => "T"
        ),
        array (
            "key" => "alarm_sens",
            "value" => "+"
        ),
        array (
            "key" => "programm_activ",
            "value" => "off"
        )
    );
    create_conf_XML($pathTemporaire . "/serverPlugUpdate/conf.xml" , $paramServerPlugUpdate);
    
    /*************************  serverSLF ***********************************/
    // On change les parametres pour le server irrigation 
    if (!is_dir($pathTemporaire . "/serverSLF")) {
        mkdir($pathTemporaire . "/serverSLF");
    }
    // Add trace level
    $paramServerSLFXML[] = array (
        "key" => "verbose",
        "level" => readInIni($userVar, 'PARAM', 'VERBOSE_SLF', 'debug')
    );
    
    // Add every parameters of the database
    $paramServerSLFXML[] = array (
        "key" => "surpresseur,ip" ,
        "value" => $GLOBALS['SURPRESSEUR']['IP']
    );
    $paramServerSLFXML[] = array (
        "key" => "surpresseur,prise" ,
        "value" => $GLOBALS['SURPRESSEUR']['prise']
    );
    
    $paramServerSLFXML[] = array (
        "key" => "surpresseur,actif" ,
        "value" => readInIni($userVar, 'PARAM', 'SURPRESSEUR_ACTIF' , "false")
    ); 
    
    $paramServerSLFXML[] = array (
        "key" => "nbzone" ,
        "value" => count($GLOBALS['IRRIGATION'])
    );    
    
    $paramServerSLFXML[] = array (
        "key" => "nettoyage" ,
        "value" => readInIni($userVar, 'PARAM', 'NETTOYAGE_GOUTEUR' , "false")
    );  
    
    $paramServerSLFXML[] = array (
        "key" => "nettoyageactif" ,
        "value" => readInIni($userVar, 'PARAM', 'NETTOYAGE_GOUTEUR_ACTIF' , "false")
    ); 

    $ZoneIndex = 0;

    foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {

        // Parametres des zones 
        $Zone_nom_upper = str_replace(" ", "", strtoupper($zone_nom));
        
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",name" ,
            "value" => "ZONE " . $zone_nom
        );
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",ip" ,
            "value" => $zone["parametres"]["IP"]
        );
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",capteur,niveau" ,
            "value" => $zone["capteur"]["niveau_cuve"]["numero"]
        );
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",nbplateforme" ,
            "value" => count($zone["plateforme"])
        );
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",nbsensor" ,
            "value" => array_count_key($zone, 'capteur')
        );
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",prise,remplissagecuve" ,
            "value" => $zone["prise"]["remplissage"]
        );
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",prise,purge" ,
            "value" => $zone["prise"]["purge"]
        );
        
        for ($i = 1 ; $i < 4 ; $i++) {
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",engrais," . $i . ",temps" ,
                "value" => readInIni($userVar, 'CUVE', $Zone_nom_upper . '_ENGRAIS_' . $i , 0)
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",engrais," . $i . ",actif" ,
                "value" => readInIni($userVar, 'CUVE', $Zone_nom_upper . '_ENGRAIS_ACTIF_' . $i , "false")
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",engrais," . $i . ",prise" ,
                "value" => $zone["prise"]["engrais" . $i]
            );
        }
        // On indique si le remplissage doit être fait 
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",remplissage,actif" ,
            "value" => readInIni($userVar, 'CUVE', $Zone_nom_upper . '_REMPLISSAGE_ACTIF' , "true")
        );

        $PFIndex = 0;
        
        foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {

            
            $PF_nom_upper = strtoupper($plateforme_nom);
            
            $ligneIndex = 0;
            
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",name" ,
                "value" => "PF " . $plateforme_nom
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ip" ,
                "value" => $zone["parametres"]["IP"]
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",nbligne" ,
                "value" => count($plateforme["ligne"])
            );
            $tempsCycle = readInIni($userVar, 'LIGNE', $PF_nom_upper . '_TEMPS_CYCLE', 300);
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",tempscycle" ,
                "value" => $tempsCycle
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",pompe,prise" ,
                "value" => $plateforme["prise"]["pompe"]
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",eauclaire,prise" ,
                "value" => $plateforme["prise"]["EV_eauclaire"]
            );

            foreach ($plateforme["ligne"] as $ligne_numero => $ligne) {

                $paramServerSLFXML[] = array (
                    "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",name" ,
                    "value" => "Ligne " . $ligne_numero
                );
                $paramServerSLFXML[] = array (
                    "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",prise" ,
                    "value" => $ligne["prise"]
                );
                // On calcul le nombre de l/h : Gouteur 4 l/h --> 2 / membranes --> max 8 l/h (divisé par le nombre de ligne )
                // ((nb L/h/membrane) / (nb lmax/h/membrane)) * tmpsCycle
                $debitMatin  = readInIni($userVar, 'LIGNE', $PF_nom_upper . '_' . $ligne_numero . '_MATIN', 1.2);
                $tmpsOnMatin = round(($debitMatin / ($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'])) * $tempsCycle);
                $debitAMidi  = readInIni($userVar, 'LIGNE', $PF_nom_upper . '_' . $ligne_numero . '_APRESMIDI', 1.2);
                $tmpsOnAMidi = round(($debitAMidi / ($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'])) * $tempsCycle);
                $debitNuit   = readInIni($userVar, 'LIGNE', $PF_nom_upper . '_' . $ligne_numero . '_SOIR', 1.2);
                $tmpsOnNuit  = round(($debitNuit  / ($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'])) * $tempsCycle);
                
                $paramServerSLFXML[] = array (
                    "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",tempsOn,matin" ,
                    "value" => $tmpsOnMatin
                );
                $paramServerSLFXML[] = array (
                    "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",tempsOn,apresmidi" ,
                    "value" => $tmpsOnAMidi
                );
                $paramServerSLFXML[] = array (
                    "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",tempsOn,nuit" ,
                    "value" => $tmpsOnNuit
                );
                
                $paramServerSLFXML[] = array (
                    "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",active" ,
                    "value" => readInIni($userVar, 'LIGNE', $PF_nom_upper . '_' . $ligne_numero . '_ACTIVE' , "false")
                );
                
                // On sauvegarde le nombre de cycle (utilisé pour stocker le nombre d'arrosage et pour déterminer l'ordre de nettoyage) 
                $paramServerSLFXML[] = array (
                    "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",nbCycle" ,
                    "value" => $PFIndex * 4 + $ligneIndex
                );                
                
                $ligneIndex++; 
            }
            
            $PFIndex++;

        }     

        $ZoneIndex++;
    }

    // Save it
    create_conf_XML($pathTemporaire . "/serverSLF/conf.xml" , $paramServerSLFXML);
    
    
    /*************************  serverAcqSensorV2 ***********************************/
    // On cré la conf pour les capteurs 
    if (!is_dir($pathTemporaire . "/serverAcqSensorV2")) {
        mkdir($pathTemporaire . "/serverAcqSensorV2");
    }


    foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {

        // On cré un fichier par zone 
        $IP = $zone["parametres"]["IP"];
        
       // Add trace level
        $paramServerAcqSensor[] = array (
            "key" => "verbose",
            "level" => readInIni($userVar, 'PARAM','VERBOSE_ACQSENSOR' , "info")
        );

        $paramServerAcqSensor[] = array (
            "key" => "simulator" ,
            "value" => "off"
        );
        
        $paramServerAcqSensor[] = array (
            "key" => "auto_search" ,
            "value" => "off"
        );
    
        $nbSensor = 0 ;
        // Pour chaque zone , on enregistre les capteurs 
        foreach ($zone["capteur"] as $capteur_nom => $capteur) {

            $nbSensor++;
            $numCapteur = $capteur["numero"];
            
            $paramServerAcqSensor[] = array (
                "key" => "sensor," . $numCapteur . ",nom" ,
                "value" => $capteur_nom
            );
            
            $paramServerAcqSensor[] = array (
                "key" => "sensor," . $numCapteur . ",type" ,
                "value" => $capteur["type"]
            );

            $paramServerAcqSensor[] = array (
                "key" => "sensor," . $numCapteur . ",index" ,
                "value" => $capteur["index"]
            );
            
            if ($capteur["type"] == "MCP230XX") {
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",nbinput" ,
                    "value" => $capteur["nbinput"]
                );
                
                for ($i = 1 ; $i <= $capteur["nbinput"] ; $i++) {
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",input," . $i ,
                        "value" => $capteur["input," . $i]
                    ); 
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",value," . $i ,
                        "value" => $capteur["value," . $i]
                    ); 
                }
            }

            if ($capteur["type"] == "ADS1015") {
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",input" ,
                    "value" => $capteur["input"]
                );
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",min" ,
                    "value" => $capteur["min"]
                );
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",max" ,
                    "value" => $capteur["max"]
                );
            }
            
            if ($capteur["type"] == "EC") {
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",comPort" ,
                    "value" => $capteur["comPort"]
                );
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",version" ,
                    "value" => $capteur["version"]
                );
            }
            
        }

        foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {

            foreach ($plateforme["capteur"] as $capteur_nom => $capteur) {
            
                $nbSensor++;
                $numCapteur = $capteur["numero"];
                
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",nom" ,
                    "value" => $capteur_nom
                );
                
                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",type" ,
                    "value" => $capteur["type"]
                );

                $paramServerAcqSensor[] = array (
                    "key" => "sensor," . $numCapteur . ",index" ,
                    "value" => $capteur["index"]
                );
                
                if ($capteur["type"] == "MCP230XX") {
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",nbinput" ,
                        "value" => $capteur["nbinput"]
                    );
                    
                    for ($i = 1 ; $i <= $capteur["nbinput"] ; $i++) {
                        $paramServerAcqSensor[] = array (
                            "key" => "sensor," . $numCapteur . ",input," . $i ,
                            "value" => $capteur["input," . $i]
                        ); 
                        $paramServerAcqSensor[] = array (
                            "key" => "sensor," . $numCapteur . ",value," . $i ,
                            "value" => $capteur["value," . $i]
                        ); 
                    }
                }

                if ($capteur["type"] == "ADS1015") {
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",input" ,
                        "value" => $capteur["input"]
                    );
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",min" ,
                        "value" => $capteur["min"]
                    );
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",max" ,
                        "value" => $capteur["max"]
                    );
                }
            }

            
            foreach ($plateforme["ligne"] as $ligne_numero => $ligne) {
                
                // On ajoute un détecteur de pression par ligne
                foreach ($ligne["capteur"] as $capteur_nom => $capteur) {

                    $nbSensor++;
                    $numCapteur = $capteur["numero"];
                    
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",nom" ,
                        "value" => $capteur_nom
                    );
                    
                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",type" ,
                        "value" => $capteur["type"]
                    );

                    $paramServerAcqSensor[] = array (
                        "key" => "sensor," . $numCapteur . ",index" ,
                        "value" => $capteur["index"]
                    );
                    
                    if ($capteur["type"] == "MCP230XX") {
                        $paramServerAcqSensor[] = array (
                            "key" => "sensor," . $numCapteur . ",nbinput" ,
                            "value" => $capteur["nbinput"]
                        );
                        
                        for ($i = 1 ; $i <= $capteur["nbinput"] ; $i++) {
                            $paramServerAcqSensor[] = array (
                                "key" => "sensor," . $numCapteur . ",input," . $i ,
                                "value" => $capteur["input," . $i]
                            ); 
                            $paramServerAcqSensor[] = array (
                                "key" => "sensor," . $numCapteur . ",value," . $i ,
                                "value" => $capteur["value," . $i]
                            ); 
                        }
                    }

                    if ($capteur["type"] == "ADS1015") {
                        $paramServerAcqSensor[] = array (
                            "key" => "sensor," . $numCapteur . ",input" ,
                            "value" => $capteur["input"]
                        );
                        $paramServerAcqSensor[] = array (
                            "key" => "sensor," . $numCapteur . ",min" ,
                            "value" => $capteur["min"]
                        );
                        $paramServerAcqSensor[] = array (
                            "key" => "sensor," . $numCapteur . ",max" ,
                            "value" => $capteur["max"]
                        );
                    }
                }
            }
        }
        
        $paramServerAcqSensor[] = array (
            "key" => "nbSensor" ,
            "value" => $nbSensor
        );
        
        // On sauvegarde 
        $arraToSave[$IP] = $paramServerAcqSensor;
        
        
        
        unset($paramServerAcqSensor);
    }
    
    foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {
        
        $IP = $zone["parametres"]["IP"];
        
        if ($IP == "localhost") {
            $extension = "";
        } else {
            $extension = "_" . $IP;
        }
        
        create_conf_XML($pathTemporaire . "/serverAcqSensorV2/conf" . $extension . ".xml" , $arraToSave[$IP]);
        
    }
    
    /*************************  serverHisto ***********************************/
    // On cré la conf pour les capteurs 
    if (!is_dir($pathTemporaire . "/serverHisto")) {
        mkdir($pathTemporaire . "/serverHisto");
    }
    // Add trace level
    $paramServerHisto[] = array (
        "key" => "verbose",
        "level" => readInIni($userVar, 'PARAM','VERBOSE_HISTO' , "warning")
    );
    $paramServerHisto[] = array (
        "key" => "logPeriode",
        "value" => "10"
    );
    $paramServerHisto[] = array (
        "key" => "pathMySQL",
        "value" => "/usr/bin/mysql"
    );   
    create_conf_XML($pathTemporaire . "/serverHisto/conf.xml" , $paramServerHisto);
    
    /*************************  serverLog ***********************************/
    // On cré la conf pour les capteurs 
    if (!is_dir($pathTemporaire . "/serverLog")) mkdir($pathTemporaire . "/serverLog");
    
    // Add trace level
    switch(php_uname('s')) {
        case 'Windows NT':
            $paramServerLog[] = array (
                "key" => "logPath",
                "level" => "C:/cultibox/"
            );
            break;
        default : 
            $paramServerLog[] = array (
                "key" => "logPath",
                "level" => "/var/log/cultipi"
            );
            
            break;
    }

    $paramServerLog[] = array (
        "key" => "verbose",
        "value" => "warning"
    );
    create_conf_XML($pathTemporaire . "/serverLog/conf.xml" , $paramServerLog);
    
    /*************************  serverMail ***********************************/
    // On cré la conf pour les capteurs 
    if (!is_dir($pathTemporaire . "/serverMail")) mkdir($pathTemporaire . "/serverMail");
    
    // Add trace level
    $paramServerMail = array (
        array (
            "key" => "verbose",
            "level" => readInIni($userVar, 'PARAM','VERBOSE_MAIL' , "info")
        ),
        array (
            "key" => "serverSMTP",
            "value" => readInIni($userVar, 'PARAM', 'MAIL_SMTP', "mail.greenbox-botanic.com")
        ),
        array (
            "key" => "port",
            "value" => readInIni($userVar, 'PARAM', 'MAIL_PORT', "26")
        ),
        array (
            "key" => "username",
            "value" => readInIni($userVar, 'PARAM', 'MAIL_USERNAME', "test@gmail.com")
        ),
        array (
            "key" => "password",
            "value" => readInIni($userVar, 'PARAM', 'MAIL_PASSWORD', "pssord")
        ),
        array (
            "key" => "useSSL",
            "value" => readInIni($userVar, 'PARAM', 'MAIL_SSL', "false")
        )
    );
   
    create_conf_XML($pathTemporaire . "/serverMail/conf.xml" , $paramServerMail);        
    
    
    /*************************  serverSupervision ***********************************/
    // On cré la conf pour les capteurs 
    if (!is_dir($pathTemporaire . "/serverSupervision")) mkdir($pathTemporaire . "/serverSupervision");
    
    $processSupervisionId = 0;

    foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {
        
        // On cré un process par cuve 
        $paramConfSupervision = array (
            array (
                "key" => "action",
                "level" => "checkSensor"
            ),
            array (
                "key" => "eMail",
                "value" => readInIni($userVar, 'PARAM', 'MAIL_USERNAME', "test@gmail.com")
            ),
            array (
                "key" => "sensorName",
                "value" => "Niveau cuve " . $zone_nom
            ),
            array (
                "key" => "sensor",
                "value" => $zone['capteur']['niveau_cuve']['numero']
            ),
            array (
                "key" => "sensorOutput",
                "value" => "1"
            ),
            array (
                "key" => "valueSeuil",
                "value" => "5"
            ),
            array (
                "key" => "timeSeuilInS",
                "value" => "3600"
            ),
            array (
                "key" => "alertIf",
                "value" => "down"
            )
        );
        
        // On sauvegarde
        create_conf_XML($pathTemporaire . "/serverSupervision/process_" . $processSupervisionId . "_checkSensor.xml" , $paramConfSupervision);  
        
        unset($paramConfSupervision);
        
        $processSupervisionId++;
        
        foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {
            foreach ($plateforme["ligne"] as $ligne_nom => $ligne) {
                // On cré un process par ligne d'arrosage
                $paramConfSupervision = array (
                    array (
                        "key" => "action",
                        "level" => "checkSensor"
                    ),
                    array (
                        "key" => "eMail",
                        "value" => readInIni($userVar, 'PARAM', 'MAIL_USERNAME', "test@gmail.com")
                    ),
                    array (
                        "key" => "sensorName",
                        "value" => "Pression ligne " . $ligne_nom
                    ),
                    array (
                        "key" => "sensor",
                        "value" => $ligne['capteur']['pression']['numero']
                    ),
                    array (
                        "key" => "sensorOutput",
                        "value" => "1"
                    ),
                    array (
                        "key" => "valueSeuil",
                        "value" => "1"
                    ),
                    array (
                        "key" => "timeSeuilInS",
                        "value" => "3600"
                    ),
                    array (
                        "key" => "alertIf",
                        "value" => "down"
                    )
                );
                
                // On sauvegarde
                create_conf_XML($pathTemporaire . "/serverSupervision/process_" . $processSupervisionId . "_checkSensor.xml" , $paramConfSupervision);  
                
                unset($paramConfSupervision);
                
                $processSupervisionId++;
                
            }
        }
    }
    
    // Add trace level
    $paramServerSupervision = array (
        array (
            "key" => "verbose",
            "level" => readInIni($userVar, 'PARAM','VERBOSE_SUPERVISION' , "info")
        ),
        array (
            "key" => "nbProcess",
            "value" => "0"
        )
    );
    
    
   
    create_conf_XML($pathTemporaire . "/serverSupervision/conf.xml" , $paramServerSupervision);            
    
    switch(php_uname('s')) {
        case 'Windows NT':
            // On repositionne cette conf comme celle par défaut
            xcopy($pathTemporaire , $path . "/00_defaultConf_Win");
            
            // On la sauvegarde 
            xcopy($pathTemporaire , $path . "/" . date("YmdH"));

            break;
        default : 
            // On supprime l'ancienne conf 
            // sudo mv /etc/bulckypi/01_defaultConf_RPi/* /tmp/ --backup=numbered
            exec("sudo mv $path/01_defaultConf_RPi/* /tmp/ --backup=numbered",$ret,$err);
            if ($err != 0) echo 'Erreur suppression ancienne conf';
        
            // On repositionne cette conf comme celle par défaut
            // sudo cp -R /tmp/conf_tmp* /etc/bulckypi/01_defaultConf_RPi/
            exec("sudo cp -R $pathTemporaire/* $path/01_defaultConf_RPi/",$ret,$err);
            if ($err != 0) echo 'Erreur copie dans 01_defaultConf_RPi';
            
            // On crée le répertoire de sauvegarde
            // sudo mkdir /etc/bulckypi/2016041414
            $err = 0;
            if (!is_dir($path . "/" . date("YmdH"))) exec("sudo mkdir $path/" .  date("YmdH"),$ret,$err);
            if ($err != 0) echo "Erreur création $path/" .  date("YmdH");
            
            // On copie la conf dedans
            // sudo cp -R /tmp/conf_tmp/* /etc/bulckypi/2016041414/
            exec("sudo cp -R $pathTemporaire/* $path/" .  date("YmdH") . "/" ,$ret,$err);
            if ($err != 0) echo "Erreur copie dans le rep $path/" .  date("YmdH");
            
            break;
    }


    // On relance l'acquisition
    exec("sudo /etc/init.d/bulckypi force-reload >/dev/null 2>&1",$ret,$err);
    if ($err != 0) echo 'Erreur de rechargement du service';
    
}

function array_count_key($array, $search)
{   
    $nbkey = 0;
    foreach($array as $key => $value) {
        if ($key === $search) {
            $nbkey = $nbkey + count($value);
        } elseif (is_array($value)) {
            $nbkey = $nbkey + array_count_key($value, $search);
        }
    }

    return $nbkey;
}

//Récupération du nom de la variable, par convention interne, les noms de COOKIE  sont 
//toujours en majuscule, on capitalise donc le nom récupéré:
if(isset($_POST['function']) && !empty($_POST['function'])) {
    $function=strtoupper($_POST['function']);
}

if(!isset($function) || empty($function)) {
    //On affiche 0 si la fonction est appelée sans le nom de la variable:
    echo json_encode("0");
} else {
    switch($function) {
        case 'GET_CONF':
        
            // On vient lire la configuration 
            $parametre = parse_ini_file("param.ini",true);

            echo json_encode($parametre);
            break;
        case 'SET_CONF':

            // On vient lire la configuration 
            $parametre = parse_ini_file("param.ini",true);

            // On récupère la conf 
            $variable = $_POST['variable'];

            // On fusionne les deux
            $fusion["PARAM"] = array_merge($parametre["PARAM"],$variable["PARAM"]);
            $fusion["CUVE"]  = array_merge($parametre["CUVE"],$variable["CUVE"]);
            $fusion["LIGNE"] = array_merge($parametre["LIGNE"],$variable["LIGNE"]);

            write_ini_file($fusion, "param.ini", true);

            // On cré la conf 
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $path = "C:/cultibox/_conf";
                $pathTmp = "C:/cultibox/tmp";
            } else {
                $path = "/etc/bulckypi";
                $pathTmp = "/tmp";
            }

            generateConf($path, $pathTmp, $fusion);

            break;
        case 'SET_PLUG':
        
            // On récupère le numéro de la prise
            $prise1 = $_POST['prise1'];
            $prise2 = $_POST['prise2'];
            $temps  = $_POST['temps'];
            $etat   = $_POST['etat'];
        
            if ($prise1 != 0 ) {
                $status = forcePlug($prise1,$temps,$etat);
                echo $status["status"];
            }
            
            if ($prise2 != 0 && $status["status"] == "done" ) {
                $status = forcePlug($prise2,$temps,$etat);
                echo $status["status"];
            }
        
            break;        
        case 'GET_SENSORS' :
            $nbSensor = array_count_key($GLOBALS['IRRIGATION'], 'capteur');
            $return_array = array();
            switch(php_uname('s')) {
                case 'Windows NT':
                    $commandLine = 'tclsh "C:/cultibox/bulckyCore/bulckyPi/get.tcl" serverAcqSensor localhost ';
                    break;
                default : 
                    $commandLine = 'tclsh "/opt/bulckypi/bulckyPi/get.tcl" serverAcqSensor localhost ';
                    break;
            }
            
            
            for ($i = 1; $i <= $nbSensor; $i++) {
                $commandLine = $commandLine . ' "::sensor(' . $i . ',value)"';
            }
            $ret = "";
            try {
                $ret = exec($commandLine);
            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
            }
            $arr = explode ("\t", $ret);

            for ($i = 0; $i < $nbSensor; $i++) {
                if (array_key_exists($i, $arr)) {
                    if ($arr[$i] != "") {
                        $return_array[$i + 1] = $arr[$i];
                    } else {
                        $return_array[$i + 1] = "DEFCOM";
                    }
                } else {
                    $return_array[$i + 1] = "DEFCOM";
                }
            }
            echo json_encode($return_array);
            break;

        case 'GET_PLUGS' :
        
            $return_array = array();
            switch(php_uname('s')) {
                case 'Windows NT':
                    $commandLine = 'tclsh "C:/cultibox/bulckyCore/bulckyPi/get.tcl" serverPlugUpdate localhost ';
                    break;
                default : 
                    $commandLine = 'tclsh "/opt/bulckypi/bulckyPi/get.tcl" serverPlugUpdate localhost ';
                    break;
            }
        
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
            $nbPlug = 0 ;
            foreach ($outPrise as $numero => $nom) {
                $commandLine = $commandLine . ' "::plug(' . $numero . ',value)"';
                $nbPlug++;
            }

            $ret = "";
            try {
                $ret = exec($commandLine);
            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
            }
            $arr = explode ("\t", $ret);
            
            $i = 0;
            foreach ($outPrise as $numero => $nom) {
                if (array_key_exists($i, $arr)) {
                    if ($arr[$i] != "") {
                        $return_array[$numero] = $arr[$i];
                    } else {
                        $return_array[$numero] = "DEFCOM";
                    }
                } else {
                    $return_array[$numero] = "DEFCOM";
                }
                $i++;
            }

            echo json_encode($return_array);
            break;

        case 'PURGE_CUVE':
        
            // On récupère le numéro de la cuve
            $cuve = $_POST['cuve'];

            $return_array = array();

            try {
                switch(php_uname('s')) {
                    case 'Windows NT':
                        $return_array["status"] = exec('C:\Tcl\bin\tclsh.exe "C:\cultibox\bulckyCore\bulckyPi\getCommand.tcl" serverSLF localhost purgeCuve ' . $cuve );
                        break;
                    default : 
                        $return_array["status"] = exec('tclsh "/opt/bulckypi/bulckyPi/getCommand.tcl" serverSLF localhost purgeCuve ' . $cuve );
                        break;
                }
            } catch (Exception $e) {
                echo 'Exception reçue : ',  $e->getMessage(), "\n";
                $return_array["status"] = $e->getMessage();
            }
            echo $return_array["status"];

            break;  
            
        case 'RPI_UPDATE':
        
            // On récupère le module a mettre à jour
            $module = $_POST['module'];
            
            // On met a jour
            exec("sudo apt-get -u upgrade --assume-no|grep " . $module,$upgrade,$err);
            echo json_encode($upgrade);

            break;              

        case 'LOAD_TEMPLATE_CONF':
        
            // On récupère le nom de la conf a appliquer
            $filenamePHP = $_POST['filename'];
            
            // on calcul le nom du fichier param 
            $filenameINI = str_replace("config", "param", $filenamePHP);
            $filenameINI = str_replace(".php",   ".ini",  $filenameINI);
            
            // On supprime les vieux 
            $cmdLine = "sudo mv /var/www/mobile/config.php /tmp/ --backup=numbered";
            exec($cmdLine,$ret,$err);
            if ($err != 0) echo "Erreur suppression config.php : $cmdLine";
            $cmdLine = "sudo mv /var/www/mobile/param.ini /tmp/ --backup=numbered";
            exec($cmdLine,$ret,$err);
            if ($err != 0) echo "Erreur suppression param.ini : $cmdLine";
            
            # On met les nouveaux
            # sudo cp /var/www/mobile/default_cnf/config_Annecy_GL.php /var/www/mobile/config.php
            $cmdLine = "sudo cp /var/www/mobile/default_cnf/$filenamePHP /var/www/mobile/config.php";
            exec($cmdLine,$ret,$err);
            if ($err != 0) echo "Erreur déplacement : $cmdLine";
            # sudo cp /var/www/mobile/default_cnf/param_Annecy_GL.ini /var/www/mobile/param.ini
            $cmdLine = "sudo cp /var/www/mobile/default_cnf/$filenameINI /var/www/mobile/param.ini";
            exec($cmdLine,$ret,$err);
            if ($err != 0) echo "Erreur déplacement : $cmdLine";

            break;             

        case 'GET_SENSOR_VALUE':
        
            // On récupère les courbes a afficher
            $sensor1 = $_POST['sensor1'];
            $nom1    = $_POST['nom1'];
            $sensor2 = $_POST['sensor2'];
            $nom2    = $_POST['nom2'];
            $sensor3 = $_POST['sensor3'];
            $nom3    = $_POST['nom3'];
            
            // On récupere la date du graphique
            $hourStart   = $_POST['hourStart'];
            $dayStart    = $_POST['dayStart'];
            $monthStart  = $_POST['monthStart'];
            $yearStart   = $_POST['yearStart'];
            $hourEnd     = $_POST['hourEnd'];
            $dayEnd      = $_POST['dayEnd'];
            $monthEnd    = $_POST['monthEnd'];
            $yearEnd     = $_POST['yearEnd'];
        
            $return_array = array();
        
            // Open connection to dabase
            switch(php_uname('s')) {
                case 'Windows NT':
                    $db = new PDO('mysql:host=127.0.0.1;port=3891;dbname=cultibox;charset=utf8', 'cultibox', 'cultibox');
                    break;
                default : 
                    $db = new PDO('mysql:host=127.0.0.1;port=3891;dbname=bulcky;charset=utf8', 'bulcky', 'bulcky');
                    break;
            }
            
            $sensor1Text = "sensor" . $sensor1 ;
            $sensor2Text = "sensor" . $sensor2 ;
            $sensor2Requ = "";
            if ($sensor2 != "") {
                $sensor2Requ = " , " . $sensor2Text ;
            }
            $sensor3Text = "sensor" . $sensor3 ;
            $sensor3Requ = "";
            if ($sensor3 != "") {
                $sensor3Requ = " , " . $sensor3Text ;
            }
            
            $sql = "SELECT HOUR(timestamp) , MINUTE(timestamp) , {$sensor1Text} {$sensor2Requ} {$sensor3Requ} FROM bpilogs"
                    . " WHERE timestamp BETWEEN '{$yearStart}-{$monthStart}-{$dayStart} {$hourStart}:00:00' AND '{$yearEnd}-{$monthEnd}-{$dayEnd} {$hourEnd}:59:59' ORDER BY timestamp;";
        
            try {
                $sth = $db->prepare($sql);
                $sth->execute();
            } catch(\PDOException $e) {
                print_r($e->getMessage());
            }

            $return_array["cols"] = array (
                array(
                    "type" => 'timeofday',
                    "label" => "Heure"
                ),
                array(
                    "type" => 'number',
                    "label" => $nom1
                )
            );
            
            if ($sensor2 != "") {
                $return_array["cols"][] = array (
                    "type" => 'number',
                    "label" => $nom2
                );
            }
            if ($sensor3 != "") {
                $return_array["cols"][] = array (
                    "type" => 'number',
                    "label" => $nom3
                );
            }
            
            $return_array["rows"] = array ();
            while ($row = $sth->fetch()) 
            {
                // Creation du vecteur de valeur 
                $valToSave = array ();
                $valToSave[] = array (
                    "v" => array(
                        $row['HOUR(timestamp)'],
                        $row['MINUTE(timestamp)'],
                        "0",
                    )
                );
                $valToSave[] = array ("v" => $row["{$sensor1Text}"]);
                if ($sensor2 != "") {
                    $valToSave[] = array ("v" => $row["{$sensor2Text}"]);
                }
                if ($sensor3 != "") {
                    $valToSave[] = array ("v" => $row["{$sensor3Text}"]);
                }

                $return_array["rows"][] = array ("c" => $valToSave);
            }

            echo json_encode($return_array);
            break;

        case 'GET_POWER':
        
            // On récupère les courbes a afficher
            $plug = $_POST['plug'];

            // On récupere la date du graphique
            $monthStart  = $_POST['monthStart'];
            $yearStart   = $_POST['yearStart'];
        
            // Open connection to dabase
            switch(php_uname('s')) {
                case 'Windows NT':
                    $db = new PDO('mysql:host=127.0.0.1;port=3891;dbname=cultibox;charset=utf8', 'cultibox', 'cultibox');
                    break;
                default : 
                    $db = new PDO('mysql:host=127.0.0.1;port=3891;dbname=bulcky;charset=utf8', 'bulcky', 'bulcky');
                    break;
            }

            $sql = "select SUBSTR(timestamp,5,2), SUBSTR(timestamp,9,2), SUBSTR(timestamp,11,2) , SUBSTR(timestamp,13,2) , record FROM power WHERE plug_number = '{$plug}'"
                    . " AND timestamp LIKE '{$yearStart}{$monthStart}%' ORDER BY timestamp;";
        
            try {
                $sth = $db->prepare($sql);
                $sth->execute();
            } catch(\PDOException $e) {
                print_r($e->getMessage());
            }

            $day = 0;
            $nbSec = array();
            while ($row = $sth->fetch()) 
            {
                if ($row["SUBSTR(timestamp,5,2)"] != $day) {
                    $startSec = 0;
                    $day = $row["SUBSTR(timestamp,5,2)"];
                    $nbSec[$day] = 0;
                }
                $secActual = $row["SUBSTR(timestamp,9,2)"] * 3600 + $row["SUBSTR(timestamp,11,2)"] * 60 + $row["SUBSTR(timestamp,13,2)"];
                if ($row["record"] == "9990" && $startSec == 0) {
                    $startSec = $secActual;
                } elseif ($row["record"] == "0" && $startSec != 0) {
                    $nbSec[$day] = $nbSec[$day] + $secActual - $startSec;
                    $startSec = 0;
                }
            }

            echo json_encode($nbSec);
            break;
            
        default:
            echo json_encode("0");
    }
}

?>
