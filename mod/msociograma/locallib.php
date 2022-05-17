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

//This file corresponds to the locallib.php.
//It contains the most very important functions

 
 defined('MOODLE_INTERNAL') || die();
 
 //this function return de activity name
 function nombreActividad ($id){
  global $DB;
  if ($registro = $DB->get_record('course_modules', array('id'=>$id))){
    $instancia = $registro->instance;
    if ($registro = $DB->get_record('msociograma', array('id'=>$instancia)))
      $nombre = $registro->name;
  }  
  return $nombre;
}

 //this function return de login type: 0->Login User, 1-> sheet user
function logintype ($id){
  global $DB,$CFG;
  if ($registro = $DB->get_record('course_modules', array('id'=>$id))){
    $instancia = $registro->instance;
    if ($registro = $DB->get_record('msociograma', array('id'=>$instancia)))
      $logintype = $registro->logintype;
  }  
  return $logintype;
}	
	
 //this function list the students names in the sheet
function listado($idActivityModule,$alias){
  global $CFG,$DB, $COURSE;
   if (isset ($_GET['edit']))
  $edit = $_GET['edit'];
else
  $edit = 'closed';
  if ($alias)
    $fields = array(
                  array('field'=>'groupclass', 'alias'=>get_string('group','msociograma'), 'width'=>40, 'type'=>'text'),
                  array('field'=>'student', 'alias'=>get_string('student','msociograma'), 'width'=>300, 'type'=>'text'),
                  array('field'=>'pass', 'alias'=>get_string('password','msociograma'), 'width'=>40, 'type'=>'text'),
                  array('field'=>'alias', 'alias'=>get_string('alias','msociograma'), 'width'=>40, 'type'=>'text'), //modif<--------------
    );
  else
     $fields = array(
                  array('field'=>'groupclass', 'alias'=>get_string('group','msociograma'), 'width'=>40, 'type'=>'text'),
                  array('field'=>'student', 'alias'=>get_string('student','msociograma'), 'width'=>300, 'type'=>'text'),
                  array('field'=>'pass', 'alias'=>get_string('password','msociograma'), 'width'=>40, 'type'=>'text'),
                  //array('field'=>'alias', 'alias'=>get_string('alias','msociograma'), 'width'=>40, 'type'=>'text'), //modif<--------------
    ); 
 $course = $COURSE->id;
 $nombreActividad = nombreActividad ($idActivityModule);
 $html1='<style type="text/css">   //estilo para cambiar de página
				 
						@media all {
							div.saltopagina{
								display: none;
							}
						}
	   
						@media print{
							div.saltopagina{ 
								display:block; 
							page-break-before:always;
						}
						
						}
						</style>
				'; 
 $arr=sacaGrupos($course,$idActivityModule);
 if ($arr =='void'){
	 //no groups in table
 }else{
 
 if (count($arr)>0)
	 foreach ($arr as $grupo){
	  $sql = "SELECT * FROM {$CFG->prefix}msociograma_sheet WHERE course = '$course' AND activity = '$idActivityModule' AND groupclass = '$grupo' ORDER BY groupclass, student";
	  $table = 'msociograma_sheet';
	  // return studentslist($sql,$table,$fields,true);
	   $html1.='<title>'.get_string('information','msociograma').'</title><div class="saltopagina"></div>';
	  if ($edit == 'open')
		$html1.= studentslist($sql,$table,$fields,$grupo, false);
	  else
		$html1.= studentslist($sql,$table,$fields,$grupo, true);
		
	  $html1.=  '<br><br>';
	  
	  }
 }
  return $html1;
 
}
	
