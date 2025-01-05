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
 * @copyright  2017 - 2020 Marco Alarcón
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Provides code to be executed during the module installation
 *
 * This file replaces the legacy STATEMENTS section in db/install.xml,
 * lib.php/modulename_install() post installation hook and partially defaults.php.
 */

/**
 * Post installation procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_msociograma_install() {
 global $DB;

/// insert db data
    $records = array(
        array_combine(array('question', 'value'), array('question1',1)),
        array_combine(array('question', 'value'), array('question2',1)),
        array_combine(array('question', 'value'), array('question3',1)),
        array_combine(array('question', 'value'), array('question4',1)),
        array_combine(array('question', 'value'), array('question5',1)),
        array_combine(array('question', 'value'), array('question6',1)),
        array_combine(array('question', 'value'), array('question7',1)),
        array_combine(array('question', 'value'), array('question8',1)),
        array_combine(array('question', 'value'), array('question9',1)),
        array_combine(array('question', 'value'), array('question10',1)),
        array_combine(array('question', 'value'), array('question11',1)),
        array_combine(array('question', 'value'), array('question12',1)),

    );
    foreach ($records as $record) {
        $DB->insert_record('msociograma_questions', $record, false);
    }

}

/**
 * Post installation recovery procedure
 *
 * @see upgrade_plugins_modules()
 */
function xmldb_msociograma_install_recovery() {
}
