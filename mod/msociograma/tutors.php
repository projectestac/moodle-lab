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

//msociograma is based in CESC sociogram, designed by Collell, J. and Escud?, C.
  
//This file corresponds to the activationgroup.php 
//Shows the enabled status of every group
  
	global $DB, $COURSE;
		
		
	if (!has_capability('mod/msociograma:gestion', $context)){  //access forbiden if no rol
	   print_error('nocapabilities', 'msociograma');
	}

	//access to tutoring table for get capabilities
	 include('tutoriaaccess.php');
	 

	$course = $COURSE->id;
	$idActivityModule = $_GET['id'];
	 
	require_login($course, true, $cm);

	$context = context_module::instance($cm->id);
	
	
	//calculates roles
	$table ='role';
	
	if ($role = $DB->get_record($table, array('shortname' => 'manager' )))
		$manager =  $role->id;
	if ($role = $DB->get_record($table, array('shortname' => 'editingteacher' )))
		$editingteacher =  $role->id;
	if ($role = $DB->get_record($table, array('shortname' => 'teacher' )))
		$teacher =  $role->id;

	

	
	  //*********************************************************************************
	  //********** synchronizes Moodle users with no editing teacher users ******************
	  //*************************************************************************************

	  $DB->execute('UPDATE '.$CFG->prefix.'msociograma_tutoring SET syncro = 0 WHERE course = '.$course );  //restart syncro flag
	 
	
	  $sqlMoodle = 'select DISTINCT u.username as username, c.id as courseid, c.fullname as coursename, 
	  u.id as userid,  u.firstname as firstname, u.lastname as lastname, u.email as email, u.country as country, 
	  (select min(log.time) from '.$CFG->prefix.'log log where log.userid = u.id and log.course = c.id ) as first_course_access from '.$CFG->prefix.'course c 
	  left outer join '.$CFG->prefix.'context cx on c.id = cx.instanceid left 
	  outer join '.$CFG->prefix.'role_assignments ra on cx.id = ra.contextid 
	  left outer join '.$CFG->prefix.'user u on ra.userid = u.id where ra.roleid  in ('.$teacher.') and c.id = ? order by u.username';
	  
	 $record2  = new stdClass();
	  if ($dataMoodle = $DB->get_records_sql($sqlMoodle, array($COURSE->id))){
		foreach($dataMoodle as $userMoodle){
			$sql   ="SELECT * FROM {$CFG->prefix}msociograma_tutoring where username = ? and course = ? order by username";
			
			if ( !$datausers = $DB->get_records_sql($sql, array($userMoodle->username, $course))){
				$record2->username = $userMoodle->username;
				$record2->name = $userMoodle->lastname.', '.$userMoodle->firstname;
				$record2->course = $course;
				$record2->syncro = 1;
				$resultado = $DB->insert_record('msociograma_tutoring', $record2);
			} else {
				$DB->execute('UPDATE '.$CFG->prefix.'msociograma_tutoring SET syncro = 1 WHERE username ="'.$userMoodle->username.'" AND course = "'.$course.'"'); 
			}	
		}	
	  }
	  
	  $DB->execute('DELETE FROM '.$CFG->prefix.'msociograma_tutoring WHERE syncro = 0  AND course = "'.$course.'"');  //delete syncro = 0 (no exists)

	  $sql   ='SELECT * FROM '.$CFG->prefix.'msociograma_tutoring WHERE course ="'.$course.'"';
	  $table='msociograma_tutoring';
	  
	  $sqlShow = "SELECT DISTINCT groupclass FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$course' ORDER BY groupclass";
	 
	  $fields = array(
						array('field'=>'name', 'alias'=>get_string('teacher','msociograma'), 'width'=>250, 'type'=>'textOnlyRead'),
						array('field'=>'tutoring', 'alias'=>get_string('tutor','msociograma'), 'width'=>40, 'type'=>'combo', 'sqlShow'=>$sqlShow, 'fieldShow'=>'groupclass', 'icons'=>'false', 'hide'=>'false'),
					);


	  tableTutoring($sql,$table,$fields,'false');
	
	
?>