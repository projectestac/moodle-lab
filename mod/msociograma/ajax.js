// JavaScript Document
 
// Function to collect the data of PHP according to the browser, is always used.
function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
 
	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		xmlhttp = false;
	}
}
 
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	  xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
 
 
//Function to collect the data of the form and to send them by post  
function enviarDatos(combo){

course  =document.getElementById("course").value;
id  = document.getElementById("id").value;
id_stu  = document.getElementById("id_stu").value;
grup  = document.getElementById("grup").value;
question  = combo;
stu_ans  = document.getElementById(combo).value;

codigo="course="+course+"&id="+id+"&id_stu="+id_stu+"&grup="+grup+"&question="+question+"&answer"+question+"="+stu_ans

//innstance to ajax object
  ajax=objetoAjax();
 
//POST metode
  ajax.open("POST", "saveCombo.php",true);
//when XMLHttpRequest object changes the state, the function restart
  ajax.onreadystatechange=function() {
	 //the responseText function got all data from server
  	if (ajax.readyState==4) {
  			//show result in this layer
		divResultado.innerHTML = ajax.responseText
  		
	}
 }
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
//send the values to registro.php for insert the data
	ajax.send(codigo)
	showIcon(combo)
	desaparece(combo) // javascript de gridSheet
	pass()
	
	eliminaItems(combo, stu_ans)
}
 
//Function to clear fields
function LimpiarCampos(){
  document.nuevo_empleado.nombre.value="";
  document.nuevo_empleado.apellido.value="";
  document.nuevo_empleado.web.value="";
  document.nuevo_empleado.nombre.focus();
}

//function to open a new html window, bsic for chars.js print
function imprimir(contenedor){
	var aleat = Math.floor(Math.random() * (100000 - 10000)) + 10000;
	var strWindowFeatures = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";
	ventana = window.open('',aleat,strWindowFeatures);
	var html = document.getElementById(contenedor).innerHTML;
	ventana.document.write("<center><table width=50% border=0><tr><td><div id='super'>"+html+"</div></td></tr></table>");
}


