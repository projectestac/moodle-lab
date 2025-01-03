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
 * Updates settings.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require("../../../config.php");
require_once("../../lib.php");
require_once("../../../lib/grade/grade_item.php");
require_once("../../../lib/grade/constants.php");
require_once("../../../lib/grade/grade_scale.php");

use core\notification;

// Get values from the URL parameters.
$gradepassparam = optional_param('gradepass', null, PARAM_TEXT);
$gradepass = unformat_float($gradepassparam, true) ?? 0.0;
$groupid = optional_param('group', null, PARAM_INT);
$courseid = required_param('course', PARAM_INT);

// Checks that the current user is logged in.
require_login();

if ($gradepassparam !== null && confirm_sesskey()) {
    // Update the grade item 'gradepass' and 'timemodified' fields.
    $gradeitemid = required_param('gradeitemid', PARAM_INT);

    // Find the grade item by ID.
    $gradeitem = (new grade_item())->fetch(['id' => $gradeitemid]);
    // Get the course module because of the privileges check.
    $cm = get_fast_modinfo($courseid)->instances[$gradeitem->itemmodule][$gradeitem->iteminstance];
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

    // Checks that the current user is logged in and has the required privileges.
    require_login($course, false, $cm);
    $modcontext = context_module::instance($cm->id);
    require_capability('moodle/course:manageactivities', $modcontext);

    // Fetch the grade item by ID, set the 'gradepass' with 'timemodified', and save it.
    $gradeitem = (new grade_item())->fetch(['id' => $gradeitemid]);
    $gradepassbeforeupdate = floatval($gradeitem->gradepass);
    $itemname = $gradeitem->get_name();

    // No change in the gradepass.
    if (($gradepass === 0.0 && $gradepassbeforeupdate === 0.0) || ($gradepass === $gradepassbeforeupdate)) {
        redirect(course_get_url($course));
    }

    // Gradepass is false, i.e. no numeric.
    if ($gradepass !== 0.0 && !$gradepass) {
        redirect(
            course_get_url($course),
            get_string('gradepasserrnumeric', 'format_etask', [
                'itemname' => $itemname,
                'gradepass' => $gradepassparam,
            ]),
            null,
            notification::ERROR
        );
    }

    // Gradepass is greater than grademax.
    if ($gradepass > $gradeitem->grademax) {
        redirect(
            course_get_url($course),
            get_string('gradepasserrgrademax', 'format_etask', [
                'itemname' => $itemname,
                'gradepass' => $gradepassparam,
            ]),
            null,
            notification::ERROR
        );
    }

    // Gradepass is lower than grademin.
    if ($gradepass < $gradeitem->grademin) {
        redirect(
            course_get_url($course),
            get_string('gradepasserrgrademin', 'format_etask', [
                'itemname' => $itemname,
                'gradepass' => $gradepassparam,
            ]),
            null,
            notification::ERROR
        );
    }

    $gradeitem->gradepass = $gradepass;
    $gradeitem->timemodified = time();
    $saved = $DB->update_record('grade_items', $gradeitem);

    if (!$saved) {
        redirect(
            course_get_url($course),
            get_string('gradepasserrdatabase', 'format_etask', $itemname),
            null,
            notification::ERROR
        );
    }

    // Gradepass removed.
    if ($gradepass === 0.0 && $gradepassbeforeupdate > 0.0) {
        redirect(
            course_get_url($course),
            get_string('gradepassremoved', 'format_etask', $itemname),
            null,
            notification::SUCCESS
        );
    }

    // Gradepass scale successfully changed.
    if (($scale = $gradeitem->load_scale()) !== null) {
        $gradepass = make_menu_from_list($scale->scale)[$gradepass];
    }

    // In other cases, gradepass points successfully changed.
    redirect(
        course_get_url($course),
        get_string('gradepasschanged', 'format_etask', [
            'itemname' => $itemname,
            'gradepass' => $gradepass,
        ]),
        null,
        notification::SUCCESS
    );
} else if ($groupid > 0) {
    // Checks that the current user is logged in and has the required privileges.
    require_login($courseid, false);

    // Update the 'currentgroup' session value.
    $SESSION->format_etask['currentgroup'] = $groupid;

    redirect(course_get_url($courseid));
}