//*****************************************************************
//***** this function create a queryview writable table ***********
//*****************************************************************
function studentslist($sql,$table, $fields, $group, $readOnly){

  global $DB, $COURSE, $PAGE, $CFG;

  if (!$data  = $DB->get_records_sql($sql, array('%')))
  {
   
	echo '<br><br><b>'.get_string('norecordset', 'msociograma').'</b><br><br>';
	return;
  }
  $html='';
  $html.= ' <form id="contact_form" action="view.php?id='.$PAGE->cm->id.'" method="POST" enctype="multipart/form-data">';
  $html.= '     <table border=0 cellpadding=5>';   //$html.= '<table width=90% border=0 cellpadding=5>';
  foreach ($fields as $f){
    $html.= '   <col width='.$f['width'].'>';
  }
  //header of the table	
  $html.= '     <tr  align=center bordercolor= "#f5d0a9">';
  foreach ($fields as $f){
    $html.= '    <td>'.$f['alias'].'</td>';
  }
   if (!$readOnly)
        $html.= '    <td>'.get_string('deleteitem','msociograma').'</td>';  //a�adido
  $html.= '     </tr>';

  $cont=0;
  $datos = new stdClass();
  foreach ($data as $e){
    if ($cont%2 == 0 ){   //alternate the color of every row
      $celda='<td style="border: thin solid; border-color: #F7BE81" bgcolor= "#ffd98f" border =1>';     //grey "#cccccc">';
	  $textBox ='style="background-color: #ffd98f" ';
	  $colorComboBox ='style="background-color: #ffd98f" ';
    } else {
      $celda='<td  style="border: thin solid;border-color: #F7BE81" >';
      $textBox='';
	  $colorComboBox ='';
    }

    $html.= '  <tr valing=top height="10">';
    //start data row
     foreach ($fields as $f){
   
	  $aux=$f['field'];
	  $datos->$aux=str_replace(" ","%",$e->$aux);
	  
      $html.= $celda;
	
      //***************************************************
      //*********** configures check type *****************
	  //***************************************************
	  if ( $f['type'] == 'check' ) {   
        //if ( $e->$f['field'] == "1" ) {//***modif 17/10/2018
		 $aux=$f['field'];
		if ( $e->$aux == "1" ) {
          $checked="checked";
        } else {
          $checked="";
        }
        if ( $readOnly ) {
          $enabled='disabled="disabled"';
        } else {
          $enabled="";
        }
        $html.= '<center><input type=checkbox '.$enabled.' name='.$f['field'].' '.$checked.' value="#ok"></center>';
      }//if check

	  //***************************************************
	  //*********** configures imagecheck type ************
	  //***************************************************
      if ( $f['type'] == 'imagecheck' ) {
        //if ( $e->$f['field'] == "1" ) {//***modif 17/10/2018
		 $aux=$f['field'];
		if ( $e->$aux == "1" ) {
			
          $image = 'pix/task-done.svg';
        } else {
          $image = 'pix/task-fail.svg';
        }   
        $html.= '<center><img src='.$image.' width=20 height=30></center>';
      }//if imagecheck
	   //***************************************************
	  //*********** configures imagenull type ************
	  //***************************************************
      if ( $f['type'] == 'imagenull' ) {
        //if ( $e->$f['field'] == null ) {//***modif 17/10/2018
		 $aux=$f['field'];
		if ( $e->$aux == null ) {
		
          $image = 'pix/task-fail.svg';
        } else {
          $image = 'pix/task-done.svg';
        }   
        $html.= '<center><img src='.$image.' width=15 height=20></center>';
      }//if imagenull

      //***************************************************
	  //*********** configures text type ******************
	  //***************************************************
      if ( $f['type'] == 'text' ) { 
        if ( $readOnly  ) {
          //$html.= $e->$f['field'];//****modif  17/10/2018
		   $aux=$f['field'];
		   $html.= $e-> $aux;
        } else {
          $div=$f['width'] / 10;
	
     	  $aux = $f['field']; 
		  $html.= '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$f['field'].$cont.'" value="'.$e->$aux .'"></center>';
        }
        $identificador=$e->id;
      }//if text
	  //***************************************************
	  //*********** configures date type ******************
	  //***************************************************
      if ( $f['type'] == 'date' ) { 
		$aux=$f['field'];
		$date = date_create($e->$aux);
		
		$fecha= date_format($date, 'd/m/Y');
        if ( $readOnly  ) {
          $html.= $fecha;
        } else {
          $div=$f['width'] / 10;
		 
          
          $html.= '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$f['field'].'" value="'.$fecha .'"></center>';
        }
        $identificador=$e->id;
      }//if date
	  //***************************************************
	  //*********** configures link type ******************only for enquestador
	  //***************************************************
	  if ( isset($_GET['id']) ) { 
	  $id = $_GET['id'];
	  } 

	
      if ( $f['type'] == 'link' ) { 
        if ( $readOnly  ) {
		  $link = "$CFG->wwwroot".'/mod/lem/view.php?id='.$id.'&page=enquestador&mod=formularis&form=';
     	  $aux=$f['field'];
		  $aux2=$f['datalink'];
		  $html.= '<A HREF='.$link.$e->$aux2.'>'.$e->$aux.'</A>';
		  
        } else {
          $div=$f['width'] / 10;
  		   $aux=$f['field'];
		  $html.= '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$f['field'].'" value="'.$e->$aux .'"></center>';
		  
        }
        $identificador=$e->id;
      }//if link
	  //***************************************************
	  //*********** configures textarea type ******************
	  //***************************************************
      if ( $f['type'] == 'textarea' ) { 
        if ( $readOnly ) {
       	  $aux=$f['field'];
		  $html.= $e->$aux;
		  
        } else {
          $div=$f['width'] / 7;
     	  $aux=$f['field'];
		  $html.='<center><textarea name="'.$f['field'].'" rows="4" cols="'.$div.'"'.$textBox.'">'.$e->$aux.' </textarea></center>';
		  
        }
        $identificador=$e->id;
      }//if text
      //***************************************************
   	  //*********** configures combo type *****************
	  //***************************************************
      if ( $f['type'] == 'combo' ) {
        if ( $readOnly  ) {
          $aux=$f['field'];
		  $html.= $e->$aux;
		  
        } else {
    	  $aux=$f['field'];
		  showCombo($f['field'], $f['field'], $e->$aux, $colorComboBox, $f['sqlShow'], $f['fieldShow'], $f['fieldShow'], $f['icons'], $f['hide'], $f['width'], 'false','false');
		  
        }
      }//if combo
    
      //***************************************************
   	  //*********** configures array type *****************
	  //***************************************************
      if ( $f['type'] == 'array' ) {
		  $aux=$f['field'];  //***modif 17/10/2018
        if ( $readOnly  ) {
      	  $html.= $e->$aux;
        } else {

		if ($e->$aux!=''){
		 
		  $valor =$e->$aux;
		  
		 } else {
		   $valor = $f['default'];
		 }
		 array_push($f['data'], "%");
		 showComboArray($f['field'], $f['field'], $valor, $f['data'], $f['icons'], $f['hide'], $f['width'], $colorComboBox); //$e->$f['field']
        }
      }//if array
    }//foreach
    //***************************************************
    //******* configures update and delete icons ********
    //***************************************************
   
	if ( !$readOnly ) {
		$html.= $celda;   //celda delete
		$html.= '<center><input name="delete'.$cont.'" type="checkbox"></center>';
		$html.= '</td>';
	}

	$html.= '<input type = "hidden" name = "Id'.$cont.'" value = "'.$identificador.'">';
  
    $html.= '  </tr>';
	
    $cont++;
	
  }//foreach
     $html.= '</table>';
	
	if (isset($_GET['page']))
	    $pagina = $_GET['page'];
	else
		$pagina = 'students';
	
	$html.= '<input type = "hidden" name = "page" value = "'.$pagina.'">';
    $html.= '<input type = "hidden" name = "operation" value = "updateRecordset">';
    $html.= '<input type = "hidden" name = "table" value = "'.$table.'">';
	$html.= '<input type = "hidden" name = "maxcont" value = "'.$cont--.'">';
	
	$html.= '<br>';
	if ( !$readOnly ) {
	    $html.= '<center><input type="submit" value="'.get_string('update','msociograma').' '.$group .' '.'"></center>';
	}
 	$html.= '</form>';
	
	return $html;

}//function	

