<?php
function generateLateral (){
    $tab = afficherNomsDernieresRecettes(5);
    foreach ($tab as $key => $value) {
        $prenom= explode("_", $value)[0];
        $nom= explode("_", $value)[1];
        $date= explode("_", $value)[2];
        $idrecette =explode("_", $value)[3];
        echo <<<END
    <a href="index.php?page=printUniqueRecette&amp;todo=unique_$idrecette"><div class="menuItemn" style="line-height:2; overflow:hidden;">$key <br>Postée par $prenom $nom le $date</div></a>
END;
    }
}
?>
