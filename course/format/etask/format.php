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
 * The eTask topics course format extends Topics format and includes the grading table above or below the course topics.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/completionlib.php');

// Horrible backwards compatible parameter aliasing.
$topic = optional_param('topic', null, PARAM_INT);
if ($topic !== null) {
    $url = $PAGE->url;
    $url->param('section', $topic);
    debugging('Outdated topic param passed to course/view.php', DEBUG_DEVELOPER);
    redirect($url);
}
// End backwards-compatible aliasing.

$context = context_course::instance($course->id);
// Retrieve course format option fields and add them to the $course object.
$course = course_get_format($course)->get_course();

if (($marker >= 0) && has_capability('moodle/course:setcurrentsection', $context) && confirm_sesskey()) {
    $course->marker = $marker;
    course_set_marker($course->id, $marker);
}

// Make sure section 0 is created.
course_create_sections_if_missing($course, 0);

$renderer = $PAGE->get_renderer('format_etask');

// Start of eTask topics course format.
if (has_capability('moodle/course:viewparticipants', $context)) {
    // Print the grading table (the position above the sections).
    if (course_get_format($PAGE->course)->get_placement() === format_etask::PLACEMENT_ABOVE) {
        $renderer->print_grading_table($context, $course);
    }

    // Print the sections.
    if ($displaysection > 0) {
        $renderer->print_single_section_page($course, null, null, null, null, $displaysection);
    } else {
        $renderer->print_multiple_section_page($course, null, null, null, null);
    }

    // Print the grading table (the position below the sections).
    if (course_get_format($PAGE->course)->get_placement() === format_etask::PLACEMENT_BELOW) {
        $renderer->print_grading_table($context, $course);
    }
} else {
    // Print the sections only (no grading table - user cannot view participants).
    if ($displaysection > 0) {
        $renderer->print_single_section_page($course, null, null, null, null, $displaysection);
    } else {
        $renderer->print_multiple_section_page($course, null, null, null, null);
    }
}
// End of eTask topics course format.

// Include course formats js modules.
$PAGE->requires->js('/course/format/topics/format.js');
$PAGE->requires->js('/course/format/etask/format.js');
