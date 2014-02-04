    <?php
function printLoginForm($askedPage){
 echo <<<END
  <form action="index.php?todo=login&amp;page=$askedPage" method="post" style="padding-top: 10px;">
    <label>Login : </label><input type="text" name="login" placeholder="login" required />
    <label >Password : </label><input type="password" name="password" placeholder="password" required />
    <p><input type="submit" value="Valider" id="login">
       <input type="button" name="register" value="Inscription" onclick="self.location.href='index.php?page=register'">
    </p>
  </form>
END;
  }
  
 function printLogoutForm($askedPage){
     echo <<<END
  <form action="index.php?todo=logout&amp;page=welcome" method="post">
    <p><input type="submit" value="Logout" id="login"/>
    <input type="button" name="account" value="Mon Compte" onclick="self.location.href='index.php?page=account'"></p>
  </form>
END;
 }
  
  function printPublishForm(){
     echo <<<END
<h2>Déposer une recette</h2>
<form action="#">
  <p><i>Déposer ici votre recette. Détaillez bien les étapes.</em></p>
  <fieldset>
    <legend>Informations générales</legend>
      <label for="nom">Nom <em>*</em></label>
      <input id="nom"  required="" name="nom"><br>
      <label for="descriptif">Descriptif</label>
      <input id="descriptif" type="text" name="descriptif"><br>
      <label for="type">Type de recette</label>
      <select id="type" "name=type>
        <option value="entree" name="type">Entrée</option>
        <option value="plet" name="type">Plat</option>
        <option value="dessert" name="type">Dessert</option>
      </select>     
  </fieldset>
  <br>
  <fieldset>
    <legend>Ingrédients</legend>
   <div>   <label for="ingredients">Ingredient : </label>
      <input id="ingredients"    name="ingredients[]">
      <label for="ingredients">Quantité : </label>
      <input id="ingredients"    name="quantites[]"></div><br>
      <label for="convives">Nombre de personnes </label>
      <input id="convives"   name="convives"><br> 
  </fieldset>
 <br>
  <fieldset>
    <legend>Etapes</legend>
   <div>   <label for="etapes">Etapes : </label>
      <input id="etapes"    name="etapes[]"></div>
  </fieldset>
  <p><input type="submit" value="Partager ma recette"></p>
</form>
END;
 }
 
 
  ?>