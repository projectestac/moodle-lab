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
//This form contains the standart Moodle forms to upload the *.csv file


defined('MOODLE_INTERNAL') || die();

require_once ($CFG->dirroot.'/course/moodleform_mod.php');



class mod_msociograma_loadSheet_form extends moodleform {

    function definition() {
        global $DB, $CFG;
        $mform =&$this->_form;
		
		
		$mform->addElement('header','attachtdisplay', get_string('addstudents','msociograma'), false);
      	
		//add combobox
		$attemptoptions = array(get_string('add', 'msociograma'),get_string('delete', 'msociograma'));
        $mform->addElement('select', 'addRegisters',get_string('behaviour','msociograma'), $attemptoptions);
    	
		//add filemanager
		$maxbytes = 5242880;
		$mform->addElement('filemanager', 'attachmentsfiles', get_string('file','msociograma'), null, array('subdirs' => 0, 'maxbytes' => $maxbytes, 'maxfiles' => 1, 'areamaxbytes' => 5242880, 'accepted_types' => array('.csv','.txt','.html') ));	 
		$mform->addElement('hidden', 'blockid');
        $mform->setType('blockid', PARAM_RAW);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_RAW);
		
		//add submit and canccel buttons
        $this->add_action_buttons(true, get_string('load','msociograma'));

    }
}
