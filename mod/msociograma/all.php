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

//This file corresponds to the all.php. 
//Shows all data page

	require_once('msociograma_lib.php');
	require_once('msociogramaFW.php');
	require_course_login($course->id, true, $cm);
	$context = context_module::instance($cm->id);

	if ((!has_capability('mod/msociograma:gestion', $context)) AND (!has_capability('mod/msociograma:tutor', $context)))
		print_error('nocapabilities', 'msociograma');


	$idActivityModule = $_GET['id'];
	$course = $COURSE->id;



	$A = new combo('group');

	$A->label=get_string('group','msociograma');

	$sql="SELECT DISTINCT groupclass FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$course' ORDER BY groupclass";

	//access to tutoring table for get capabilities
	include('tutoriaaccess.php');
	  
	$A->loadDataFromSql($sql,'groupclass', 'groupclass');
	$A->iniValue = 'nulo';

	if ((isset($_SESSION['group'])) && (in_multiarray($_SESSION['group'], $A->datos))) {
		$A->iniValue = $_SESSION['group'];
		$A->iniText = $_SESSION['group'];
		$currentgroup = $_SESSION['group'];
	} else {
		$A->iniValue = 'nulo';
		$A->iniText = get_string('selectgroup','msociograma');
	}

	echo '<CENTER>';
	$A->show();
	echo '</CENTER>';
	echo '<BR><BR>';

	if($A->ejecutaAccion($A->name)){
		$_SESSION['group'] = $_POST[$A->name];
		$currentgroup = $_POST[$A->name];
	}

	if ((isset($_SESSION['group']))&& (in_multiarray($_SESSION['group'], $A->datos)))
	   require_once('allSheet.php');
	   
	unset($A);
	   
