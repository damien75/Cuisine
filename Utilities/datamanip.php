<?php

                                    /* Connexion à la base de données*/

class Database {
    public static function connect() {
        $dsn = 'mysql:dbname=cuisinons;host=127.0.0.1';
        $user = 'root';
        $password = '';
        $dbh = null;
        try {
            $dbh = new PDO($dsn, $user, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
            exit(0);
        }
        return $dbh;
    }
    
}

                                    /*Gestion des utilisateurs dans la table: utilisateurs*/

class Utilisateur {
    public $login;
    public $mdp;
    public $promotion;
    public $mail;
    public $admin;
    public $inscription;
    public $genre;
    public $nom;
    public $prenom;
 
    /*Imprimer les informations d'un utilisateur*/
    
    public function __toString() {
        $date=  explode("-", $this->inscription);
        return "[".$this->login."] ".  $this->prenom." ".  $this->nom." ".", inscrit(e) le ".$date[2]."/".$date[1]."/".$date[0].($this->promotion == NULL ? "" : ", X".$this->promotion).", ".$this->mail;
    }
    
    /*Insere un utilisateur dont la cle primaire est le login*/
    
    public static function insererUtilisateur($login,$mdp,$promotion,$mail,$admin,$inscription,$genre,$nom,$prenom){
        $dbh = Database::connect();
        $sth = $dbh->prepare("INSERT INTO `utilisateurs` (`login`, `mdp`, `promotion`, `mail`, `admin`, `inscription`, `genre`, `nom`,`prenom`) VALUES(?,SHA1(?),?,?,?,?,?,?,?)");
        $sth->execute(array($login,$mdp,$promotion,$mail,$admin,$inscription,$genre,$nom,$prenom));
        $dbh=NULL;
    }

    public static function getUtilisateur($login){
        $dbh = Database::connect();
            $query = "SELECT * FROM `utilisateurs` WHERE  login=?";
            $sth = $dbh->prepare($query);
            $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
            $sth->execute(array($login));
            $user = $sth->fetch();
            $sth->closeCursor();
            return $user;
    }
 
    public static function testerMdp($login, $password){
        if (Utilisateur::getUtilisateur($login)==NULL){
            return false;}
        else {
            $u=Utilisateur::getUtilisateur($login);
            if (SHA1($password)==$u->mdp){
                return TRUE;
            }
            else return false;
            
        }
    }

    /*Retoourne une information demandee en parametre concernant un utilisateur*/
    
    public static function getInfoUtilisateurs($info,$login){
        $dbh = Database::connect();
        $query = "SELECT * FROM `utilisateurs` WHERE  login=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Utilisateur');
        $sth->execute(array($login));
        return $sth->fetch(PDO::FETCH_ASSOC)[$info];
        $sth->closeCursor();
    }


}

         /*Gestion des recettes dans les tables: recette, recetteingredients, recetteetapes et ingredients*/

 class Recette{
    public $idrecette;
    public $idingredient;
    public $nomrecette;
    public $nomingredient;
    
    /*Ces fonctions servent a obtenir differentes informations qui sont liees a la recette que l on veut afficher*/
    
    
    
  public static function getInfoRecette($info,$idrecette){
        $dbh = Database::connect();
        $query = "SELECT * FROM `recette` WHERE  id=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recette');
        $sth->execute(array($idrecette));
        return $sth->fetch(PDO::FETCH_ASSOC)[$info];
        $sth->closeCursor();
  }
  
  public static function getDateRecette($idrecette){
        $dbh = Database::connect();
        $query = "SELECT * FROM `recette` WHERE  id=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recette');
        $sth->execute(array($idrecette));
        $date = explode("-",$sth->fetch(PDO::FETCH_ASSOC)['date']);
        return explode(" ",$date[2])[0]."/".$date[1]."/".$date[0];
        $sth->closeCursor();
  }  
  public static function getQuantite($idrecette,$idingredient){
        $dbh = Database::connect();
        $query = "SELECT * FROM `recetteingredients` WHERE  recette=? && idingredient=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recette');
        $sth->execute(array($idrecette,$idingredient));
        return $sth->fetch(PDO::FETCH_ASSOC)['quantite'];
        $sth->closeCursor();
  }
  