//this function show form for enter students file
function csvForm($idActivityModule){
	global $DB, $CFG, $COURSE;
	$curs = $COURSE->id;
	//********************************************calculate $cm*********************
	$id = optional_param('id', 0, PARAM_INT);  // Course module ID
	$f  = optional_param('f', 0, PARAM_INT);   // msociograma instance id

	if ($f) {  // Two ways to specify the module
		$msociograma = $DB->get_record('msociograma', array('id'=>$f), '*', MUST_EXIST);
		$cm = get_coursemodule_from_instance('msociograma', $msociograma->id, $msociograma->course, false, MUST_EXIST);

	} else {
		$cm = get_coursemodule_from_id('msociograma', $id, 0, false, MUST_EXIST);
		$msociograma = $DB->get_record('msociograma', array('id'=>$cm->instance), '*', MUST_EXIST);
	}
		
		require_once('mod_form2.php');
				
		$form = new mod_msociograma_loadSheet_form('view.php?id='.$cm->id.'&page=students&mode=enter');//alerta id
		if ($form->is_cancelled()) {
		
	  // Display a message or redirect somewhere if cancelled
	  redirect("$CFG->wwwroot/mod/msociograma/view.php?id=$cm->id");//redirect 
	} else if ($data = $form->get_data()){ {
		$link     = $data->attachmentsfiles;
		if ($data->addRegisters == '1')
		  $addRegisters = 'delete';
		else
		   $addRegisters = 'add';

		 urlFile($link,$idActivityModule,$curs,$addRegisters);
		
	 }

	}
	$form->display();
}//function csvForm	

//this function loads students file
function urlFile($link,$id,$curs,$addRegisters){
  global $DB, $CFG, $USER,$COURSE;

  require_once('../../config.php');
  $usercontext = context_user::instance($USER->id);
  $fs = get_file_storage();
 
  $sql = "SELECT * FROM {$CFG->prefix}files WHERE itemid like ? AND filename <> '.'";
  if ($data = $DB->get_records_sql($sql, array($link))){
	foreach($data as $element){
	  $fs = get_file_storage();
	  $file = $fs->get_file_by_hash($element->pathnamehash); //pista correcta se refiere a pathnamehash de mysql
      $filename = $file->get_filename();
      $url= "$CFG->wwwroot".'/mod/msociograma/msociograma_draftfile.php?id='.$id.'&course='.$curs.'&addRegisters='.$addRegisters.'&file='.'/'.$file->get_contextid().'/'. $file->get_component().'/'.$file->get_filearea().'/'.$file->get_itemid(). $file->get_filepath(). $filename;
      echo '<br>'; //unmark if list
	  
	  redirect($url);//redirect

    }
   }
   
} //function urlFile

//this function creates a random name
function nom_atzar(){
$num = rand (1000000,9999999);
return $num;
} //function nom_atzar

//this function creates a database combobox for loginSheet
function showComboLogin($id, $name, $value, $color, $sqlShow, $fieldShow, $fieldValue, $icons, $hide, $width, $all, $inform){
  global $DB, $CFG, $USER,$COURSE;
  //combobox paramenters
  if ( $inform == true ) {
    echo '<select id = "'.$id.'" class = "'.$id.'" name = "'.$name.'" '.$color.'; onchange = "submit()" >';
  }	else {
	echo '<select id = "'.$id.'" class = "'.$id.'" name = "'.$name.'" '.$color.' >';
  }
  
  //list within
  if (!$data = $DB->get_records_sql($sqlShow, array('%'))){
  } else {
	echo '<option value="'.$value.'">'.$value.'</option>';
    foreach($data as $entrada_log){
	  echo '<option value="'.$entrada_log->$fieldValue.'">';
	  echo $entrada_log->$fieldShow;
	  echo '</option>';
	}	
	if ($all == 'true') {
	  echo '<option value="%">'.'%'.'</option>'; //last item in the list 
	}
	echo'</select>'; //end of combobox paramenters
  }//if

}//function 	

