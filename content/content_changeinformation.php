<?php
function generate(){
    if (array_key_exists('loggedIn', $_SESSION)){
        if(array_key_exists('todo',$_GET) && $_GET['todo'] == "changeinformation"){
        if(Utilisateur::testerMdp($_SESSION['login'], $_POST['mdpa'])){
            if (!($_POST['news']=="")){
                echo "Succès de la modification du champs.";
                changerInformationUtilisateur($_POST['info'], $_POST['news'], $_SESSION['login']);
                    if($_POST['info']=='login'){
        $_SESSION['login']=$_POST['news'];
    }
                echo "<div class='alerte'>Vous pouvez modifier d'autres informations:</div>";
                printFormulaireModif();
            }
            else{
                echo "<div class='alerte'>Le champ que vous voulez modifier ne doit pas être vide.</div>";
                printFormulaireModif();  
            }
        }
        else{
            echo "<div class='alerte'>Le mot de passe actuel rentré est erroné. Merci de remplir à nouveau le formulaire.</div>";
            printFormulaireModif();
        }
            
        }
        else{
            printFormulaireModif();
        }
    }
    else {
        echo "<div class='alerte'>Il faut vous connecter pour accéder à cette page !</div>";
    }
}

function printFormulaireModif (){
                echo <<<END
    <form action="index.php?todo=changeinformation&amp;page=changeinformation" method =post>
        <fieldset>
    <legend>Modifications à apporter</legend>
    <label for="info">Information à modifier</label>
    <select id="info" name=info>
        <option value="login" name="info">Login</option>
        <option value="mdp" name="info">Mot de Passe</option>
        <option value="promotion" name="info">Promotion</option>
        <option value="prenom" name="info">Prenom</option>
        <option value="nom" name="info">Nom</option>
        <option value="mail" name="info">Adresse Mail</option>
      </select><br>
      <label for="news">Modification à effectuer<em>*</em></label>
      <input id="news"  required="" name=news><br>
      <label for="mdpa">Vérification du Mot de Passe Actuel<em>*</em></label>
      <input id="mdpa" type = "password"  required="" name=mdpa><br>
      </fieldset>
        <p><input type="submit" value="Appliquer la modification"></p>
    </form>
END;
}
?>