  function RecetteNom($idrecette){
        $dbh = Database::connect();
        $query = "SELECT * FROM `recette` WHERE  id=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recette');
        $sth->execute(array($idrecette));
        return $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
  }
    
    public static function getNomIngredient($id){
        $dbh = Database::connect();
        $query = "SELECT * FROM `ingredients` WHERE  id=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'ingredients');
        $sth->execute(array($id));
        return $sth->fetch(PDO::FETCH_ASSOC)['nom'];
        $sth->closeCursor();
    }
    
    /*Fonction tres importante qui est appelee des qu une recette doit etre affichee a l ecran*/
    
    public static function printRecette($idrecette){
        
        /*1: on cherche, stocke pour le code HTML et affiche des informations: nom de la recette, par qui elle est postee...*/
        
        $nomrecette=  Recette::getInfoRecette("nom",$idrecette);
        $utilisateur=  Recette::getInfoRecette("auteur",$idrecette);
        $date = Recette::getDateRecette($idrecette);
        $prenom = Utilisateur::getInfoUtilisateurs("prenom", $utilisateur);
        $nom = Utilisateur::getInfoUtilisateurs("nom", $utilisateur);
        $descriptif = Recette::getInfoRecette("descriptif", $idrecette);
        $convives = Recette::getInfoRecette("convives", $idrecette);
        $promotion = Utilisateur::getInfoUtilisateurs("promotion", $utilisateur);
        $type=  Recette::getInfoRecette("type", $idrecette);
        if ($type=="entree"){
            $type="Entrée";
        }
        if ($type=="plat"){
            $type="Plat";
        }
        if ($type=="dessert"){
            $type="Dessert";
        }
        echo <<<CHAINE_DE_FIN
            <div class='cadrerecette'><div><h1 style ="font-family: Craie; font-size:50px;">$nomrecette</h1>
                <h3>$type</h3>
                <p>Pour $convives personne(s).</p>
                    <p>$descriptif</p></div>
                <div class="contourutilisateur">Posté le $date<br>Par $prenom $nom<br>(promotion X$promotion)<br></div>
                    <div class = "tableauingredient"><p style ="font-family: Craie; font-size:30px;">Ingrédients:</p><ul>
                    
CHAINE_DE_FIN;
        
        /*2: on cherche les ingredients contenus dans la recette*/
        
        $dbh = Database::connect();
        $query = "SELECT * FROM `recetteingredients` WHERE  recette=?";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recette');
        $sth->execute(array($idrecette));
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
            $nomingredient = Recette::getNomIngredient($courant['idingredient']);
            $quantite =  Recette::getQuantite($idrecette,$courant['idingredient']);
            
            /*on affiche les ingredients*/
            
            echo <<<CHAINE_DE_FIN
            <li>$nomingredient ($quantite)</li>
CHAINE_DE_FIN;
        }
        
        /*on cherche et affiche les etapes de realisation de la recette*/
        
        echo <<<END
                  </ul></div>
                      <div class = "blocrecette"><ol>
END;
        $query = "SELECT * FROM `etape` WHERE  recette=? ORDER BY numero";
        $sth = $dbh->prepare($query);
        $sth->setFetchMode(PDO::FETCH_CLASS, 'Recette');
        $sth->execute(array($idrecette));
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
            $etape= $courant['texte'];
                    echo <<<END
        <li>$etape</li>
END;
        }
        echo <<<END
        </ol></div>
END;

