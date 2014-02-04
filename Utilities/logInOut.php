<?php

function logIn () {
    if (Utilisateur::testerMdp($_POST['login'], $_POST['password'])){
        $_SESSION['loggedIn'] =true;
        $_SESSION['login'] = $_POST['login'];
    }
}

function logOut (){
    unset($_SESSION['loggedIn']);
    $_GET['page']="welcome";
}

function estConnecte(){
return (array_key_exists('loggedIn',$_SESSION) && $_SESSION['loggedIn']== true);
}
?>
