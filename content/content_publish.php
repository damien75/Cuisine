<?php

function generate (){
$form_values_valid = false;
    if (array_key_exists('loggedIn', $_SESSION)){
        if(array_key_exists('todo',$_GET) && $_GET['todo'] == "publish") {
                    if (!(isset($_POST["nom"]) && $_POST["nom"] != ""))
                             {echo "Ajoutez un nom à votre recette";}
                     else{
    $form_values_valid = true;
    insererRecette($_POST["nom"], $_POST["descriptif"], $_POST["convives"], $_POST['ingredients'], $_POST["type"], $_POST['quantites'], $_POST['etapes']);
                                                                
 }
          }
          
          if (!($form_values_valid)){   
    echo <<<CHAINE_DE_FIN
<h1>Publier votre recette ici</h1>
CHAINE_DE_FIN;
    $liste = arrayToString(listeTotaleIngredient());
    echo <<<END
<h2>Déposer une recette</h2>
<form action="index.php?todo=publish&amp;page=publish" method =post>
  <p>Déposer ici votre recette. Détaillez bien les étapes.<em>*</em></p>
  <fieldset>
    <legend>Informations générales</legend>
      <label for="nom">Nom <em>*</em></label>
      <input id="nom"  required="" name="nom"><br>
      <label for="descriptif">Descriptif</label>
      <input id="descriptif" type="text" name="descriptif"><br>
      <label>Type de recette</label>
      <select id="type">
        <option value="entree">Entrée</option>
        <option value="plat">Plat</option>
        <option value="dessert">Dessert</option>
      </select>     
  </fieldset>
  <br>
  
 <script>
  $(function() {
    var availableTags = $liste;
    $( ".ingredients" ).autocomplete({
      source: availableTags
    });
  });
  </script>
  
  <fieldset>
    <legend>Ingrédients</legend>
   <div id="ingredient">   <label>Ingredient : </label><input class="ingredients"   name="ingredients[]"><label>Quantité : </label><input   name="quantites[]"><br></div>
      <div id="fin2"></div>
      <div id="convives"><br><br><label>Nombre de personnes <em>*</em></label>
      <input name="convives" required=""><br></div>
  </fieldset>
 <br>
  <fieldset>
    <legend>Etapes</legend>
   <div id="etape">   <label>Etape 1: </label><textarea id="etapes"    name="etapes[]"></textarea><br></div>
      <div id="fin1"></div>
  </fieldset>
  <p><input type="submit" value="Partager ma recette" name="valider"></p>
</form>
END;
          }
          else {
              echo "<div class='alerte'>Votre recette a bien été postée, elle sera affichée après validation par les administrateurs.</div>";
              Recette::printRecette(getLatestRecette());
          }                
                }
    else {echo "<div class='alerte'>Connectez-vous pour voir cette page!</div>";}
}
?>