        /*3: Module qui permet, si on est connecte, de rajouter ou retirer une recette de son livre de recette*/
        
        
        if (array_key_exists('loggedIn', $_SESSION)){
            echo<<<END
                    <form action="index.php?todo=unlike_$idrecette" method =post id=unlike_$idrecette>
                            <p><input  type="submit" value="Retirer de mon livre de recettes" name="like"></p>
                    </form>
                    <script>
                            $("#unlike_$idrecette").submit(function(){
                                $.post('Utilities/unliker.php',{idrecette:$idrecette},function(){
                                    $("#unlike_$idrecette").hide();
                                    $("#like_$idrecette").show();
                                });
                                return false;
                                });
                     </script>        
END;
            echo<<<END
                    <form action="index.php?todo=like_$idrecette" method =post id=like_$idrecette>
                             <p><input  type="submit" value="Ajouter à mon livre de recettes" name="like"></p>
                    </form>
                    <script>
                             $("#like_$idrecette").submit(function(){
                                 $.post('Utilities/liker.php',{idrecette:$idrecette},function(){
                                     $("#unlike_$idrecette").show();
                                     $("#like_$idrecette").hide();
                                 });
                                 return false;
                             });
                    </script>        
                 </div>
END;

            if(contientRecette($_SESSION['login'], $idrecette)){
                echo<<<END
                    <script>
                        $("#unlike_$idrecette").show();
                        $("#like_$idrecette").hide();
                    </script>
END;
            }
            else{
                echo<<<END
                    <script>
                        $("#like_$idrecette").show();
                        $("#unlike_$idrecette").hide();
                    </script>
END;
            }
          }
          else{
               echo<<<END
                        </div>
END;
          }
        $sth->closeCursor();

  }
}

                                        /*Pour la fonction d affichage des recettes*/

        /*Fonction qui imprime toutes les recettes*/

function printAll (){
    $dbh = Database::connect();
    $query = "SELECT * FROM `recette` WHERE autorisation =? ORDER BY nom";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        Recette::printRecette($courant['id']);
    }
}

      /*Fonction qui permet d'imprimer un nombre limité de recette, en partant de la recette ,n° $debut*/
function printLimite ($debut,$nombre){
    $dbh = Database::connect();
    $query = "SELECT * FROM recette WHERE autorisation=? ORDER BY nom LIMIT $debut,$nombre; ";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        Recette::printRecette($courant['id']);
    }
}

     /*Fonction identique à printLimite, mais qui n'affiche qu'un seul type de recette, les entrées par exemple*/
function printLimiteType ($type,$debut,$nombre){
    $dbh = Database::connect();
    $query = "SELECT * FROM recette WHERE autorisation=? AND type=? ORDER BY nom LIMIT $debut,$nombre; ";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true,$type));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        Recette::printRecette($courant['id']);
    }
}
    /*Fonction qui compte le nombre de recettes autorisées (utile pour l'affichage réparti par pages).*/
function nbRecettes (){
    $dbh = Database::connect();
    $query = "SELECT * FROM recette WHERE autorisation=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true));
    return $sth->rowCount();
}
   /*Fonction identique à nbRecettes, mais qui ne compte qu'un seul type de recette, les entrées par exemple*/
function nbRecettesType ($type){
    $dbh = Database::connect();
    $query = "SELECT * FROM recette WHERE autorisation=? AND type=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true,$type));
    return $sth->rowCount();
}
        /*Fonction qui imprime soit: entrees, plats ou desserts*/

function printType ($type){
    $dbh = Database::connect();
    $query = "SELECT * FROM `recette` WHERE autorisation =? AND type=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true,$type));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        Recette::printRecette($courant['id']);
    }
}

                                    /*Pour la fonction de recherche de recette par ingredient*/

        /*Fonction qui retourne l ID d un ingredient quand l utilisateur rentre son nom dans un recherche*/

function getIdIngredient ($nom){
    $b=-1;
    $dbh = Database::connect();
    $query = "SELECT * FROM `ingredients` WHERE nom=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($nom));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        if ($courant['nom']==$nom){$b=$courant['id'];}
    }
    return $b;
}

        /*Fonction qui determine si une recette contient un ingredient rentre par l utilisateur*/

function contientIngredient ($idrecette,$idingredient){
    $b=false;
    $dbh = Database::connect();
    $query = "SELECT * FROM `recetteingredients` WHERE recette=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($idrecette));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        if ($courant['idingredient']==$idingredient){$b=true;}
    }
    return $b;
}

                                            /*Incription d un nouvel utilisateur*/

    /*Verifie que le login, qui est une cle primaire dans la base de donnees utilisateurs, est libre*/

