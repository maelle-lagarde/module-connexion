<?php
    session_start();

    // connexion à la BDD.
    $bdd = new PDO("mysql:host=localhost; dbname=moduleconnexion", 'root', 'root');
    $user = array(
        'login' => $_SESSION['login'],
        'prenom' => $_SESSION['prenom'],
        'nom' => $_SESSION['nom'],
        'password' => $_SESSION['password']
    );

    // vérifie si l'utilisateur est connecté.
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Redirection vers la page de connexion.
        header('Location: connexion.php');
        exit;
    }

    try {
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // récupération des informations de l'utilisateur connecté.
        $query = "SELECT * FROM utilisateurs WHERE id = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$_SESSION['id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Échec de la connexion à la base de données : " . $e->getMessage());
    }

    // traitement du formulaire de mise à jour du profil.
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $login = $_POST["login"];
        $prenom = $_POST["prenom"];
        $nom = $_POST["nom"];
        $password = $_POST["password"];

        // mise à jour des informations dans la BDD.
        $query = "UPDATE utilisateurs SET prenom = ?, nom = ?, password = ? WHERE id = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$prenom, $nom, $password, $_SESSION['id']]);

        // redirection vers la page de profil mise à jour.
        header('Location: profil.php');
        exit;
    }

    // validation des données du formulaire si nécessaire.
    if (isset($_SESSION['profil_success_message'])) {
        $successMessage = $_SESSION['profil_success_message'];
        unset($_SESSION['profil_success_message']); // suppression du message de la variable de session.
    }

?>

<!DOCTYPE html>
<html lang="fr">
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

        <?php if (isset($successMessage)) : ?>
            <p style="color: green;"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <form method="POST" action="modification.php">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" value="<?php echo $user['login']; ?>" required><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required><br>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required><br>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" value="Mettre à jour">
        </form>

        <a href="index.php"><button class="accueil-button">Accueil</button></a>

        <a href="deconnexion.php"><button class="deconnexion-button">Déconnexion</button></a>
    </div>
</body>
</html>