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

//    dump copyright disclaimer and AGPL license notice.
echo file_get_contents("./disclaimer_notice.html");

?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
  <?php
          if(isset($_GET['page']))
            switch($_GET['page']) {
            case 'about':
              include './about.php';
              break;
            case 'help':
              include './help.php';
              break;
            case 'phototeque':
              include './phototeque.php';
              break;
            case 'vue':
              include './vue.php';
              break;
            case 'edition':
              include './edition.php';
              break;
            case 'settings':
              include './configuration.php';
              break;
            default:
              include './login_page.htm';
              }
           else
             include './login_page.htm';
  ?>
  <!--<div id="feet">
  </div>-->
  </body>
</html>
