<?php
session_name("Utilisateur");
session_start();
require_once('datamanip.php');
ajouterRecetteUtilisateur ($_SESSION['login'],$_POST['idrecette']);
?>
