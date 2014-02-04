<?php
function generate(){
    if (array_key_exists('loggedIn', $_SESSION)){
        
                echo <<<END
                <h1>Voici la page de votre compte</h1>
                <h2>Sur cette page vous pouvez</h2>
                <ul>
                    <li><a href="index.php?page=changeinformation">Modifier des informations sur votre compte</a></li>
END;
            if (Utilisateur::getInfoUtilisateurs('admin', $_SESSION['login'])==true){
                   echo <<<END
                <li><a href="index.php?page=validate">Vérifier des reccettes publiées par les autres utilisateurs</a></li>
                <li><a href="index.php?page=validate_encours">Gérer les recettes déjà validées</a></li>
END;
                   }
                   echo <<<END
   
            <li><a href="index.php?page=modifier">Modifier mes Recettes</a></li>
                </ul>
END;
                    
}
else {
    echo '<div class="alerte">Il faut vous logger pour accéder à cette page</div>';
}
}
?>