//this function is the ini of form in loginSheet.php
function iniForm($height, $width){
    global  $PAGE;
	echo '<center><div style="width:'.$width.';height:'.$height.';-webkit-border-radius: 20px;-moz-border-radius: 20px;border-radius: 20px;border:7px solid #837E91;background-color:#B6C8D1;-webkit-box-shadow: #B3B3B3 36px 36px 36px;-moz-box-shadow: #B3B3B3 36px 36px 36px; box-shadow: #B3B3B3 26px 26px 26px;">';
	echo '<center><table><tr><td>'; 
	echo '<form id="log_form" autocomplete="false" action="view.php?id='.$PAGE->cm->id.'" method="POST" enctype="multipart/form-data">';
}
  
  
  //this function is the end of form in loginSheet.php
function finalForm($button){
	echo '<br>';
	echo '<br>';
	if ($button) 
		echo ' <input type="submit" value="'.get_string('send','msociograma').'">';
	echo '</form>';
	echo '</td></tr></table></center>'; 
	echo '</div></center>'; //end of rounded window
}
  
  //this function build a combobox and the icon in every cell 
function showcomboxSheet($student,$group,$num,$me,$neighbours){

	 global $CFG,$COURSE,$DB,$LISTA;
	 $idActivityModule = $_GET['id'];
	 $nombreActividad = nombreActividad ($idActivityModule);
	 $course = $COURSE->id;
	 
	 $idStudent = name2IdStudent($course,$idActivityModule, $group, $student);
	 $valor = idStudent2Name($course,$idActivityModule, $group, $LISTA[$num]);
	 if ($valor == get_string('selectstudent','msociograma'))
	   echo'<IMG SRC="./images/no_record.gif"  id ="icon'.$num.'" ALIGN=LEFT WIDTH=20 HEIGHT=20 BORDER=0>';
	 else
	   echo'<IMG SRC="./images/ok.gif" id ="icon'.$num.'" ALIGN=LEFT WIDTH=20 HEIGHT=20 BORDER=0>';

	 $sql2="SELECT  DISTINCT student FROM {msociograma_sheet} WHERE activity = '$idActivityModule' and groupclass = '$group' ORDER BY student"; //***modif 17/10/2018 (add DISTINCT)
	 $data = $DB->get_records_sql($sql2, array('%')); 
	if ($valor == get_string('selectstudent','msociograma')){
		echo '<input type="password" id="'.$num.'pass" value="nuncalosabras" size= 25 style="display: none" onClick="aparece(this.id)">';  //****************************************************señuelo 
        echo '<select id = "'.$num.'" class = "'.$num.'" width = 100 name = "answer'.$num.'" style="display: block" onchange = "enviarDatos(this.id); return false" onBlur="desaparece(this.id)">';
	}else{
 		echo '<input type="password" id="'.$num.'pass" value="nuncalosabras" size= 25 style="display: block" onClick="aparece(this.id)">';  //****************************************************señuelo 
		echo '<select id = "'.$num.'" class = "'.$num.'" width = 100 name = "answer'.$num.'" style="display: none" onchange = "enviarDatos(this.id); return false" onBlur="desaparece(this.id)">';
	}

	if ($valor == get_string('selectstudent','msociograma')) //Choose answer if it has not yet been done
		echo '<option value="0" disabled selected >'.get_string('selectstudent','msociograma').'</option>'; //nobody
	else
		echo '<option value="0" disabled>'.get_string('selectstudent','msociograma').'</option>'; //nobody
	
	foreach($data as $nombres){
	
		if ((($me) || ($nombres->student != $student)) && (!in_array($nombres->student,$neighbours))){  //Discriminates the reflexive response
			if (($valor == $nombres->student) && (!$hidden)) //Choose the answer provided it is not hidden environment
				echo '<option value="'.name2IdStudent($course,$idActivityModule, $group, $nombres->student).'" selected >';
			else 
				echo '<option value="'.name2IdStudent($course,$idActivityModule, $group, $nombres->student).'">';
			
		   echo $nombres->student;
		   echo '</option>';
		 }  else {
			echo '<option value="'.name2IdStudent($course,$idActivityModule, $group, $nombres->student).'" disabled >';
			 echo $nombres->student;
		     echo '</option>';
		 }
  }
		 if (($valor == get_string('nobody','msociograma')) && (!$hidden)) //Choose the answer if is nobody
			echo '<option value="10000" selected>'.get_string('nobody','msociograma').'</option>'; //nobody
		 else
			echo '<option value="10000">'.get_string('nobody','msociograma').'</option>'; //nobody
	echo '</select>';
	echo '<INPUT TYPE = HIDDEN id = "course" NAME = course VALUE = "'.$course.'">';
	echo '<INPUT TYPE = HIDDEN id= "id" NAME = id VALUE = "'.$idActivityModule.'">';
	echo '<INPUT TYPE = HIDDEN id= "id_stu" NAME = id_stu VALUE = "'.$idStudent.'">';
	echo '<INPUT TYPE = HIDDEN id = "grup" NAME = grup VALUE = "'.$group.'">';
	echo '<INPUT TYPE = HIDDEN id = "question" NAME = question VALUE = "'.$num.'">';
  
}//function showcomboSheet($num)

