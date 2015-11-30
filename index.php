<!DOCTYPE html>
<html>
   <head>

      <title>Live !</title>
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
                        '<img src="/mobile/img/shortlogo2.png" class="image_header" />',
                        '<a href="#/" class="fa fa-envelope" class="humidity_zone" ></a>'
                    ]
                }
            });
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
            <p><strong>This is a demo.</strong><br />
               Click the menu icon to open the menu.</p>
         </div>
         
         <?php 
            // Add every elements
         ?>
         
      </div>

        
        <?php 

            // Get Every plugs 
            require_once '../bulcky/main/libs/db_get_common.php';

            // Number of plugs 
            $nb_plugs = get_configuration("NB_PLUGS",$main_error);

            // Plugs informations
            $plugs_infos = get_plugs_infos($nb_plugs,$main_error);

            // Retrieve sensors info
            require_once '../bulcky/main/libs/lib_sensors.php';
            $sensors_infos = \sensors\getDB();

        ?>
    
        
        <!-- The menu -->
        <nav id="menu" style="min-height: 100vh;">

            <div id="app">
                <ul>
                    <li><a href="#configuration"><i class="fa fa-camera"></i>Webcam</a></li>
                    <li><a href="#configuration" class="mm-arrow"><i class="fa fa-table"></i>Configuration</a></li>
                    <li><label>Prises</label></li>
                    <?php
                        foreach ($plugs_infos As  $plugs_info)
                        {
                            ?>
                                <li><a href="#plug_conf_<?php echo $plugs_info["id"] ;?>" class="mm-arrow"><?php echo $plugs_info["PLUG_NAME"] ;?> (prise <?php echo $plugs_info["id"] ;?>)</a></li>
                            <?php
                        }
                    ?>
                    <li><label>Capteurs</label></li>
                    <?php
                        foreach ($sensors_infos As  $sensor_infos)
                        {
                            if($sensor_infos["type"] != 0)
                            {
                                ?>
                                    <li><a href="#sensor_conf_<?php echo $sensor_infos["id"] ;?>" class="mm-arrow"><?php echo $sensor_infos["name"] ;?> (capteur <?php echo $sensor_infos["id"] ;?>)</a></li>
                                <?php
                            }
                        }
                    ?>
                </ul>
                
                <!-- Configuration part -->
                <div id="configuration" class="Panel">
                    <ul>
                        <!-- Todo : changer automatiquement -->
                        <li><a href="#">Croissance</a><input class="Toggle" type="radio" name="sex" value="croissance" checked /></li>
                        <li><a href="#">Floraison</a><input class="Toggle" type="radio" name="sex" value="floraison" /></li>
                        <li><a href="#conf_startHour">Heure d'allumage</a><em class="Counter">18h00</em></li>
                        <li><span><i class="fa fa-wifi"></i>Wi-Fi</span>
                            <ul>
                                <li>Choisissez un réseau</li>
                                <li><a href="#">Cultinet</a></li>
                                <li><a href="#">Cultinet2</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                
                <!-- subpanel -->
                <div id="conf_startHour" class="Panel">
                    <label for="price-from">Choisissez</label>
                    <select id="price-from">
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
                    <br />
                    <a href="#configuration" class="button">Appliquer</a>
                </div>
                
                <!-- plugs part -->
                <?php
                    foreach ($plugs_infos As  $plugs_info)
                    {
                        ?>
                            <div id="plug_conf_<?php echo $plugs_info["id"] ;?>" class="Panel">
                                <ul>
                                    <li><a href="#">État : ON</a></li>
                                    <li><a href="#plug_selectType">Type de prise</a></li>
                                </ul>
                            </div>
                        <?php
                        
                    }
                ?>

                <!-- subpanel -->
                <div id="plug_selectType" class="Panel">
                    <label for="price-from">Choisissez</label>
                    <select id="price-from">
                        <option value="lamp">Lampe</option>
                        <option value="extractor">Extracteur</option>
                        <option value="intractor">Intracteur</option>
                        <option value="ventilator">Brasseur d'air</option>
                        <option value="humidifier">Humidificateur</option>
                        <option value="dehumidifier">Dés-humidificateur</option>
                    </select>
                    <br />
                    <!-- Faire pointer le lien vers la page prise associée lors du chargement -->
                    <a href="#app" class="button">Set range</a>
                </div>
                
                <!-- sensor part -->
                <?php
                    foreach ($sensors_infos As  $sensor_infos)
                    {
                        ?>
                            <div id="sensor_conf_<?php echo $sensor_infos["id"] ;?>" class="Panel">
                                <ul>
                                    <li><a href="#">Valeur 18°C</a></li>
                                    <li><a href="#plug_selectType">Courbe de la journée</a></li>
                                </ul>
                            </div>
                        <?php
                        
                    }
                ?>
                
            </div>
        </nav>

    </body>
</html>
