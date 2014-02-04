<?php

function generateHTMLHeader($title, $cssfile){
// on ecrit HTML
echo <<<CHAINE_DE_FIN
<!DOCTYPE html>
<html>
<head>
  <title>$title</title>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="$cssfile" />
  <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="js/code.js"></script>
  <script type="text/javascript" src="js/jquery.autocomplete.js"></script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
</head>
<body>
CHAINE_DE_FIN;
}  

function generateHTMLFooter(){
echo <<<CHAINE_DE_FIN
</body>
</html>
CHAINE_DE_FIN;
}

global $page_list;
$page_list = array(
  array(
    "name"=>"welcome",
    "title"=>"Accueil de notre site",
    "menutitle"=>"Accueil"),
  array(
    "name"=>"search",
    "title"=>"Rechercher une recette",
    "menutitle"=>"Rechercher une recette"),
  array(
    "name"=>"match",
    "title"=>"Qu'y a-t-il dans mon frigo ?",
    "menutitle"=>"Qu'y a-t-il dans mon frigo ?"),
  array(
    "name"=>"publish",
    "title"=>"Partager une recette",
    "menutitle"=>"Partager une recette"),
  array(
    "name"=>"register",
    "title"=>"Inscription",
    "menutitle"=>"Inscription"),
    array(
    "name"=>"account",
    "title"=>"Mon Compte",
    "menutitle"=>"Mon Compte"),
    array(
    "name"=>"validate",
    "title"=>"Valider les recettes",
    "menutitle"=>"Valider les recettes"),
    array(
    "name"=>"printUniqueRecette",
    "title"=>"Imprimer une recette",
    "menutitle"=>"Imprimer une recette"
    ),
    array(
    "name"=>"changeinformation",
    "title"=>"Changer mes informations",
    "menutitle"=>"Changer mes informations"
    ),
    array(
    "name"=>"livrerecettes",
    "title"=>"Mon livre de recettes",
    "menutitle"=>"Mon livre de recettes"
    ),
        array(
    "name"=>"search_entree",
    "title"=>"Entrees",
    "menutitle"=>"Entrees"
    ),
        array(
    "name"=>"search_plat",
    "title"=>"Plats",
    "menutitle"=>"Plat"
    ),
        array(
    "name"=>"matchNom",
    "title"=>"Rechercher par nom",
    "menutitle"=>"Rechercher par nom"
    ),
        array(
    "name"=>"search_dessert",
    "title"=>"Desserts",
    "menutitle"=>"Desserts"
    ),
        array(
    "name"=>"modifier",
    "title"=>"Modifier Mes Recettes",
    "menutitle"=>"Modifier Mes Recettes"
    ),
        array(
    "name"=>"validate_encours",
    "title"=>"Modifier Les Recettes Publiées",
    "menutitle"=>"Modifier Les Recettes Publiées"
    )
    
);

function checkPage($askedPage){
    global $page_list;
      foreach($page_list as $page){
    if ($page['name']==$askedPage){
        return true;
    }     
  }
  return false;
}

function getPageTitle ($askedPage){
    global $page_list;
    foreach ($page_list as $page){
        if ($page['name']==$askedPage){
            return $page['title'];
        }
    }
}

function arrayToString($tab){
    $n=  count($tab);
    $output="[";
    for ($i=0;$i<$n-1;$i++){
        $output=$output."\"".$tab[$i]."\",";
    }
    $output=$output."\"".$tab[$i]."\"]";
    return $output;
}
?>