//this function return the student id
function name2IdStudent($course,$idActivityModule, $group, $student){
	global $DB;
	$sql = "SELECT * FROM {msociograma_sheet} WHERE course= '$course' AND activity = '$idActivityModule' AND groupclass = '$group' AND student = '$student'"; // moodle 3.0

	if ($data = $DB->get_records_sql($sql, array('%'))){				
		foreach($data as $registro){
			   $salida = $registro->id;
		}   
		return $salida;
		}
	  else
		return 0;

}//funtion name2IdStudent

//this function return the student name
function idStudent2Name($course,$idActivityModule, $group, $idStudent){
  global $DB;
  if ($idStudent == '10000'){
		  return get_string('nobody','msociograma'); //if is nobody
  } else {
	  $sql = "SELECT * FROM {msociograma_sheet} WHERE course= '$course' AND activity = '$idActivityModule' AND groupclass = '$group' AND id = '$idStudent'"; // moodle 3.0

		if ($data = $DB->get_records_sql($sql, array('%'))){				
			foreach($data as $registro){
				   $salida = $registro->student;
				 }   
			return $salida;
		} else {
		return get_string('selectstudent','msociograma');
		}
  }
}//funtion name2IdStudent


//this funtion calculates all groups
function sacaGrupos($course,$idActivityModule){
	global $DB;
	$sql="SELECT DISTINCT groupclass FROM {msociograma_sheet} WHERE course= '$course' AND activity = '$idActivityModule' ORDER BY  groupclass";
	
	if (!$data = $DB->get_records_sql($sql, array('%'))){
		//connection error
		return 'void';
	}else{
		$i=0;
		$temp = array();
		foreach($data as $registro){
		  $temp[$i]=$registro->groupclass;
		  $i++;
		}
		return $temp;
	}
}//sacaGrupos
	
//this function shows all answers
function showAnswers($course, $idActivityModule, $idStudent, $group){
 global $CFG,$COURSE,$DB;

	$sql = 	"SELECT * FROM {$CFG->prefix}msociograma_answers 
			WHERE course='$course' 
			AND activity='$idActivityModule' 
			AND id_stu='$idStudent' 
			AND grup ='$group' 
			ORDER BY id";
	
	if ($data = $DB->get_records_sql($sql, array('%'))){
		
		$temp = array();
		for ($x=1;$x<=36;$x++) //restart $temp
			$temp[$x]= '0';
			
		foreach($data as $registro){
		  $temp[$registro->question]=$registro->stu_ans;
		}
      return $temp;      
	}
	
	
}	//showAnswers

//update the groups if are modified
function updateGroups($course, $activity){
    global $CFG,$COURSE,$DB; 
    $sql = 	"SELECT DISTINCT groupclass FROM {$CFG->prefix}msociograma_sheet
			WHERE course='$course' 
			AND activity='$activity' 
			ORDER BY groupclass";

	if ($data = $DB->get_records_sql($sql, array('%'))){
            $sheetGroup = array();
            $cont=0;
            foreach($data as $registro){
              $sheetGroup[$cont]= $registro->groupclass;  
              $cont++;
            }
            
    $sql = 	"SELECT groupclass FROM {$CFG->prefix}msociograma_groups
			WHERE course='$course' 
			AND activity='$activity' 
			ORDER BY groupclass";

	if ($data = $DB->get_records_sql($sql, array('%'))){
		$DBGroup = array();
		$cont=0;
		foreach($data as $registro){
		  $DBGroup[$cont]= $registro->groupclass;  
		  $cont++;
		}        
	   
		//delete of msociograma_groups if no exists
		  foreach($DBGroup as $elem1){
			  $existe=false;
			   foreach($sheetGroup as $elem2){
				  if ($elem1==$elem2)
					  $existe=true;
				  }
				  if (!$existe){//delete recordset
			  
					  $sql = "DELETE FROM {$CFG->prefix}msociograma_groups WHERE activity = '$activity' AND course='$course' AND groupclass='$elem1'";
					  $params = array('%');
					  $DB->execute($sql, $params);
				  }
		  }
			//add to msociograma_groups the new students sheet
		  foreach( $sheetGroup as $elem1){
			  $existe=false;
			   foreach($DBGroup as $elem2){
				  if ($elem1==$elem2)
					  $existe=true;
				  }
				  if (!$existe){//add recordset
					  echo "insertar";
					 $sql = "INSERT INTO {$CFG->prefix}msociograma_groups (course,activity,groupclass,enabled) VALUES ('$course','$activity','$elem1','0') ";
					 $params = array('%');
					 $DB->execute($sql, $params);
				  }
		  }
		  
        } else {//if no recordset add all
            
             foreach($sheetGroup as $elem){
                $sql = "INSERT INTO {$CFG->prefix}msociograma_groups (course,activity,groupclass,enabled) VALUES ('$course','$activity','$elem','0') ";
				$params = array('%');
				$DB->execute($sql, $params);
             }
            
        }   
            
    }
    
    
    
}//updateGroups

