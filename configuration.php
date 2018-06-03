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
    <?php echo file_get_contents('main-cat.htm'); ?>

    <table class="settings">
      <tr>
        <th hidden="true">SECTIONS   </th>
        <th hidden="true">PARAMETERS</th>
      </tr>
      <tr>
        <td class="settings-section" rowspan="1">GENERAL</td>
        <td class="settings-parameters">
        <p class="settings-params-title"> EXTRA IMAGE FORMATS </p>
        <p> FITS support : no </p>
        <p> DICOM support : no </p>
        </td>
      </tr>
      <tr>
        <td class="settings-section" rowspan="2">ACCOUNT</td>
        <td class="settings-parameters">
        <p class="settings-params-title"> STORAGE </p>
        <p> Available space : </p>
        <p> Snapshot per image count : </p>
        <p> Gamma : </p>
        <p> Floating point editing : </p>
        </td>
      </tr>
      <tr>
        <td class="settings-parameters">
        <p class="settings-params-title"> PREFERENCES </p>
        <p> Language : English </p>
        <p> Password </p>
        </td>
      </tr>
      <tr>
        <td class="settings-section" rowspan="3">ADMIN</td>
        <td class="settings-parameters">
        <p class="settings-params-title"> ACCOUNT MANAGEMENT </p>
        </td>
      </tr>
      <tr>
        <td class="settings-parameters">
        <p class="settings-params-title"> STORAGE MANAGEMENT </p>
        <p> Default storage space per user : </p>
        <p> Maximum size of imported images : </p>
        </td>
      </tr>
      <tr>
        <td class="settings-parameters">
        <p class="settings-params-title"> COMMUNICATION </p>
        <p> HTTPS : disabled </p>
        <p> Portfolio server IP : 0.0.0.0</p>
        </td>
      </tr>
    </table>
