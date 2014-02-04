<?php
function generate(){
if(array_key_exists('todo',$_GET) && explode("_",$_GET['todo'])[0] == "unique"){
    Recette::printRecette(explode("_",$_GET['todo'])[1]);    
     }
}
?>
