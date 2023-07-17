<?php

if(isset($_GET["action"])) {
    switch($_GET["action"]) {
        case "register":

            // connexion à la base de données
            $pdo = new PDO("mysql:host=localhost; dbname=security_training; charset=utf8", "root", "");

            // Filtrer la saisie des champs du formulaires d'inscription
            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
            $pass1 = filter_input(INPUT_POST, "pass1", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $pass2 = filter_input(INPUT_POST, "pass2", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if($pseudo && $email && $pass1 && $pass2) {
                // var_dump("ok");die;
                $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                $requete->execute(["email" => $email]);
                $user = $requete->fetch();
                // si l'utilisateur existe
                if($user){
                    header("Location: register.php"); exit;
                } else {
                    // var_dump("utilisateur inexistant"); die;
                    // insertion de l'utilisateur en bdd
                    if($pass1 == $pass2 && strlen($pass1) >= 5) {
                        $insertUser = $pdo->prepare("INSERT INTO user (pseudo, email, password) VALUES (:pseudo, :email, :password)");
                        $insertUser->execute([
                            "pseudo" => $pseudo,
                            "email" => $email,
                            "password" => password_hash($pass1, PASSWORD_DEFAULT)
                        ]);
                        header("Location: login.php"); exit;
                    } else {
                        // message "les mots de passe ne sont pas identiques ou mot de passe trop court !
                    }
                }
            } else {
                // problème de saisie dans les chmpas de formulaire
            }
        break;

        case"login": 
            //connexion à l'application

            if($_POST["submit"]){
            // connexion à la base de données
                $pdo = new PDO("mysql:host=localhost; dbname=security_training; charset=utf8", "root", "");

                // filtrer les champs (faille XSS)
                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // si les filtres sont valides
                if($email && $password){
                    $requete = $pdo->prepare("SELECT * FROM user WHERE email = :email");
                    $requete->execute(["email" => $email]);
                    $user = $requete->fetch();
                    // var_dump($user);die;
                    // Est-ce que l'utilisateur existe;
                    if($user){
                        $hash = $user["password"];
                        if(password_verify($password, $hash)) {
                            $_SESSION["user"] = $user;
                            header("Location: home.php"); exit;
                        } else {
                            header("Location: login.php"); exit;
                            // message utilisateur inconnu ou mot de passe incorrect
                        }
                    } else {
                        // message utilisateur inconnu ou mot de passe incorrect
                        header("Location: login.php"); exit;
                    }
                }
            }

            header("Location: login.php"); exit;
        

        case "logout":
        break;
    }
}