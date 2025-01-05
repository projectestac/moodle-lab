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
  
// Define all the backup steps that will be used by the backup_msociograma_activity_task


defined('MOODLE_INTERNAL') || die;

class backup_msociograma_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Get know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define the root element describing the msociograma instance.
        $msociograma = new backup_nested_element('msociograma', array('id'), array(
            'course','name', 'intro', 'introformat', 'timecreated','revision','timemodified','logintype'));

        // If we had more elements, we would build the tree here.

		
		$sheets = new backup_nested_element('sheets');
 
        $sheet = new backup_nested_element('sheet', array('id'), array(
            'course','activity','groupclass','student','pass','sexo','alias'));	
		
		$answers = new backup_nested_element('answers');
 
        $answer = new backup_nested_element('answer', array('id'), array(
            'course','activity','id_stu','grup','question','order_id','stu_ans'));
			
		$diagrams = new backup_nested_element('diagrams');
 
        $diagram = new backup_nested_element('diagram', array('id'), array(
           'id_stu','posx','posy','course','activity' ,'group_class','question'));	
			
		$msociograma->add_child($sheets);
        $sheets->add_child($sheet);
		
		$msociograma->add_child($answers);
        $answers->add_child($answer);
		
		$msociograma->add_child($diagrams);
        $diagrams->add_child($diagram);
		
		
		
		
        // Define data sources.
        $msociograma->set_source_table('msociograma', array('id' => backup::VAR_ACTIVITYID));
		
		$sheet->set_source_table('msociograma_sheet', array('course' => backup::VAR_COURSEID,'activity' => backup::VAR_MODID));
		
		$answer->set_source_table('msociograma_answers', array('course' => backup::VAR_COURSEID,'activity' => backup::VAR_MODID));

		$diagram->set_source_table('msociograma_diagram', array('course' => backup::VAR_COURSEID,'activity' => backup::VAR_MODID));
		
		
	
		
        // If we were referring to other tables, we would annotate the relation
        // with the element's annotate_ids() method.

        // Define file annotations (we do not use itemid in this example).
        $msociograma->annotate_files('mod_msociograma', 'intro', null);

        // Return the root element (msociograma), wrapped into standard activity structure.
        return $this->prepare_activity_structure($msociograma);
    }
}
