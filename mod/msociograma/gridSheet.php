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

//This file corresponds to the  gridSheet.php. It is  students's form
//for send the answers to the CESC questions.

global $DB,$LISTA, $COURSE;
 
require_once('msociogramaFW.php');
require_once("$CFG->dirroot/mod/msociograma/locallib.php");

//$noReflexivo = array(1,2,12); //no himself
$noReflexivo = array(1,2,3,4,5,6,7,8,9,10,11,12); //every question is no himself
	
if (!$_SESSION['login'])
	print_error('nocapabilities', 'msociograma');

if (isset($_POST['log_student'])){
    $student = $_POST['log_student'];  
    $group = $_POST['log_group'];  
} else {
    $student =  $_SESSION['student'];  
    $group =  $_SESSION['group'];    
}
$course = $COURSE->id;

$idStundent = name2IdStudent($course,$idActivityModule, $group, $student);
	
echo '<script language="JavaScript" type="text/javascript" src="ajax.js"></script>';
	 
echo '<font face=arial style="font-size: 12pt" color=red>'.get_string('hide','msociograma').'</font>
<input type="checkbox" value="texto" id="ch" checked onclick="procesar()">
<script language="javascript">

function procesar(){
	if(document.getElementById("ch").checked) 
		pass();
	else
		texto();
	
}

function pass(){
	for (i=1;i<=36;i++){
		var e = document.getElementById(i);
		var strUser = e.options[e.selectedIndex].text;
		if (strUser != "'.get_string('selectstudent','msociograma').'"){ //evitar el cambio si no hay elegido
			document.getElementById(i).style.display="none";
			document.getElementById(i+"pass").style.display="block";
		}
	}
	
	document.getElementById("ch").checked=true
}
function texto(){
for (i=1;i<=36;i++){
	document.getElementById(i).style.display="block";
	document.getElementById(i+"pass").style.display="none";
	}
}

function aparece(id){
	
		id = id.replace("pass", ""); //reconvierte el id del señuelo
		document.getElementById(id).style.display="block";
		document.getElementById(id).focus();
		document.getElementById(id+"pass").style.display="none";
}

function desaparece(id){
		var e = document.getElementById(id);
		var strUser = e.options[e.selectedIndex].text;
		if (strUser != "'.get_string('selectstudent','msociograma').'"){ //evitar el evento si no hay elegido
			document.getElementById(id).style.display="none";
			document.getElementById(id+"pass").style.display="block";
			
		}
}

function showIcon(id){
		var e = document.getElementById(id);
		var strUser = e.options[e.selectedIndex].text;
		if (strUser != "'.get_string('selectstudent','msociograma').'")
			document.getElementById("icon"+id).src="./images/ok.gif";
		else
			document.getElementById("icon"+id).src="./images/no_record.gif";
			
			//document.getElementById("12").options[2].disabled=true
}

function eliminaItems(combo,stu_ans){
resto= combo%3
valor = parseInt(combo)
if (resto == 1){
		objetivo1= valor +1
		objetivo2= valor +2
	}else if (resto ==2){
		objetivo1= valor -1
		objetivo2= valor +1
	}else if (resto ==0){
		objetivo1= valor -2
		objetivo2= valor -1
	}
	reiniciaDisable(objetivo1)
	reiniciaDisable(objetivo2)
	
	/*sel = document.getElementById(objetivo1); 
	for (x=1;x < sel.length; x++) 
		if (sel.options[x].value == stu_ans)
			sel.options[x].disabled=true
			
	sel = document.getElementById(objetivo2); 
	for (x=1;x < sel.length; x++) 
		if (sel.options[x].value == stu_ans)
			sel.options[x].disabled=true	*/
			
	sel = document.getElementById(objetivo1);
	valor1 = sel.options[sel.selectedIndex].value;
	sel = document.getElementById(objetivo2);
	valor2 = sel.options[sel.selectedIndex].value;
	
	 
	quitaElemento (objetivo1,stu_ans)		
	quitaElemento (objetivo1,valor2)
	quitaElemento (objetivo2,stu_ans)
	quitaElemento (objetivo2,valor1)	
			
//alert(combo+"-"+stu_ans+"-"+resto +"-"+objetivo1+"-"+objetivo2)
}
function quitaElemento (combo,valor){
if (valor == 10000) exit
sel = document.getElementById(combo); 
	for (x=1;x < sel.length; x++) 
		if (sel.options[x].value == valor)
			sel.options[x].disabled=true


}


