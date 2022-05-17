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

//This file corresponds to the mod_form.php.
//This form contains the standart Moodle form to create the activity


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');


class mod_msociograma_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
	 global $COURSE, $CFG, $DB, $PAGE,$USER;
	
		   
        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('msociogramaname', 'msociograma'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'msociogramaname', 'msociograma');

        // Adding the standard "intro" and "introformat" fields.
     	$this->standard_intro_elements();
        // Adding the rest of msociograma settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.
    
        $mform->addElement('header', 'msociogramaTypeStudent', get_string('msociogramaTypeStudent', 'msociograma'));
        // Shuffle questions.

		$options = array(
            0 => get_string('loginStudents', 'msociograma'),  //for moodle users login 
            1 => get_string('sheetStudents', 'msociograma')    // for sheet users login
        );
	
        $mform->addElement('select', 'logintype', get_string('type', 'msociograma'), $options);
		$mform->setDefault('logintype','1');

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}
