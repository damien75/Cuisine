<?php
function generate(){
$type="plat";
   $nb=  nbRecettesType($type);
   $nbrecettesparpage=2;

   echo <<<END
   <input type="button" name=precedent class="precedent"  value="Precedent" onclick="self.location.href='index.php?page=search_$type&amp;todo=precedent'"> 
   <input type="button" name=suivant class="suivant"  value="Suivant" onclick="self.location.href='index.php?page=search_$type&amp;todo=suivant'">
END;
   
if (!(array_key_exists('todo',$_GET))){
    printLimiteType($type,0, $nbrecettesparpage);
    $_SESSION['indice_debut']=0;
}
else {
    if($_GET['todo']=="suivant"){
    $_SESSION['indice_debut']=$_SESSION['indice_debut']+$nbrecettesparpage;
    printLimiteType($type,$_SESSION['indice_debut'],$nbrecettesparpage);
    }
    if($_GET['todo']=="precedent"){
    $_SESSION['indice_debut']=$_SESSION['indice_debut']-$nbrecettesparpage;
    printLimiteType($type,$_SESSION['indice_debut'],$nbrecettesparpage);
    }
    
}
echo <<<END

   <input type="button" name=precedent class="precedent"  value="Precedent" onclick="self.location.href='index.php?page=search_$type&amp;todo=precedent'"> 
   <input type="button" name=suivant class="suivant"  value="Suivant" onclick="self.location.href='index.php?page=search_$type&amp;todo=suivant'">
   
END;

if ($_SESSION['indice_debut']+$nbrecettesparpage>$nb-1){
echo <<<END
   <script>
                             $(".suivant").hide();                       
   </script>     
   
END;
}
if ($_SESSION['indice_debut']<$nbrecettesparpage){
echo <<<END
   <script>
                             $(".precedent").hide();                       
   </script>     
   
END;
}
}
?>
