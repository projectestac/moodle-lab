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


//This file corresponds to the  loginSheet.php.
//Allows to identify the express users (which come from a *.csv file)

global $DB, $COURSE, $PAGE, $CFG;
require_once("$CFG->dirroot/mod/msociograma/locallib.php");

$idActivityModule = $_GET['id'];
$nombreActividad = nombreActividad ($idActivityModule);
$course = $COURSE->id;

if (isset($_POST['log_mode']))
  $mode = $_POST['log_mode'];
else
  $mode = 'selectGroup';

//select group window
if ($mode == 'selectGroup'){
		   iniForm('150px','250px');
	if (isset($_GET['errorPass']))
		echo '<font face=arial style="font-size: 8pt" color=red>* '.get_string('incorrectpass','msociograma').'</font><br><br>';
	else
		echo '<br><br>';    
	
	$sql="SELECT groupclass FROM {$CFG->prefix}msociograma_groups 
			WHERE activity = '$idActivityModule' 	
				AND course='$course' 
					AND enabled ='#ok' 
						ORDER BY groupclass";
						
	echo get_string('group','msociograma');
	echo '<br>';
	
	showComboLogin('1', 'log_group', get_string('selectgroup','msociograma'), '', $sql, 'groupclass', 'groupclass', '', false, 200 , false, true);
	
	echo '<input name="log_mode" type="hidden" value="selectstudent">';
	finalForm(false);

//select student window	
} elseif ($mode == 'selectstudent'){
	
	iniForm('150px','350px');
	$group = $_POST['log_group'];
	$sql="SELECT  student FROM {$CFG->prefix}msociograma_sheet 
			WHERE activity = '$idActivityModule' 
				AND groupclass = '$group' 
					ORDER BY student";
	echo '<br><br>'; 
	echo get_string('student','msociograma');
	echo '<br>';
	showComboLogin('2', 'log_student', get_string('selectyou','msociograma'), '', $sql, 'student', 'student', '', false, 300 , false, true); 
	echo '<input name="log_mode" type="hidden" value="inputpass">';
	echo '<input name="log_group" type="hidden" value="'.$_POST['log_group'].'">';
	finalForm(false);
		
//input password window		
} elseif ($mode == 'inputpass'){
	
	iniForm('200px','200px');
	echo '<br>'; 
	echo '<br>'; 	
	echo get_string('password','msociograma');
	echo '<br>';
	echo '<input type="password" name="log_password" default="secret" style="width:100px;">';
	echo '<input name="log_mode" type="hidden" value="checkpass">';
	echo '<input name="log_group" type="hidden" value="'.$_POST['log_group'].'">';
	echo '<input name="log_student" type="hidden" value="'.$_POST['log_student'].'">';
	finalForm(true);
	
} elseif ($mode == 'checkpass'){ 

   $group = $_POST['log_group'];
   $student = $_POST['log_student'];
   $pass = $_POST['log_password'];
   $sql="SELECT * FROM {$CFG->prefix}msociograma_sheet where activity = ? and groupclass = ? and student = ? and pass = ?";
	//if password is incorrect
	if (!$data = $DB->get_records_sql($sql, array( $idActivityModule, $group, $student, $pass))){
		
	  redirect("$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&errorPass=ok");
	  
	} else {
		
	  $_SESSION['login']= true;
	  $_SESSION['student']=$student;
	  $_SESSION['group']=$group;

	}
 }

