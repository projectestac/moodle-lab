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


//This file corresponds to the allSheet.php. 
//Shows the agress data in a chart

	echo '<SCRIPT language="JavaScript" type="text/javascript" src="ajax.js"></SCRIPT>';
	echo '<TABLE><TR><TD WIDTH=10% VALIGN="top">';

	//link print students
	echo '<A HREF=# onclick="imprimir(\'divAll\');" TITLE="'.get_string('popup','msociograma').'">'.get_string('printgraphics','msociograma').'</A>'; 
	echo '<BR><BR>';
                    
                    
	echo '</TD><TD>';
    $html = '<TITLE>'.get_string('information','msociograma').'</TITLE>'.estiloSaltaPagina();
    $html=$html."<META CHARSET='UTF-8' />";
	echo '<DIV id="super"></DIV>'; 
	 
  //load functions created from tableCescChartjs in msociograma_lib.php
	$html=$html. trioTableCescChartjs ('trioAgress',$COURSE->id, $id,$currentgroup, array(5), 1,get_string('agressFis','msociograma'),array(8), 1,get_string('agressVerb','msociograma'), array(3,6), .5,get_string('agressRel','msociograma'));
	$html=$html. trioTableCescChartjs ('trioVictim',$COURSE->id, $id,$currentgroup, array(9), 1,get_string('victimFis','msociograma'),array(10), 1,get_string('victimVerb','msociograma'), array(11), 1,get_string('victimRel','msociograma'));
	
	$html=$html. tableCescChartjs ('prosocTab',$COURSE->id, $id, $currentgroup, array(4,7), .5, get_string('prosocTab','msociograma').' ('.$_SESSION['group'].')','yellow' );
	$html=$html. tableCescChartjs ('selectPositiv',$COURSE->id, $id, $currentgroup, array(1), 1, get_string('selectPositiv','msociograma').' ('.$_SESSION['group'].')','green' );
	$html=$html. tableCescChartjs ('selectNegativ',$COURSE->id, $id, $currentgroup, array(2), 1, get_string('selectNegativ','msociograma').' ('.$_SESSION['group'].')','red' );

	
    $html=$html." <SCRIPT>
							document.getElementById(\"super\").addEventListener(\"load\", function1());function function1(){ trioAgress();trioVictim();prosocTab();selectPositiv();selectNegativ();}
					</SCRIPT>";
	
	
	echo '<DIV id="divAll">'.$html.'</DIV>';
    
	echo '</TD></TR></TABLE>';

     