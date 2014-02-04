<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="js/code.js"></script>

<div style="width:400px;margin:auto;margin-bottom:200px">
    <div id="message" style="display: none;width: 385px;border: black 1px solid;padding: 5px;text-align: center">
    </div>
    <div id="attente" style="display: none;">
        Un instant<br />
        <img src="ajax-loader.gif" title="Loader" alt="Loader" />
    </div>
    <form action="" id="demoForm" method="post" style="width:400px;margin-bottom: 20px">
        <fieldset style="height:120px;width:360px;">
            <legend>Formulaire</legend>
            <span style="font-size: 0.9em;">Quel est le nom du prof d'amphi du modal web?</span>
            <p>
                <label for="nom">Nom:</label>
                <input type="text" name="nom" id="nom" value="" />
            </p>
            <p>
                <input type="submit" name="Soumettre" id="submit" style="float: right; clear: both; margin-right: 3px;" value="Soumettre" />
            </p>
        </fieldset>
    </form>
</div>



<?php
    // on attend un peu
    sleep(1);
 
    if ($_POST['nom']!="Rossin") {
            $return['erreur'] = true;
            $return['message'] = "Ne vas-tu jamais en amphi???";
    }
    else {
            $return['erreur'] = false;
            $return['message'] = "VICTOIRE!!!";
    }
 
    echo json_encode($return);
?>