//*****************************************************************
//***** this function create a queryview writable table ***********
//*****************************************************************
function tableGroups($sql,$table, $fields,$readOnly){

  global $DB, $COURSE, $PAGE, $CFG;

  if (!$data  = $DB->get_records_sql($sql, array('%')))
  {
   
	echo '<br><br><b>'.get_string('norecordset', 'msociograma').'</b><br><br>';
	return;
  }
  echo ' <form id="contact_form" action="view.php?id='.$PAGE->cm->id.'" method="POST" enctype="multipart/form-data">';
  echo '     <table border=0 cellpadding=5>';   
  foreach ($fields as $f){
    echo '   <col width='.$f['width'].'>';
  }
  //header of the table	
  echo '     <tr  align=center bordercolor= "#f5d0a9">';
  foreach ($fields as $f){
    echo '    <td>'.$f['alias'].'</td>';
  }
 
  echo '     </tr>';

  $cont=0;
  $datos = new stdClass();
  foreach ($data as $e){
    if ($cont%2 == 0 ){   //alternate the color of every row
      $celda='<td style="border: thin solid; border-color: #F7BE81" bgcolor= "#ffd98f" border =1>';     //grey "#cccccc">';
	  $textBox ='style="background-color: #ffd98f" ';
	  $colorComboBox ='style="background-color: #ffd98f" ';
    } else {
      $celda='<td  style="border: thin solid;border-color: #F7BE81" >';
      $textBox='';
	  $colorComboBox ='';
    }

    echo '  <tr valing=top height="10">';
    //start data row
    foreach ($fields as $f){ 
 	$aux=$f['field'];
	$aux2=str_replace(" ","%",$e->$aux);
	$datos->$aux=$aux2;
	
      echo $celda;
	
      //***************************************************
      //*********** configures check type *****************
	  //***************************************************
	  if ( $f['type'] == 'check' ) {   
		 $aux=$f['field'];
		 if ( $e->$aux == "#ok" ) { 
		 
          $checked="checked";
        } else {
          $checked="";
        }
        if ( $readOnly == 'true' ) {
          $enabled='disabled="disabled"';
        } else {
          $enabled="";
        }
        echo '<center><input type=checkbox '.$enabled.' name='.$f['field'].$cont.' '.$checked.' value="#ok"></center>';
      }//if check

	  //***************************************************
	  //*********** configures imagecheck type ************
	  //***************************************************
      if ( $f['type'] == 'imagecheck' ) {
        if ( $e->$f['field'] == "1" ) {
          $image = 'pix/task-done.svg';
        } else {
          $image = 'pix/task-fail.svg';
        }   
        echo '<center><img src='.$image.' width=20 height=30></center>';
      }//if imagecheck
	   //***************************************************
	  //*********** configures imagenull type ************
	  //***************************************************
      if ( $f['type'] == 'imagenull' ) {
        if ( $e->$f['field'] == null ) {
          $image = 'pix/task-fail.svg';
        } else {
          $image = 'pix/task-done.svg';
        }   
        echo '<center><img src='.$image.' width=15 height=20></center>';
      }//if imagenull

      //***************************************************
	  //*********** configures text type ******************
	  //***************************************************
      if ( $f['type'] == 'text' ) { //modificado para no editar texto
   
         $aux=$f['field'];
		 echo $e->$aux;
		 
		$identificador=$e->id;
      }//if text
	  
	  //***************************************************
	  //*********** configures date type ******************
	  //***************************************************
      if ( $f['type'] == 'date' ) { 
	    $date = date_create($e->$f['field']);
		$fecha= date_format($date, 'd/m/Y');
        if ( $readOnly == 'true' ) {
          echo $fecha;
        } else {
          $div=$f['width'] / 10;

          echo '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$f['field'].'" value="'.$fecha .'"></center>';
        }
        $identificador=$e->id;
      }//if date
	  
	  //***************************************************
	  //*********** configures link type ******************only for enquestador
	  //***************************************************
	  if ( isset($_GET['id']) ) { 
		$id = $_GET['id'];
	  } 

	
      if ( $f['type'] == 'link' ) { 
        if ( $readOnly == 'true' ) {
		  $link = "$CFG->wwwroot".'/mod/lem/view.php?id='.$id.'&page=enquestador&mod=formularis&form=';
          echo '<A HREF='.$link.$e->$f['datalink'].'>'.$e->$f['field'].'</A>';
        } else {
          $div=$f['width'] / 10;
          echo '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$f['field'].'" value="'.$e->$f['field'] .'"></center>';
        }
        $identificador=$e->id;
      }//if link
	  
	  //***************************************************
	  //*********** configures textarea type ******************
	  //***************************************************
      if ( $f['type'] == 'textarea' ) { 
        if ( $readOnly == 'true' ) {
          echo $e->$f['field'];
        } else {
          $div=$f['width'] / 7;
          echo'<center><textarea name="'.$f['field'].'" rows="4" cols="'.$div.'"'.$textBox.'">'.$e->$f['field'].' </textarea></center>';
        }
        $identificador=$e->id;
      }//if text
	  
      //***************************************************
   	  //*********** configures combo type *****************
	  //***************************************************
      if ( $f['type'] == 'combo' ) {
        if ( $readOnly == 'true' ) {
          echo $e->$f['field'];
        } else {
          showCombo($f['field'], $f['field'], $e->$f['field'], $colorComboBox, $f['sqlShow'], $f['fieldShow'], $f['fieldShow'], $f['icons'], $f['hide'], $f['width'], 'false','false');
        }
      }//if combo
    
      //***************************************************
   	  //*********** configures array type *****************
	  //***************************************************
      if ( $f['type'] == 'array' ) {
        if ( $readOnly == 'true' ) {
          echo $e->$f['field'];
        } else {
    		 if ($e->$f['field']!=''){
		   $valor =$e->$f['field'];
		 } else {
		   $valor = $f['default'];
		 }
		 array_push($f['data'], "%");
		 showComboArray($f['field'], $f['field'], $valor, $f['data'], $f['icons'], $f['hide'], $f['width'], $colorComboBox); //$e->$f['field']
        }
      }//if array
    }//foreach

	echo '<input type = "hidden" name = "Id'.$cont.'" value = "'.$identificador.'">';
  
    echo '  </tr>';
	
    $cont++;
	
  }//foreach
     echo '</table>';
	
	if (isset($_GET['page']))
	    $pagina = $_GET['page'];
	else
		$pagina = 'activationgroup';
	
	echo '<input type = "hidden" name = "page" value = "'.$pagina.'">';
    echo '<input type = "hidden" name = "operation" value = "updateRecordsetGroups">';
    echo '<input type = "hidden" name = "table" value = "'.$table.'">';
	echo '<input type = "hidden" name = "maxcont" value = "'.$cont--.'">';
	
	echo '<br>';
	echo '<input type="submit" value="'.get_string('update','msociograma').'">';
	
 	echo '</form>';


}//function

