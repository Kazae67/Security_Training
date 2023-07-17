<?php
session_start();
$password = "monMotdePasse1234";
$password2 = "monMotdePasse1234";

/*
 *  Algorithm faible
 */
// Ne génère pas à chaque refresh, hashage FAIBLE court
$md5 = hash('md5', $password);
$md5_2 = hash('md5', $password2);
// echo $md5."<br>";
// echo $md5_2."<br>";

// Ne génère pas à chaque refresh, hashage FAIBLE un peu plus long
$sha256 = hash('sha256', $password);
$sha256_2 = hash('sha256', $password2);
// echo $sha256."<br>";
// echo $sha256_2."<br>";

/*
 * Algorithm fort
 * https://www.php.net/manual/en/function.password-hash.php#:~:text=password_hash()%20creates%20a%20new,algorithms%20are%20added%20to%20PHP.
 */ 

 // Génère à chaque refresh, hashage fort et n'est pas similaire 
 // PASSWORD_DEFALT (conseillé, se met à jour)
 $hash = password_hash($password, PASSWORD_DEFAULT);
 $hash2 = password_hash($password2, PASSWORD_DEFAULT);
//  echo $hash."<br>";
//  echo $hash2."<br>";

 // PASSWORD_ARGON2I
 $argo2i = password_hash($password, PASSWORD_ARGON2I);
 $argo2i_2 = password_hash($password2, PASSWORD_ARGON2I);
//  echo $argo2i."<br>";
//  echo $argo2i_2."<br>";

  // PASSWORD_BCRYPT
 $bcrypt = password_hash($password, PASSWORD_BCRYPT);
 $bcrypt_2 = password_hash($password2, PASSWORD_BCRYPT);
//  echo $bcrypt."<br>";
//  echo $bcrypt_2."<br>";


 // Saisie dans le formulaire de login
 $saisie = "monMotdePasse1234";

 $check = password_verify($saisie, $hash);
 $user = "Kaz";
 var_dump($check);
 // Si vérification $saisie, $hash, l'utilisateur entre en session.
if(password_verify($saisie, $hash)){
    echo "les mots de passe correspondent ! <br>";
    $_SESSION["user"] = $user;
    echo $user." est connecté !";
} else {
    echo "les mots de passe ne correspondent pas ! <br>";
}

?>