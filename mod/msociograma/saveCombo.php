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

//This file corresponds to the saveCombo.php. 
//It supports to ajax operations

require('../../config.php');
require_once("$CFG->dirroot/mod/msociograma/locallib.php");

global $USER, $DB;


  $record = array();
  $record['course']=$_POST['course'];  
  $record['activity']=$_POST['id'];
  $record['id_stu'] =$_POST['id_stu'];
  $record['grup']=$_POST['grup']; 
  $record['question']=$_POST['question'];
  $record['order_id']=$_POST['question'];
  $record['stu_ans']=$_POST['answer'.$record['question']];
  

  $resultado = $DB->insert_record('msociograma_answers', $record);

    