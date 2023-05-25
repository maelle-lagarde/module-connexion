<?php
    // Vérifier si l'utilisateur est connecté
    // (assumant que vous avez déjà une logique d'authentification en place)
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        exit();
    }

    // Connexion à la base de données (à adapter selon votre configuration)
    $bdd = new PDO("mysql:host=localhost; dbname=moduleconnexion", 'root', 'root');

    // Récupérer les informations de l'utilisateur à partir de la base de données
    $userID = $_SESSION['user']; // Supposons que vous stockez l'ID de l'utilisateur dans la session
    $query = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = :userID");
    $query->bindParam(':userID', $userID);
    $query->execute();
    $userData = $query->fetch(PDO::FETCH_ASSOC);

    // Traitement du formulaire de mise à jour
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données soumises par le formulaire
        $newLogin= $_POST['login'];
        $newPrenom = $_POST['prenom'];
        $newNom = $_POST['nom'];
        $newPassword = $_POST['password'];

        // Mettre à jour les informations de l'utilisateur dans la base de données
        $query = $bdd->prepare("UPDATE utilisateurs SET login = :login, prenom = :prenom, nom = :nom, password = :password WHERE id = :userID");
        $query->bindParam(':login', $newLogin);
        $query->bindParam(':prenom', $newPrenom);
        $query->bindParam(':nom', $newNom);
        $query->bindParam(':password', $newPassword);
        $query->bindParam(':userID', $userID);
        $query->execute();

        // Rediriger vers une page de confirmation ou actualiser la page actuelle
        header("Location: profil.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>Modifier le profil</title>
</head>
<body>
    <div class="box">
        <h1>Profil test</h1>

        <form method="POST" action="profil.php">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" value="<?php echo $userData['login']; ?>"><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $userData['prenom']; ?>"><br>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo $userData['nom']; ?>"><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" value="<?php echo $userData['password']; ?>"><br>

            <input type="submit" value="Mettre à jour">
        </form>
    </div>
</body>
</html>