function existeLogin ($login){
    $b=false;
    $dbh = Database::connect();
    $query = "SELECT * FROM `utilisateurs` WHERE login=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($login));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $b=true;
    }
    return $b;
}

                                            /*Publier une nouvelle recette*/

     /*Teste si un ingredient propose existe deja dans la base de donnees*/

function existeIngredient ($nom){
    $b=false;
    $dbh = Database::connect();
    $query = "SELECT * FROM `ingredients` WHERE nom=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($nom));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $b=true;
    }
    return $b;
}

     /*Permet d inserer un nouvel ingredient dans la base de donnees dans l etape de validation*/

function insererIngredient ($nom,$autorisation){
    $dbh = Database::connect();
    if (!(existeIngredient($nom))&&!($nom=="")){
    $sth = $dbh->prepare("INSERT INTO `ingredients` (`nom`,`autorisation`) VALUES(?,?)");
    $sth->execute(array($nom,$autorisation));}
}

    /*Fonction non utilisee qui servira a ajouter un ingredient propose 
     Sans forcement valider la recette qui le contient*/


function insererAdminIngredient ($nom){
    $dbh = Database::connect();
    if ((existeIngredient($nom))){
    $query = "UPDATE ingredients SET autorisation=? WHERE nom=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true,$nom));
    }
    else {if (!($nom=="")){
    $sth = $dbh->prepare("INSERT INTO `ingredients` (`nom`,`autorisation`) VALUES(?,?)");
    $sth->execute(array($nom,true));}}
}

    /*Insere une recette qui est automatiquement validee si l utilisateur est administrateur 
      ou qui sera a valider par un administrateur sinon*/

function insererRecette($nom,$descriptif,$convives,$ingredients,$type,$quantites,$etapes)
{
        $dbh = Database::connect();
        $sth = $dbh->prepare("INSERT INTO `recette` (`nom`, `descriptif`, `autorisation`, `auteur`, `date`, `type`, `convives`) VALUES(?,?,?,?,?,?,?)");
        $sth->execute(array($nom,$descriptif, Utilisateur::getInfoUtilisateurs("admin", $_SESSION['login']),$_SESSION['login'],date('Y-m-d H:i:s'),$type,$convives));
        $id=  getLatestRecette();
        foreach ($etapes as $numero =>$etape ){
            if (!($etape==""))
            {$sth = $dbh->prepare("INSERT INTO `etape` (`recette`, `texte`, `numero`)VALUES(?,?,?)");
            $sth->execute(array($id,$etape,$numero+1));}
        }
        for ($i=0;$i<count($ingredients);$i++){
            insererIngredient($ingredients[$i],Utilisateur::getInfoUtilisateurs("admin", $_SESSION['login']));
            $sth = $dbh->prepare("INSERT INTO `recetteingredients` (`recette`, `idingredient`, `quantite`)VALUES(?,?,?)");
            $sth->execute(array($id,  getIdIngredient($ingredients[$i]),$quantites[$i]));
        }
        $dbh=NULL;
}

                         /*Affichage des derniers recettes sur la droite du site*/

function getLatestRecette (){
    $dbh = Database::connect();
    $sth = $dbh->prepare("SELECT * FROM recette ORDER BY date DESC");
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC)['id'];
}

function afficherNomsDernieresRecettes ($n){
    $dbh = Database::connect();
    $tab = array();
        $query = "SELECT * FROM `recette` WHERE autorisation= ? ORDER BY date DESC LIMIT $n";
        $sth = $dbh->prepare($query);
        $sth->execute(array(true));
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
            $iduser = $courant['auteur'];
            $nomuser = Utilisateur::getInfoUtilisateurs("nom", $iduser);
            $date = Recette::getDateRecette($courant['id']);
            $idrecette = $courant['id'];
            $prenomuser = Utilisateur::getInfoUtilisateurs("prenom", $iduser);
            $tab[$courant['nom']]=$prenomuser."_".$nomuser."_".$date."_".$idrecette;
        }
        return $tab;
}

                                    /*Validation d une recette par un administrateur*/

      /*Fonctions qui retournent des tableaux de quantites, ingredients et etapes de la recette a valider*/

