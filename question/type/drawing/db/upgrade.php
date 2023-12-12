<?php
// This file is part of Moodle - http://moodle.org/
//
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
 * @package qtype_drawing
 * @author Amr Hourani amr.hourani@id.ethz.ch
 * @copyright ETHz 2016 amr.hourani@id.ethz.ch
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for the drawing question type.
 *
 * @param int $oldversion
 *        the version we are upgrading from.
 */
function xmldb_qtype_drawing_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2020042103) {
        // Define field drawing_usage to control display of result table.
        $table = new xmldb_table('qtype_drawing_annotations');

        $index = new xmldb_index('mdl_qtypdrawanno_dra_ix', XMLDB_INDEX_NOTUNIQUE, array('drawingid'));

        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }
        if ($dbman->field_exists($table, 'drawingid')) {
            $field = new xmldb_field('drawingid');
            $dbman->drop_field($table, $field);
        }
        // Add needed columns.
        if (!$dbman->field_exists($table, 'questionid')) {
            $field = new xmldb_field('questionid', XMLDB_TYPE_INTEGER, '10', null, null, false, null);
            $dbman->add_field($table, $field);

            // Conditionally add index questionid.
            $index = new xmldb_index('questionid_idx', XMLDB_INDEX_NOTUNIQUE, array('questionid'));
            if (!$dbman->index_exists($table, $index)) {
                $dbman->add_index($table, $index);
            }
        }
        if (!$dbman->field_exists($table, 'annotatedby')) {
            $field = new xmldb_field('annotatedby', XMLDB_TYPE_INTEGER, '10', null, null, false, null);
            $dbman->add_field($table, $field);
        }
        if (!$dbman->field_exists($table, 'annotatedfor')) {
            $field = new xmldb_field('annotatedfor', XMLDB_TYPE_INTEGER, '10', null, null, false, null);
            $dbman->add_field($table, $field);
        }
        if (!$dbman->field_exists($table, 'timemodified')) {
            $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, false, null);
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2020042103, 'qtype', 'drawing');
    }
    if ($oldversion < 2020062900) {
        // Define field drawing_usage to control display of result table.
        $table = new xmldb_table('qtype_drawing_annotations');
        if ($dbman->field_exists($table, 'attemptid')) {
            $field = new xmldb_field('attemptid');
            $dbman->drop_field($table, $field);
        }
        // Re-add it.
        $field = new xmldb_field('attemptid', XMLDB_TYPE_CHAR, '16', null, XMLDB_NOTNULL, false, null);
        $dbman->add_field($table, $field);
        upgrade_plugin_savepoint(true, 2020062900, 'qtype', 'drawing');
    }
    if ($oldversion < 2021022100) {
        // Define field drawing_usage to control display of result table.
        $table = new xmldb_table('qtype_drawing');
        if (!$dbman->field_exists($table, 'alloweraser')) {
            $field = new xmldb_field('alloweraser', XMLDB_TYPE_INTEGER, '4', null, null, false, null, 0);
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2021022100, 'qtype', 'drawing');
    }
    if ($oldversion < 2021081300) {
        // Define field drawing_usage to control display of result table.
        $table = new xmldb_table('qtype_drawing_annotations');
        if (!$dbman->field_exists($table, 'attemptcount')) {
            $field = new xmldb_field('attemptcount', XMLDB_TYPE_INTEGER, '3', null, null, false, null, 0);
            $dbman->add_field($table, $field);
            // Rest attempt count to attempt 1 just in case.
            $DB->execute('update {qtype_drawing_annotations} set attemptcount = 1');
        }

        upgrade_plugin_savepoint(true, 2021081300, 'qtype', 'drawing');
    }
    return true;
}
