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

 //This file corresponds to the tabs.php
 //Shows the tabs of the module

defined('MOODLE_INTERNAL') || die('not allowed');

global $DB,$PAGE;

$tabs = array();
$row  = array();
$inactive = array();
$activated = array();



if (!isset($currenttab)) {
    $currenttab = '';
}

if (!isset($cm)) {
    $cm = get_coursemodule_from_instance('msociograma', $msociograma->id);
    $context = context_module::instance($cm->id);
}

if (!isset($course)) {
    $course = $DB->get_record('course', array('id' => $msociograma->course));
}

$tabs = $row = $inactive = $activated = array();

$context = context_module::instance($cm->id);

$tabs = array();
$row = array();


if (has_capability('mod/msociograma:gestion', $context)) {
	
  $row[] = new tabobject('students', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=students", get_string('studentTab', 'msociograma'), get_string('studentTabHit', 'msociograma'));
  $row[] = new tabobject('tutors', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=tutors", get_string('tutorsTab', 'msociograma'), get_string('tutorsTabHit', 'msociograma'));
  $row[] = new tabobject('activationgroup', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=activationgroup", get_string('activationgrouptab', 'msociograma'), get_string('activationgrouphit', 'msociograma'));
  $row[] = new tabobject('agress', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=agress", get_string('agressTab', 'msociograma'), get_string('agressTabHit', 'msociograma'));
  $row[] = new tabobject('victim', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=victim", get_string('victimTab', 'msociograma'), get_string('victimTabHit', 'msociograma'));
  $row[] = new tabobject('prosoc', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=prosoc", get_string('prosocTab', 'msociograma'), get_string('prosocTabHit', 'msociograma'));
  $row[] = new tabobject('estatus', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=estatus", get_string('estatSocTab', 'msociograma'), get_string('estatSocTabHit', 'msociograma'));
  $row[] = new tabobject('all', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=all", get_string('allTab', 'msociograma'), get_string('allTabHit', 'msociograma'));
  $row[] = new tabobject('sociogram', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=sociogram", get_string('sociogramTab', 'msociograma'), get_string('sociogramTabHit', 'msociograma'));
  $row[] = new tabobject('help', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=help", get_string('help', 'msociograma'), get_string('helpTabHit', 'msociograma'));

  $tabs[] = $row;
  
  if (isset ($_GET['page'])) {
    $page = $_GET['page'];
	
    if ($page == 'students'){
      $currenttab = 'students';
    } elseif ($page == 'agress'){
      $currenttab = 'agress';
    } elseif ($page == 'victim'){
      $currenttab = 'victim';
	} elseif ($page == 'prosoc'){
      $currenttab = 'prosoc';
    } elseif ($page == 'estatus'){
      $currenttab = 'estatus';
    } elseif ($page == 'activationgroup'){
      $currenttab = 'activationgroup';	
	} elseif ($page == 'tutors'){
      $currenttab = 'tutors';	
	} elseif ($page == 'all'){
      $currenttab = 'all';	  
	} elseif ($page == 'sociogram'){
      $currenttab = 'sociogram';  
    } elseif ($page == 'help'){
      $currenttab = 'help';  
    }
  } else {
    $currenttab = 'students';   //default tab
  }
  
	print_tabs($tabs, $currenttab, $inactive, $activated);

} else if (has_capability('mod/msociograma:tutor', $context)) {
  $row[] = new tabobject('agress', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=agress", get_string('agressTab', 'msociograma'), get_string('agressTabHit', 'msociograma'));
  $row[] = new tabobject('victim', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=victim", get_string('victimTab', 'msociograma'), get_string('victimTabHit', 'msociograma'));
  $row[] = new tabobject('prosoc', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=prosoc", get_string('prosocTab', 'msociograma'), get_string('prosocTabHit', 'msociograma'));
  $row[] = new tabobject('estatus', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=estatus", get_string('estatSocTab', 'msociograma'), get_string('estatSocTabHit', 'msociograma'));
  $row[] = new tabobject('all', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=all", get_string('allTab', 'msociograma'), get_string('allTabHit', 'msociograma'));
  $row[] = new tabobject('sociogram', "$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id&page=sociogram", get_string('sociogramTab', 'msociograma'), get_string('sociogramTabHit', 'msociograma'));

  $tabs[] = $row;
  
  if (isset ($_GET['page'])) {
    $page = $_GET['page'];
	
    if ($page == 'students'){
      $currenttab = 'students';   
    } elseif ($page == 'agress'){
      $currenttab = 'agress';
    } elseif ($page == 'victim'){
      $currenttab = 'victim';
	} elseif ($page == 'prosoc'){
      $currenttab = 'prosoc';
    } elseif ($page == 'estatus'){
      $currenttab = 'estatus';
    } elseif ($page == 'all'){
      $currenttab = 'all';	  
	} elseif ($page == 'sociogram'){
      $currenttab = 'sociogram';  
    }
  } else {
    $currenttab = 'agress';   //default tab
  }
  
	print_tabs($tabs, $currenttab, $inactive, $activated);

} else if (has_capability('mod/msociograma:view', $context)){

   

}