//*****************************************************************
//***** this function create a queryview writable table ***********
//*****************************************************************
function tableTutoring($sql,$table, $fields,$readOnly){

  global $DB, $COURSE, $PAGE, $CFG;

  if (!$data  = $DB->get_records_sql($sql, array('%')))
  {
   
	echo '<br><br><b>'.get_string('norecordset', 'msociograma').'</b><br><br>';
	return;
  }
  echo ' <form id="contact_form" action="view.php?id='.$PAGE->cm->id.'" method="POST" enctype="multipart/form-data">';
  echo '     <table border=0 cellpadding=5>';   
  foreach ($fields as $f){
    echo '   <col width='.$f['width'].'>';
  }
  //header of the table	
  echo '     <tr  align=center bordercolor= "#f5d0a9">';
  foreach ($fields as $f){
    echo '    <td>'.$f['alias'].'</td>';
  }
 
  echo '     </tr>';

  $cont=0;
  $datos = new stdClass();
  foreach ($data as $e){
    if ($cont%2 == 0 ){   //alternate the color of every row
      $celda='<td style="border: thin solid; border-color: #F7BE81" bgcolor= "#ffd98f" border =1>';     //grey "#cccccc">';
	  $textBox ='style="background-color: #ffd98f" ';
	  $colorComboBox ='style="background-color: #ffd98f" ';
    } else {
      $celda='<td  style="border: thin solid;border-color: #F7BE81" >';
      $textBox='';
	  $colorComboBox ='';
    }

    echo '  <tr valing=top height="10">';
    //start data row
    foreach ($fields as $f){ 
 	$aux=$f['field'];
	$aux2=str_replace(" ","%",$e->$aux);
	$datos->$aux=$aux2;
	
      echo $celda;
	
      //***************************************************
      //*********** configures check type *****************
	  //***************************************************
	  if ( $f['type'] == 'check' ) {   
		 $aux=$f['field'];
		 if ( $e->$aux == "#ok" ) { 
		 
          $checked="checked";
        } else {
          $checked="";
        }
        if ( $readOnly == 'true' ) {
          $enabled='disabled="disabled"';
        } else {
          $enabled="";
        }
        echo '<center><input type=checkbox '.$enabled.' name='.$f['field'].$cont.' '.$checked.' value="#ok"></center>';
      }//if check

	  //***************************************************
	  //*********** configures imagecheck type ************
	  //***************************************************
      if ( $f['type'] == 'imagecheck' ) {
        if ( $e->$f['field'] == "1" ) {
          $image = 'pix/task-done.svg';
        } else {
          $image = 'pix/task-fail.svg';
        }   
        echo '<center><img src='.$image.' width=20 height=30></center>';
      }//if imagecheck
	   //***************************************************
	  //*********** configures imagenull type ************
	  //***************************************************
      if ( $f['type'] == 'imagenull' ) {
        if ( $e->$f['field'] == null ) {
          $image = 'pix/task-fail.svg';
        } else {
          $image = 'pix/task-done.svg';
        }   
        echo '<center><img src='.$image.' width=15 height=20></center>';
      }//if imagenull

      //***************************************************
	  //*********** configures text type ******************
	  //***************************************************
      if ( $f['type'] == 'text' ) { 
		$aux=$f['field'];
        if ( $readOnly == 'true' ) {
        	  echo $e->$aux;
        } else {
          $div=$f['width'] / 10;
       	  //echo '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$aux.'" value="'.$e->$aux .'"></center>';
		  echo '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$aux.$cont.'" value="'.$e->$aux .'"></center>';
		 
        }
        $identificador=$e->id;
      }//if text
	  
	  //***************************************************
	  //*********** configures textOnlyRead type ******************
	  //***************************************************
      if ( $f['type'] == 'textOnlyRead' ) { 
    	echo $e->$aux;
     
        $identificador=$e->id;
      }//if text
	  
	  //***************************************************
	  //*********** configures date type ******************
	  //***************************************************
      if ( $f['type'] == 'date' ) { 
	    $date = date_create($e->$f['field']);
		$fecha= date_format($date, 'd/m/Y');
        if ( $readOnly == 'true' ) {
          echo $fecha;
        } else {
          $div=$f['width'] / 10;

          echo '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$f['field'].'" value="'.$fecha .'"></center>';
        }
        $identificador=$e->id;
      }//if date
	  
	  //***************************************************
	  //*********** configures link type ******************only for enquestador
	  //***************************************************
	  if ( isset($_GET['id']) ) { 
		$id = $_GET['id'];
	  } 

	
      if ( $f['type'] == 'link' ) { 
        if ( $readOnly == 'true' ) {
		  $link = "$CFG->wwwroot".'/mod/lem/view.php?id='.$id.'&page=enquestador&mod=formularis&form=';
          echo '<A HREF='.$link.$e->$f['datalink'].'>'.$e->$f['field'].'</A>';
        } else {
          $div=$f['width'] / 10;
          echo '<center><input type=input size="'.$div.'" '.$textBox.' name="'.$f['field'].'" value="'.$e->$f['field'] .'"></center>';
        }
        $identificador=$e->id;
      }//if link
	  
	  //***************************************************
	  //*********** configures textarea type ******************
	  //***************************************************
      if ( $f['type'] == 'textarea' ) { 
        if ( $readOnly == 'true' ) {
          echo $e->$f['field'];
        } else {
          $div=$f['width'] / 7;
          echo'<center><textarea name="'.$f['field'].'" rows="4" cols="'.$div.'"'.$textBox.'">'.$e->$f['field'].' </textarea></center>';
        }
        $identificador=$e->id;
      }//if text
	  
      //***************************************************
   	  //*********** configures combo type *****************
	  //***************************************************
      if ( $f['type'] == 'combo' ) {
		  $aux=$f['field'];
        if ( $readOnly == 'true' ) {
          echo $e->$f['field'];
        } else {
          showComboTutoring( $aux.$cont,  $aux.$cont, $e->$aux, $colorComboBox, $f['sqlShow'], $f['fieldShow'], $f['fieldShow'], $f['icons'], $f['hide'], $f['width'], 'false','false');
        }
      }//if combo
    
      //***************************************************
   	  //*********** configures array type *****************
	  //***************************************************
      if ( $f['type'] == 'array' ) {
        if ( $readOnly == 'true' ) {
          echo $e->$f['field'];
        } else {
    		 if ($e->$f['field']!=''){
		   $valor =$e->$f['field'];
		 } else {
		   $valor = $f['default'];
		 }
		 array_push($f['data'], "%");
		 showComboArray($f['field'], $f['field'], $valor, $f['data'], $f['icons'], $f['hide'], $f['width'], $colorComboBox); //$e->$f['field']
        }
      }//if array
    }//foreach

	echo '<input type = "hidden" name = "Id'.$cont.'" value = "'.$identificador.'">';
  
    echo '  </tr>';
	
    $cont++;
	
  }//foreach
     echo '</table>';
	
	if (isset($_GET['page']))
	    $pagina = $_GET['page'];
	else
		$pagina = 'activationgroup';
	
	echo '<input type = "hidden" name = "page" value = "'.$pagina.'">';
    echo '<input type = "hidden" name = "operation" value = "updateRecordsetTutoring">';
    echo '<input type = "hidden" name = "table" value = "'.$table.'">';
	echo '<input type = "hidden" name = "maxcont" value = "'.$cont--.'">';
	
	echo '<br>';
	echo '<input type="submit" value="'.get_string('update','msociograma').'">';
	
 	echo '</form>';


}//function


