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

    // On copie le parametrage actuel 
    //$newPath = $path . "/" . date("YmdHms");
    $newPath = $path . "/test-cnf" ;
    
    mkdir($newPath);
    xcopy($path . "/01_defaultConf_RPi/" , $newPath );


    // On change les parametres pour le server irrigation 
    mkdir($newPath . "/serverSLF");
    // Add trace level
    $paramServerSLFXML[] = array (
        "name" => "verbose",
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
    
    
    $ZoneIndex = 0;    
    $PFIndex = 0;
    foreach ($GLOBALS['IRRIGATION'] as $zone_nom => $zone) {

        
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",name" ,
            "value" => "ZONE " . $zone_nom
        );
        $paramServerSLFXML[] = array (
            "key" => "zone," . $ZoneIndex . ",ip" ,
            "value" => $zone["parametres"]["IP"]
        );
    
        foreach ($zone["plateforme"] as $plateforme_nom => $plateforme) {

            
            $PF_nom_upper = strtoupper($plateforme_nom);
            
            $ligneIndex = 0;
            
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",name" ,
                "value" => "PF " . $plateforme_nom
            );
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",ip" ,
                "value" => $zone["parametres"]["IP"]
            );
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",nbligne" ,
                "value" => count($plateforme["ligne"])
            );
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",tempscycle" ,
                "value" => $plateforme["parametre"]["temps_cycle"]
            );
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",pompe,prise" ,
                "value" => $plateforme["prise"]["pompe"]
            );
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",eauclaire,prise" ,
                "value" => $plateforme["prise"]["EV_eauclaire"]
            );
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",boutonarret,prise" ,
                "value" => $plateforme["capteur"]["tor_boutonarret"]
            );
            $paramServerSLFXML[] = array (
                "key" => "plateforme," . $PFIndex . ",afficheurarret,prise" ,
                "value" => $plateforme["prise"]["Afficheur_arret"]
            );

            
            foreach ($plateforme["ligne"] as $ligne_numero => $ligne) {
                $paramServerSLFXML[] = array (
                    "key" => "plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",name" ,
                    "value" => "Ligne " . $ligne_numero
                );
                $paramServerSLFXML[] = array (
                    "key" => "plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",prise" ,
                    "value" => $ligne["prise"]
                );
                $paramServerSLFXML[] = array (
                    "key" => "plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",tempsOn,matin" ,
                    "value" => $userVar['LIGNE'][$PF_nom_upper . '_' . $ligne_numero . '_MATIN']
                );
                $paramServerSLFXML[] = array (
                    "key" => "plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",tempsOn,apresmidi" ,
                    "value" => $userVar['LIGNE'][$PF_nom_upper . '_' . $ligne_numero . '_APRESMIDI']
                );
                $paramServerSLFXML[] = array (
                    "key" => "plateforme," . $PFIndex . ",ligne," . $ligneIndex . ",tempsOn,nuit" ,
                    "value" => $userVar['LIGNE'][$PF_nom_upper . '_' . $ligne_numero . '_SOIR']
                );
                $ligneIndex++; 
            }
            
            $PFIndex++;

        }     

        $ZoneIndex++;
    }
    
    $paramServerSLFXML[] = array (
        "key" => "nbplateforme" ,
        "value" => $PFIndex
    );
    
    // Save it
    create_conf_XML($newPath . "/serverSLF/conf.xml" , $paramServerSLFXML);
    
    // On repositionne cette conf comme celle apr défaut
    //xcopy($newPath , $path . "/01_defaultConf_RPi");
    
    
    // On relance l'acquisition

    
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
            $path = "C:/cultibox/xampp/htdocs";

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
