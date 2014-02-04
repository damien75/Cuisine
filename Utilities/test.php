
<?php
session_start();
echo <<<END
  <meta charset="utf-8" />
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
END;
require_once('datamanip.php');
require_once('utils.php');



$liste = arrayToString(listeTotaleIngredient());
echo <<<END
<script>
  $(function() {
    var availableTags = $liste;
    $( ".ingredients" ).autocomplete({
      source: availableTags
    });
  });
  </script>
  
<div id="ingredient" class="ui-widget">   <label for="ingredients">Ingredient : </label><input class="ingredients" name="ingredients[]"></input>                                                                        
   <br></div>

END;
?>
