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

//This file corresponds to the  "sociograma" tab. In this frame  the
//user can see the image with the sociograma map and the elements for
//change the position of the every student on the grid or select
//the answers to view.

require_once('msociograma_lib.php');
require_once('msociogramaFW.php');


echo'
    <style>
div.bordesolido{ 
   	border: 1px solid #aaaaaa; 
}
  </style>
';

$numberQuestionSelected ='0';

$groupSelected =get_string('select','msociograma'); 
$aliasselected =get_string('names','msociograma'); 
$questionSelected ='select';
$answerSelected = get_string('select','msociograma'); 

if (isset($_SESSION['group']))
  $groupSelected =$_SESSION['group'];

 $course = $COURSE->id;
 $idActivityModule = $_GET['id'];

if (isset($_POST['groupSelected']))
	if ($_POST['groupSelected'] !=  null ){
		$groupSelected =$_POST['groupSelected']; 
                $_SESSION['group'] = $_POST['groupSelected'];
        }
        
if (isset($_POST['aliasselected']))
	if ($_POST['aliasselected'] !=  null ){
		$aliasselected =$_POST['aliasselected']; 
    }
		
if (isset($_POST['questionSelected']))
	if ($_POST['questionSelected'] !=  null ){		
		$questionSelected =$_POST['questionSelected'];
        $numberQuestionSelected = str_replace('question','',$questionSelected);
    }

$sql="SELECT * FROM {msociograma_diagram} WHERE course= '$course' 
		AND activity = '$idActivityModule' 
		AND group_class ='$groupSelected' 
		AND question='$numberQuestionSelected'";
		
 if (!$data  = $DB->get_records_sql($sql, array('%')))	//only if no exists
	if ($questionSelected != 'select')
		colocaAzarCytoscan($groupSelected,$questionSelected);
 else
	$existsRecordset=true;

echo '<input type="hidden" id="answer" value="'.$answerSelected .'">';
echo '<input type="hidden" id="course" value="'.$course .'">';
echo '<input type="hidden" id="activity" value="'.$idActivityModule .'">';

echo '<input id="posx" type="hidden" value="o">';
echo '<input id="posy" type="hidden" value="o">';
echo '<input id="nodox" type="hidden" value="o"><br>';

if (isset($_SESSION['group']))
    echo '<input type="hidden" id="group_class" value="'.$_SESSION['group'] .'">';

echo "<input type='hidden' id='aliasselected2' value='$aliasselected' >";

$A = new combo('group');

if (isset($_SESSION['group'])){
    $A->iniValue = $_SESSION['group'];
    $A->iniText = $_SESSION['group'];
    $currentgroup = $_SESSION['group'];
} else {
    $A->iniValue = 'select';
    $A->iniText = get_string('selectgroup','msociograma');
}

$A->send  = false;
$A->select = $groupSelected;
   
$A->label=get_string('group','msociograma');
$sql="SELECT DISTINCT groupclass FROM {$CFG->prefix}msociograma_sheet WHERE activity = '$idActivityModule' AND course='$course' ORDER BY groupclass";

$A->loadDataFromSql($sql,'groupclass', 'groupclass');

$B = new combo('questions');
$B->send  = false;
$B->select = get_string($questionSelected,'msociograma');
$B->iniValue = 'select';
$B->iniText = get_string('select','msociograma');
$B->label=get_string('question','msociograma');
$sql="SELECT question  FROM {$CFG->prefix}msociograma_questions ORDER BY id";
$B->loadDataFromSqlLang($sql,'question', 'question');
 
$C = new combo('alias');
 
$C->iniValue = 'select';
$C->iniText = get_string('select','msociograma');

$C->send  = false;
$C->select = $aliasselected;
$C->label=get_string('labels','msociograma');

$datos = array(
         array('value'=>get_string('names','msociograma'),'text'=>get_string('names','msociograma')),
         array('value'=>get_string('names','msociograma'),'text'=>get_string('alias','msociograma')),
               );
$C->datos=$datos;
 
