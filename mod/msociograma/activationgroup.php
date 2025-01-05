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

//msociograma is based in CESC sociogram, designed by Collell, J. and Escud�, C.
  
//This file corresponds to the activationgroup.php 
//Shows the enabled status of every group
  
	global $DB, $COURSE;
		
		
	if (!has_capability('mod/msociograma:gestion', $context)){  //access forbiden if no rol
	   print_error('nocapabilities', 'msociograma');
	}

	//access to tutoring table for get capabilities
	 include('tutoriaaccess.php');//tutoria patch (provisional)
	
	$course = $COURSE->id;
	$idActivityModule = $_GET['id'];
	 
	updateGroups($course, $idActivityModule);

	$fields = array(
			  array('field'=>'groupclass', 'alias'=>get_string('group','msociograma'), 'width'=>40, 'type'=>'text'),
			  array('field'=>'enabled', 'alias'=>get_string('active','msociograma'), 'width'=>40, 'type'=>'check'),
			  array('field'=>'resp', 'alias'=>'Num', 'width'=>40, 'type'=>'text'),
			  );
	 

	$sql = "SELECT id, groupclass, enabled, resp FROM {$CFG->prefix}msociograma_groups g LEFT JOIN (SELECT  count(*) AS resp, grup FROM {$CFG->prefix}msociograma_answers a WHERE  a.activity = '$idActivityModule' AND a.course='$course'  GROUP BY grup) an
	ON (groupclass =grup ) WHERE  g.activity = '$idActivityModule' AND g.course='$course' ORDER BY groupclass";
	  
   $table = 'msociograma_groups';
   tableGroups($sql,$table,$fields,'false');
