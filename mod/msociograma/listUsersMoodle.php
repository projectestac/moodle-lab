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

//This file corresponds to the  lisUsersMoodle.php.
//It loads the users name and their groups from  Moodle users (if they are in course)
//IMPORTANT: it is necessary that the course has a defined grouping

global $CFG,$DB, $COURSE;

$course = $COURSE->id;
$idActivityModule = $_GET['id'];

//check for data
$mode ="";
if (isset($_GET['mode']))
	$mode = $_GET['mode'];

$sql = "SELECT * FROM {$CFG->prefix}msociograma_sheet WHERE course = '$course' AND activity = '$idActivityModule' ";	

//if there is no data or the update is forced
if ((!$log = $DB->get_records_sql($sql, array('%'))) OR ($mode == "update")){  

	//instert new data
	$sql="SELECT m.id, name,  firstname , lastname FROM {$CFG->prefix}user u 
			JOIN {$CFG->prefix}groups g 
				JOIN {$CFG->prefix}groups_members m 
					WHERE u.id=m.userid 
						AND g.courseid='$course' 
							AND m.groupid =g.id 
								ORDER BY name ASC , lastname ASC, firstname ASC, m.id ;";
	
	//if no founds groups in this course
	if (!$logx = $DB->get_records_sql($sql, array('%'))){
		echo '<center><font face=arial style="font-size: 28pt; LINE-HEIGHT:150%;" color=red>'.get_string('nogroupsseparate','msociograma').'</font></center><br><br>';
	} else {
	    foreach($logx as $entrada_log){
	
			$registro0 = $entrada_log->name;
			$registro1 = $entrada_log->lastname.", ".$entrada_log->firstname;
			$registro2 = "******";
			$registro3 = chr(rand (65,90)).chr(rand (65,90)).rand (1,99); 
			$registro4 = 'N'; 
			
			//check if recordset exists. if no exists is created
			$sqlaux = "SELECT * FROM {$CFG->prefix}msociograma_sheet 
						WHERE course = '$course' 
							AND activity = '$idActivityModule' 
								AND groupclass='$registro0' 
									AND student = '$registro1'";	
									
			if (!$logaux = $DB->get_records_sql($sqlaux, array('%'))){
				//insert new recordsets		
				$sql1 = "INSERT INTO {$CFG->prefix}msociograma_sheet (activity,groupclass,student,pass,course,alias,sexo) 
						VALUES ('$idActivityModule','$registro0','$registro1','$registro2','$course','$registro3','$registro4')";
				$params = array('%');
				$DB->execute($sql1, $params);
			}
		}//foreach
	  }//if
	
	//delete old data
	$sqlaux = "SELECT * FROM {$CFG->prefix}msociograma_sheet 
				WHERE course = '$course' 
					AND activity = '$idActivityModule' ";	
	
	
	if (!$logx = $DB->get_records_sql($sqlaux, array('%'))){
		//recordset
	} else {
	    foreach($logx as $entrada_log){
	
			//check if recordset exists. if no exists is erased
			$grupo = $entrada_log->groupclass;
			$alum = $entrada_log->student;
			$alumno = explode(", ", $alum);
			$llin= $alumno[0];
			$nom= $alumno[1];
			
			$sql="SELECT m.id, name, lastname,  firstname FROM {$CFG->prefix}user u 
					JOIN {$CFG->prefix}groups g 
						JOIN {$CFG->prefix}groups_members m 
							WHERE u.id=m.userid 
								AND g.courseid='$course' 
									AND m.groupid =g.id 
										AND name = '$grupo' 
											AND lastname = '$llin' 
												AND firstname = '$nom' ;";
		
			if (!$logaux = $DB->get_records_sql($sql, array('%'))){
				//erase old records	
				$sql1 = "DELETE FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$course' AND groupclass = '$grupo' AND student='$alum'";
				$params = array('%');
				$DB->execute($sql1, $params);
			}
		}//foreach
	 }//if
		 
} 
		
include ('loadSheet.php');

?>