function listeQuantite ($recette){
        $dbh = Database::connect();
        $query = "SELECT * FROM `recetteingredients` WHERE recette=?";
        $sth = $dbh->prepare($query);
        $sth->execute(array($recette));
        $quantite = array ();
        $i=0;
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $quantite[$i]=$courant['quantite'];
        $i++;
    }
        return $quantite;
}

function listeIngredient ($recette){
            $dbh = Database::connect();
        $query = "SELECT * FROM `recetteingredients` WHERE recette=?";
        $sth = $dbh->prepare($query);
        $sth->execute(array($recette));
        $ingredient = array ();
        $i=0;
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $ingredient[$i]=$courant['idingredient'];
        $i++;
    }
        return $ingredient;
}

function listeEtape ($recette){
                $dbh = Database::connect();
        $query = "SELECT * FROM `etape` WHERE recette=? ORDER BY 'numero'";
        $sth = $dbh->prepare($query);
        $sth->execute(array($recette));
        $etape = array ();
        $i=0;
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $etape[$i]=$courant['texte'];
        $i++;
    }
        return $etape;
}

        /*Fonction tres importante qui permet de valider une recette:
         1: on cherche et on stocke les informations de la recette rentrees par l auteur
         2: on propose a l administrateur, sous forme de formulaire, de modifier les champs remplis
         3: les champs sont remplis par defaut avec les valeurs rentrees par l utilisateur
         4: l administrateur peut valider la recette, les nouvelles valeurs des champs seront 
            mises a jour pour la recette ou la recette peut etre detruite*/

function adminRecette (){
    $dbh = Database::connect();
    $query = "SELECT * FROM `recette` WHERE autorisation=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array(false));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $id = $courant['id'];
        $nom = $courant['nom'];
        $descriptif = $courant['descriptif'];
        $type = $courant['type'];
        $selected1 =  ($type == "entree" ? "selected" :"");
        $selected2 =  ($type == "plat" ? "selected" :"");
        $selected3 =  ($type == "dessert" ? "selected" :"");
        $ingredient = listeIngredient($id);
        $quantite = listeQuantite($id);
        $nbingredient = count($ingredient);
        $etape = listeEtape($id);
        $nbetape = count($etape);
        $convives = $courant['convives'];
        
        echo <<<END

            <div class="blocrecette">
            <h1>$nom</h1>
            <form action="index.php?todo=validate_$id&amp;page=validate" method =post>
              <fieldset>
                <legend>Informations générales</legend>
                  <label for="nom">Nom <em>*</em></label>
                  <input id="nom"  required="" value="$nom" name="nom"><br>
                  <label for="descriptif">Descriptif</label>
                  <input id="descriptif" type="text" value = "$descriptif" name="descriptif"><br>
                  <label for="type">Type de recette</label>
                  <select id="typeplat" name="typeplat">
                    <option value="entree" name="typeplat" $selected1>Entrée</option>
                    <option value="plat" name="typeplat" $selected2>Plat</option>
                    <option value="dessert" name="typeplat" $selected3>Dessert</option>
                  </select>     
              </fieldset>
              <br>
              <fieldset>
                <legend>Ingrédients</legend>
END;

         for ($i=0;$i<$nbingredient;$i++){
                $ingredient[$i]=  Recette::getNomIngredient($ingredient[$i]);
         echo <<<END
                <div id="ingredient">   <label for="ingredients">Ingredient : </label><input    name="ingredients[]" value = "$ingredient[$i]" ><label for="ingredients">Quantité : </label><input   name="quantites[]" value ="$quantite[$i]"><br></div>   
END;
            }
         echo <<<END
                <div id="convives"><br><br><label for="convives">Nombre de personnes <em>*</em></label>
                      <input id="convives"   name="convives" required="" value = "$convives"><br></div>
                 </fieldset>
                 <br>
                 <fieldset>
                 <legend>Etapes</legend>
END;
         for ($i=0;$i<$nbetape;$i++){
                $j=$i+1;
         echo <<<END
                 <div id="etape">   <label for="etapes">Etape $j: </label><textarea id="etapes"    name="etapes[]">$etape[$i]</textarea><br></div>
END;
         }
         echo <<<END
                  </fieldset>
                  <input type="submit" value="Valider la recette">
                  </form>
                  <form action="index.php?todo=delete_$id&amp;page=validate" method =post>
                      <input type="submit" value="Détruire la recette">
                  </form>
                  </div>
END;
    }
}

        /*Fonction identique qui permet de modifier des recettes precedemment validees*/

