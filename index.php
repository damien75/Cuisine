<?php
session_name("Utilisateur");
session_start();
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id();
        $_SESSION['initiated'] = true;
    }

require ('Utilities/utils.php');
require ('Utilities/datamanip.php');
require ('Utilities/printForms.php');
require ('Utilities/logInOut.php');
   global $form_values_valid;
   $form_values_valid = false;

              if(array_key_exists('todo',$_GET) && $_GET['todo'] == "login") {
                logIn();
                }
              if(array_key_exists('todo',$_GET) && $_GET['todo'] == "logout") {
                  logOut();
                }
                

                
if (isset( $_GET['page'])) {$askedPage=$_GET['page'];}
else {$askedPage = 'welcome';}

$authorized = checkPage($askedPage);

if ($authorized){
    $pageTitle = getPageTitle($askedPage);
}
else {$pageTitle = "erreur : page non autorisée ou inexistante";}

if (array_key_exists('loggedIn', $_SESSION)){
            if (array_key_exists('todo',$_GET) && explode("_",$_GET['todo'])[0]=="like"){
                $idrecette = explode("_",$_GET['todo'])[1];
                ajouterRecetteUtilisateur($_SESSION['login'], $idrecette);
            }
            if (array_key_exists('todo',$_GET) && explode("_",$_GET['todo'])[0]=="unlike"){
                $idrecette = explode("_",$_GET['todo'])[1];
                retirerRecetteUtilisateur($_SESSION['login'], $idrecette);
       }
}

generateHTMLHeader($pageTitle, 'StyleSheet.css');
?>


<!-- en tete de la page et modules -->



<div id="global">
        <div id="logo"><img src ="Images/logo1.jpg" alt ="Cuisinons" height="200"></div><div class="title"><p>Find Me, Eat Me, Like Me</p></div>
        
<!-- Barre latérale droite -->       
        
        <div id="lateral">
            <div id="connexion">
                            <?php
                if(array_key_exists('login',$_SESSION) &&array_key_exists('loggedIn',$_SESSION)&& $_SESSION['loggedIn']) {
                    $prenom = Utilisateur::getInfoUtilisateurs('prenom',$_SESSION['login']);
                echo <<<END
                    <h5>Bonjour $prenom</h5> 
END;
                printLogoutForm($askedPage);
                } else {
                printLoginForm($askedPage);
                }
                ?>   
            </div>
            <a href="index.php?page=welcome"><div class="menuItem">Accueil</div></a>
                
            <div class ="sectionMenu">
                <div class="menuItemDeroulant">Afficher toutes les recettes</div>
                    <a class="sousMenu" href="index.php?page=search_entree">Entrées</a>
                    <a class="sousMenu" href="index.php?page=search_plat">Plats</a>
                    <a class="sousMenu" href="index.php?page=search_dessert">Desserts</a>
                    <a class="sousMenu" href="index.php?page=search">Toutes les recettes</a>
                </div>
                    
            <div class="sectionMenu">
                <div class="menuItemDeroulant">Rechercher une recette</div>
                    <a class="sousMenu" href="index.php?page=match">Par ingrédients</a>
                    <a class="sousMenu" href="index.php?page=matchNom">Par nom</a>
            </div>
            <a href="index.php?page=publish"><div class="menuItem">Partager une recette</div></a>
            <a href="index.php?page=livrerecettes"><div class="menuItem">Mon Livre de Recettes</div></a>
        </div>

<!-- Chargement du contenu de la page -->

            <div id="content">
                <?php
                
                if ($authorized== false) {
                    echo "<div class='alerte'>Vous n'êtes pas autorisé à accéder à cette page</div>";
                }
                else {
                    $l = "content/content_".$askedPage.".php";
                    require $l;
                    generate();
                }
                ?>
            </div>
        
<!-- Barre latérale à droite -->

            <div id="lateraldroit">
                <div id="new">Nouvelles recettes</div>
                    <?php
                    require ('content/nouvelleRecette.php');
                    generateLateral();
                    ?>
            </div>
</div>
<?php
generateHTMLFooter();
?>