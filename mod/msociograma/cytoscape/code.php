<?php




$sql = "SELECT * FROM {msociograma_sheet} WHERE course= '$course' AND activity = '$idActivityModule' AND groupclass = '$groupSelected' order by student"; // moodle 3.0 ALERTA $group	
     $cont=0;
	if ($data = $DB->get_records_sql($sql, array('%'))){	
	  $alumnos = array();
		 foreach($data as $registro){
			  $alumnos[$cont]['id']=$registro->id;  
			  $alumnos[$cont]['course']=$registro->course;
			  $alumnos[$cont]['activity']=$registro->activity;
			  $alumnos[$cont]['groupclass']=$registro->groupclass;
			  $alumnos[$cont]['student']=$registro->student; 
                          $alumnos[$cont]['alias']=$registro->alias;
                           
			   $cont++;
		}
	}	
	

$preg=str_replace("question","",$questionSelected);
$num =(($preg-1)*3)+1;
	$subsql = '(question='.$num.') OR (question='.($num+1).') OR (question='.($num+2).')';

$sql="SELECT a.id,a.id_stu, s.student, a.stu_ans FROM {$CFG->prefix}msociograma_answers a join {$CFG->prefix}msociograma_sheet s
WHERE a.stu_ans=s.id AND a.id in 
(SELECT max(id) FROM {$CFG->prefix}msociograma_answers a 
WHERE ($subsql)  
and course = '$course' AND activity = '$idActivityModule' AND grup= '$groupSelected' GROUP BY id_stu,question ORDER BY id)";


     $cont=0;
	if ($data = $DB->get_records_sql($sql, array('%'))){	
	  $respuestas = array();
		 foreach($data as $registro){
			  $respuestas[$cont]['id_stu']=$registro->id_stu;  
			  $respuestas[$cont]['stu_ans']=$registro->stu_ans;
		      $cont++;
		}
	}	
	
	

   
$html= "<script>
  
function alias(valor){
var aliasJS=valor;  
//document.write(variable+valor);
//alert(valor);

}  


//***************************************************

function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
 
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
}
 
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	  xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
                function enviarDatosCytoscape(id_stu, posx,posy){//nuevo para cytoscape
                    
                alert(id_stu+'-'+posx+'-#'+posy);
                }
		
		function enviarDatosCytoscape2(id_stu, posx,posy){//nuevo para cytoscape
                    
		
		question  = document.getElementById('questions').value;
                course  =document.getElementById('course').value;
		activity  =document.getElementById('activity').value;
		group_class  = document.getElementById('group_class').value;
		
		

		codigo='course='+course+'&activity='+activity+'&id_stu='+id_stu+'&grup='+group_class+'&question='+question+'&posx='+posx+'&posy='+posy;

		  //instanciamos el objetoAjax
		  ajax=objetoAjax();
		 
		  //uso del medotod POST
		  //archivo que realizará la operacion
		  //registro.php
		  ajax.open('POST', 'saveNodox.php',true);
		 
		  //cuando el objeto XMLHttpRequest cambia de estado, la función se inicia
		  ajax.onreadystatechange=function() {
			  //la función responseText tiene todos los datos pedidos al servidor
			if (ajax.readyState==4) {
				//mostrar resultados en esta capa
				divResultado.innerHTML = ajax.responseText
				//llamar a funcion para limpiar los inputs
				//LimpiarCampos();
			}
		 }
			ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			//enviando los valores a registro.php para que inserte los datos
			ajax.send(codigo)
			//alert(codigo) 

			
		}


