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


// Show message
$getMessage = $bdd->prepare('SELECT email,envoi,message,pseudo,color FROM messages JOIN users ON users.id = messages.users_id ORDER BY envoi ASC');
$getMessage->execute();
$showMessage = $getMessage->fetchAll(PDO::FETCH_ASSOC);
include('emoji.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <meta http-equiv="refresh" content="7" url='chat.php#bot'>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Chaterminal 😃</h1>
<ul>
<?php

    foreach ($showMessage as $showMessage) {
      $phpdate = strtotime($showMessage['envoi']);
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
      $string = substr($showMessage['email'], 0, strpos($showMessage['email'], '@'));
  ?>

  <?php if ($previousValue == $showMessage['pseudo']) { ?>
  <li class='marginmsg'>
    <?=make_links_clickable($showMessage['message'])?>
  </li>
  <?php }else { ?>
    <li class='pseudo--position'>
      <a href="test.php?pseudo=<?=$showMessage['pseudo']?>" target='iframe'>
      <span class='
      <?php if ($_SESSION['email'] == $showMessage['email']) { ?>
      <?php  echo "email"; ?>
      <?php }else{ ?>
      <?php    echo $showMessage['color']; ?>
      <?php  } ?>'>
      <?php echo $showMessage['pseudo']; ?>@<?=$string?></span>
      </a>
    </li>
    <li class='marginmsg'>
  <?=make_links_clickable($showMessage['message'])?>
  <span class='date'><?=$date?></span>
  </li>
  <?php
    }
  ?>


  <?php
  $previousValue = $showMessage['pseudo'];
  }
  ?>
</ul>
<div id='bot'></div>
</body>
</html>