function adminRecetteEnCours (){
    $dbh = Database::connect();
    $query = "SELECT * FROM `recette` WHERE autorisation=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array(true));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $id = $courant['id'];
        $nom = $courant['nom'];
        $descriptif = $courant['descriptif'];
        $type = $courant['type'];
        $selected1 =  ($type == "entree" ? "selected" :"");
        $selected2 =  ($type == "plat" ? "selected" :"");
        $selected3 =  ($type == "dessert" ? "selected" :"");
        $ingredient = listeIngredient($id);
        $quantite = listeQuantite($id);
        $nbingredient = count($ingredient);
        $etape = listeEtape($id);
        $nbetape = count($etape);
        $convives = $courant['convives'];
        
            echo <<<END

<div class="blocrecette">
<h1>$nom</h1>
<form action="index.php?todo=validate_$id&amp;page=validate_encours" method =post>
  <fieldset>
    <legend>Informations générales</legend>
      <label for="nom">Nom <em>*</em></label>
      <input id="nom"  required="" value="$nom" name="nom"><br>
      <label for="descriptif">Descriptif</label>
      <input id="descriptif" type="text" value = "$descriptif" name="descriptif"><br>
      <label for="type">Type de recette</label>
      <select id="typeplat" name="typeplat">
        <option value="entree" name="typeplat" $selected1>Entrée</option>
        <option value="plat" name="typeplat" $selected2>Plat</option>
        <option value="dessert" name="typeplat" $selected3>Dessert</option>
      </select>     
  </fieldset>
  <br>
  <fieldset>
    <legend>Ingrédients</legend>
END;

            for ($i=0;$i<$nbingredient;$i++){
                $ingredient[$i]=  Recette::getNomIngredient($ingredient[$i]);
            echo <<<END
   <div id="ingredient">   <label for="ingredients">Ingredient : </label><input    name="ingredients[]" value = "$ingredient[$i]" ><label for="ingredients">Quantité : </label><input   name="quantites[]" value ="$quantite[$i]"><br></div>   
END;
            }
            echo <<<END
<div id="convives"><br><br><label for="convives">Nombre de personnes <em>*</em></label>
      <input id="convives"   name="convives" required="" value = "$convives"><br></div>
  </fieldset>
 <br>
  <fieldset>
    <legend>Etapes</legend>
END;
            for ($i=0;$i<$nbetape;$i++){
                $j=$i+1;
            echo <<<END
   <div id="etape">   <label for="etapes">Etape $j: </label><textarea id="etapes"    name="etapes[]">$etape[$i]</textarea><br></div>
END;
            }
            echo <<<END
  </fieldset>
  <input type="submit" value="Valider la recette">
</form>
<form action="index.php?todo=delete_$id&amp;page=validate" method =post>
    <input type="submit" value="Détruire la recette">
</form>
</div>
END;
    }
}

      /*Fonctions qui permettent de mettre a jour apres validation tous les champs d une recette*/

function modifierRecette ($id,$champ,$new){
    $dbh = Database::connect();
    $query = "UPDATE recette SET $champ=? WHERE id=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($new, $id));
}

function modifierEtape($idrecette,$numero,$new){
    $dbh = Database::connect();
    $query = "UPDATE etape SET texte=? WHERE recette=? AND numero=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($new,$idrecette,$numero));
}

