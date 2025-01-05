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


///This file corresponds to the logingroup.php. 
//Determines the user's group and modifies the session variables if activated

global $CFG, $USER,$COURSE,$DB;

$course = $COURSE->id;
$idActivityModule = $_GET['id'];
$studentId = $USER->id;

$sql="SELECT m.id, name, u.id, firstname , lastname FROM {$CFG->prefix}user u 
		JOIN {$CFG->prefix}groups g 
			JOIN {$CFG->prefix}groups_members m 
				WHERE u.id=m.userid 
					AND g.courseid='$course' 
						AND m.groupid =g.id 
							AND u.id='$studentId'";
	
if (!$logx = $DB->get_records_sql($sql, array('%'))){
	//user without group
	echo '<center><font face=arial style="font-size: 28pt" color=red>'.get_string('withoutgroup','msociograma').'</font></center><br><br>';
} else {
	
	if (count($logx)>1)
		//user in more than one group in this course
		 echo '<center><font face=arial style="font-size: 28pt" color=red>'.get_string('twogroups','msociograma').'</font></center><br><br>';
	else {
		
		foreach($logx as $alum){
			$group = $alum->name;
			$student  = $alum->lastname.", ".$alum->firstname;
		}	
			
		$sql="SELECT groupclass FROM {$CFG->prefix}msociograma_groups 
				WHERE activity = '$idActivityModule'
					AND groupclass ='$group' 	
						AND course='$course' 
							AND enabled ='#ok'";
		
		if (!$logx = $DB->get_records_sql($sql, array('%'))){
			// group disabled
			 echo "<center><font face=arial style='font-size: 28pt' color=red>".get_string('groupdisabled','msociograma')." ($group)</font> </center><br><br>";
 
		} else {
			
			//modified session variables
			$_SESSION['login']= true;
			$_SESSION['student']=$student;
			$_SESSION['group']=$group;
		
			//load main student form
			require ('./gridSheet.php');
		}
	}
}
