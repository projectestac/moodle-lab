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


//This file corresponds to the students.php.
//Determines if the user is student or teacher.
//If user is student determines if are entered by the express mode or by the users Moodle mode.
//If user is teacher show user list (user Moodle mode or express mode)

global $DB;

require_course_login($course->id, true, $cm);
$context = context_module::instance($cm->id);

$idActivityModule = $_GET['id'];

if ((!has_capability('mod/msociograma:gestion', $context)) AND (!has_capability('mod/msociograma:tutor', $context))){ //students
  if (logintype($idActivityModule)==0){// Moodle users

    require ('./logingroup.php');
	
  } else if (logintype($idActivityModule)==1){//express mode
  
		if ((!isset( $_SESSION['login'])) ||(!$_SESSION['login'])) //if no login, login
				 require ('./loginSheet.php');
		
		if ((isset($_SESSION['login'])) && ($_SESSION['login'])) //if login
		  require ('./gridSheet.php');
    
  } else {

  }

} else {  //teachers
//*************************************************************
 include('tutoriaaccess.php');//tutoring access
 if (($tutoria <> '') AND ($tutoria <> '*'  ))
	print_error('nocapabilities', 'msociograma');
//*************************************************************
	if (logintype($idActivityModule)==0){
	   include ('listUsersMoodle.php');
	} else if (logintype($idActivityModule)==1){
	  include ('loadSheet.php');
	} else {
	
	}
		
}

?>
