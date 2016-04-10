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
        fwrite($fidPlgA,(2999 + $i). "\r\n");
        
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

    try {
        switch(php_uname('s')) {
            case 'Windows NT':
                $return_array["status"] = exec('C:\Tcl\bin\tclsh.exe "D:\CBX\06_bulckyCore\cultiPi\getCommand.tcl" serverPlugUpdate localhost setGetRepere ' . $number . ' ' . $value . ' ' . $time);
                break;
            default : 
                $return_array["status"] = exec('tclsh "/opt/cultipi/cultiPi/getCommand.tcl" serverPlugUpdate localhost setGetRepere ' . $number . ' ' . $value . ' ' . $time);
                break;
        }
    } catch (Exception $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
        $return_array["status"] = $e->getMessage();
    }

    return $return_array;
}

function xcopy($src, $dest) {
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

function generateConf ($path, $userVar) {

    // On sauvegarde le parametrage actuel 
    $newPath = $path . "/" . date("YmdH");
    //$newPath = $path . "/test-cnf" ;
    
    if (!is_dir($newPath)) {
        mkdir($newPath);
    }
    xcopy($path . "/01_defaultConf_RPi/" , $newPath );

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
        'name' => "serverHisto",
        'waitAfterUS' => "100",
        'pathexe' => "tclsh",
        'path' => "./serverHisto/serverHisto.tcl",
        'xmlconf' => "./serverHisto/conf.xml",
    );
    $paramListCultipiStart[] = array ( 
        'name' => "serverMail",
        'waitAfterUS' => "100",
        'pathexe' => "tclsh",
        'path' => "./serverMail/serverMail.tcl",
        'xmlconf' => "./serverMail/conf.xml",
    );
    $paramListCultipiStart[] = array ( 
        'name' => "serverSupervision",
        'waitAfterUS' => "100",
        'pathexe' => "tclsh",
        'path' => "./serverSupervision/serverSupervision.tcl",
        'xmlconf' => "./serverSupervision/conf.xml",
    );
    if ($userVar['PARAM']['IRRIGATION_ACTIF'] != "false") {
        $paramListCultipiStart[] = array ( 
            'name' => "serverSLF",
            'waitAfterUS' => "100",
            'pathexe' => "tclsh",
            'path' => "./serverSLF/serverSLF.tcl",
            'xmlconf' => "./serverSLF/conf.xml",
        );    
    }
   
    $paramListCultipiConf[] = array (
        "key" => "verbose",
        "level" => "warning"
    );
   
    if (!is_dir($newPath . "/bulckyPi")) {
        mkdir($newPath . "/bulckyPi");
    }

    create_conf_XML($newPath . "/bulckyPi/start.xml" , $paramListCultipiStart,"starts");
    create_conf_XML($newPath . "/bulckyPi/conf.xml" , $paramListCultipiConf,);

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
                    $prisemax = $numero;
                }
            }
        }
    }

    // On cré le fichier pluga 
    create_plugConf($newPath . "/serverPlugUpdate" , $prisemax);
    
    /*************************  Serveur Irrigation ***********************************/
    // On change les parametres pour le server irrigation 
    if (!is_dir($newPath . "/serverSLF")) {
        mkdir($newPath . "/serverSLF");
    }
    // Add trace level
    $paramServerSLFXML[] = array (
        "key" => "verbose",
        "level" => $userVar['PARAM']['VERBOSE_SLF']
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
        "key" => "nbzone" ,
        "value" => count($GLOBALS['IRRIGATION'])
    );    
    
    $paramServerSLFXML[] = array (
        "key" => "nettoyage" ,
        "value" => $userVar['PARAM']['NETTOYAGE_GOUTEUR']
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
        
        for ($i = 1 ; $i < 4 ; $i++) {
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",engrais," . $i . ",temps" ,
                "value" => $userVar['CUVE'][$Zone_nom_upper . '_ENGRAIS_' . $i]
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",engrais," . $i . ",actif" ,
                "value" => $userVar['CUVE'][$Zone_nom_upper . '_ENGRAIS_ACTIF_' . $i]
            );
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",engrais," . $i . ",prise" ,
                "value" => $zone["prise"]["engrais" . $i]
            );
        }

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
            $paramServerSLFXML[] = array (
                "key" => "zone," . $ZoneIndex . ",plateforme," . $PFIndex . ",tempscycle" ,
                "value" => $plateforme["parametre"]["temps_cycle"]
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
                $tmpsOnMatin = round(($userVar['LIGNE'][$PF_nom_upper . '_' . $ligne_numero . '_MATIN']     / ($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'])) * $plateforme["parametre"]["temps_cycle"]);
                $tmpsOnAMidi = round(($userVar['LIGNE'][$PF_nom_upper . '_' . $ligne_numero . '_APRESMIDI'] / ($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'])) * $plateforme["parametre"]["temps_cycle"]);
                $tmpsOnNuit  = round(($userVar['LIGNE'][$PF_nom_upper . '_' . $ligne_numero . '_SOIR']      / ($GLOBALS['CONFIG']['debit_gouteur'] * $GLOBALS['CONFIG']['gouteur_membrane'])) * $plateforme["parametre"]["temps_cycle"]);
                
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
                    "value" => $userVar['LIGNE'][$PF_nom_upper . '_' . $ligne_numero . '_ACTIVE']
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
    create_conf_XML($newPath . "/serverSLF/conf.xml" , $paramServerSLFXML);
    
    
    /*************************  capteurs ***********************************/
    // On cré la conf pour les capteurs 
    if (!is_dir($newPath . "/serverAcqSensor")) {
        mkdir($newPath . "/serverAcqSensor");
    }


    foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {

        // On cré un fichier par zone 
        $IP = $zone["parametres"]["IP"];
        
       // Add trace level
        $paramServerAcqSensor[] = array (
            "key" => "verbose",
            "level" => $userVar['PARAM']['VERBOSE_ACQSENSOR']
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
        
        create_conf_XML($newPath . "/serverAcqSensor/conf" . $extension . ".xml" , $arraToSave[$IP]);
        
    }
    
    
    // On repositionne cette conf comme celle apr défaut
    xcopy($newPath , $path . "/01_defaultConf_RPi");

    // On relance l'acquisition
    exec("sudo /etc/init.d/bulckypi force-reload >/dev/null 2>&1",$ret,$ret_var);
    
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

            // On récupère la conf 
            $variable = $_POST['variable'];
            write_ini_file($variable, "param.ini", true);
            
            // On cré la conf 
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $path = "C:/cultibox/xampp/htdocs";
            } else {
                $path = "/etc/bulckypi";
            }
            
            generateConf($path, $variable);
            
            break;
        case 'SET_PLUG':
        
            // On récupère le numéro de la prise
            $prise1 = $_POST['prise1'];
            $prise2 = $_POST['prise2'];
            $temps  = $_POST['temps'];
            $etat   = $_POST['etat'];
        
            if ($prise1 != 0 ) {
                forcePlug($prise1,$temps,$etat);
            }
            
            if ($prise2 != 0 ) {
                forcePlug($prise2,$temps,$etat);
            }
        
            break;        
            
            
        default:
            echo json_encode("0");
    }
}

?>
