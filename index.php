<?php
session_start();
try {
  $bdd= new PDO('mysql:host=localhost;dbname=chat;charset=utf8','root', 'Five137');
}
catch (Exception $e) {
  die('Erreur : '.$e->getMessage());
}

function sV($txt) {
    $txt = filter_var($txt, FILTER_SANITIZE_STRING);
    $txt = trim($txt);
    if (!empty($txt)) {
    return $txt;
    }else{
      return "";
    }
}

function sVe($txt) {
  $txt = filter_var($txt, FILTER_SANITIZE_EMAIL);
  $txt = filter_var($txt, FILTER_VALIDATE_EMAIL);
  if (!empty($txt)) {
    return $txt;
  }else{
    return "";
  }
}

// function emoticon($text){
//   $test = preg_quote(":)", "#");
//   $match = '(?<=\s|^)(' . $test .')(?=\s|$)';
//   $text = preg_replace($test, '<img src="emoji/act-up.png">', $text);
//   return $text;
// }

$classColor = array("red", "blue", "green", "white", "orange");


if (!isset($type) && !isset($nametxt) && !isset($nameinput) || !isset($_SESSION['id'])) {
  $type = 'text';
  $nametxt = 'first';
  $nameinput = 'connection';
}


// login
if (sV($_POST['first']) == "/connect") {
  $_v = 'Votre adresse mail ୧(๑❛ั⌔❛ั๑)୨ ॢ : ';
  $nametxt = 'login';
  $type = 'email';
  $nameinput = 'verifylogin';
}

if (isset($_POST['login']) && isset($_POST['verifylogin'])) {
  $_SESSION['email'] = sV($_POST['login']);
  $_v = 'Votre mot de passe ( º﹃º )  : ';
  $nametxt = 'loginpwd';
  $type = 'password';
  $nameinput = 'verifypwd';
}

if (isset($_POST['loginpwd']) && isset($_POST['verifypwd'])) {
  $verifypwd = sV($_POST['loginpwd']);
  $verifymail = $_SESSION['email'];
  $log = $bdd->prepare("SELECT * FROM users WHERE email = '$verifymail'");
  $log->execute();
  $verify = $log->fetchAll(PDO::FETCH_ASSOC);
  $verify = $verify['0'];
  if (password_verify($verifypwd, $verify['password'])) {
    $_v = 'Bienvenue !';
    $getID = $bdd->prepare("SELECT * FROM users WHERE email = :email ");
    $getID->execute(array(
      'email' => $_SESSION['email'],
    ));
    $id = $getID->fetchAll(PDO::FETCH_ASSOC);
    $id = $id['0'];
    $pseudo = $id['pseudo'];
    $id = $id['id'];
    $_SESSION['id'] = $id;
    $_SESSION['pseudo'] = $pseudo;

  }else{
    $_v = "Votre mot de passe ou votre adresse mail n'est pas correcte (´･_･`)<br>
    Si vous ne vous en souvenez plus, taper /help et recréer un nouveau compte ⊙▂⊙";
    $nametxt = 'loginpwd';
    $type = 'password';
    $nameinput = 'verifypwd';
  }
}

if (sV($_POST['chat']) == "/disconnect" || sV($_POST['loginpwd']) == '/help') {
  session_destroy();
  header("LOCATION: index.php");
}

// Register : POSSIBILITE DE FAIRE UNE FONCTION
if (sV($_POST['first']) == '/register' && isset($_POST['connection'])) {
  $_v = 'Votre pseudo : ';
  $nametxt = 'pseudo';
  $nameinput = 'verifypseudo';
}

if (isset($_POST['pseudo']) && isset($_POST['verifypseudo'])) {
  $_SESSION['pseudo'] = sV($_POST['pseudo']);
  if (empty($_SESSION['pseudo'])) {
    $_v = "Votre pseudo n'est pas correcte";
  }else{
  $_v = 'Votre adresse mail : ';
  $nametxt = 'email';
  $type = 'email';
  $nameinput = 'verifyemail';
  }
}

if (isset($_POST['email']) && isset($_POST['verifyemail'])) {
  $_SESSION['email'] = sVe($_POST['email']);
  if (empty($_SESSION['email'])) {
    $_v = "Votre adresse email n'est pas correcte";
  }
  $_v = 'Votre mot de passe : ';
  $nametxt = 'password';
  $type = 'password';
  $nameinput = 'verifypassword';
}

if (isset($_POST['password']) && isset($_POST['verifypassword'])) {
  $_v = 'Merci pour votre inscription o(^-^)o<br>';
  $nametxt= 'chat';
  $type= 'text';
  $nameinput = 'verifyinput';

    if (isset($_SESSION['pseudo']) && isset($_SESSION['email']) && isset($_POST['password']) ) {
      $hash = password_hash(sV($_POST['password']), PASSWORD_DEFAULT);
      $send = $bdd->prepare('INSERT INTO users (pseudo,email,password,color)
      VALUES (:pseudo,:email,:password,:color)');
      $send ->execute(array(
        'pseudo' => $_SESSION['pseudo'],
        'email' => $_SESSION['email'],
        'password' => $hash,
        'color' => $classColor[array_rand($classColor)],
      ));
      $send = null;
      $pseudo = $_SESSION['pseudo'];
      $email = $_SESSION['email'];
      $getID = $bdd->prepare("SELECT * FROM users WHERE pseudo ='$pseudo' && email = '$email' ");
      $getID->execute();
      $id = $getID->fetchAll(PDO::FETCH_ASSOC);
      $id = $id['0'];
      $id = $id['id'];
      $_SESSION['id'] = $id;
    }
}



if (isset($_SESSION['pseudo']) && isset($_SESSION['email']) && isset($_SESSION['id']) && $_POST['chat'] != '/disconnect') {
  $nametxt= 'chat';
  $type= 'text';
  $nameinput = 'verifyinput';
  $message = sV($_POST['chat']);
  if (!empty($message)) {
  $send = $bdd->prepare("INSERT INTO messages(message,users_id)
  VALUES (:message,:users_id)");
  $send->execute(array(
    'message' => $message,
    'users_id' => $_SESSION['id'],
  ));
}else{
  $_v = "Votre message est vide ಠ_ಠ";
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="style.css">
  <title>Document</title>
</head>
<body>

    <iframe class='vtff' width="25%" height="100%" src="test.php" frameborder="0" name='iframe'></iframe>

    <iframe class='iframe--chat' src="chat.php#bot" height="" width="75%" frameborder="0" fullscreen></iframe>

  <ul>
    <li class='input--position'>
    <form method='post' action=''>
        <?=$_v?><br>
        <span class='email'>
        <?=$_SESSION['pseudo']?>@</span>:<span class='home'>~/chat</span><span>$</span>
        <input type="<?=$type?>" name='<?=$nametxt?>' class='input' autofocus>
      <input type="submit" class="sub" name='<?=$nameinput?>'>
    </form>
    </li>
  </ul>
</body>
</html>
