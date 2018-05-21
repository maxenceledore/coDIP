<?php
//    Copyright (C) 2018  Maxence Le Doré (maxence.ledore@gmail.com)

//    This file is part of coDIP.

//    coDIP is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of 
//    the License, or (at your option) any later version.

//    coDIP is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.

//    You should have received a copy of the GNU Affero General Public
//    License along with coDIP.
//    If not, see <http://www.gnu.org/licenses/>.
?>

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