$D = new boton('boton');
$D->value=get_string('send','msociograma');
$D->hidden = array(
                   array('name'=>'groupSelected','value'=>$groupSelected),
                   array('name'=>'questionSelected','value'=>$questionSelected),
                   array('name'=>'aliasselected','value'=>$aliasselected), 
                   );

echo' <center><table><tr>';
echo '<td>';

$script=	'<script type="text/javascript"> 
			function guarda1(){
			document.getElementById("groupSelected").value=document.getElementById("group").options[document.getElementById("group").selectedIndex].text
			}
			</script>';
		
$A->showScript('guarda1()',$script);
      
echo '</td>';      
echo '<td>';

$script='<script type="text/javascript"> 
		function guarda2(){
		document.getElementById("questionSelected").value=document.getElementById("questions").options[document.getElementById("questions").selectedIndex].value
		}
		</script>';
		
$B->showScript('guarda2()',$script);
echo '</td>';
echo '<td>';
$script='<script type="text/javascript"> 
		function guarda3(){
		document.getElementById("aliasselected").value=document.getElementById("alias").options[document.getElementById("alias").selectedIndex].text
		}
		</script>';
		
$C->showScript('guarda3()',$script);

echo '</td>';
echo '<td VALIGN="BOTTOM" >';
$D->show();
echo '</td>';
echo' </tr></table></center>';
   
unset($A);
unset($B);
unset($C);
unset($D);

echo '<link href="cytoscape/style.css" rel="stylesheet" />';
echo ' <meta name="viewport" content="user-scalable=no, initial-scale=10.0, minimum-scale=1.0, maximum-scale=10.0, minimal-ui">';
echo ' <script src="cytoscape/jquery.min.js"></script>';
echo '<script src="cytoscape/cytoscape.min.js"></script>';

 
 if ((isset($questionSelected)) && ((isset($groupSelected))))
   if (( $questionSelected!= 'select') && ( $groupSelected!= 'select'))
    if (( $questionSelected!= get_string('select','msociograma')) && ( $groupSelected!= get_string('select','msociograma'))){
        require('cytoscape/code.php');
    }

echo' <div   border-style: solid; id="cy"></div> ';

//Increases frame size
for ($x=1;$x<50;$x++)
    echo '<br>';
 
function letrerojpg($id,$texto){

  if ($texto!= get_string('selectstudent','msociograma')){  
		$img=imagecreatetruecolor(100, 30);  
		$text_color=imagecolorallocate($img, 255, 255, 255);
		imagestring($img, 5, 5, 5,  utf8_decode($texto), $text_color);
		ob_start();
		imagejpeg($img);
		printf('<img id="'.$id.'" src="data:image/png;base64,%s" draggable="true" ondragstart="drag(event)" width="100" height="30"/>', base64_encode(ob_get_clean()));
		imagedestroy($img);
    }
  }

function zona($id, $texto){
    echo '<div class ="mover" style="border: 2px solid" id="'.$id.'" draggable="true" ondragstart="drag(event)" width="90" height="90"><center>'.$texto.' </center></div>';
}


function tablaimagen($numcols, $numrows, $imagen,$border){

	$cel=1;
	if ($border){
		echo '<style>';
		echo 'td.tabla1 {border-style: dashed;}';
                echo 'div.mover { height: 98px; width: 98px; display: flex; justify-content: center; align-items: center;}';
		echo '</style>';
	
		echo '<table border=1>';
	}else{
		echo '<table border=0>';
	}
	   for ($x=1;$x<=10;$x++){
		   echo '<tr height=100>';
		   for ($y=1;$y<=10;$y++){
				echo '<td class="tabla1" width=100 id="celda'.$cel.'" '
                                        . 'ondrop="drop(event,this.id)" ondragover="allowDrop(event,this.id)" '
                                        . 'onmouseover="colorON(this.id)" onmouseout="colorOFF()"  >';
					if (!isset($imagen[$cel]['name'])){
						echo  '';
                    } else if ($imagen[$cel]['name']!= get_string('selectstudent','msociograma'))
						zona($imagen[$cel]['id_stu'],$imagen[$cel]['name']);
                    else
                        echo  '';
				echo '</td>';
			  $cel++;	
		   }	   
		   echo '</tr>';
		 
	   }
	 echo '</table>'; 
 }
  