//***************************************************
$(function(){ // on dom ready

$('#cy').cytoscape({
	  wheelSensitivity: 0.2,
  layout: {
    name: 'cose',
    padding: 10,";
$existsRecordset=true;

    $html=$html.dataposition($preg,$groupSelected);
    

 $html=$html."   
        
        directed: true,
	padding: 10,
  },
  
  style: cytoscape.stylesheet()
    .selector('node')
      .css({
        'shape': 'data(faveShape)',
        'width': 'mapData(weight, 40, 80, 40, 100)',
        'content': 'data(name)',
        'text-valign': 'center',
        'text-outline-width': 2,
        'text-outline-color': 'data(faveColor)',
        'background-color': 'data(faveColor)',
        'color': '#fff'
      })
    .selector(':selected')
      .css({
        'border-width': 3,
        'border-color': '#333'
      })
    .selector('edge')
      .css({
        'curve-style': 'bezier',
        'opacity': 1, //0.666,
        'width': 'mapData(strength, 70, 100, 2, 6)',
        'target-arrow-shape': 'triangle',
        'source-arrow-shape': 'circle',
        'line-color': 'data(faveColor)',
        'source-arrow-color': 'green',
        'target-arrow-color': 'red'
      })
    .selector('edge.questionable')
      .css({
        'line-style': 'dotted',
        'target-arrow-shape': 'diamond'
      })
    .selector('.faded')
      .css({
        'opacity': 0.25,
        'text-opacity': 0
      }),
  
  elements: {
    nodes: [";
	
	
    $cont=0;

	  foreach($alumnos as $alumno){
      
              
              if ($_POST['aliasselected']==get_string('names','msociograma')){  
                 $nombre = $alumnos[$cont]['student'];
              
              }else{
                 $nombre = $alumnos[$cont]['alias'];
                 
              }
              
	    $id = $alumnos[$cont]['id'];
		 $html=$html. "{ data: { id: '$id', name: '$nombre', weight: 65, faveColor: 'green', faveShape: 'octagon' } },";
		$cont++;
	  }
	  
	 $html=$html. "],
    edges: [";
	
	$cont=0;
	if(isset($respuestas))
	  foreach($respuestas as $resp){
	    $id_stu = $respuestas[$cont]['id_stu'];
	    $stu_ans = $respuestas[$cont]['stu_ans'];
		 $html=$html. "{ data: { source: '$id_stu', target: '$stu_ans', faveColor: '#6FB1FC', strength: 80 } },";
		$cont++;
	  }
	
	
	
   $html=$html. " ]
  },
  
  ready: function(){
    window.cy = this;
    
    // giddy up
  }
 
});

cy.on  ('mouseup', 'node', function(e){
  var node = e.cyTarget; 


 // document.getElementById('posx').value=Math.round(node.position('x'));
  // document.getElementById('posy').value=Math.round(node.position('y'));
   // document.getElementById('nodox').value=node.id();
    var posx=Math.round(node.position('x'));
   var posy=Math.round(node.position('y'));
   var id_stu=node.id();
   document.getElementById('posx').value=posx;
  document.getElementById('posy').value=posy;
   document.getElementById('nodox').value=id_stu;

//alert(id_stu+'-'+posx+'-'+posy);

    enviarDatosCytoscape2(id_stu, posx,posy);
 
//kk();
});

}); // on dom ready


</script>";
 
 
 echo $html; 

 
 function dataPosition($questionSelected,$groupSelected){
     
     global $DB, $COURSE;
 
 
 $course = $COURSE->id;
 $idActivityModule = $_GET['id'];
 $group = $groupSelected;
 $numberQuestionSelected = $questionSelected;
	
$sql="SELECT * FROM {msociograma_diagram} WHERE course= '$course' 
		AND activity = '$idActivityModule' 
		AND group_class ='$group' 
		AND question='$numberQuestionSelected'";
 
 
        $html ="name: 'preset',
                 positions: {";
                
	if ($data = $DB->get_records_sql($sql, array('%'))){				
		 foreach($data as $registro){
                     
                 $id_stu = $registro->id_stu;
                 $posx = $registro->posx;
                 $posy = $registro->posy; 
                  
                $html=$html." '$id_stu': {x: $posx, y:$posy},";
           
       
 		}
                
                $html=$html."},";
	}
	return $html;
     
     
     
 }
 
?>