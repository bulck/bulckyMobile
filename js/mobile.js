

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
function upValTxtLigne (plateforme, nbLigne , varname, debit_par_goutteur , goutteur_par_membrane) {
    // On affiche le temps que ça correspond
    // temps_cycle * Debit / (Debit_par_gouteur * gouteur_par_membrane)
    tempsOn = parseFloat(CONF["LIGNE"][plateforme + "_TEMPS_CYCLE"]) * parseFloat(CONF["LIGNE"][varname]) / ( parseFloat(debit_par_goutteur) * parseFloat(goutteur_par_membrane) );
    tempsOff = parseFloat(CONF["LIGNE"][plateforme + "_TEMPS_CYCLE"]) / nbLigne - tempsOn
    logMessage("ON : " + tempsOn + "s" + " OFF : " + tempsOff + "s", 5000);
    
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

    logMessage("Lecture capteurs...", 0);
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

function readPlugs (periode) {

    logMessage("Lecture sorties...", 0);
	document.getElementById('btn_reload_periodic_plug').disabled=true;
	document.getElementById('btn_reload_plug').disabled=true;
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"GET_PLUGS"
         }
    }).done(function (data) {
        logMessage("Valeurs sorties lues" , 5000);
        PLUGS = jQuery.parseJSON(data);
        if (PLUGS != "") {
            for(var index in PLUGS) { 
               if (PLUGS.hasOwnProperty(index)) {
                   var attr = PLUGS[index];
                   if (attr == "on") {
                       document.getElementById("plug_" + index).classList.add('fa-play');
                       document.getElementById("plug_" + index).classList.remove('fa-stop');
                       document.getElementById("plug_" + index).classList.remove('fa-question-circle');
                   } else if (attr == "off") {
                       document.getElementById("plug_" + index).classList.add('fa-stop');
                       document.getElementById("plug_" + index).classList.remove('fa-play');
                       document.getElementById("plug_" + index).classList.remove('fa-question-circle');
                   } else {
                       // DEFCOM
                       document.getElementById("plug_" + index).classList.remove('fa-play');
                       document.getElementById("plug_" + index).classList.remove('fa-stop');
                       document.getElementById("plug_" + index).classList.add('fa-question-circle');
                   }
               }
            }
        }

		
		if (periode != 0) {
			setTimeout(function(){ readPlugs(periode - 1); }, 1000);
		} else {
			document.getElementById('btn_reload_periodic_plug').disabled=false;
			document.getElementById('btn_reload_plug').disabled=false;
		}
		
    });
}

// Cette function permet de purger la cuve 
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
google.charts.load("current", {packages: ["corechart"]});
// Set a callback to run when the Google Visualization API is loaded.


function drawChart(graphType, zoneName, plateformeName , ligneNumero) {

    logMessage("Chargement courbe ...", 0);
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd<10) {
        dd='0'+dd
    } 

    if(mm<10) {
        mm='0'+mm
    } 

    $.ajax({
        cache: false,
        async: true,
        type: "POST",
        url: "lib.php",
        data: {
            function:"GET_GRAPH_VALUES",
            zone:zoneName,
            plateforme:plateformeName,
            graph_type:graphType,
            ligne:ligneNumero,
            day:dd,
            month:mm,
            year:yyyy
        }
    }).done(function (jsonData) {
        logMessage("Chargement terminée", 10000);

        var data = new google.visualization.DataTable(jsonData);

        var width = $(window).width();
        
        if (width >= 900) {
            width = $("#page").width() - 20;
        }

        var options = {
            chart: {
                title: 'Courbe ' + graphType + ' ' + zoneName + ' ' + plateformeName + ' ' + ligneNumero
            },
            series: {
              0: {targetAxisIndex: 1},
              1: {targetAxisIndex: 0}
            },        
            vAxes: {
              // Adds titles to each axis.
              0: {title: 'Pression'},
              1: {title: 'Niveau cuve'}
            },
            explorer: {
                actions: ['dragToZoom', 'rightClickToReset'],
                axis: 'horizontal',
                keepInBounds: true
            },
            width: width,
            height: 500,
            legend:"bottom"
        };

        
        //var chart = new google.charts.Line(document.getElementById('chart_div'));
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    });
}

function drawSensor(sensor1Numero, nom1txt , sensor2Numero, nom2txt , sensor3Numero, nom3txt) {

    logMessage("Chargement courbe ...", 0);
    var endDate = new Date();
    var startDate = endDate;
    
    var hhe = endDate.getHours();
    var dde = endDate.getDate();
    var mme = endDate.getMonth()+1; //January is 0!
    var yyyye = endDate.getFullYear();
    if(hhe<10) hhe='0'+hhe; 
    if(dde<10) dde='0'+dde; 
    if(mme<10) mme='0'+mme;
    
    startDate.setHours(startDate.getHours() - 2);
    var hhs = startDate.getHours();
    var dds = startDate.getDate();
    var mms = startDate.getMonth()+1; //January is 0!
    var yyyys = startDate.getFullYear();
    if(hhs<10) hhs='0'+hhs; 
    if(dds<10) dds='0'+dds; 
    if(mms<10) mms='0'+mms;

    $.ajax({
        cache: false,
        async: true,
        type: "POST",
        url: "lib.php",
        data: {
            function:"GET_SENSOR_VALUE",
            sensor1:sensor1Numero,
            nom1:nom1txt,
            sensor2:sensor2Numero,
            nom2:nom2txt,
            sensor3:sensor3Numero,
            nom3:nom3txt,
            hourStart:hhs,
            dayStart:dds,
            monthStart:mms,
            yearStart:yyyys,
            hourEnd:hhe,
            dayEnd:dde,
            monthEnd:mme,
            yearEnd:yyyye
        }
    }).done(function (jsonData) {
        logMessage("Chargement terminée", 10000);

        var data = new google.visualization.DataTable(jsonData);

        var width = $(window).width();
        
        if (width >= 900) {
            width = $("#page").width() - 20;
        }

        var options = {
            chart: {
                title: 'Courbe ' + nom1txt + ' ' + nom2txt + ' ' + nom3txt
            },
            series: {
              0: {targetAxisIndex: 0},
              1: {targetAxisIndex: 1}
            },
            explorer: {
                actions: ['dragToZoom', 'rightClickToReset'],
                axis: 'horizontal',
                keepInBounds: true
            },
            width: width,
            height: 500,
            legend:"bottom"
        };

        
        //var chart = new google.charts.Line(document.getElementById('chart_div'));
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    });
}


function readPower(plugNum, yyyys, mms) {

    logMessage("Lecture puissance ...", 0);

    if(mms<10) mms='0'+mms;
    if(yyyys<10) yyyys='0'+yyyys;
    
    $.ajax({
        cache: false,
        async: true,
        type: "POST",
        url: "lib.php",
        data: {
            function:"GET_POWER",
            plug:plugNum,
            monthStart:mms,
            yearStart:yyyys
        }
    }).done(function (data) {
        logMessage("Chargement terminée ", 10000);
    });
}
