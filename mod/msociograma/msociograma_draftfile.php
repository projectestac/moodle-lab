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

//This file corresponds to the msociograma_draftfile.php.
//This creates the records of the students list from *.csv file when is uploaded


global $DB, $CFG, $COURSE, $USER, $PAGE;

require_once("../../config.php");
require_once($CFG->libdir.'/filelib.php');
require_once("$CFG->dirroot/mod/msociograma/locallib.php");
 
require_login();

if (isguestuser()) {
    print_error('noguest');
}

$relativepath = get_file_argument();
$preview = optional_param('preview', null, PARAM_ALPHANUM);


// relative path must start with '/'
if (!$relativepath) {
    print_error('invalidargorconf');
} else if ($relativepath{0} != '/') {
    print_error('pathdoesnotstartslash');
}

// extract relative path components
$args = explode('/', ltrim($relativepath, '/'));

if (count($args) == 0) { // always at least user id
    print_error('invalidarguments');
}

$contextid = (int)array_shift($args);
$component = array_shift($args);
$filearea  = array_shift($args);
$draftid   = (int)array_shift($args);
$filename  = array_shift($args);
if ($component !== 'user' or $filearea !== 'draft') {
    send_file_not_found();
}

$context = context::instance_by_id($contextid);
if ($context->contextlevel != CONTEXT_USER) {
    send_file_not_found();
}

$fs = get_file_storage();

// Prepare file record object
$fileinfo = array(
    'component' => $component,   // usually = table name
    'filearea' => $filearea,     // usually = table name
    'itemid' => $draftid,        // usually = ID of row in table
    'contextid' => $contextid, 	 // ID of context
    'filepath' => '/',           // any path beginning and ending in /
    'filename' => $filename); 	 // any filename
 
// Get file
$file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                      $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
 
// Read contents
if ($file) {
    $contents = $file->get_content();
    $contents = str_replace("\n",";",$contents); //erase change of line
	$contents = str_replace("\"","",$contents);  //erase double quotes
	$contents = str_replace("\'","",$contents);  //erase simply quotes 

	$registros = explode (';',$contents);
	
	$addRegisters = $_GET['addRegisters'];
	
    //calculates activity
        $idActivityModule = $_GET['id'];
	$nombreActividad = nombreActividad ($idActivityModule);
         $curs = $_GET['course'];
	
	//delete if necessary	
   if ( $addRegisters == 'delete'){
     //delete recordset
     $sql = "DELETE FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$curs'";
	 $params = array('%');
	 $DB->execute($sql, $params);
   }

	 $cant = count($registros);
	 for ($x=0; $x<$cant-1;$x=$x+5){
	 	   
	   if (isset($registros[$x]))
				$registro0 = utf8_encode(trim($registros[$x]));
			else	
				$registro0 = '#';

			if (isset($registros[$x+1]))
				$registro1 = utf8_encode(trim($registros[$x+1]));
			else	
				$registro1 = '#';

			if (isset($registros[$x+2])){ //password
				$pass=utf8_encode(trim($registros[$x+2]));
				if ($pass=='?')
				  $registro2 = rand (100000,999999); 
				else
				  $registro2 = $pass;
			} else	
				$registro2 = '#';
                        
                        if (isset($registros[$x+3])){  //alias
				$alias=utf8_encode(trim($registros[$x+3]));
				if ($alias=='?')
				  $registro3 = chr(rand (65,90)).chr(rand (65,90)).rand (1,99); 
				else
				  $registro3 = strtoupper($alias);
			} else	
				$registro3 = '#';
                        
                       if (isset($registros[$x+4])){  //sex
				$sexo=utf8_encode(trim($registros[$x+4]));
				if ((strtoupper($sexo)!="H") && (strtoupper($sexo)!="M"))
				  $registro4 = 'N';
				else
				  $registro4 = strtoupper($sexo);
			} else	
				$registro4 = '#';


	//insert new recordsets			
	$sql = "INSERT INTO {$CFG->prefix}msociograma_sheet (activity,groupclass,student,pass,course,alias,sexo) VALUES ('$idActivityModule','$registro0','$registro1','$registro2','$curs','$registro3','$registro4')";
	$params = array('%');
	$DB->execute($sql, $params);

	}
	
	//test unique student name in sheet
	$sql="SELECT count(*), student,groupclass FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$curs' GROUP BY `student`,groupclass HAVING count(*) > 1;";
	$duplicados ='';
	if ($log = $DB->get_records_sql($sql, array('%'))){
		foreach($log as $entrada_log){
			$duplicados= $duplicados.$entrada_log->student.'-'.$entrada_log->groupclass.'\n';
		}
		$mensaje = get_string ('notice1','msociograma') . ' '. $duplicados;
		
		echo '<script> alert("'.$mensaje.'");window.location="'.$CFG->wwwroot.'/mod/msociograma/view.php?id='.$idActivityModule.'";</script>';
    } else {
		redirect("$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule");
	}
	

	
} else {
   echo 'NO FILE DRAFT FOUND';
    // file doesn't exist - do something
}
