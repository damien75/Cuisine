<?php
function generate (){
    $form_values_valid =false;
                  if(array_key_exists('todo',$_GET) && $_GET['todo'] == "register") {
                    if (!(isset($_POST["login"]) && $_POST["login"] != "" &&
                        isset($_POST["email"]) && $_POST["email"] != "" &&
                        isset($_POST["mdp"]) && $_POST["mdp"] != "" &&
                        isset($_POST["mdpv"]) && $_POST["mdpv"] != "" &&
                        isset($_POST["promotion"]) && $_POST["promotion"] != "" &&
                        isset($_POST["genre"]) && $_POST["genre"] != "" &&
                        isset($_POST["prenom"]) && $_POST["prenom"] != "" &&
                        isset($_POST["nom"]) && $_POST["nom"] != ""))
                             {echo "Un champ n a pas été rempli";}
                     else{
                         if (!(!existeLogin($_POST["login"]) && $_POST["mdp"]==$_POST["mdpv"]))
                             {echo "Le login existe déjà ou les mots de passe ne concordent pas.";}
                         else {Utilisateur::insererUtilisateur($_POST["login"], $_POST["mdp"], $_POST["promotion"], $_POST["email"], 0, date('Y-m-d'), $_POST["genre"], $_POST["nom"], $_POST["prenom"]);
                               $form_values_valid=true;}
                     }     
                }
                            
    if (!$form_values_valid) {
        if (isset($_POST["prenom"])) {$prenom = $_POST["prenom"];}
        else {$prenom = "";}
        if (isset($_POST["nom"])) {$nom = $_POST["nom"];}
        else {$nom = "";}
        if (isset($_POST["genre"])) {$genre = $_POST["genre"];}
        else {$genre = "";}
        if (isset($_POST["email"])) {$email = $_POST["email"];}
        else {$email = "";}
        if (isset($_POST["promotion"])) {$promotion = $_POST["promotion"];}
        else {$promotion = "";}
        if (isset($_POST["login"])&&!existeLogin($_POST["login"])) {$login = $_POST["login"];}
        else {$login = "";}
      ?>
<h2>Formulaire d'inscription</h2>
<form action="index.php?todo=register&amp;page=register" method =post oninput="mdpv.setCustomValidity(mdpv.value != mdp.value ? 'Les mots de passe diffèrent.' : '')">
  <p><i>Complétez le formulaire. Les champs marqué par </i><em>*</em> sont <em>obligatoires</em></p>
  <fieldset>
    <legend>Informations personnelles</legend>
      <label for="nom">Nom <em>*</em></label>
      <input id="nom"  value="<?php echo $nom ?>" required="" name =nom><br>
      <label for="prenom">Prenom <em>*</em></label>
      <input id="prenom"  value="<?php echo $prenom ?>" required="" name = prenom><br>      
      <label for="email">Email <em>*</em></label>
      <input id="email" type="email" placeholder="prenom.nom@polytechnique.edu" required="" value="<?php echo $email ?>" name=email><br>
      <label for="genre">Genre</label>
      <select id="genre" value="<?php echo $genre ?>"name=genre>
        <option value="M" name="sexe">M</option>
        <option value="Mlle" name="sexe">Mlle</option>
        <option value="Mme" name="sexe">Mme</option>
      </select><br>
      <label for="promotion">Promotion<em>*</em></label>
      <input id="promotion" type="number" value="<?php echo $promotion ?>" required="" name=promotion><br>
  </fieldset>
  <br>
  <fieldset>
    <legend>Informations de connexion</legend>
      <label for="login">Login <em>*</em></label>
      <input id="login"  value="<?php echo $login ?>" required="" name=login><br>
      <label for="mdp">Mot de Passe <em>*</em></label>
      <input id="mdp" type = "password"  required="" name=mdp><br>
      <label for="mdpv">Vérification du Mot de Passe <em>*</em></label>
      <input id="mdpv" type = "password"  required="" name=mdpv><br>

  </fieldset>
  <p><input type="submit" value="Créer un compte"></p>
</form>
<?php
}
else {
    echo "<div class='alerte'>Votre compte vient d'être crée<br>Vous pouvez maintenant vous connecter</div>";
}

}
?>
