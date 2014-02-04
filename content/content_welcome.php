<?php

function generate (){
    echo <<<CHAINE_DE_FIN
<h1> Bienvenue sur notre site de recettes </h1>
<h2> Découvrez les fonctionnalités du site : <br>

Vous pouvez vous inscrire facilement pour profiter de nombreux avantages : </h2>

   <h3>Rechercher des recettes</h3>
            <ul>
                <li><h4>Par nom</h4></li>
                <li><h4>Par ingrédient</h4></li>
                <li><h4>Les dernières recettes publiées</h4></li>
            </ul>
   <h3>Votre Compte</h3>
            <ul>
                <li><h4>Partagez des recettes dont vous seul aves le secret</h4></li>
                <li><h4>Elaborez facilement un livre de vos recettes préférées</h4></li>
            </ul>
CHAINE_DE_FIN;
}
?>
