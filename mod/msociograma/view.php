<?php
// This file is extern part of Moodle - http://moodle.org/
//
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

 //This file corresponds to the view.php
 //It contains the basic structure of the module
 
/*
	Current Limitations
	===================
	As this software is in beta, it should have bugs.
	This module is functional but is not considered as ready for production sites.

	THIS SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
	MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
	IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
	OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
	ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	OTHER DEALINGS IN THE SOFTWARE.
	
*/

require('../../config.php');
require_once("$CFG->dirroot/mod/msociograma/locallib.php");
require_once("$CFG->dirroot/repository/lib.php");
require_once($CFG->libdir . '/completionlib.php');

global $USER, $DB;

$id = optional_param('id', 0, PARAM_INT);  // Course module ID
$f  = optional_param('f', 0, PARAM_INT);   // msociograma instance id

if ($f) {  // Two ways to specify the module
    $msociograma = $DB->get_record('msociograma', array('id'=>$f), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('msociograma', $msociograma->id, $msociograma->course, false, MUST_EXIST);

} else {
    $cm = get_coursemodule_from_id('msociograma', $id, 0, false, MUST_EXIST);
    $msociograma = $DB->get_record('msociograma', array('id'=>$cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/msociograma:view', $context);

// Update 'viewed' state if required by completion system
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_url('/mod/msociograma/view.php', array('id' => $cm->id));

$PAGE->set_title($course->shortname.': '.$msociograma->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_activity_record($msociograma);


$output = $PAGE->get_renderer('mod_msociograma');

echo $output->header();

$idActivityModule = $_GET['id'];
 

//***************************************************************************
//******************** management GET data **********************************
//***************************************************************************


if (isset ($_GET['mode']))  //if logout
	if ($_GET['mode']=='restart')
	{ 
		$_SESSION['login'] = false;
	} 


if (isset ($_GET["funct"]))
if ($_GET["funct"]=="dades")
{

  $record = array();
  $record['course']=$_POST['course'];  
  $record['activity']=$_POST['id'];
  $record['id_stu'] =$_POST['id_stu'];
  $record['grup']=$_POST['grup']; 
  $record['question']=$_POST['question'];
  $record['order_id']=$_POST['question'];
  $record['stu_ans']=$_POST['answer'.$record['question']];
 
  $resultado = $DB->insert_record('msociograma_answers', $record);
     
}

//if information is send by POST metode 
if (isset ($_POST['operation'])) {
  $operation = $_POST['operation'];
  if ($operation == "updateRecordset"){
		$maxim = $_POST['maxcont']-1;
		$table = $_POST['table'];
		$page = $_POST['page'];
		
		for ($i=0;$i<=$maxim;$i++)
		  if (isset ($_POST['delete'.$i])){
		   $id = $_POST['Id'.$i];
		   deleteRecordsetMsociograma($id, $table);
		} else {
		  if (isset ( $_POST['Id'.$i])){
				$id = $_POST['Id'.$i];
				$record=array('id'=>$id);
				$record['groupclass'] = $_POST['groupclass'.$i];
				$record['student'] = $_POST['student'.$i];
				$record['pass'] = $_POST['pass'.$i];
				$record['alias'] = $_POST['alias'.$i];
				$resultado = $DB->update_record($table, $record);
		  }
		}
  }
	
  if ($operation == "updateRecordsetGroups"){
	$maxim = $_POST['maxcont']-1;
    $table = $_POST['table'];
    $page = $_POST['page'];
	
        for ($i=0;$i<=$maxim;$i++){
                $id = $_POST['Id'.$i];
		$record=array('id'=>$id);
                if (isset($_POST['enabled'.$i]))
                   $record['enabled'] = $_POST['enabled'.$i];
                else
                      $record['enabled'] = '';
		$resultado = $DB->update_record($table, $record);
        }
        
        redirect("$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&page=activationgroup"); 
                
  }
  
  if ($operation == "updateRecordsetTutoring"){
	$maxim = $_POST['maxcont']-1;
    $table = $_POST['table'];
    $page = $_POST['page'];
	
        for ($i=0;$i<=$maxim;$i++){
                $id = $_POST['Id'.$i];
		$record=array('id'=>$id);
              
        $record['tutoring'] = $_POST['tutoring'.$i];
              
		$resultado = $DB->update_record($table, $record);
        }
        
        redirect("$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&page=tutors"); 
                
  }
  
}

include($CFG->dirroot.'/mod/msociograma/tabs.php');  //include de general tabs
if (isset($_GET["page"])){
	if ($_GET["page"]=="students")
	 include('students.php');
	elseif ($_GET["page"]=="agress")
	 include('agress.php');
	elseif ($_GET["page"]=="victim") 
	 include('victim.php');
	elseif ($_GET["page"]=="prosoc")
	 include('prosoc.php');
	elseif ($_GET["page"]=="estatus")
	 include('estatus.php');
	elseif ($_GET["page"]=="activationgroup")
	 include('activationgroup.php');
	elseif ($_GET["page"]=="tutors")
	 include('tutors.php');
	elseif ($_GET["page"]=="all")
	  include('all.php');
	elseif ($_GET["page"]=="sociogram")
	 include('sociograma.php');
	elseif ($_GET["page"]=="help")
	include('help.php');
} else {
	
	if (has_capability('mod/msociograma:gestion', $context))  	//gestor and teacher
		include('students.php');  //default page
	else if (has_capability('mod/msociograma:tutor', $context)) //no editor teacher
		include('agress.php');  //default page
	else 														//studnet
		include('students.php');  //default page	
}



// Finish the page.
echo $OUTPUT->footer();
