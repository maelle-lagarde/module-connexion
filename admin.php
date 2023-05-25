<?php

    session_start();

    // vérifier si l'utilisateur est "admin".
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['login'] !== 'admin') {
        header("Location: connexion.php");
        exit;
    }

    // connexion à la BDD.
    $host = "localhost";
    $db = "moduleconnexion";
    $user = "root";
    $password = "root";

    try {
        $bdd = new PDO("mysql:host=$host;dbname=$db", $user, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }

    // récupérer les informations des utilisateurs depuis la BDD.
    $query = "SELECT * FROM utilisateurs";
    $stmt = $bdd->prepare($query);
    $stmt->execute();
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>Page d'administration</title>
</head>
<body>
    <div class="box">
        <h1>Page d'administration</h1>

        <table>
            <tr>
                <th>Id</th>
                <th>Login</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Mot de passe</th>
            </tr>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?php echo $utilisateur['id']; ?></td>
                    <td><?php echo $utilisateur['login']; ?></td>
                    <td><?php echo $utilisateur['prenom']; ?></td>
                    <td><?php echo $utilisateur['nom']; ?></td>
                    <td><?php echo $utilisateur['password']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>