function modifierRecetteIngredientQuantite ($recette,$idingredient,$quantite,$idingredientini){
    $dbh = Database::connect();
    for ($i=0;$i<count($idingredient);$i++){
    $query = "UPDATE recetteingredients SET idingredient=?,quantite=? WHERE recette=? AND idingredient =?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($idingredient[$i],$quantite[$i],$recette,$idingredientini[$i]));
    }    
}

function effacerRecette ($id){
    $dbh = Database::connect();
    $query = "DELETE FROM recette WHERE id=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($id));
}

                                /*Recherche d une recette par ingredients*/

        /*Fonction qui sert a associer le nombre d ingredients a chaque recette
          Prend en argument un tableau d'ingredient 
          Construit un tableau dont les clés sont les id des recettes 
          et les valeurs sont le nombre d'ingredients de la liste qu'elles contiennent*/

function construitTableauRecetteIngredient ($idingredient){
        $dbh = Database::connect();
        $query = "SELECT * FROM `recette`";
        $sth = $dbh->prepare($query);
        $sth->execute();
        $nbIngredient = 0;
        $tab = array();
        $n = count($idingredient);
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){

            for ($i=0;$i<$n;$i++){

                if (contientIngredient($courant['id'], getIdIngredient($idingredient[$i]))){
                    $nbIngredient++;
                }
            }
            if ($nbIngredient>0){
                $tab[$courant['id']]=$nbIngredient;
                $nbIngredient=0;
            }
        }
    return $tab;
}

    /*Cette fonction imprime les recettes par ordre decroissant d ingredients correspondant a la 
      requete de l utilisateur*/

function printRecetteIngredient ($idingredient){
    $tab = construitTableauRecetteIngredient($idingredient);
    $n = count($idingredient);
    for ($j=$n;$j>0;$j--){
        if ($j==1){
            echo <<<END
                <h2>Recettes contenant $j ingrédient demandé</h2>
END;
        }
        else {
            echo <<<END
                <h2>Recettes contenant $j ingrédients demandés</h2>
END;
        }
        foreach ($tab as $cle => $valeur){
            if ($valeur==$j){
                Recette::printRecette($cle);
            }
        }
    }
}

                                /*Recherche d une recette par nom*/

function afficherRecetteNom ($nom){
    $dbh = Database::connect();
    $query = "SELECT * FROM `recette` WHERE nom=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($nom));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        Recette::printRecette($courant['id']);
    }
}

                                /*Autocompletion*/

       /*Fonction qui stocke dans un tableau la liste de tous le ingredients de la table*/

function listeTotaleIngredient (){
        $tab = array();
        $i=0;
        $dbh = Database::connect();
        $query = "SELECT * FROM `ingredients` WHERE autorisation = ? ORDER BY nom";
        $sth = $dbh->prepare($query);
        $sth->execute(array(true));
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
            $tab[$i]=$courant['nom'];
            $i++;
        }
        return $tab;
}

        /*Fonction qui stocke dans un tableau la liste de toutes les recettes de la table*/

function listeTotaleRecettes (){
        $tab = array();
        $i=0;
        $dbh = Database::connect();
        $query = "SELECT * FROM `recette` WHERE autorisation = ? ORDER BY nom";
        $sth = $dbh->prepare($query);
        $sth->execute(array(true));
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
            $tab[$i]=$courant['nom'];
            $i++;
        }
        return $tab;
}

                                        /*Changer les informations utilisateurs*/

function changerInformationUtilisateur($info,$new,$user){
    $dbh = Database::connect();
    $query = "UPDATE utilisateurs SET $info=? WHERE login=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($new,$user));
    if($info=='login'){
        $_SESSION['login']=$new;
    }
}

                                        /*Gestion du livre de recettes en ligne*/

function ajouterRecetteUtilisateur ($login,$idrecette){
        $dbh = Database::connect();
        $sth = $dbh->prepare("INSERT INTO `utilisateursrecette` (`utilisateur`, `recette`) VALUES(?,?)");
        $sth->execute(array($login,$idrecette));
        $dbh=NULL;
}
function retirerRecetteUtilisateur ($login,$idrecette){
        $dbh = Database::connect();
        $sth = $dbh->prepare("DELETE  FROM `utilisateursrecette` WHERE utilisateur=? AND recette=?");
        $sth->execute(array($login,$idrecette));
        $dbh=NULL;
}

