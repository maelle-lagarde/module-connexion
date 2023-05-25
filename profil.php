<?php
    // connexion à la base de données.
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "moduleconnexion";

    session_start();

    // vérifier si l'utilisateur est connecté.
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Rediriger vers la page de connexion
        header("Location: connexion.php");
        exit;
    }

    $message = ""; // variable pour stocker le message de succès.

    try {
        // connexion avec la BDD.
        $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // configurer PDO pour lancer des exceptions en cas d'erreur.
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // récupérer les informations de l'utilisateur connecté.
        $login = $_SESSION['login'];
        $stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // si le formulaire est soumis.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];

            // mettre à jour les informations de l'utilisateur dans la BDD.
            $stmt = $bdd->prepare("UPDATE utilisateurs SET prenom = :prenom, nom = :nom WHERE login = :login");
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':login', $login);
            $stmt->execute();

            // mettre à jour les informations de l'utilisateur dans la session.
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;

            $message = "Vos informations ont été modifiées avec succès!";
        }
    } catch(PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }

    $bdd = null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>Profil</title>
</head>
<body>
    <div class="box">
        <h1>Profil</h1>
        <p class="sub">Pour modifier votre profil veuillez utiliser le formulaire ci-dessous!</p>

        <?php if (!empty($message)) : ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="profil.php">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" value="<?php echo $_SESSION['login']; ?>"><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $_SESSION['prenom']; ?>"><br>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo $_SESSION['nom']; ?>"><br>

            <input type="submit" value="Mettre à jour">
        </form>

        <a href="index.php"><button class="accueil-button">Accueil</button></a>

        <a href="deconnexion.php"><button class="deconnexion-button">Déconnexion</button></a>

    </div>
</body>
</html>