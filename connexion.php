<?php
    session_start();

    // connexion à la BDD.
    $bdd = new PDO("mysql:host=localhost; dbname=moduleconnexion", 'root', 'root');

    // vérifie si l'utilisateur est déjà connecté.
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // Redirection vers la page de destination pour l'utilisateur connecté.
        header('Location: index.php');
        exit;
    }

    // vérifie les informations de connexion.
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $login = $_POST["login"];
        $password = $_POST["password"];

        try {
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // vérifie les informations de connexion dans la BDD.
            $query = "SELECT * FROM utilisateurs WHERE login = ? AND password = ?";
            $stmt = $bdd->prepare($query);
            $stmt->execute([$login, $password]);

            if ($stmt->rowCount() === 1) {
                // informations de connexion valides.
                $_SESSION['loggedin'] = true;
                $_SESSION['login'] = $login;

                // redirection vers la page de destination pour l'utilisateur connecté.
                header('Location: index.php');
                exit;
            } else {
                $error = "Login ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            die("Échec de la connexion à la base de données : " . $e->getMessage());
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>Connexion</title>
</head>
<body>
    <div class="box">
        <h1>Connexion</h1>
        <p class="sub">Renseignez votre login et votre mot de passe pour accéder au site!</p>

        <?php
            if (isset($error)) {
                echo "<p style='color: red;'>$error</p>";
            }
        ?>

        <form method="POST" action="">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" required><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Se connecter">
        </form>

        <a href="index.php"><button class="accueil-button">Accueil</button></a>

    </div>
</body>
</html>