function in_multiarray($elem, $array)
{
    while (current($array) !== false) {
        if (current($array) == $elem) {
            return true;
        } elseif (is_array(current($array))) {
            if (in_multiarray($elem, current($array))) {
                return true;
            }
        }
        next($array);
    }
    return false;
}

//*****************************************************************************
//****** this function build a combobox and the icon in every cell ************
//*****************************************************************************
function showComboTutoring($id, $name, $value, $color, $sqlShow, $fieldShow, $fieldValue, $icons, $hide, $width, $all, $inform){
  global $DB, $CFG, $USER,$COURSE;
  //combobox paramenters
  if ( $inform == 'true' ) {
    echo '<select id = "'.$id.'" class = "'.$id.'" name = "'.$name.'" '.$color.'; onchange = "submit()" >';
  }	else {
	echo '<select id = "'.$id.'" class = "'.$id.'" name = "'.$name.'" '.$color.' >';
  }
  
  //list within
  if (!$data = $DB->get_records_sql($sqlShow, array('%'))){
  } else {
	echo '<option value="'.$value.'">'.$value.'</option>';
	echo '<option value=""></option>';
	echo '<option value="%">%</option>';
    foreach($data as $entrada_log){
	  echo '<option value="'.$entrada_log->$fieldValue.'">';
	  echo $entrada_log->$fieldShow;
	  echo '</option>';
	}	
	if ($all == 'true') {
	  echo '<option value="%">'.'%'.'</option>'; //last item in the list 
	}
	echo'</select>'; //end of combobox paramenters
  }//if

}//function 
