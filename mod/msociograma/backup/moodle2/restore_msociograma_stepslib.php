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

//msociograma is based in CESC sociogram, designed by Collell, J. and Escud?, C.
  
//Structure step to restore one msociograma activity


	
class restore_msociograma_activity_structure_step extends restore_activity_structure_step {
	
    /**
     * Defines structure of path elements to be processed during the restore
     *
     * @return array of {@link restore_path_element}
     */
    protected function define_structure() {

        $paths = array();
		$userinfo = $this->get_setting_value('userinfo');
		 
        $paths[] = new restore_path_element('msociograma', '/activity/msociograma');
		$paths[] = new restore_path_element('msociograma_sheet', '/activity/msociograma/sheets/sheet');
		$paths[] = new restore_path_element('msociograma_answer', '/activity/msociograma/answers/answer');
		$paths[] = new restore_path_element('msociograma_diagram', '/activity/msociograma/diagrams/diagram');		
		
		
		
		
        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process the given restore path element data
     *
     * @param array $data parsed element data
     */
    protected function process_msociograma($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

	
        if (empty($data->timecreated)) {
            $data->timecreated = time();
        }

        if (empty($data->timemodified)) {
            $data->timemodified = time();
        }

        // Create the msociograma instance.
        $newitemid = $DB->insert_record('msociograma', $data);
        $this->apply_activity_instance($newitemid);
    }

	
	 protected function process_msociograma_sheet($data) {
        global $DB;
					
        $data = (object)$data;
        $oldid = $data->id;
		$data->course = $this->get_courseid();
		$data->activity = last_course_module_id($data->course);
		
	
		// Create the msociograma instance.
        $newitemid = $DB->insert_record('msociograma_sheet', $data);
        $this->set_mapping('msociograma_sheet', $oldid, $newitemid);
		$this->meteArray($oldid,$newitemid);
	  
    }
	
	protected function process_msociograma_answer($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
		$oldid_stu = $data->id_stu;
		$oldstu_ans = $data->stu_ans;
        $data->course = $this->get_courseid();
		$data->activity =  last_course_module_id($data->course);
		$data->id_stu = $this->sacaArray($oldid_stu);
		$data->stu_ans = $this->sacaArray($oldstu_ans);
	    // Create the msociograma instance.
		$newitemid = $DB->insert_record('msociograma_answers', $data);
		$this->set_mapping('msociograma_answer', $oldid, $newitemid);
		
    }
	
	protected function process_msociograma_diagram($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
		$oldid_stu = $data->id_stu;
        $data->course = $this->get_courseid();
		$data->activity =  last_course_module_id($data->course);
		$data->id_stu = $this->sacaArray($oldid_stu);
		
	    // Create the msociograma instance.
		$newitemid = $DB->insert_record('msociograma_diagram', $data);
		$this->set_mapping('msociograma_diagram', $oldid, $newitemid);
    }
	
	

	
    /**
     * Post-execution actions
     */
    protected function after_execute() {
        // Add msociograma related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_msociograma', 'intro', null);
    }
	
	protected function meteArray($pos, $valor){
	
		global $biyection2;
		
	
		$biyection2[$pos] = $valor;
	
	}

	protected function sacaArray($pos){

		global $biyection2;

		if ($pos==10000)
			return 10000;
		else
			return $biyection2[$pos];
			 
	}


	 

}
	
	//get de last course_module creates
	function last_course_module_id($course){
			global $DB;
			$sql = "SELECT id FROM {course_modules} WHERE course= '$course' ORDER BY id DESC LIMIT 1"; // moodle 3.0

			if ($data = $DB->get_records_sql($sql, array('%')))				
				 foreach($data as $registro)
					  $id=$registro->id;  
			
			return $id;
			
	}

	