function afficherLivreRecette ($login){
    $dbh = Database::connect();
        $query = "SELECT * FROM `utilisateursrecette` WHERE utilisateur= ?";
        $sth = $dbh->prepare($query);
        $sth->execute(array($login));
        while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
            Recette::printRecette($courant['recette']);
        }
}

        /*Teste si une recette est deja dans le livre de recette de l utilisateur*/

    function contientRecette ($login,$idrecette){
    $b=false;
    $dbh = Database::connect();
    $query = "SELECT * FROM `utilisateursrecette` WHERE utilisateur=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($login));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        if ($courant['recette']==$idrecette){$b=true;}
    }
    return $b;
}

                            /*Fonction qui permet de modifier ses propres recettes*/

function recetteUtilisateur ($user){
    $dbh = Database::connect();
    $query = "SELECT * FROM `recette` WHERE auteur=?";
    $sth = $dbh->prepare($query);
    $sth->execute(array($user));
    while ($courant =  $sth->fetch(PDO::FETCH_ASSOC)){
        $id = $courant['id'];
        $nom = $courant['nom'];
        $descriptif = $courant['descriptif'];
        $type = $courant['type'];
        $selected1 =  ($type == "entree" ? "selected" :"");
        $selected2 =  ($type == "plat" ? "selected" :"");
        $selected3 =  ($type == "dessert" ? "selected" :"");
        $ingredient = listeIngredient($id);
        $quantite = listeQuantite($id);
        $nbingredient = count($ingredient);
        $etape = listeEtape($id);
        $nbetape = count($etape);
        $convives = $courant['convives'];
        
            echo <<<END

                <div class="blocrecette">
                <h1>$nom</h1>
                <form action="index.php?todo=validate_$id&amp;page=modifier" method =post>
                  <fieldset>
                    <legend>Informations générales</legend>
                      <label for="nom">Nom <em>*</em></label>
                      <input id="nom"  required="" value="$nom" name="nom"><br>
                      <label for="descriptif">Descriptif</label>
                      <input id="descriptif" type="text" value = "$descriptif" name="descriptif"><br>
                      <label for="type">Type de recette</label>
                      <select id="typeplat" name="typeplat">
                        <option value="entree" name="typeplat" $selected1>Entrée</option>
                        <option value="plat" name="typeplat" $selected2>Plat</option>
                        <option value="dessert" name="typeplat" $selected3>Dessert</option>
                      </select>     
                  </fieldset>
                  <br>
                  <fieldset>
                    <legend>Ingrédients</legend>
END;

            for ($i=0;$i<$nbingredient;$i++){
                $ingredient[$i]=  Recette::getNomIngredient($ingredient[$i]);
                echo <<<END
                        <div id="ingredient">   <label for="ingredients">Ingredient : </label><input    name="ingredients[]" value = "$ingredient[$i]" ><label for="ingredients">Quantité : </label><input   name="quantites[]" value ="$quantite[$i]"><br></div>   
END;
            }
            echo <<<END
                <div id="convives"><br><br><label for="convives">Nombre de personnes <em>*</em></label>
                      <input id="convives"   name="convives" required="" value = "$convives"><br></div>
                </fieldset>
                <br>
                <fieldset>
                <legend>Etapes</legend>
END;
            for ($i=0;$i<$nbetape;$i++){
                $j=$i+1;
                 echo <<<END
                        <div id="etape">   <label for="etapes">Etape $j: </label><textarea id="etapes"    name="etapes[]">$etape[$i]</textarea><br></div>
END;
            }
            echo <<<END
                  </fieldset>
                  <input type="submit" value="Valider la recette">
                  </form>
                  <form action="index.php?todo=delete_$id&amp;page=validate" method =post>
                      <input type="submit" value="Détruire la recette">
                  </form>
                  </div>
END;
    }
}

$dbh=null;
?>
