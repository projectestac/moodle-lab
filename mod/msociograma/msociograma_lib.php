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

//This file corresponds to the msociograma_lib.php.
//It contains specific msociograma functions

defined('MOODLE_INTERNAL') || die();


//this function draw a tag cloud with the answers selected in the array $arr
function cloudTags ($curs, $act,$grup, $arr,$coef,$title,$colorbar)
{
?> 

<STYLE>
H1.SaltoDePagina
{
  PAGE-BREAK-AFTER: always
}
</STYLE>

<?php
global $CFG, $USER,$COURSE;

$currentgroup = get_current_group($COURSE->id);

//what answers we need avaluate
$questions =$arr ;
$subsql='';
foreach($questions as $x){
	$num =(($x-1)*3)+1;
	$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
}
$subsql = $subsql.'(1=2)';

//query for diagram. 
$sql='SELECT  b.username,b.lastname,a.* 
	FROM (SELECT stu_ans, count(*) 
		FROM (SELECT * 
			FROM (SELECT  id,id_stu,question,stu_ans 
				FROM '.$CFG->prefix.'msociograma_answers m 
					WHERE ('.$subsql.	
						') AND m.grup='.$grup.' AND m.course='.$curs.' AND m.activity='.$act.' ORDER BY id desc)t GROUP BY id_stu, question) x 
							GROUP BY stu_ans)a RIGHT JOIN '.$CFG->prefix.'user b 
								ON a.stu_ans=b.id LEFT JOIN '.$CFG->prefix.'groups_members g 
									ON b.id=g.userid 
										WHERE b.username<>"guest" AND g.groupid='.$currentgroup.'  ORDER BY b.lastname, firstname;';



//get the records
$log = $DB->get_records_sql($sql, array('%'));
echo '<CENTER><P><FONT SIZE="5">'.$title.'</FONT></P></CENTER>';
echo '  <center>';
echo'	<TABLE WIDTH=50% BORDER=1 CELLPADDING=4 CELLSPACING=3>';
echo'						<COL WIDTH=359>';
echo'						<TR>';
echo'							<TD WIDTH=359 HEIGHT=100 VALIGN=TOP>';
echo'								<P ALIGN=CENTER>';

foreach($log as $entrada_log){
	$paraula='count(*)';
   
      $size= ($entrada_log->$paraula)*0.75 + 3;
	$pt=$size *2+2;
if (($entrada_log->$paraula)!=0)
  echo'  <FONT COLOR="#000080" FONT SIZE='.$size .' STYLE="font-size: '.$entrada_log->$pt.'pt">'.$entrada_log->username.'&nbsp;</FONT>';

}

echo'	</P>';
echo'							</TD>';
echo'						</TR>';
echo'					</TABLE>';
echo '  </center>';

echo '<H1 class=SaltoDePagina> </H1>';
}

//this function draw a table with the answers selected in the array $arr
function tableCescTrio ($title, $curs, $act,$grup, $arr1,$coef1, $arr2,$coef2, $arr3,$coef3,$escala){

	global $CFG, $USER,$COURSE,$DB;

	$currentgroup = get_current_group($COURSE->id);

	//what answers we need avaluate
	$questions =$arr1 ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	//query for diagram. 
	$sql='SELECT  b.*,a.* 
		FROM (SELECT stu_ans, count(*) 
			FROM (SELECT * 
				FROM (SELECT  id,id_stu,question,stu_ans 
					FROM '.$CFG->prefix.'msociograma_answers m 
						WHERE ('.$subsql.	
							') AND m.grup='.$grup.' AND m.course='.$curs.' AND m.activity='.$act.' ORDER BY id desc)t GROUP BY id_stu, question) x 
								GROUP BY stu_ans)a RIGHT JOIN '.$CFG->prefix.'user b 
									ON a.stu_ans=b.id LEFT JOIN '.$CFG->prefix.'groups_members g 
										ON b.id=g.userid 
											WHERE b.username<>"guest" AND g.groupid='.$currentgroup. ' ORDER BY b.lastname, firstname ;';


	$log = $DB->get_records_sql($sql, array('%'));

	////create one row for each record
	$cont=0;
	foreach($log as $entrada_log){
		
	$arrNom[$cont]=$entrada_log->lastname.', '.$entrada_log->firstname;

		$paraula='count(*)';

		if ($entrada_log->$paraula!=0) 
			$arr1[$cont]=($entrada_log->$paraula)*$coef1;
		else
		  $arr1[$cont]=' ';


	$cont++;
	}


	$questions =$arr2 ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	//query for diagram. 
	$sql='SELECT  b.*,a.* 
		FROM (SELECT stu_ans, count(*) 
			FROM (SELECT * 
				FROM (SELECT  id,id_stu,question,stu_ans 
					FROM '.$CFG->prefix.'msociograma_answers m 
						WHERE ('.$subsql.	
							') AND m.grup='.$grup.' AND m.course='.$curs.' AND m.activity='.$act.' ORDER BY id desc)t GROUP BY id_stu, question) x 
								GROUP BY stu_ans)a RIGHT JOIN '.$CFG->prefix.'user b 
									ON a.stu_ans=b.id LEFT JOIN '.$CFG->prefix.'groups_members g 
										ON b.id=g.userid 
											WHERE b.username<>"guest" AND g.groupid='.$currentgroup. ' ORDER BY b.lastname, firstname ;';

	$log = $DB->get_records_sql($sql, array('%'));


	////create one row for each record
	$cont=0;
	foreach($log as $entrada_log){
		
		$paraula='count(*)';

		if ($entrada_log->$paraula!=0) 
			$arr2[$cont]=($entrada_log->$paraula)*$coef2;
		else
		  $arr2[$cont]=' ';

	$cont++;
	}

	$questions =$arr3 ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	//query for diagram. 
	$sql='SELECT  b.*,a.* 
		FROM (SELECT stu_ans, count(*) 
			FROM (SELECT * 
				FROM (SELECT  id,id_stu,question,stu_ans 
					FROM '.$CFG->prefix.'msociograma_answers m 
						WHERE ('.$subsql.	
							') AND m.grup='.$grup.' AND m.course='.$curs.' AND m.activity='.$act.' ORDER BY id desc)t GROUP BY id_stu, question) x 
								GROUP BY stu_ans)a RIGHT JOIN '.$CFG->prefix.'user b 
									ON a.stu_ans=b.id LEFT JOIN '.$CFG->prefix.'groups_members g 
										ON b.id=g.userid 
											WHERE b.username<>"guest" AND g.groupid='.$currentgroup. ' ORDER BY b.lastname, firstname ;';


	$log = $DB->get_records_sql($sql, array('%'));

	////create one row for each record
	$cont=0;
	foreach($log as $entrada_log){

		$paraula='count(*)';

		if ($entrada_log->$paraula!=0) 
			$arr3[$cont]=($entrada_log->$paraula)*$coef3;
		else
			$arr3[$cont]=' ';

		$cont++;
	}

	echo '<CENTER><P><FONT SIZE="5">'.$title.'</FONT></P></CENTER>';
	createTableHtml (array (get_string('student','msociograma'),get_string('graphic','msociograma')), 
				 array(100,300), 
				 $arrNom, 
				 $arr1,
				 $arr2,
					 $arr3,
				 $escala );

}



//this function draw the line and he arrow between two blocs
function linea($obj,$cella1, $cella2,$ampl, $color)
{
  if ($cella2 !=""){ 
    $x1=($cella1%10)-1;
    $y1=floor($cella1/10);
    if ($x1<0) {
      $x1=9;
      $y1=$y1-1;
    }
    $x2=($cella2%10)-1;
    $y2=floor($cella2/10);
    if ($x2<0) {
      $x2=9;
      $y2=$y2-1;
    }
    $Bx=$x1*$ampl+$ampl/2;
    $By=$y1*$ampl+$ampl/2;
    $Ax=$x2*$ampl+$ampl/2;
    $Ay=$y2*$ampl+$ampl/2;
    $Px=($Ax+$Bx)/2 ;
    $Py=($Ay+$By)/2;
    $Wx=($Ax+$Px)/2 ;
    $Wy=($Ay+$Py)/2;

    $hipo=sqrt(($Ax-$Bx)*($Ax-$Bx)+($Ay-$By)*($Ay-$By));
  
    $l=$hipo/10;

    $h=($l/2);


  if ($cella1 != $cella2)
  {
    $Vx=((($h-2)*$Ax+($h+2)*$Px)/(2*$h))+(($Py-$Ay)/($l));
    $Vy=((($h-2)*$Ay+($h+2)*$Py)/(2*$h))+(($Ax-$Px)/($l));
    $Ux=((($h-2)*$Ax+($h+2)*$Px)/(2*$h))-(($Py-$Ay)/($l));
    $Uy=((($h-2)*$Ay+($h+2)*$Py)/(2*$h))-(($Ax-$Px)/($l));

	imageline($obj,$Bx,$By, $Ax,$Ay,$color);

	$esquinas=array($Wx,$Wy,$Vx,$Vy,$Vx,$Vy,$Ux,$Uy,$Wx);
	imagefilledpolygon ($obj, $esquinas, 4, $color);
 } else { //if student selects himself
	$radio=40;	
	$fletxaAmpl=10;
	$fletxaAlt=10;
	$PosRelativaX= 30;
	$PosRelativaY= 20;	
	$offsetFletxa=4;
	imagearc ($obj,$Ax-$PosRelativaX,$Ay-$PosRelativaY, $radio, $radio, 0, 360, $color);
	$esquinas=array($Ax-$PosRelativaX-$offsetFletxa,$Ay-2*$PosRelativaY,
	$Ax-$PosRelativaX-$offsetFletxa+$fletxaAmpl,$Ay-2*$PosRelativaY-($fletxaAlt/2),
	$Ax-$PosRelativaX-$offsetFletxa+$fletxaAmpl,$Ay-2*$PosRelativaY-($fletxaAlt/2),
	$Ax-$PosRelativaX-$offsetFletxa+$fletxaAmpl,$Ay-2*$PosRelativaY+($fletxaAlt/2),
	$Ax-$PosRelativaX-$offsetFletxa,$Ay-2*$PosRelativaY);
	imagefilledpolygon ($obj, $esquinas, 4, $color);

  }
 }
}

//this function draw the student's name as an image
function colocaNom($obj,$nombre,$cella,$ampl,$color1,$color2, $color3){

	$x=($cella%10)-1;
	$y=floor($cella/10);
	if ($x<0){
		$x=9;
		$y=$y-1;
	}
	$longitud=strlen($nombre)*10+5;
	imagefilledrectangle ($obj, $x*$ampl, $y*$ampl+14, $x*$ampl+$longitud, $y*$ampl+35, $color1);
	imagerectangle ($obj, $x*$ampl, $y*$ampl+14, $x*$ampl+$longitud, $y*$ampl+35, $color3);
	imagestring($obj, 900, $x*$ampl+5, $y*$ampl+15, $nombre, $color2);
}


function  createTableHtml ($arrTitle, $arrColWidth, $arrNom, $arr1, $arr2, $arr3, $escala){

	echo '<CENTER>';
	echo '<TABLE WIDTH=700 BORDER=1 CELLPADDING=2 style="border: thin solid black" >';
	echo '  <COL WIDTH=300>';
	echo '  <COL WIDTH=400>';
	echo '  <TR  ALIGN=LEFT >';	
	echo '    <TD bgcolor="#C0C0C0" style="border: thin solid black" >';
	echo 		$arrTitle[0];
	echo '    </TD>';
	echo '    <TD bgcolor="#C0C0C0" style="border: thin solid black" >';
	echo 		$arrTitle[1];
	echo '    </TD>';
	echo '  </TR>';

	$cont=0;
	foreach($arr1 as $x){
		echo '  <TR  ALIGN=LEFT >';	
		echo '    <TD style="border: thin solid black" >';
		echo 		$arrNom[$cont];
		echo '    </TD>';
		echo '    <TD style="border: thin solid black" >';

		$min=1;

		if($arr1[$cont]!=' ')
		  $valor1=$arr1[$cont]*$escala;
		else
		  $valor1=$min;

		if($arr2[$cont]!=' ')
		  $valor2=$arr2[$cont]*$escala;
		else
		  $valor2=$min;

		if($arr3[$cont]!=' ')
		  $valor3=$arr3[$cont]*$escala;
		else
		  $valor3=$min;

		echo '<p style="line-height: 55%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/redbar.gif" width="'.$valor1.'" height="4" >&nbsp;'.$arr1[$cont].'</font></p>';
		echo '<p style="line-height: 55%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/bluebar.gif" width="'.$valor2.'" height="4" >&nbsp;'.$arr2[$cont].'</font></p>';
		echo '<p style="line-height: 55%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/greenbar.gif" width="'.$valor3.'" height="4" >&nbsp;'.$arr3[$cont].'</font></p>';
		echo '    </TD>';
		echo '  </TR>';

		$cont++;
	}

	echo '</TABLE>';
	echo '</CENTER>';

}

//this function draw a table with the answers selected in the array $arr
function tableCesc ($curs, $act,$grup, $arr,$coef,$title,$colorbar){
?> 

<STYLE>
H1.SaltoDePagina
{
  PAGE-BREAK-AFTER: always
}
</STYLE>

<?php



global $CFG, $USER,$COURSE, $DB;

$escala=20;
$currentgroup = get_current_group($COURSE->id);

$questions =$arr ;
$subsql='';
foreach($questions as $x){
	$num =(($x-1)*3)+1;
	$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
}
$subsql = $subsql.'(1=2)';// close the subquery with a false operation

//query for diagram. 
$sql='SELECT  b.*,a.* 
	FROM (SELECT stu_ans, count(*) 
		FROM (SELECT * 
			FROM (SELECT  id,id_stu,question,stu_ans 
				FROM '.$CFG->prefix.'msociograma_answers m 
					WHERE ('.$subsql.	
						') AND m.grup='.$grup.' AND m.course='.$curs.' AND m.activity='.$act.' ORDER BY id desc)t GROUP BY id_stu, question) x 
							GROUP BY stu_ans)a RIGHT JOIN '.$CFG->prefix.'user b 
								ON a.stu_ans=b.id LEFT JOIN '.$CFG->prefix.'groups_members g 
									ON b.id=g.userid 
										WHERE b.username<>"guest" AND g.groupid='.$currentgroup. ' ORDER BY b.lastname, firstname ;';



//get the records
$log = $DB->get_records_sql($sql, array('%'));

echo '<p align="center"><font size="5">'.($title).'</font></p>';
echo '<div align="center">';
echo '  <center>';
echo '<TABLE WIDTH=720 height="5"  BORDER=0 CELLPADDING=2>';

echo '    <tr ALIGN=LEFT >';
echo '      <td width="270" bgcolor="#C0C0C0" style="border: thin solid black" >'.get_string('student','msociograma').'</td>';
echo '      <td width="52" bgcolor="#C0C0C0" style="border: thin solid black" >'.get_string('quotes','msociograma').'</td>';
echo '      <td width="394" bgcolor="#C0C0C0" style="border: thin solid black" >'.get_string('graphic','msociograma').'</td>';
echo '    </tr>';

//create one row for each record
foreach($log as $entrada_log){
	echo '    <tr ALIGN=LEFT > ';
	echo '      <td  width="270" style="border: thin solid black">'.$entrada_log->lastname.', '.$entrada_log->firstname.'</td>';
	echo '      <td width="52" style="border: thin solid black">';
	$paraula='count(*)';
      if ($entrada_log->$paraula!=0)
	  echo '        '.$entrada_log->$paraula.'</td>';
	else
        echo '        '."0".'</td>';

	echo '      <td  width="394" style="border: thin solid black">';
	$valor=($entrada_log->$paraula)*$coef;
	$widthBar=$valor*$escala;

	echo '        <p style="line-height: 100%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/'.$colorbar.'.gif" width="'.$widthBar.'" height="4" >&nbsp;'.$valor.'</font></p>';
	echo '    </tr>';
}
echo '  </table>';
echo '  </center>';
echo '</div>';
echo'<br>';
echo '<H1 class=SaltoDePagina> </H1>';

}

function llegenda($item1, $item2, $item3 )
{
?> 

<STYLE>
H1.SaltoDePagina
{
  PAGE-BREAK-AFTER: always
}
</STYLE>

<?php

$html = '';
$html = $html. '<CENTER>';
$html = $html. '<TABLE WIDTH=396 BORDER=0 CELLPADDING=5 >';
$html = $html. '	<TR VALIGN=TOP>';
$html = $html. '       	<TD WIDTH=38>';
$html = $html. '			<P ALIGN=RIGHT><IMG SRC="./images/redbar.gif" NAME="redbar" ALIGN=RIGHT WIDTH=11 HEIGHT=10 BORDER=0><BR CLEAR=RIGHT><BR></P>';
$html = $html. '		</TD>';
$html = $html. '		<TD WIDTH=44>';
$html = $html. '			<P ALIGN=LEFT>'.$item1.'</P>';
$html = $html. '		</TD>';
$html = $html. '		<TD WIDTH=38>';
$html = $html. '			<P ALIGN=RIGHT><IMG SRC="./images/bluebar.gif" NAME="bluebar" ALIGN=RIGHT WIDTH=11 HEIGHT=10 BORDER=0><BR CLEAR=RIGHT><BR></P>';
$html = $html. '		</TD>';
$html = $html. '		<TD WIDTH=67>';
$html = $html. '			<P ALIGN=LEFT>'.$item2.'</P>';
$html = $html. '		</TD>';
$html = $html. '		<TD WIDTH=28>';
$html = $html. '			<P ALIGN=RIGHT><IMG SRC="./images/greenbar.gif" NAME="greenbar" ALIGN=RIGHT WIDTH=11 HEIGHT=10 BORDER=0><BR CLEAR=RIGHT><BR></P>';
$html = $html. '		</TD>';
$html = $html. '		<TD WIDTH=110>';
$html = $html. '			<P ALIGN=LEFT>'.$item3.'</P>';
$html = $html. '		</TD>';
$html = $html. '	</TR>';
$html = $html. '  </TABLE>';
$html = $html. '</CENTER>';

$html = $html. '<H1 class=SaltoDePagina> </H1>';
$html = $html.'<br>';

return $html;
}


//function to get the array with all of class students
function listaclase ($course,$activity,$group){
  global $CFG, $USER,$COURSE,$DB;  
  $sql="SELECT id, student FROM {$CFG->prefix}msociograma_sheet
    WHERE course = '$course' AND activity = '$activity' AND groupclass= '$group' 
   ORDER BY student"; 

  $log = $DB->get_records_sql($sql, array('%'));
  
            $i=0;
            $temp = array();
            foreach($log as $registro){
              $temp[$i]['idAlum']=$registro->id;
              $temp[$i]['nomAlum']=$registro->student;  
              $i++;
            }
           return $temp;
}

//triple table for data

function tableCescTrioSheet ($title, $curs, $act,$grup, $arr1,$coef1, $arr2,$coef2, $arr3,$coef3,$escala)
{
global $CFG, $USER,$COURSE,$DB;

//get the class list

$arrNom = listaclase ($curs,$act,$grup);

$questions =$arr1 ;
$subsql='';
foreach($questions as $x){
	$num =(($x-1)*3)+1;
	$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
}
$subsql = $subsql.'(1=2)';// close the subquery with a false operation

$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
WHERE a.stu_ans=s.id AND a.id in 
(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
WHERE ($subsql) 
and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
GROUP BY stu_ans order by stu_ans";

$log = $DB->get_records_sql($sql, array('%'));

            //sql to array
            $i=0;
            $contador='count(*)';
            $temp = array();
            foreach($log as $registro){
                $id_student = $registro->stu_ans;
              $temp[$id_student]['nomAlum']=$registro->student;  
              $temp[$id_student]['contador']=$registro->$contador*$coef1;
        
              $i++;
            }
           $arr1=$temp;
           
          

$questions =$arr2 ;
$subsql='';
foreach($questions as $x){
	$num =(($x-1)*3)+1;
	$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
}
$subsql = $subsql.'(1=2)';// close the subquery with a false operation

$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
WHERE a.stu_ans=s.id AND a.id in 
(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
WHERE ($subsql) 
and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
GROUP BY stu_ans order by stu_ans";

$log = $DB->get_records_sql($sql, array('%'));
                
              //sql to array
            $i=0;
            $contador='count(*)';
            $temp = array();
            foreach($log as $registro){
               $id_student = $registro->stu_ans;
              $temp[$id_student]['nomAlum']=$registro->student;  
              $temp[$id_student]['contador']=$registro->$contador*$coef2; 
  
              $i++;
            }
           $arr2=$temp;
           
$questions =$arr3 ;
$subsql='';
foreach($questions as $x){
	$num =(($x-1)*3)+1;
	$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
}
$subsql = $subsql.'(1=2)';// close the subquery with a false operation

$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
WHERE a.stu_ans=s.id AND a.id in 
(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
WHERE ($subsql) 
and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
GROUP BY stu_ans order by stu_ans";

$log = $DB->get_records_sql($sql, array('%'));

              //sql to array
            $i=0;
            $contador='count(*)';
            $temp = array();
            foreach($log as $registro){
              $id_student = $registro->stu_ans;
              $temp[$id_student]['nomAlum']=$registro->student;  
              $temp[$id_student]['contador']=$registro->$contador*$coef3;

              $i++;
            }
           $arr3=$temp;
$html='';
$html = $html. '<CENTER><P><FONT SIZE="5">'.$title.'</FONT></P></CENTER>';
$html = $html. createTableHtmlSheet (array (get_string('student','msociograma'),get_string('graphic','msociograma')), 
		     array(100,300), 
		     $arrNom, 
		     $arr1,
		     $arr2,
                     $arr3,
		     $escala );

return $html;

} //tableCescTrioSheet

function  createTableHtmlSheet ($arrTitle, $arrColWidth, $arrNom, $arr1, $arr2, $arr3, $escala){

	$html='';
	$html = $html. '<CENTER>';
	$html = $html. '<TABLE WIDTH=700 BORDER=1 CELLPADDING=2 style="border: thin solid black" >';
	$html = $html. '  <COL WIDTH=300>';
	$html = $html. '  <COL WIDTH=400>';
	$html = $html. '  <TR  ALIGN=LEFT >';	
	$html = $html. '    <TD bgcolor="#C0C0C0" style="border: thin solid black" >';
	$html = $html. 		$arrTitle[0];
	$html = $html. '    </TD>';
	$html = $html. '    <TD bgcolor="#C0C0C0" style="border: thin solid black" >';
	$html = $html. 		$arrTitle[1];
	$html = $html. '    </TD>';

	$html = $html. '  </TR>';


	$cont=0;
	foreach($arrNom as $x){
		$id_alumno=$arrNom[$cont]['idAlum'];   
		$html = $html. '  <TR  ALIGN=LEFT >';	
		$html = $html. '    <TD style="border: thin solid black" >';
		$html = $html. 		$arrNom[$cont]['nomAlum'];
		$html = $html. '    </TD>';
		$html = $html. '    <TD style="border: thin solid black" >';

		$min=1;

		if ((isset($arr1[$id_alumno]['contador'])) and ($arr1[$id_alumno]['contador']!=null)){
		  $value1= $arr1[$id_alumno]['contador']*$escala;
		  $label1= $arr1[$id_alumno]['contador'];
		}else{
		  $value1=$min;
		  $label1 = '';
		}
		if ((isset($arr2[$id_alumno]['contador'])) and ($arr2[$id_alumno]['contador']!=null)){
		  $value2= $arr2[$id_alumno]['contador']*$escala;
		  $label2= $arr2[$id_alumno]['contador'];
		}else{
		  $value2=$min;
		  $label2 = '';
		}
		if ((isset($arr3[$id_alumno]['contador'])) and ($arr3[$id_alumno]['contador']!=null)){
		  $value3= $arr3[$id_alumno]['contador']*$escala;
		  $label3= $arr3[$id_alumno]['contador'];
		}else{
		  $value3=$min;
		  $label3 = '';
		}

		$html = $html. '<p style="line-height: 55%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/redbar.gif"   width="'.$value1.'" height="4" >&nbsp;'.$label1.'</font></p>';
		$html = $html. '<p style="line-height: 55%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/bluebar.gif"  width="'.$value2.'" height="4" >&nbsp;'.$label2.'</font></p>';
		$html = $html. '<p style="line-height: 55%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/greenbar.gif" width="'.$value3.'" height="4" >&nbsp;'.$label3.'</font></p>';

		$html = $html. '    </TD>';

		$html = $html. '  </TR>';

		$cont++;
	}
	$html = $html. '</TABLE>';
	$html = $html. '</CENTER>';
	return $html;
}

function tableCescSheet ($curs, $act,$grup, $arr,$coef,$title,$colorbar){

	global $CFG, $USER,$COURSE, $DB;

	$escala=5;
	$questions =$arr ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	//query for diagram. 
	$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
	WHERE a.stu_ans=s.id AND a.id in 
	(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
	WHERE ($subsql) 
	and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
	GROUP BY stu_ans order by stu_ans";

	$log = $DB->get_records_sql($sql, array('%'));

				//sql to array
				$i=0;
				$contador='count(*)';
				$temp = array();
				foreach($log as $registro){
				  $id_student = $registro->stu_ans;
				  $temp[$id_student]['nomAlum']=$registro->student;  
				  $temp[$id_student]['contador']=$registro->$contador*$coef;

				  $i++;
				}
	   $arrDiagrama = $temp;
	  $arrNom = listaclase ($curs,$act,$grup);         
			   
	$html='';
	$html=$html. '<p align="center"><font size="5">'.($title).'</font></p>';
	$html=$html. '<div align="center">';
	$html=$html. '  <center>';
	$html=$html. '<TABLE WIDTH=720 height="5"  BORDER=0 CELLPADDING=2>';

	$html=$html. '    <tr ALIGN=LEFT >';
	$html=$html. '      <td width="270" bgcolor="#C0C0C0" style="border: thin solid black" >'.get_string('student','msociograma').'</td>';
	$html=$html. '      <td width="52" bgcolor="#C0C0C0" style="border: thin solid black" >'.get_string('quotes','msociograma').'</td>';
	$html=$html. '      <td width="394" bgcolor="#C0C0C0" style="border: thin solid black" >'.get_string('graphic','msociograma').'</td>';
	$html=$html. '    </tr>';

	//create one row for each record
		$cont=0;
		foreach($arrNom as $x){
			$id_alumno=$arrNom[$cont]['idAlum'];
		$html=$html. '    <tr ALIGN=LEFT > ';
		$html=$html. '      <td  width="270" style="border: thin solid black">'.$arrNom[$cont]['nomAlum'].'</td>';
		$html=$html. '      <td width="52" style="border: thin solid black">';
				
			$min=1;

			if ((isset($arrDiagrama[$id_alumno]['contador'])) and ($arrDiagrama[$id_alumno]['contador']!=null)){
			  $widthBar= $arrDiagrama[$id_alumno]['contador']*$escala;//1 es escala
			  $label= $arrDiagrama[$id_alumno]['contador'];
			}else{
			  $widthBar=$min;
			  $label = '';
			}
			  $html=$html. '        '.$label.'</td>';
		
			$html=$html. '      <td  width="394" style="border: thin solid black">';
		$html=$html. '        <p style="line-height: 100%; word-spacing: 0; margin-top: 0; margin-bottom: 0"><font size="1"><img border="0" src="./images/'.$colorbar.'.gif" width="'.$widthBar.'" height="4" >&nbsp;'.$label.'</font></p>';
		$html=$html. '    </tr>';
			$cont++;
		}
	$html=$html. '  </table>';
	$html=$html. '  </center>';
	$html=$html. '</div>';
	$html=$html.'<br>';

	return $html;
}

//draw the chart using chartjs
function tableCescChartjs ($nombre,$curs, $act,$grup, $arr,$coef,$title,$color){
	global $CFG, $USER,$COURSE, $DB;	
	

	//array js de datos
	$questions =$arr ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
	WHERE a.stu_ans=s.id AND a.id in 
	(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
	WHERE ($subsql) 
	and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
	GROUP BY stu_ans order by stu_ans";

	$log = $DB->get_records_sql($sql, array('%'));

				//sql to array
				$i=0;
				$contador='count(*)';
				$temp = array();
				foreach($log as $registro){
				  $id_student = $registro->stu_ans;
				  $temp[$id_student]['nomAlum']=$registro->student;  
				  $temp[$id_student]['contador']=$registro->$contador*$coef;

				  $i++;
				}
	   $arrDiagrama = $temp;
	

		$cont=0;
		$cadenaArrayDatos ="";
		$cadenaArrayAlumnos ="'";
		$arrNom = listaclase ($curs,$act,$grup);      
		foreach($arrNom as $x){
			$id_alumno=$arrNom[$cont]['idAlum'];
		
			if ((isset($arrDiagrama[$id_alumno]['contador'])) and ($arrDiagrama[$id_alumno]['contador']!=null)){
			
			  $dato= $arrDiagrama[$id_alumno]['contador'];
			}else{
			
			  $dato = 0;
			}
			$cadenaArrayDatos =$cadenaArrayDatos.",".$dato;
			$cadenaArrayAlumnos = $cadenaArrayAlumnos. $arrNom[$cont]['nomAlum']."','";
			$cont++;
		}
		$cadenaArrayDatos = substr($cadenaArrayDatos,1);//get values for js array
	
		$cadenaArrayAlumnos = substr($cadenaArrayAlumnos,0,-2); //get names for js array
		
		$ruta = "$CFG->wwwroot/mod/msociograma";
		
	$html="		<scr"."ipt language='JavaScript' type='text/javascript' src='$ruta/Chartjs/dist/Chart.bundle.js'></scr"."ipt>
				<scr"."ipt language='JavaScript' type='text/javascript' src='$ruta/Chartjs/utils.js'></scr"."ipt>
				<style>
				canvas {
					//-moz-user-select: none;
					//-webkit-user-select: none;
					//-ms-user-select: none;
				}
				</style>
			
				<div id='container' >
					<canvas id='id$nombre'></canvas>
				</div>
				
				<scr"."ipt>
				
					var color = Chart.helpers.color;
					var barChartData$nombre = {
						labels: [$cadenaArrayAlumnos],
						datasets: [{
							label: '$title',
							backgroundColor: color(window.chartColors.$color).alpha(1).rgbString(),
							borderColor: window.chartColors.$color,
							borderWidth: 1,
							data: [
								$cadenaArrayDatos
							]
						}]

					};

					
					//function start() {
					//						 agressFis();
					//					}
					//		window.onload = start;
					
					function $nombre() {
						var ctx$nombre = document.getElementById('id$nombre').getContext('2d');
						window.myBar$nombre = new Chart(ctx$nombre, {
							      
							type: 'bar',
							data: barChartData$nombre,
							options: {
							
								responsive: true,
								legend: {
									position: 'top',
								},
								title: {
									display: true,
									text: '$grup'
								},
								scales: {
                    
									yAxes: [{
											display: true,
											ticks: {
												beginAtZero: true,
												steps: 10,
												stepValue: 5,
												
											}
										}],
									xAxes: [{
										ticks: {
										  autoSkip: false
										}
									  }]
								},
							}
						});

					};

					
				</scr"."ipt>
			
			";
	
return $html;
	
}
//draw triple chart using chartjs
function trioTableCescChartjs ($nombre,$curs, $act,$grup, $arr1,$coef1,$title1,$arr2,$coef2,$title2, $arr3,$coef3,$title3){
	global $CFG, $USER,$COURSE, $DB;	
	
	//******************************************first graphic*****************************
	$questions =$arr1 ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
	WHERE a.stu_ans=s.id AND a.id in 
	(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
	WHERE ($subsql) 
	and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
	GROUP BY stu_ans order by stu_ans";

	$log = $DB->get_records_sql($sql, array('%'));

				//sql to array
				$i=0;
				$contador='count(*)';
				$temp = array();
				foreach($log as $registro){
				  $id_student = $registro->stu_ans;
				  $temp[$id_student]['nomAlum']=$registro->student;  
				  $temp[$id_student]['contador']=$registro->$contador*$coef1;

				  $i++;
				}
	   $arrDiagrama1 = $temp;
	
	
		$cont=0;
		$cadenaArrayDatos1 ="";
		$cadenaArrayAlumnos1 ="'";
		$arrNom = listaclase ($curs,$act,$grup);      
		foreach($arrNom as $x){
			$id_alumno=$arrNom[$cont]['idAlum'];
		
			if ((isset($arrDiagrama1[$id_alumno]['contador'])) and ($arrDiagrama1[$id_alumno]['contador']!=null)){
			
			  $dato= $arrDiagrama1[$id_alumno]['contador'];
			}else{
			
			  $dato = 0;
			}
			$cadenaArrayDatos1 =$cadenaArrayDatos1.",".$dato;
			$cadenaArrayAlumnos1 = $cadenaArrayAlumnos1. $arrNom[$cont]['nomAlum']."','";
			$cont++;
		}
		$cadenaArrayDatos1 = substr($cadenaArrayDatos1,1);//get values for js array
		$cadenaArrayAlumnos1 = substr($cadenaArrayAlumnos1,0,-2); //get names for js array
		
	//******************************************second graphic*****************************
	$questions =$arr2 ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
	WHERE a.stu_ans=s.id AND a.id in 
	(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
	WHERE ($subsql) 
	and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
	GROUP BY stu_ans order by stu_ans";

	$log = $DB->get_records_sql($sql, array('%'));

				//sql to array
				$i=0;
				$contador='count(*)';
				$temp = array();
				foreach($log as $registro){
				  $id_student = $registro->stu_ans;
				  $temp[$id_student]['nomAlum']=$registro->student;  
				  $temp[$id_student]['contador']=$registro->$contador*$coef2;

				  $i++;
				}
	   $arrDiagrama2 = $temp;
	

		$cont=0;
		$cadenaArrayDatos2 ="";
		$cadenaArrayAlumnos2 ="'";
		$arrNom = listaclase ($curs,$act,$grup);      
		foreach($arrNom as $x){
			$id_alumno=$arrNom[$cont]['idAlum'];
		
			if ((isset($arrDiagrama2[$id_alumno]['contador'])) and ($arrDiagrama2[$id_alumno]['contador']!=null)){
			
			  $dato= $arrDiagrama2[$id_alumno]['contador'];
			}else{
			
			  $dato = 0;
			}
			$cadenaArrayDatos2 =$cadenaArrayDatos2.",".$dato;
			$cadenaArrayAlumnos2 = $cadenaArrayAlumnos2. $arrNom[$cont]['nomAlum']."','";
			$cont++;
		}
		$cadenaArrayDatos2 = substr($cadenaArrayDatos2,1);//get values for js array
		$cadenaArrayAlumnos2 = substr($cadenaArrayAlumnos2,0,-2); //get names for js array	
		
		//******************************************third graphic*****************************
	$questions =$arr3 ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';// close the subquery with a false operation

	$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
	WHERE a.stu_ans=s.id AND a.id in 
	(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
	WHERE ($subsql) 
	and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
	GROUP BY stu_ans order by stu_ans";

	$log = $DB->get_records_sql($sql, array('%'));

				//sql to array
				$i=0;
				$contador='count(*)';
				$temp = array();
				foreach($log as $registro){
				  $id_student = $registro->stu_ans;
				  $temp[$id_student]['nomAlum']=$registro->student;  
				  $temp[$id_student]['contador']=$registro->$contador*$coef3;

				  $i++;
				}
	   $arrDiagrama3 = $temp;
	

		$cont=0;
		$cadenaArrayDatos3 ="";
		$cadenaArrayAlumnos3 ="'";
		$arrNom = listaclase ($curs,$act,$grup);      
		foreach($arrNom as $x){
			$id_alumno=$arrNom[$cont]['idAlum'];
		
			if ((isset($arrDiagrama3[$id_alumno]['contador'])) and ($arrDiagrama3[$id_alumno]['contador']!=null)){
			
			  $dato= $arrDiagrama3[$id_alumno]['contador'];
			}else{
			
			  $dato = 0;
			}
			$cadenaArrayDatos3 =$cadenaArrayDatos3.",".$dato;
			$cadenaArrayAlumnos3 = $cadenaArrayAlumnos3. $arrNom[$cont]['nomAlum']."','";
			$cont++;
		}
		$cadenaArrayDatos3 = substr($cadenaArrayDatos3,1);//get values for js array
		$cadenaArrayAlumnos3 = substr($cadenaArrayAlumnos3,0,-2); //get names for js array
		
		$ruta = "$CFG->wwwroot/mod/msociograma";
		
		
	$html="		<scr"."ipt language='JavaScript' type='text/javascript' src='$ruta/Chartjs/dist/Chart.bundle.js'></scr"."ipt>
				<scr"."ipt language='JavaScript' type='text/javascript' src='$ruta/Chartjs/utils.js'></scr"."ipt>
				<style>
				canvas {
					//-moz-user-select: none;
					//-webkit-user-select: none;
					//-ms-user-select: none;
				}
				</style>
			
				<div id='container' >
					<canvas id='id$nombre'></canvas>
				</div>
				
				<scr"."ipt>
				
					var color = Chart.helpers.color;
					var barChartData$nombre = {
						labels: [$cadenaArrayAlumnos1],
						datasets: [{
							label: '$title1',
							backgroundColor: color(window.chartColors.red).alpha(1).rgbString(),
							borderColor: window.chartColors.red,
							borderWidth: 1,
							data: [
								$cadenaArrayDatos1
							]
						},{
							label: '$title2',
							backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
							borderColor: window.chartColors.blue,
							borderWidth: 1,
							data: [
								$cadenaArrayDatos2
							]
						},{
							label: '$title3',
							backgroundColor: color(window.chartColors.green).alpha(1).rgbString(),
							borderColor: window.chartColors.green,
							borderWidth: 1,
							data: [
								$cadenaArrayDatos3
							]
						}]

					};

				
					
					function $nombre() {
						var ctx$nombre = document.getElementById('id$nombre').getContext('2d');
						window.myBar$nombre = new Chart(ctx$nombre, {
							      
							type: 'bar',
							data: barChartData$nombre,
							options: {
								responsive: true,
								legend: {
									position: 'top',
								},
								title: {
									display: true,
									text: '$grup'
								},
								scales: {
                    
									yAxes: [{
											display: true,
											ticks: {
												beginAtZero: true,
												steps: 10,
												stepValue: 5,
												
											}
										}],
									xAxes: [{
										ticks: {
										  autoSkip: false
										}
									  }]
								},
							}
						});

					};

					
				</scr"."ipt>
			
			";
	
return $html;
	
}

//this function draw a tag cloud with the answers selected in the array $arr
function cloudTagsSheet ($curs, $act,$grup, $arr,$coef,$title,$colorbar){

	global $DB, $CFG,$COURSE;

	$questions =$arr ;
	$subsql='';
	foreach($questions as $x){
		$num =(($x-1)*3)+1;
		$subsql = $subsql. '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).') OR ';
	}
	$subsql = $subsql.'(1=2)';

	//query for diagram. 
	$sql="SELECT a.stu_ans, s.student, count(*) FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
	WHERE a.stu_ans=s.id AND a.id in 
	(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
	WHERE ($subsql) 
	and course = '$COURSE->id' AND activity = '$act' AND grup= '$grup' GROUP BY id_stu,question ORDER BY id) 
	GROUP BY stu_ans order by stu_ans";


	$log = $DB->get_records_sql($sql, array('%'));
	$html='';
	$html=$html. '<CENTER><P><FONT SIZE="5">'.$title.'</FONT></P></CENTER>';
	$html=$html. '  <center>';
	$html=$html.'	<TABLE WIDTH=80% BORDER=1 CELLPADDING=10 CELLSPACING=10>';
	$html=$html.'						<COL WIDTH=359>';
	$html=$html.'						<TR><td>';
	$html=$html.'					<P ALIGN=CENTER>';

	foreach($log as $entrada_log){
		$paraula='count(*)';
		$size= ($entrada_log->$paraula)*0.75 + 3;
		$pt=$size *2+2;
		if (($entrada_log->$paraula)!=0)
			$html=$html.'  <FONT FACE=arial COLOR="#000080" FONT SIZE='.$size .' STYLE="font-size: '.$pt.'pt">'.$entrada_log->student.'&nbsp;</FONT>';
	}

	$html=$html.'	</P>';
	$html=$html.'							</TD>';
	$html=$html.'						</TR>';
	$html=$html.'					</TABLE>';
	$html=$html. '  </center>';

	return $html;
}

function estiloSaltaPagina(){
    
    return '<style type="text/css">   //estilo para cambiar de página
				 
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
    
}



?>