function reiniciaDisable(combo){

var noReflexivoJS='. json_encode($noReflexivo).'
question=Math.ceil(combo/3)
nameStudent="'.$student.'"
var sel = document.getElementById(combo);
		//var nombre = e.options[e.selectedIndex].text;

	//alert(nameStudent)
	for (x=1;x < sel.length; x++){ 
	  nameOption=sel.options[x].text
	  if((noReflexivoJS.indexOf(question) != -1) && (nameOption == nameStudent))
		sel.options[x].disabled=true
	  else	
		sel.options[x].disabled=false
	}
}


</script>';

	
	
		
$LISTA = showAnswers($course,$idActivityModule,$idStundent, $group); //get all answers


	echo '&nbsp;&nbsp;&nbsp;&nbsp<font face=arial style="font-size: 12pt" color=grey>'.get_string('student','msociograma').': <b>'.$_SESSION['student'].'</b></font>';	
	
	
	 if (logintype($idActivityModule)==1){// Moodle sheet
		$link = "$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&mode=restart";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<A HREF=$link>[".get_string('logout','msociograma')."]</A>";  
	 }

	//build the html-table
	echo '<center><TABLE WIDTH=90% BORDER=0 CELLPADDING=5>';
	//header of the table
	echo '  <TR  ALIGN=CENTER >';
	echo '    <TD ALIGN=LEFT>';
	echo '    </TD>';
	echo '    <TD>';
	echo 		get_string('first','msociograma');
	echo '    </TD>';
	echo '    <TD>';
	echo 		get_string('second','msociograma');
	echo '    </TD>';
	echo '    <TD>';
	echo        get_string('third','msociograma');
	echo '    </TD>';
	echo '  </TR>';

	//query for return the questions
	$sql="SELECT * FROM {$CFG->prefix}msociograma_questions";
	  if (!$log = $DB->get_records_sql($sql, array('%'))){
		   print_error('norecordset', 'msociograma');
	  } else {
		$cont=0;
		foreach($log as $entrada_log){
		  if ($cont%2 == 0 ){   //alternate the color of every row
			$celda='<TD  style="border: thin solid" BGCOLOR="#cccccc">';
		  } else {
			$celda='<TD  style="border: thin solid" >';
		  }
	echo '  <TR VALIGN=TOP >';
	echo      $celda;//<TD>
	echo        $cont + 1 .'- '. get_string($entrada_log->question,'msociograma');
	echo '    </TD>';
	echo      $celda; //<TD>
				 $valor1 = idStudent2Name($course,$idActivityModule, $group, $LISTA[(($cont)*3)+2]); 
				 $valor2 = idStudent2Name($course,$idActivityModule, $group, $LISTA[(($cont)*3)+3]);
				$neighbours = array ($valor1, $valor2); //no repeat answer
				if (in_array($cont+1, $noReflexivo))
				  showcomboxSheet($student,$group,($cont*3)+1,false,$neighbours);
			   else
				  showcomboxSheet($student,$group,($cont*3)+1,true,$neighbours);
	echo '    </TD>';
	echo      $celda; //<TD>
				$valor1 = idStudent2Name($course,$idActivityModule, $group, $LISTA[(($cont)*3)+1]);
				$valor2 = idStudent2Name($course,$idActivityModule, $group, $LISTA[(($cont)*3)+3]);
				$neighbours = array ($valor1, $valor2); //no repeat answer
				if (in_array($cont+1, $noReflexivo))
				  showcomboxSheet($student,$group,($cont*3)+2,false,$neighbours);
			   else
				  showcomboxSheet($student,$group,($cont*3)+2,true,$neighbours);
	echo '    </TD>';
	echo      $celda; //<TD>
				$valor1 = idStudent2Name($course,$idActivityModule, $group, $LISTA[(($cont)*3)+1]);
				$valor2 = idStudent2Name($course,$idActivityModule, $group, $LISTA[(($cont)*3)+2]);
				$neighbours = array ($valor1, $valor2);//no repeat answer
			   if (in_array($cont+1, $noReflexivo))
				  showcomboxSheet($student,$group,($cont*3)+3,false,$neighbours);
			   else
				  showcomboxSheet($student,$group,($cont*3)+3,true,$neighbours);
	echo '    </TD>';
	echo '  </TR>';
		  $cont++;
		}//foreach
	  }//if

	echo '</TABLE></center>';
	echo '<BR>';

	 if (logintype($idActivityModule)==1){// Moodle sheet
		$link = "$CFG->wwwroot/mod/msociograma/view.php?id=$idActivityModule&mode=restart";
		echo "<center><A HREF=$link>[".get_string('logout','msociograma')."]</A></center>";
	 }
	
	echo '<BR>';	

