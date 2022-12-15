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
 
 //This file corresponds to the statusSheet.php
 //Shows the sociometric status data in a chart

	echo '<SCRIPT language="JavaScript" type="text/javascript" src="ajax.js"></SCRIPT>';
	echo '<TABLE><TR><TD WIDTH=10% VALIGN="top">';

	//link for stundents print
	echo '<A HREF=# onclick="imprimir(\'divStatus\');" title="'.get_string('popup','msociograma').'">'.get_string('printgraphics','msociograma').'</A>'; 
	echo '<BR><BR>';
                
	echo '</TD><TD>';
    $html = '<TITLE>'.get_string('information','msociograma').'</TITLE>'.estiloSaltaPagina();
	$html=$html."<META CHARSET='UTF-8' />";
	  echo '<DIV id="super"></DIV>'; 
	
	$html=$html. tableCescChartjs ('selectPositiv',$COURSE->id, $id, $currentgroup, array(1), 1, get_string('selectPositiv','msociograma').' ('.$_SESSION['group'].')','red' );
	$html=$html. cloudTagsSheet ($COURSE->id, $id,$currentgroup, array(1),1, get_string("selectPositiv", "msociograma").' ('.$_SESSION['group'].')','brownbar');
  
	$html=$html. tableCescChartjs ('selectNegativ',$COURSE->id, $id, $currentgroup, array(2), 1, get_string('selectNegativ','msociograma').' ('.$_SESSION['group'].')','blue' );
	$html=$html.cloudTagsSheet ($COURSE->id, $id,$currentgroup, array(2),1,  get_string("selectNegativ", "msociograma").' ('.$_SESSION['group'].')','bluebar');
  
	$html=$html." <SCRIPT>
							document.getElementById(\"super\").addEventListener(\"load\", function1());function function1(){ selectPositiv();selectNegativ();}
					</SCRIPT>";

   echo '<DIV id="divStatus">'.$html.'</DIV>';
   echo '</TD></TR></TABLE>';
