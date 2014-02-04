<?php
function generate(){
    if (array_key_exists('loggedIn', $_SESSION)&&Utilisateur::getInfoUtilisateurs('admin', $_SESSION['login'])==true){
    echo <<<END
   <div class='alerte'>Bienvenue cher administrateur, voici la liste de recettes qui restent à valider</div>
END;
    if (array_key_exists('todo',$_GET) && explode("_",$_GET['todo'])[0]=="validate"){
        $id = explode("_",$_GET['todo'])[1];
        $etapes = $_POST['etapes'];
        $nbetapes = count($etapes);
        $ingredientini = listeIngredient($id);
        $quantiteini = listeQuantite($id);
        $ingredient = $_POST['ingredients'];
        $quantite = $_POST['quantites'];
        $nbingredient = count($ingredient);
        $idingredient =array ();
        
        
        modifierRecette($id, 'nom', $_POST['nom']);
        modifierRecette($id, 'autorisation', true);
        modifierRecette($id, 'type', $_POST['typeplat']);
        modifierRecette($id, 'convives', $_POST['convives']);

        for ($i=0;$i<$nbetapes;$i++){
            modifierEtape($id, $i+1, $etapes[$i]);
        }

        for ($i=0;$i<$nbingredient;$i++){
            insererIngredient($ingredient[$i],true);
            $idingredient[$i]=  getIdIngredient($ingredient[$i]);
        }

        modifierRecetteIngredientQuantite($id, $idingredient, $quantite,$ingredientini);
    }
   if (array_key_exists('todo',$_GET) && explode("_",$_GET['todo'])[0]=="delete"){
       $id = explode("_",$_GET['todo'])[1];
       effacerRecette($id);
   }
    adminRecette();
    }
    else {
        echo <<<END
   <div class='alerte'>Vous devez vous connecter en tant qu'administrateur pour accéder à cette zone.</div>
END;
    }
}
?>
