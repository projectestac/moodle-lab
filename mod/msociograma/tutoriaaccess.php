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

//This file corresponds to the tutoriaaccess.php. 


/*
	Afects to:
		activationgroup.php
		agress.php
		all.php
		estatus.php
		prosoc.php
		students.php
		sociograma.php
		victim.php
*/

defined('MOODLE_INTERNAL') || die();

	global $CFG, $USER,$COURSE,$DB;

	$course = $COURSE->id;
	$idActivityModule = $_GET['id'];
	$profeId = $USER->id;
	$profeUserName = $USER->username;
	$table = "msociograma_tutoring";

	$tutoria='';

	if (has_capability('mod/msociograma:gestion', $context)) { //gestor rol
		$sql="SELECT DISTINCT groupclass FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$course' ORDER BY groupclass";
	} else {	
		 if ($tutor = $DB->get_record($table, array('username' => $profeUserName ))){ //tutor rol	
			$tutoria =  $tutor->tutoring;
			if (($tutoria=='*') OR ($tutoria=='%') )//all groups
				 $sql="SELECT DISTINCT groupclass FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$course' ORDER BY groupclass";
			else
				 $sql="SELECT '$tutoria' As groupclass"; //only a group
		 } else {
			 $sql="SELECT DISTINCT groupclass FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$course' ORDER BY groupclass";
		  }
	} 
