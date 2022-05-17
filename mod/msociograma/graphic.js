
		//functions for cytoscape management using Ajax

		function allowDrop(ev,celda) {
		   ev.preventDefault();
		 
		}

		function drag(ev) {
			ev.dataTransfer.setData("foto", ev.target.id);
		}

		function drop(ev,celda) {
			ev.preventDefault();
			var data = ev.dataTransfer.getData("foto");
			ev.target.appendChild(document.getElementById(data));
			enviarDatosCeldas(data, celda);
            colorOFF();
		}
		
        function colorON(celda){
            document.getElementById(celda).style.backgroundColor="#E6E6E6" ;//#FAFAFA
        }
               
        function colorOFF(){
            for (x=1;x<=100;x++)
                document.getElementById("celda"+x).style.backgroundColor="white" ;
        }
                
                
		// Function to get PHP data by browser
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
		
        function enviarDatosCytoscape(id_stu, posx,posy){//new for cytoscape  
             alert(id_stu+'-'+posx+'-#'+posy);
        }
		
		function enviarDatosCytoscape2(id_stu, posx,posy){//new for cytoscape

			question  = document.getElementById("questions").value;
			course  =document.getElementById("course").value;
			activity  =document.getElementById("activity").value;
			group_class  = document.getElementById("group_class").value;

			codigo="course="+course+"&activity="+activity+"&id_stu="+id_stu+"&grup="+group_class+"&question="+question+"&posx="+posx+"&posy="+posy;

			//instance to ajax object
			ajax=objetoAjax();
		
			ajax.open("POST", "saveNodox.php",true);
			 
			//when the XMLHttpRequest objet change the state, the function restart
			ajax.onreadystatechange=function() {
				//the responseText function has got every data from server
				if (ajax.readyState==4) {
					//show results in this layer
					divResultado.innerHTML = ajax.responseText
				}
			}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				//send values to registro.php for insert data
				ajax.send(codigo)
		}
                
        function enviarDatosCeldas(id_stu, cel){//old for cells

			celda = cel.replace("celda",""); //erase the word "celda" from id
			question  = document.getElementById("question").value;
			answer  =document.getElementById("answer").value;
			course  =document.getElementById("course").value;
			activity  =document.getElementById("activity").value;
			group_class  = document.getElementById("group_class").value;

			codigo="course="+course+"&activity="+activity+"&id_stu="+id_stu+"&grup="+group_class+"&question="+question+"&answer="+answer+"&cel="+celda;

			//instance to ajax object
			ajax=objetoAjax();

			ajax.open("POST", "saveCelda.php",true);
			 
			//when the XMLHttpRequest objet change the state, the function restart
			ajax.onreadystatechange=function() {
				//the responseText function has got every data from server
				if (ajax.readyState==4) {
					//show results in this layer
					divResultado.innerHTML = ajax.responseText
				}
			}
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			//send values to registro.php for insert data
			ajax.send(codigo)

		}