<?php
    session_start();

    // vérifie si l'utilisateur est déjà connecté.
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // redirection vers la page de destination pour l'utilisateur connecté.
        header('Location: index.php');
        exit;
    }

    $message = ""; // variable pour stocker le message de succès.

    // vérification des informations d'inscription.
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $login = $_POST["login"];
        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm_password"];

        // validation des contraintes de mot de passe.
        $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

        if (!preg_match($passwordPattern, $password)) {
            $error = "Le mot de passe doit contenir au moins huit caractères, avec au moins une majuscule, une minuscule, un chiffre et un caractère spécial.";
        } elseif ($password !== $confirmPassword) {
            $error = "La confirmation du mot de passe ne correspond pas.";
        } else {
            // connexion à la BDD.
            $bdd = new PDO("mysql:host=localhost; dbname=moduleconnexion", 'root', 'root');

            try {
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // vérification de l'unicité du login.
                $query = "SELECT COUNT(*) AS count FROM utilisateurs WHERE login = ?";
                $stmt = $bdd->prepare($query);
                $stmt->execute([$login]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result['count'] > 0) {
                    $error = "Le login est déjà utilisé. Veuillez en choisir un autre.";
                } else {
                    // insertion des informations dans la BDD.
                    $query = "INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?, ?, ?, ?)";
                    $stmt = $bdd->prepare($query);
                    $stmt->execute([$login, $prenom, $nom, $password]);

                    $message = "Votre inscription a été validé avec succès!";
                }
            } catch (PDOException $e) {
                die("Échec de la connexion à la base de données : " . $e->getMessage());
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>Inscription</title>
</head>

<body>
    <div class="box">
        <h1>Inscription</h1>
        <p class="sub">Merci de remplir le formulaire ci-dessous pour vous inscrire!</p>

        <?php
            if (isset($error)) {
                echo "<p style='color: red;'>$error</p>";
            }
        ?>

        <?php if (!empty($message)) : ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="form">
            <form method="POST" action="">
                <label for="login">Login :</label>
                <input type="text" id="login" name="login" required><br>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required><br>

                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required><br>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required><br>

                <label for="confirm_password">Confirmation du mot de passe:</label>
                <input type="password" id="confirm_password" name="confirm_password" required><br>

                <input type="submit" value="S'inscrire">
            </form>
        </div>
        <a href="index.php"><button class="accueil-button">Accueil</button></a>

    </div>

</body>
</html>