function colocaBDAzar($group,$questionSelected, $answerSelected){
	global $DB, $COURSE;
	$rand =array();
	$rand = range(1, 10*10); //create an array with numbers unsorted
	shuffle($rand); 
	 
	$course = $COURSE->id;
	$idActivityModule = $_GET['id'];

	$sql = "SELECT * FROM {msociograma_sheet} WHERE course= '$course' AND activity = '$idActivityModule' AND groupclass = '$group' order by student"; // moodle 3.0
		 $cont=0;
		if ($data = $DB->get_records_sql($sql, array('%'))){				
			 foreach($data as $registro){
				  $cont++;
				  $record = array();
				  $record['id_stu']=$registro->id;  
				  $record['cel']= $rand[$cont];
				  $record['question'] =str_replace('question','',$questionSelected); //only get de question name
				  $record['answer'] =$answerSelected; 
				  $record['course']=$course ;
				  $record['activity']=$idActivityModule;
				  $record['group_class']=$group;
				  

				  $resultado = $DB->insert_record('msociograma_diagram', $record);
				   
				 }   
		 }
 
 } 

 function colocaArray($group,$questionSelected,$answerSelected){ 
	 global $DB, $COURSE;
	 
	 $course = $COURSE->id;
	 $idActivityModule = $_GET['id'];
	 
	 $numberQuestionSelected = str_replace('question','',$questionSelected);
		
	$sql="SELECT * FROM {msociograma_diagram} WHERE course= '$course' 
			AND activity = '$idActivityModule' 
			AND group_class ='$group' 
			AND answer ='$answerSelected' 
			AND question='$numberQuestionSelected'
			ORDER BY cel";
	 
		$students = array();
		$cont=0;
		if ($data = $DB->get_records_sql($sql, array('%'))){				
			 foreach($data as $registro){
			 $cont++;
				$idStudent = $registro->id_stu;
				$student = idStudent2Name($course,$idActivityModule, $group, $idStudent);
				$cel = $registro->cel;
				$students [$cel]['id_stu']= $idStudent;
				$students [$cel]['name']= $student;
			}
		}
		return $students;
}


function colocaAzarCytoscan($group,$questionSelected){ 
	 global $DB, $COURSE;
	 $randx =array();
	 $randy =array();
	 $randx = range(-25, 25); //create an array with numbers unsorted
	 $randy = range(-22, 22); //create an array with numbers unsorted

	shuffle($randx); 
	shuffle($randy);
	 $grid=60;
	 
	 
	 $celda =array();
	 $celda = range(0, 100);
	 shuffle($celda); 
	 
	$gridx=80;
	$gridy=50;

	$col=12;
	$fil=10;
	 $course = $COURSE->id;
	 $idActivityModule = $_GET['id'];

	 
	 $sql = "SELECT * FROM {msociograma_sheet} WHERE course= '$course' AND activity = '$idActivityModule' AND groupclass = '$group' order by student"; // moodle 3.0
		 $cont=0;
		if ($data = $DB->get_records_sql($sql, array('%'))){				
			 foreach($data as $registro){
				
				  $record = array();
				  $record['id_stu']=$registro->id;  
				  $record['posx']= ($celda[$cont]%$col)*$gridx;
				  $record['posy']= round(($celda[$cont]/$col),0, PHP_ROUND_HALF_UP)*$gridy;
				  $record['question'] =str_replace('question','',$questionSelected); //s�lo coge el n�mnero de la cuesti�n
				  $record['course']=$course ;
				  $record['activity']=$idActivityModule;
				  $record['group_class']=$group;
				  $resultado = $DB->insert_record('msociograma_diagram', $record);
				  $cont++;
			}   
		 }
	 
 } 

  