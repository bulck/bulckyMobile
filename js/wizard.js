
// Utilis� pour la page 6
// Recherche des r�seau wifi et ajout dans la liste
savePlugElement = function(plugNumber,elementPlugged) {
    
};

// Utilis� pour la page 6
// Recherche des r�seau wifi et ajout dans la liste
loadWiFiSSID = function() {
    
    $.ajax({
         cache: false,
         async: true,
         url: "../cultibox/main/modules/external/scan_network.php"
    }).done(function (data) {
         $("#wifi_essid_list").empty();
         $.each($.parseJSON(data),function(index,value) {
             var checked="";
             if($("#wifi_ssid").val()==value) {
                 checked="checked";
             }
             $("#wifi_essid_list").append('<option value="'+value+'" '+checked+' >'+value+'</option>');
         });
    });
};


$(document).ready(function(){
    
    // CHargement du menu
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
        }
    });
    
    // Page 2 
    //	Modification du nombre d'espace
    $("#valid_nb_space").click(function() {
        $("#nb_space_counter").text( $('input[name=nb_space_toggle]:checked', '#nb_space').val() );
    });
    
    // Page 3
    // Modification de l'�l�ment branch� sur la prise
    
    // Au chargement on pr�s�lectionne le bon
    $("a[name='link_plugtype']").click(function() {
        // On les reset tous
        $('input:radio[name=radio_plugtype]').attr('checked',false);
        
        // On vient lire le num�ro de la prise 
        plugnumber = $(this).data("bulcky-plug");
        
        // On r�cup�rer la valeur de celui d�j� s�lectionn�
        plugelement = $("#plugtype_counter_" + plugnumber).data("bulcky-plugelement");
        
        // On s�lectionne le d�j� s�lectionn�
        $('input:radio[name=radio_plugtype]').filter('[value=' + plugelement + ']').prop('checked', true);
        
        // On sauvegarde le num�ro de la prise dans l'�l�ment 
        $("#valid_plugtype").data("bulcky-plugelement", plugnumber);

        // Trick pour compenser un bug de mmenu
        // On change temporairement le nom du titre
        $('.mm-title' , '#plugtype').text('Prise ' + (plugnumber + 1) );
        
    });
    
    // A la validation, on sauvegarde et on met � jour
    $("#valid_plugtype").click(function() {
        
        // On vient r�cup�rer le num�ro de la prise 
        plugnumber = $(this).data("bulcky-plugelement");
        
        // On met � jour le texte de l'�lement s�lectionn�
        plugelement = $('input[name=radio_plugtype]:checked').val();
        $("#plugtype_counter_" + plugnumber).data("bulcky-plugelement", plugelement);
        plugname = $('input[name=radio_plugtype]:checked').data("bulcky-name");
        $("#plugtype_counter_" + plugnumber).text(plugname);
        
        // On sauvegarde dans la base de donn�e
        
        
    });
    
    
    // Page 6 : recherche des r�seau wifi 
    loadWiFiSSID();

    
});
