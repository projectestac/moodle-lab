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

/**
 * This file keeps track of upgrades to the msociograma module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute msociograma upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_msociograma_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.

    
	if ($oldversion < 2020020126) {

        // Define table msociograma_tutoring to be created.
        $table = new xmldb_table('msociograma_tutoring');

        // Adding fields to table msociograma_tutoring.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
		$table->add_field('username', XMLDB_TYPE_CHAR, '40', null, null, null, null);
		$table->add_field('name', XMLDB_TYPE_CHAR, '200', null, null, null, null);
		$table->add_field('tutoring', XMLDB_TYPE_CHAR, '40', null, null, null, null);
		$table->add_field('syncro', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
		
		
        // Adding keys to table msociograma_tutoring.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        

        // Conditionally launch create table for msociograma_tutoring.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // msociograma savepoint reached.
        upgrade_mod_savepoint(true, 2020020126, 'msociograma');
    }
	
	
	
    return true;
}
