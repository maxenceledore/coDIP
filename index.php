<?php

$pipe_liste = popen('cd photos && /bin/ls ./photos/ -1 *.jpg 2>/dev/null','r');

$stdout = fread($pipe_liste, 16384);

$fichiers_gal = explode("\n", $stdout);

pclose($pipe_liste);

?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <h1>Gallerie</h1>
    <div id="import-et-retour">
    <form method="post" action="import.php" enctype="multipart/form-data">
    <input type="file" name="fichier" id="fichier"/>
    <input type="submit" name="submit" value="Importer"/>
    </form>
    </div>

    <div id="cadre-gallerie">
    <?php
      foreach($fichiers_gal as $fichier) {
        echo "<a href=\"./vue.php?img_id=$fichier\">
                <img alt=\"$fichier\" src=\"./min/$fichier\"/>
             </a>";
      }
    ?>
    </div>

  </body>
</html>
