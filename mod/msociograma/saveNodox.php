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

//This file corresponds to the saveNodox.php. 
//It supports to ajax operations

require('../../config.php');
require_once("$CFG->dirroot/mod/msociograma/locallib.php");

global $USER, $DB;

$course=$_POST['course'];
$activity=$_POST['activity'];
$group_class=$_POST['grup'];

$question=$_POST['question'];
$question =str_replace('question','',$question);
$id_stu=$_POST['id_stu'];
$posx=$_POST['posx'];
$posy=$_POST['posy'];			  


$record = array();
$record['id'] = idDiagram($course, $activity,$group_class,$question,$id_stu);	
$record['posx']=$_POST['posx'];
$record['posy']=$_POST['posy'];
			  
$resultado = $DB->update_record('msociograma_diagram', $record);

function idDiagram($course, $activity,$group_class,$question,$id_stu){
	global $DB;

	if ($data = $DB->get_record('msociograma_diagram', array('course'=>$course,'activity'=>$activity,'group_class'=>$group_class, 'question'=>$question,'id_stu'=>$id_stu))) // moodle 3.0	
		 return $data->id;
}

    