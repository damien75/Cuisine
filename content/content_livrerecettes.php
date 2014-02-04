<?php
function generate (){
    if (array_key_exists('loggedIn', $_SESSION)){
    echo "Pour ajouter des recettes à votre livre, visitez notre site, dès qu'une recette vous plait, cliquez sur ajouter à mon livre, et elle figurera sur cette page.";
    afficherLivreRecette($_SESSION['login']);
}
else {
    echo "<div class='alerte'>Il faut vous connecter pour accéder à cette page.</div>";
}
}
?>
