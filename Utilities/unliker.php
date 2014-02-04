<?php
session_name("Utilisateur");
session_start();
require_once('datamanip.php');
retirerRecetteUtilisateur($_SESSION['login'], $_POST['idrecette']);
?>
