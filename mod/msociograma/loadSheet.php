<?php
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * 
 *
 * @package    mod_msociograma
 * @copyright  2009 - 2020 Marco Alarcón
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//msociograma is based in CESC sociogram, designed by Collell, J. and Escudé, C.


//This file corresponds to the  loadSheet.php.
//This form shows the lists options and allows upload the *.csv files.

global $CFG,$DB;

require_once("$CFG->dirroot/mod/msociograma/locallib.php");

echo '<script language="JavaScript" type="text/javascript" src="ajax.js"></script>';

//only for manager rol
if (!has_capability('mod/msociograma:gestion', $context)){  //access forbiden if no rol
	print_error('nocapabilities', 'msociograma');
}

//test the work mode of the page(list/enter). Default list
if (isset ($_GET['mode']))
	$mode = $_GET['mode'];
else
	$mode = 'list';

if (isset ($_GET['edit']))
	$edit = $_GET['edit'];
else
	$edit = 'closed';


$idActivityModule = $_GET['id']; 
//execute the mode work   
if (($mode == 'list') OR ($mode == 'update')){  //list students from sheet
	$html  = listado($idActivityModule,true);//get html code from list
	$html2 = listado($idActivityModule,false);//get html code from list without alias
	echo '<center><table><tr><td width=30% valign="top">';
	echo '<br><br>';
	
	 if (logintype($idActivityModule)==0){
		 
		echo "<a href=$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&page=students&mode=update>".get_string('update','msociograma')."</a>"; 
		echo '<br><br>';
		
	} else if (logintype($idActivityModule)==1){	
		
		if ($edit == 'open'){
			//link to add students
			echo "<a href=$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&page=students&mode=enter&edit=open>".get_string('addstudents','msociograma')."</a>"; 
			echo '<br><br>';
		}

		if ($edit == 'closed'){	
			echo '<a href=# onclick="imprimir(\'superdiv2\');" title="'.get_string('popup','msociograma').'">'.get_string('printusers','msociograma').'</a>'; 
			echo '<br><br>';
		}

		if ($edit == 'closed'){
			//link edit
			echo "<a href=$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&page=students&mode=list&edit=open>".get_string('openedit','msociograma')."</a>"; 
			echo '<br><br>';
		}

		if ($edit == 'open'){
			//link no edit
			echo "<a href=$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&page=students&mode=list&edit=closed>".get_string('closeedit','msociograma')."</a>"; 
			echo '<br><br>';
		}
	}
	
	echo '</td>';
	echo '<td> <center>';

	echo '<div id="superdiv">'.$html.'</div>';
	echo '</center>';
	echo '</td></tr></table></center>';

	if ($edit == 'closed')   {
		$html2= str_replace('<center><input type="submit" value="Actualizar"></center>','',$html2);//form button out
		echo '<div id="superdiv2" style="display: none;">'.$html2.'</div>';
	}
} elseif ($mode == 'enter'){  //show form to load the sheet
	echo '<center><table><tr><td width=20% valign="top">';
	echo "<a href=$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&page=students&mode=list>".get_string('listsheet','msociograma')."</a>";
	echo '<br><br>';
	echo '</td>';
	echo '<td>';
	csvForm($idActivityModule);
	echo '</td></tr></table></center>';
}  






?>