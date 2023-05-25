<?php
    session_start();

    // déconnexion de l'utilisateur.
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // suppression des variables de session.
        session_unset();
        session_destroy();

        // redirection vers la page de connexion.
        header('Location: index.php');
        exit;
    } else {
        // redirection vers la page de connexion si l'utilisateur n'est pas connecté.
        header('Location: index.php');
        exit;
    }
?>
