<?php
function generate (){
     if(array_key_exists('todo',$_GET) && $_GET['todo'] == "matchNom"){
         afficherRecetteNom($_POST['recette']);  
     }
     else{
         $liste = arrayToString(listeTotaleRecettes());
echo <<<END
     <script>
  $(function() {
    var availableTags = $liste;
    $( ".recettes" ).autocomplete({
      source: availableTags
    });
  });
  </script>
  

    <h1>Trouvez une recette que vous voulez préparer !</h1>
    <form action="index.php?todo=matchNom&amp;page=matchNom" method =post>
      <fieldset>
    <legend>Recette</legend>
   <div id="recettes" class="ui-widget">   <label>Ingredient : </label><input class="recettes" name="recette">                                                                      
   <br></div>
      </fieldset>
      <p><input type="submit" value="Trouver une recette"></p>
    </form>
END;
     }
}
?>
