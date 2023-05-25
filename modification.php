<?php
    session_start();

    // Traitement du formulaire de modification du profil
    // ...

    // message de validation après inscription.
    $_SESSION['inscription_success_message'] = "Votre inscription a été enregistré avec succès.";

    // message de validation après avoir mis à jour le profil avec succès.
    $_SESSION['profil_success_message'] = "Votre profil a été mis à jour avec succès.";

    // Redirection vers la page de profil
    header('Location: profil.php');
    exit;
?>
