<?php

function generate (){
     if(array_key_exists('todo',$_GET) && $_GET['todo'] == "match"){
         

         
         printRecetteIngredient($_POST['ingredients']);    
     }
     else{
         $liste = arrayToString(listeTotaleIngredient());
echo <<<END
     <script>
  $(function() {
    var availableTags = $liste;
    $( ".ingredients" ).autocomplete({
      source: availableTags
    });
  });
  </script>

    <h1>Choisissez les ingrédients que vous voulez cuisiner !</h1>
    <form action="index.php?todo=match&amp;page=match" method =post>
      <fieldset>
    <legend>Ingrédients</legend>
   <div id="ingredient" class="ui-widget">   <label>Ingredient : </label><input class="ingredients" name="ingredients[]">                                                                        
   <br></div>
      <div id="fin3"></div>
      </fieldset>
      <br>
      <p><input type="submit" value="Trouver une recette"></p>
    </form>
END;
     }
}
?>
