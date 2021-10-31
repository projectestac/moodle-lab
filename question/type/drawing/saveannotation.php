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

/**
 * Generates the output for drawing questions.
 *
 * @copyright ETHZ LET <amr.hourani@id.ethz.chh>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
define('AJAX_SCRIPT', true);
require_once('../../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_RAW);
$stid = required_param('stid', PARAM_INT);
$attemptid = required_param('attemptid', PARAM_RAW_TRIMMED);
$annotation = required_param('annotation', PARAM_RAW);
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

// Just in case, remove any <script> if direct saving happens (how?!).
$annotation = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $annotation);
// After cleaning from script, is it empty?.
if(trim($annotation) == ''){
    echo json_encode(array('result' => 'No annotation submitted'));
    die();
}
// Check if record exists.
$fields = array('questionid' => $id, 'annotatedby' => $USER->id, 'annotatedfor' => $stid, 'attemptid' => $attemptid, 'attemptcount' => $attemptcount);
if ($recordexists = $DB->get_record('qtype_drawing_annotations', $fields)) {
    // Update annotation.
    $annotationrecord = new stdClass();
    $annotationrecord->id = $recordexists->id;
    $annotationrecord->questionid = $id;
    $annotationrecord->timemodified = time();
    $annotationrecord->annotation = $annotation;
    $annotationrecord->annotatedby = $USER->id;
    $annotationrecord->annotatedfor = $stid;
    $annotationrecord->attemptid = $attemptid;
    $annotationrecord->attemptcount = $attemptcount;
    $annotationrecord->notes = '';
    $DB->update_record('qtype_drawing_annotations', $annotationrecord);
} else {
    // Create annotation.
    $annotationrecord = new stdClass();
    $annotationrecord->id = $recordexists->id;
    $annotationrecord->questionid = $id;
    $annotationrecord->timemodified = time();
    $annotationrecord->timecreated = time();
    $annotationrecord->annotation = $annotation;
    $annotationrecord->annotatedby = $USER->id;
    $annotationrecord->annotatedfor = $stid;
    $annotationrecord->attemptid = $attemptid;
    $annotationrecord->attemptcount = $attemptcount;
    $annotationrecord->notes = '';
    $DB->insert_record('qtype_drawing_annotations', $annotationrecord);
    $result = 'insert '.$attemptcount;
}
$result = 'OK';
echo json_encode($result);
