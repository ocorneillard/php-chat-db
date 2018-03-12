<?php
session_start();
try {
  $bdd= new PDO('mysql:host=localhost;dbname=chat;charset=utf8','root', 'Five137');
}
catch (Exception $e) {
  die('Erreur : '.$e->getMessage());
}

function make_links_clickable($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank">$1</a>', $text);
}
function emoticon($text){
  return preg_replace('/\s:)\s/', '<img src="emoji/act-up.png">', $text);
}
// Show message
$getPseudo = $bdd->prepare('SELECT email,pseudo,color,id FROM users');
$getPseudo->execute();
$showPseudo = $getPseudo->fetchAll(PDO::FETCH_ASSOC);

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



<p>
  <?php
  foreach ($showPseudo as $showPseudo) {
    if ($_GET['pseudo'] == $showPseudo['pseudo']) { ?>

        <span class='
          <?php if ($_SESSION['email'] == $showPseudo['email']) { ?>
          <?php  echo "email"; ?>
          <?php }else{ ?>
          <?php    echo $showPseudo['color']; ?>
          <?php  } ?>'>
          Email : <?= $showPseudo['email'] ?>
        </span>
      <br>
  <div class="pseudal">
    Pseudo : <?= $showPseudo['pseudo'] ?>
  </div>
</p>
<p>
  <div class="lastmsg">
    Derniers messages postés :
  </div>
</p>
  <div class="allmsg">

<?php
      $id = $showPseudo['id'];
      $getMessage = $bdd->prepare("SELECT * from messages WHERE users_id = :id ORDER BY envoi DESC LIMIT 10");
      $getMessage->execute(array(
        'id' => $id,
      ));
      $message = $getMessage->fetchAll(PDO::FETCH_ASSOC);
      foreach ($message as $message) {
        $phpdate = strtotime($message['envoi']);
        $ago = time()-$phpdate;

        if ($ago > 604800) {
          $date = gmdate("l jS \of F", $phpdate);
        } elseif ($ago > 86400) {
          $date = gmdate("l H:i", $phpdate);
        } elseif ($ago > 3600) {
          $date = 'il y a ' .gmdate("H \h i \m s \s", $ago);
        } elseif ($ago < 3600 && $ago > 60) {
          $date = 'il y a ' .gmdate("i \m s \s", $ago);
        } elseif ($ago < 60 && $ago > 10) {
          $date = 'il y a ' .gmdate("s", $ago);
        } elseif ($ago <= 10) {
          $date = "now";
        }
?>
<p>
<?php
if (!isset($_SESSION['email']) && !isset($_SESSION['id'])) {
  echo "Vous devez vous connectez pour voir les derniers messages postés";
  return ;
}else{
  echo make_links_clickable($message['message']).'<br>';
}
 ?>
  <span class="datemsg">
    <?= $date ?>
  </span>
</p>
<?php
      }
    }
  }
?>
</div>
<img src="emoji/act-up.png">
</body>
</html>
