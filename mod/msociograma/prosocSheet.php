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

//This file corresponds to the prosocSheet.php. 
//Shows the prosocial data in a chart
 
    echo '<SCRIPT language="JavaScript" type="text/javascript" src="ajax.js"></SCRIPT>';
	echo '<TABLE><TR><TD width=10% valign="top">';

	//link for print students
	echo '<A HREF=# onclick="imprimir(\'divProsoc\');" title="'.get_string('popup','msociograma').'">'.get_string('printgraphics','msociograma').'</A>'; 
	echo '<BR><BR>';
                    
                    
	echo '</TD><TD>';
    $html = '<TITLE>'.get_string('information','msociograma').'</TITLE>'.estiloSaltaPagina();
    $html=$html."<META CHARSET='UTF-8' />";
	echo '<DIV id="super"></DIV>'; 
    $html=$html. tableCescChartjs ('prosocTab',$COURSE->id, $id, $currentgroup, array(4,7), .5, get_string('prosocTab','msociograma').' ('.$_SESSION['group'].')','red' );
	
    $html=$html." <SCRIPT>
							document.getElementById(\"super\").addEventListener(\"load\", function1());function function1(){ prosocTab();}
					</SCRIPT>";
    echo '<DIV id="divProsoc">'.$html.'</DIV>';
    
	echo '</TD></TR></TABLE>';

           