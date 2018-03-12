# php-chat-db
Un chat, sans javascript


https://www.dorianc.monprofil.be/index.php

Il reste plusieurs choses à améliorer : 
1. Eviter les doublons
2. Rendre le code plus lisible grâce au MVC
3. Retirer les "br" pour display les 10 derniers messages des connectés 
4. Trouver le problème avec l'heure : sur mon portable, l'heure reste affiché à "now", alors que sur mon fixe, tout fonctionne correctement (cache ?)
5. Message/tuto pour les 4/5 commandes disponibles, à display lors de la première connection
6. Trouver un autre moyen que les position: absolute, même si c'est pas évident avec les iframe
7. Faire plus de fonctions pour rendre le tout plus facile
8. Trouver des manières plus évidentes pour display les dates, même si, jouer avec les secondes, c'est plutôt chouette

à ajouter (même si l'effet terminal risque d'en prendre un coup) : 
1. Emojis
2. Photo de profil
3. Description


Ce que j'ai appris 
1. Les iframes utilisant une même page css ne font pas mal aux yeux quand la page reload
2. La gestion des classes/input=[type/name] avec le PHP, les possibilités sont presques illimitées
3. L'importance de bien nommer les classes en CSS , d'éviter de surcharger une page HTML avec du PHP
4. Webhoost, c'est merdique
5. array_rand() pour donner aléatoirement une couleur à un pseudo
6. Utiliser opacity plutôt que de rendre le texte petit et illisible
7. Apporter du dynamisme à un site ne passe pas tout le temps par de gros <script>. 
  Possibilité de favoriser l'ethos au pathos : if ($_SESSION['email') == $showPseudo['email']) {...;}
