

// Cette fonction permet de g√©rer les message  en haut de l'application
idMessageTimeout = "";
function logMessage (message) {
    
    if (idMessageTimeout != "") {
        clearTimeout(idMessageTimeout); 
    }

    document.getElementById("texte_info").innerHTML = message;
    
    idMessageTimeout = setTimeout(
        function() {
          document.getElementById("texte_info").innerHTML = "";
          idMessageTimeout = "";
        },
        5000
    );
    

}

// Cette fonction charge en m√©moire la configuration
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
    logMessage("Envoi de la conf...");
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
            alert (data);
            logMessage("Erreur lors de l'envoi de la conf...");
        } else {
            logMessage("Configuration appliqu√©e");
        }
    });
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
    // On vÈrifie si la variable existe
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
    // On vÈrifie si la variable existe
    if (!checkNested(CONF, type)) {
        CONF[type] = {};        
    }
    
    if (!checkNested(CONF, type, varname)) {
        CONF[type][varname] = 0;        
    }

    CONF[type][varname] = val;
    
}

// Cette fonction pilote des prises
function setPlug (time, plug1, plug2) {

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
             etat:"on"
         }
    }).done(function (data) {
        // page_cultipi.js l 1380
        //CONF = jQuery.parseJSON(data);
        
    });
}

function savParam (param, value) {
    CONF["PARAM"][param] = value;  
}

function readSensors () {
    logMessage("Lecture de la valeur des capteurs...");
    $.ajax({
         cache: false,
         async: true,
         type: "POST",
         url: "lib.php",
         data: {
             function:"GET_SENSORS"
         }
    }).done(function (data) {
        logMessage("Valeurs capteurs lues");
        SENSORS = jQuery.parseJSON(data);
        for(var index in SENSORS) { 
           if (SENSORS.hasOwnProperty(index)) {
               var attr = SENSORS[index];
               document.getElementById("sensor_" + index).innerHTML = attr;
           }
        }
    });
}
