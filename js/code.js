$(document).ready(function () {
    var i=1;
    $("#fin1").prepend('<input type="button" id="ajoutEtape" value="Ajouter une étape" />');
    $("#ajoutEtape").click(function() {
        i++;
        $("#etape").append('<label for="etapes">Etape '+i+': </label><textarea id="etapes"    name="etapes[]"></textarea><br>');
    });
  }
);

$(document).ready(function () {
    $("#fin2").prepend('<input type="button" id="ajoutIngredient" value="Ajouter un ingrédient" />');
    $("#ajoutIngredient").click(function() {
        $.post('Utilities/test3.php',function(rep){
            $("#ingredient").append(rep);
        })
    });
  }
);

$(document).ready(function () {
    $("#fin3").prepend('<input type="button" id="ajoutIngredients" value="Ajouter un ingrédient" />');
    $("#ajoutIngredients").click(function() {
        $.post('Utilities/test.php',function(rep){
            $("#ingredient").append(rep);
        })
    });
  }
);


$(document).ready(function(){
    $("div.sectionprincipale").each(function(){
        $(this).mouseenter(function(){
            $(this).nextAll(".soussection").slideDown("slow");
        });
    // Pour cacher les menus. 0 correspond au nombre de millisecondes, donc c'est instantanné
        $(this).nextAll(".soussection").slideUp(0);
    });
    $("div.sectionaccueil").each(function(){
        $(this).mouseleave(function(){
            $(this).children(".soussection").slideUp("slow");
        });
    });
});

$(document).ready(function(){
    $("div.menuItemDeroulant").each(function(){
        $(this).mouseenter(function(){
            $(this).nextAll(".sousMenu").stop(true,true).slideDown("slow");
        });
    // Pour cacher les menus. 0 correspond au nombre de millisecondes, donc c'est instantanné
        $(this).nextAll(".sousMenu").stop(true,true).slideUp(0);
    });
    $("div.sectionMenu").each(function(){
        $(this).mouseleave(function(){
            $(this).children(".sousMenu").slideUp("slow");
        });
    });
});

