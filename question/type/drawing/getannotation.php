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
 * drawing question renderer class.
 *
 * @package qtype
 * @subpackage drawing
 * @copyright ETHZ LET <amr.hourani@id.ethz.ch>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require_once('../../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$annotationid = required_param('annotationid', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_RAW);
$stid = required_param('stid', PARAM_INT);
$attemptid = required_param('attemptid', PARAM_RAW_TRIMMED);
$type = optional_param('type', 0, PARAM_INT);
$attemptcount = optional_param('attemptcount', 1, PARAM_INT);

if (!confirm_sesskey()) {
    echo json_encode(array('result' => 'Session lost.'));
    die();
}

require_once('../../../question/type/questiontypebase.php');
if (!$question = question_bank::load_question_data($id)) {
    echo json_encode(array('result' => 'Question attempt not found'));
    die();
}
if (!has_capability('mod/quiz:grade', context::instance_by_id($question->contextid))) {
    echo json_encode(array('result' => 'No permission'));
    die();
}
if (!$fhd = $DB->get_record('qtype_drawing', array('questionid' => $id))) {
    echo json_encode(array('result' => 'Question not found'));
    die();
}

// Get original student answer.
if ($type == 0) {
    // Check if annotation exists, and return it.
    $fields = array('questionid' => $id, 'attemptid' => $attemptid, 'id' => $annotationid, 'annotatedfor' => $stid, 'attemptcount' => $attemptcount);
    if (!$drawingannotation = $DB->get_record('qtype_drawing_annotations', $fields)) {
        echo json_encode(array('result' => 'No Such annotation.'));
        die();
    }
    echo json_encode(array('result' => 'OK', 'drawing' => $drawingannotation->annotation));
} else if ($type == 2) {
    // Check if annotation exists, and return it.
    $fields = array('questionid' => $id, 'attemptid' => $attemptid, 'annotatedfor' => $stid, 'attemptcount' => $attemptcount);
    if (!$drawingannotations = $DB->get_records('qtype_drawing_annotations', $fields)) {
        echo json_encode(array('result' => 'OK', 'drawing' => ''));
        die();
    }
    $annotationstr = '';
    foreach ($drawingannotations as $drawingannotation) {
        $annotationstr .= $drawingannotation->annotation;
    }
    echo json_encode(array('result' => 'OK', 'drawing' => $annotationstr));
}
