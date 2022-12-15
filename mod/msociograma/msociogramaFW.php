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

//This file corresponds to the msocigramaFW.php.
//It contains especific FrameWork classes

class boton {
    public $method  ='post';
    public $value  ='';
    public $action  ='#actualURL';  // #actualURL  meet url completely, #actualView  only meet view?id=xxxx
    public $hidden = array();
	public $send =true;
	public $functionScript ='';
 
    public function  __construct($name) {
       $this->name = $name;
    }
    
    public function muestra($value){
     $this->value=$value;   
     echo ' <input type="submit" value="'.$this->value.'">';  
    }
    
     public function show(){
        global $CFG;
		
		$url= $CFG->wwwroot.$_SERVER["REQUEST_URI"];  
        $view= explode('&', $url);
        $vista = $view[0];
        $this->action = str_replace("#actualURL","$url",$this->action);
		
        $this->action = str_replace("#actualView","$vista",$this->action);
		$this->action = str_replace("/moodle/mod","/mod",$this->action); //erase the double moodle/moodle
		
       if ($this->send)
          echo '<form method="'.$this->method.'" enctype="multipart/form-data" action="'.$this->action.'">';
        foreach ($this->hidden as $elemento)  //inputs hidden
          echo  '<INPUT TYPE = HIDDEN ID = "'.$elemento["name"].'" NAME = "'.$elemento["name"].'" VALUE = "'.$elemento["value"].'">';
		  
          echo '<input type="submit" value="'.$this->value.'">';  //submit
		
        if ($this->send)
		  echo '</form>';
    }

      public function ejecutaAccion($post){
         
        if (isset($_POST[$post])){
          return true;
        }else{
          return false; 
        }
    }
    
}
class check {
    public $method  ='post';
    public $action  ='#actualURL';  // #actualURL  meet url completely, #actualView  only meet view?id=xxxx
    public $label  ='';          
    public $name  ='';
    public $checked =false; 

    public $send =false;
    public $hidden = array();
    
    
    public function  __construct($name) {
       $this->name = $name;
       if (isset($_POST[$this->name])){
           if ($_POST[$this->name]=='true')
              $this->checked=true;
           else
             $this->checked=false;  
         }else {
          $this->checked=false;
         }
    }
    public function showScript($function,$script){
        global $CFG;
        echo $this->label.'<br>'; 
        echo '<input type="checkbox" id = "'.$this->name.'" name="'.$this->name.'" onchange = "'.$function.'" >'; //envía datos
        echo $script;
    }

    
}

class combo {

    public $method  ='post';
    public $action  ='#actualURL';  // #actualURL  meet url completely, #actualView  only meet view?id=xxxx
    public $label  ='';          
    public $name  ='';
    public $select =''; 
    public $iniValue =''; 
    public $iniText ='';
    public $send =true;
    public $hidden = array();
    public $datos = array();
   
       
   public function  __construct($name) {
       $this->name = $name;
       if (isset($_POST[$this->name]))
           $this->select = $_POST[$this->name];
     
   }
    
    public function show(){
        global $CFG;
        
        $url= $CFG->wwwroot.$_SERVER["REQUEST_URI"];  
        $view= explode('&', $url);
        $vista = $view[0];
        $this->action = str_replace("#actualURL","$url",$this->action);
		
        $this->action = str_replace("#actualView","$vista",$this->action);
		$this->action = str_replace("/moodle/mod","/mod",$this->action); //erase the double moodle/moodle

        echo '<form method="'.$this->method.'" enctype="multipart/form-data" action="'.$this->action.'">';
        echo $this->label.'<br>'; 
		if ($this->send)
           echo '<select id = "'.$this->name.'" name="'.$this->name.'" onchange = "this.form.submit()" >'; //send data
		else
		    echo '<select id = "'.$this->name.'" name="'.$this->name.'" >'; //sólo muestra
        if (($this->iniValue != '' )&& ($this->iniText != '' ))                             //inicitial data
          echo '<option value="'.$this->iniValue.'">'.$this->iniText.'</option>'; 
        foreach ($this->datos as $elemento)                                                  //bucle of values
          if ($elemento["text"] == $this->select)
            echo '<option value="'.$elemento["value"].'" selected>'.$elemento["text"].'</option>';
          else
            echo '<option value="'.$elemento["value"].'">'.$elemento["text"].'</option>';    
        echo '</select>';
        foreach ($this->hidden as $elemento)                                                 //inputs hidden
          echo  '<INPUT TYPE = HIDDEN NAME = "'.$elemento["name"].'" VALUE = "'.$elemento["value"].'">';
        
        echo '</form>';
    }

	public function showScript($function,$script){
        global $CFG;
        
        echo $this->label.'<br>'; 
	
		echo '<select id = "'.$this->name.'" name="'.$this->name.'" onchange = "'.$function.'" >'; //send data
		
        if (($this->iniValue != '' )&& ($this->iniText != '' ))                             // inicitial data
          echo '<option value="'.$this->iniValue.'">'.$this->iniText.'</option>'; 
        foreach ($this->datos as $elemento)                                                  //bucle of values
          if ($elemento["text"] == $this->select)
            echo '<option value="'.$elemento["value"].'" selected>'.$elemento["text"].'</option>';
          else
            echo '<option value="'.$elemento["value"].'">'.$elemento["text"].'</option>';    
        echo '</select>';
       
	   echo $script;
    }

	
    public function loadDataFromSql($sqlShow, $fieldValue, $fieldText){
        global $DB;
        if (!$data = $DB->get_records_sql($sqlShow, array('%'))){
             //connection error
        }else{
            $i=0;
            $temp = array();
            foreach($data as $registro){
              $temp[$i]['value']=$registro->$fieldValue;
              $temp[$i]['text']=$registro->$fieldText;  
              $i++;
            }
            $this->datos=$temp;
        }
                   
    }
        
	public function loadDataFromSqlLang($sqlShow, $fieldValue, $fieldText){
        global $DB;
        if (!$data = $DB->get_records_sql($sqlShow, array('%'))){
            //connection error
        }else{
            $i=0;
            $temp = array();
            foreach($data as $registro){
              $temp[$i]['value']=$registro->$fieldValue;
              $temp[$i]['text']=get_string($registro->$fieldText,'msociograma');  
              $i++;
            }
            $this->datos=$temp;
        }
                   
    }
		
      public function ejecutaAccion($post){
     
        if (isset($_POST[$post])){
          return true;
        }else{
          return false; 
        }
    }
        
}       
      