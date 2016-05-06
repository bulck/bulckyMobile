

// Cette fonction permet de gérer les message  en haut de l'application
idMessageTimeout = "";
function logMessage (message, timeOut) {
    
    if (idMessageTimeout != "") {
        clearTimeout(idMessageTimeout); 
    }

    document.getElementById("texte_info").innerHTML = message;
    
    if (timeOut != 0) {
        idMessageTimeout = setTimeout(
            function() {
              document.getElementById("texte_info").innerHTML = "";
              idMessageTimeout = "";
            },
            timeOut
        );  
    }
}

// Cette fonction charge en mémoire la configuration
function loadConf () {
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"GET_CONF"
         }
    }).done(function (data) {
        
        CONF = jQuery.parseJSON(data);
        
    });
}

function saveConf () {
    var retVal = confirm("Confirmez-vous l'envoi ?");
    if( retVal == true ) {
        logMessage("Envoi de la conf...", 0);
        $( ".btnApply" ).removeClass( "fa-arrow-circle-right" ).addClass( "fa-refresh fa-spin" );
        $.ajax({
             cache: false,
             async: true,
             type: "POST",
             url: "lib.php",
             data: {
                 function:"SET_CONF",
                 variable:CONF
             }
        }).done(function (data) {
            if (data != 0 ) {
                //alert (data);
                logMessage("Erreur conf : " + data , 5000);
            } else {
                logMessage("Configuration appliquée", 5000);
            }
            $( ".btnApply" ).removeClass( "fa-refresh fa-spin" ).addClass( "fa-arrow-circle-right" );
        });
    }
}

// var test = {level1:{level2:25},level12:{level2:25}};
// checkNested(test, 'level12', 'level2');
function checkNested(obj /*, level1, level2, ... levelN*/) {
  var args = Array.prototype.slice.call(arguments, 1);

  for (var i = 0; i < args.length; i++) {
    if (!obj || !obj.hasOwnProperty(args[i])) {
      return false;
    }
    obj = obj[args[i]];
  }
  return true;
}

// Change la valeur d'un des champs
var CONF = {};
function upVal (type, varname, incr, unit, max) {
    // On vérifie si la variable existe
    if (!checkNested(CONF, type)) {
        CONF[type] = {};        
    }
    
    if (!checkNested(CONF, type, varname)) {
        CONF[type][varname] = 0;        
    }

    if (parseFloat(CONF[type][varname]) + incr > parseFloat(max)) {
        alert ("Maximum " + max + " atteint pour cette ligne");
    } else {
        CONF[type][varname] = parseFloat(CONF[type][varname]) + incr;
        CONF[type][varname] = CONF[type][varname].toFixed(1);
        
        $( "#" + type + "_" + varname ).text( CONF[type][varname] + " " + unit);
    }
    

}

function changeVal (type, varname, val) {
    // On vérifie si la variable existe
    if (!checkNested(CONF, type)) {
        CONF[type] = {};        
    }
    
    if (!checkNested(CONF, type, varname)) {
        CONF[type][varname] = 0;        
    }

    CONF[type][varname] = val;
    
}

// Cette fonction pilote des prises
function setPlug (time, etatPrise, plug1, plug2) {
    logMessage("Pilotage en cours ...", 0);
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"SET_PLUG",
             prise1:plug1,
             prise2:plug2,
             temps:time,
             etat:etatPrise
         }
    }).done(function (data) {
        if (data == "TIMEOUT") {
            logMessage("Erreur : serverPlugUpdate ne répond pas " , 10000);
		} else if (data == "done")  {
			logMessage("Pilotage : OK ", 5000);
        } else {
            logMessage("Erreur " + data + ".", 5000);
        }
    });
}

function readSensors (periode) {

    logMessage("Lecture de la valeur des capteurs...", 0);
	document.getElementById('btn_reload_periodic_sensor').disabled=true;
	document.getElementById('btn_reload_sensor').disabled=true;
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"GET_SENSORS"
         }
    }).done(function (data) {
        logMessage("Valeurs capteurs lues" , 5000);
        SENSORS = jQuery.parseJSON(data);
        for(var index in SENSORS) { 
           if (SENSORS.hasOwnProperty(index)) {
               var attr = SENSORS[index];
               document.getElementById("sensor_" + index).innerHTML = attr;
           }
        }
		
		if (periode != 0) {
			setTimeout(function(){ readSensors(periode - 1); }, 1000);
		} else {
			document.getElementById('btn_reload_periodic_sensor').disabled=false;
			document.getElementById('btn_reload_sensor').disabled=false;
		}
		
    });
}

function purgeCuve (cuveIdx) {
    logMessage("Demande purge...", 0);
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"PURGE_CUVE",
             cuve:cuveIdx
         }
    }).done(function (data) {
        if (data == "TIMEOUT") {
            logMessage("Erreur : serverSLF ne répond pas " , 10000);
        } else {
            logMessage("Purge en cours " + data , 5000);
        }
        
    });
}

function rpi_update (moduleName) {
    logMessage("Demande mise à jour...", 0);
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"RPI_UPDATE",
             module:moduleName
         }
    }).done(function (data) {
        logMessage("MAJ terminée :" + data, 10000);        
    });
}

// Cette fonction permet d'appliquer une conf template
function loadTemplateConf (confBaseName) {
    logMessage("Chargement de la conf...", 0);
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"LOAD_TEMPLATE_CONF",
             filename:confBaseName
         }
    }).done(function (data) {
        if (data != "") {
            logMessage("Erreur :" + data, 10000);
        } else {
            logMessage("Chargement terminée :" + data, 10000);
            location.reload(true);
        }
    });
}

function displayBlock (sectionName) {
    // On cache tous les éléments
    var divsToHide = document.getElementsByClassName("conf_section");
    for(var i = 0; i < divsToHide.length; i++){
        divsToHide[i].style.display = "none";
    }
    // On affiche le bon 
    document.getElementById(sectionName).style.display = "block";
}

// Load the Visualization API and the corechart package.
google.charts.load("current", {packages: ["line"]});
// Set a callback to run when the Google Visualization API is loaded.


function drawChart() {

    var data = new google.visualization.DataTable();
      data.addColumn('timeofday', 'Heure');
      data.addColumn('number', 'Pression pompe');
      data.addColumn('number', 'Pression ligne');
      data.addColumn('number', 'Niveau d\'eau');

      data.addRows([
        [[8, 30, 10],  37.8, 80.8, 41.8],
        [[8, 30, 20],  30.9, 69.5, 32.4],
        [[8, 30, 30],  25.4,   57, 25.7],
        [[8, 30, 40],  11.7, 18.8, 10.5],
        [[8, 30, 50],  11.9, 17.6, 10.4],
        [[8, 40, 00],   8.8, 13.6,  7.7],
        [[8, 40, 10],   7.6, 12.3,  9.6],
        [[8, 40, 20],  12.3, 29.2, 10.6],
        [[8, 40, 30],  16.9, 42.9, 14.8]
      ]);
        var width = window.innerWidth
        || document.documentElement.clientWidth
        || document.body.clientWidth - 30;
        
        
      var options = {
        chart: {
          title: 'Box Office Earnings in First Two Weeks of Opening',
          subtitle: 'in millions of dollars (USD)'
        },
        width: width,
        height: 500,
        axes: {
          x: {
            0: {side: 'bottom'}
          }
        },
        legend: { position: 'bottom' }
      };

      var chart = new google.charts.Line(document.getElementById('chart_div'));

      chart.draw(data, options);
}

