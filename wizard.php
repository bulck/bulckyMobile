<!DOCTYPE html>
<html>
   <head>

      <title>Assistant</title>
      <meta charset="utf-8" />
      <meta content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" name="viewport">
      <link type="text/css" href="/mobile/css/layout.css" rel="stylesheet" />

      <!-- Include jQuery.mmenu .css files -->
      <link type="text/css" href="/mobile/css/jquery.mmenu.all.css" rel="stylesheet" />
      <link type="text/css" href="/mobile/css/font-awesome.min.css" rel="stylesheet" />
      <link type="text/css" href="/mobile/css/jquery.mmenu.fullscreen.css" rel="stylesheet" />

      <!-- Include jQuery and the jQuery.mmenu .js files -->
      <script type="text/javascript" src="/mobile/js/jquery.min.js"></script>
      <script type="text/javascript" src="/mobile/js/jquery.mmenu.min.all.js"></script>
      <script type="text/javascript" src="/mobile/js/wizard.js"></script>

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
				color: #fff !important;
			}
            
		</style>
    </head>
    <body>

      <!-- The page -->
      <div class="page">
         <div class="header">
            <a href="#menu"></a>
         </div>
         <div class="content">
         </div>
         
         <?php 
            // Add every elements
         ?>
         
      </div>

        
        <?php 

            // Get Every plugs 
            require_once '../cultibox/main/libs/db_get_common.php';

            // Number of plugs 
            $nb_plugs = get_configuration("NB_PLUGS",$main_error);

            // Plugs informations
            $plugs_infos = get_plugs_infos($nb_plugs,$main_error);

            // Retrieve sensors info
            require_once '../cultibox/main/libs/lib_sensors.php';
            $sensors_infos = \sensors\getDB();

        ?>
    
        
        <!-- The menu -->
        <nav id="menu"  style="min-height: 100vh;max-width: 600px;">
        
            <!-- Wrapper (mandatory for menu) -->
            <div id="app">
            
                <br />
                <p><strong>Assistant de configuration</strong><br />
                    Bienvenue dans l'assistant de configuration.<br />
                    En quelques cliques vous allez pouvoir configurer votre système.
                </p>

                <ul>
                    <li>
                        <a href="#second" class="mm-arrow">Étape 1 : Sélection des éléments branchés </a>
                    </li>
                </ul>

                <!-- Page 2 : sélection des éléments branchés-->
                <div id="second" class="Panel">
                    <br />
                    <p><strong>Sélection des éléments branchés :</strong><br />

                    <br />
                    <ul>
                        <li>
                            <em id="nb_space_counter" class="mm-counter">1</em>
                            <a class="mm-arrow" href="#nb_space">Nombre d'espace de culture :</a>
                        </li>
                                <br />
                        <?php
                            for ($i = 0 ; $i < 4 ; $i++) 
                            {
                                switch ($i)
                                {
                                    case 0 :
                                        $pluggedElementName = "Lampe";
                                        $pluggedElement = "lamp";
                                        break;
                                    case 1 :
                                        $pluggedElementName = "Extracteur";
                                        $pluggedElement = "extractor";
                                        break;
                                    case 2 :
                                        $pluggedElementName = "Intracteur";
                                        $pluggedElement = "intractor";
                                        break;
                                    case 3 :
                                        $pluggedElementName = "Humidificateur";
                                        $pluggedElement = "humidifier";
                                        break;
                                }
                                ?>
                                <li>
                                    <em class="mm-counter" id="plugtype_counter_<?php echo $i;?>" data-bulcky-plugelement="<?php echo $pluggedElement;?>" ><?php echo $pluggedElementName;?></em>
                                    <a class="mm-arrow" href="#plugtype" name="link_plugtype" data-bulcky-plug="<?php echo $i;?>" >Équipement branché sur la prise <?php echo $i + 1;?> :</a>
                                </li>
                                <?php
                            }
                        ?>
                        <br />
                        <?php
                            for ($i = 0 ; $i < 4 ; $i++) 
                            {
                                $pluggedSpaceName = "Croissance";
                                $pluggedSpace = "Croissance";
                                ?>
                                <li>
                                    <em class="mm-counter" id="plugspace_counter_<?php echo $i;?>" ><?php echo $pluggedSpaceName;?></em>
                                    <a class="mm-arrow" href="#plugspace" name="link_plugspace" data-bulcky-plug="<?php echo $i;?>" >Équipement présent dans l'espace : <?php echo $i + 1;?> :</a>
                                </li>
                                <?php
                            }
                        ?>
                        <li>
                            <a href="#third" class="mm-arrow">Étape 2 : configuration de l'environnement</a>
                        </li>
                    </ul>
                    
                    <!-- Sous menu pour choisir le nombre d'espace -->
                    <div id="nb_space" class="Panel">
                        <ul>
                            <li>
                                <span>
                                    <p>1 espace de culture</p>
                                    <input class="Toggle" type="radio" name="nb_space_toggle" value="1" checked="checked" />
                                </span>
                            </li>
                            <li>
                                <span>
                                    <p>2 espaces de culture</p>
                                    <input class="Toggle" type="radio" name="nb_space_toggle" value="2" />
                                </span>
                            </li>
                            <br />
                            <li>
                                <a href="#second" class="mm-arrow" id="valid_nb_space" >Valider</a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Sous menu pour choisir le type d'élément branché -->
                    <div id="plugtype" class="Panel">
                        <ul>
                            <li>
                                <span>
                                    <p>Lampe</p>
                                    <input class="Toggle" type="radio" name="radio_plugtype" value="lamp" data-bulcky-name="Lampe" />
                                </span>
                            </li>
                            <li>
                                <span>
                                    <p>Extracteur</p>
                                    <input class="Toggle" type="radio" name="radio_plugtype" value="extractor" data-bulcky-name="Extracteur" />
                                </span>
                            </li>
                            <li>
                                <span>
                                    <p>Intracteur</p>
                                    <input class="Toggle" type="radio" name="radio_plugtype" value="intractor" data-bulcky-name="Intracteur" />
                                </span>
                            </li>
                            <li>
                                <span>
                                    <p>Brasseur d'air</p>
                                    <input class="Toggle" type="radio" name="radio_plugtype" value="ventilator" data-bulcky-name="Brasseur d'air" />
                                </span>
                            </li>
                            <li>
                                <span>
                                    <p>Humidificateur</p>
                                    <input class="Toggle" type="radio" name="radio_plugtype" value="humidifier" data-bulcky-name="Humidificateur" />
                                </span>
                            </li>
                            <li>
                                <span>
                                    <p>Dés-humidificateur</p>
                                    <input class="Toggle" type="radio" name="radio_plugtype" value="dehumidifier" data-bulcky-name="Dés-humidificateur" />
                                </span>
                            </li>
                            <br />
                            <li>
                                <a href="#second" class="mm-arrow" data-bulcky-plug="" id="valid_plugtype" >Valider</a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
                
                
                <!-- Page 3 : sélection de croissance ou floraison -->
                <div id="third" class="Panel">
                    <br />
                    <p><strong>Configuration de l'environnement :</strong>
                    <br />
                    <ul>
                        <li>
                            <em class="mm-counter">Croissance</em>
                            <a class="mm-arrow" href="#select_periode">Période de culture :</a>
                        </li>                   
                        <br />
                        <li>
                            <em class="mm-counter">8h</em>
                            <a class="mm-arrow" href="#select_hour">Heure d'allumage de la lampe :</a>
                        </li>
                        <br />
                        <li>
                            <a href="#fourth" class="mm-arrow">Étape suivante</a>
                        </li>
                    </ul>

                    <!-- Sous menu pour choisir l'heure -->
                    <div id="select_hour" class="Panel">
                        <ul>
                            <li>
                                <select id="start_cycle" style="display: inline;">
                                    <option value="0">0h</option>
                                    <option value="1">1h</option>
                                    <option value="2">2h</option>
                                    <option value="3">3h</option>
                                    <option value="4">4h</option>
                                    <option value="5">5h</option>
                                    <option value="6">6h</option>
                                    <option value="7">7h</option>
                                    <option value="8">8h</option>
                                    <option value="9">9h</option>
                                    <option value="10">10h</option>
                                    <option value="11">11h</option>
                                    <option value="12">12h</option>
                                    <option value="13">13h</option>
                                    <option value="14">14h</option>
                                    <option value="15">15h</option>
                                    <option value="16">16h</option>
                                    <option value="17">17h</option>
                                    <option value="18">18h</option>
                                    <option value="19">19h</option>
                                    <option value="20">20h</option>
                                    <option value="21">21h</option>
                                    <option value="22">22h</option>
                                    <option value="23">23h</option>
                                </select>
                            </li>
                            <li class="Spacer">
                                <a href="#third" class="mm-arrow">Valider</a>
                            </li>
                        </ul>
                    </div>   
                    
                    <!-- Sous menu pour choisir le moment -->
                    <div id="select_periode" class="Panel">
                        <ul>
                            <li>
                                <span>Période de culture :</span>
                            </li>
                            <li>
                                <a href="#">Croissance</a><input class="Toggle" type="radio" name="sex" value="croissance" checked />
                            </li>
                            <li>
                                <a href="#">Floraison</a><input class="Toggle" type="radio" name="sex" value="floraison" />
                            </li>
                            <li class="Spacer">
                                <a href="#third" class="mm-arrow">Valider</a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
                
                
                <!-- Page 4 : nom d'utilisateur et mot de passe -->
                <div id="fourth" class="Panel">
                    <br />
                    <p><strong>Sécurité :</strong><br />
                        Pour assurer la sécurité de votre connexion, veuillez choisir un nom d'utilisateur et un mot de passe</p>
                    <br />
                    <ul>
                        <li>
                            <p>Nom d'utilisateur : </p>
                        </li>
                        <li>
                            <input type="text" name="username">
                        </li>
                        <li>
                            <p>Mot de passe :</p>
                        </li>
                        <li>
                            <input type="password" name="userpassword">
                        </li>
                        <li class="Spacer">
                            <a href="#five" class="mm-arrow">Étape suivante</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Page 5 : Sélection du wifi -->
                <div id="five" class="Panel">
                    <br />
                    <p><strong>Connexion WiFi :</strong><br />
                        Sélectionner parmi les réseau suivant celui qui correspond à votre WiFi :</p>
                    <br />
                    <ul>
                        <li>
                            <p>Nom du réseau wifi: </p>
                        </li>
                        <li>
                            <select id="wifi_essid_list">
                            </select>
                        </li>
                        <li>
                            <input type="button"  value="Recharger la liste des réseaux wifi">
                        </li>
                        <li>
                            <p>Mot de passe :</p>
                        </li>
                        <li>
                            <input type="password" name="userpassword">
                        </li>
                        <li class="Spacer">
                            <a href="#six" class="mm-arrow">Étape suivante</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Page 6 : Changement de réseau -->
                <div id="six" class="Panel">
                    <br />
                    <p><strong>Reconnexion :</strong><br />
                        Votre module vient de changer de réseau WiFi.<br />
                        Vous devez maintenant vous connecter sur votre réseau wifi
                    </p>
                    <br />
                    <ul> 
                        <li class="Spacer">
                            <a href="#heith" class="mm-arrow">Finalisation</a>
                        </li>
                    </ul>
                </div>
            </div>
		</nav>

    </body>
</html>