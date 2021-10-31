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
 * @package    qtype
 * @subpackage drawing
 * @copyright ETHZ LET <amr.hourani@id.ethz.ch>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Generates the output for drawing questions.
 *
 * @copyright  ETHZ LET <amr.hourani@id.ethz.chh>
 * @license    http://opensource.org/licenses/BSD-3-Clause
 */

define('AJAX_SCRIPT', true);
require_once('../../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_RAW);
$stid = required_param('stid', PARAM_INT);
$attemptcount = optional_param('attemptcount', 1, PARAM_INT);

$attemptid = required_param('attemptid', PARAM_RAW_TRIMMED);
if (!confirm_sesskey()) {
    echo json_encode(array('result' => 'Session lost.'));
    die;
}

require_once("../../../question/type/questiontypebase.php");
if (!$question = question_bank::load_question_data($id)) {
    echo json_encode(array('result' => 'Question attempt not found'));
    die;
}
if (!has_capability('mod/quiz:grade', context::instance_by_id($question->contextid)) ) {
    echo json_encode(array('result' => 'No permission'));
    die;
}
if (!$fhd = $DB->get_record('qtype_drawing', array('questionid' => $id)) ) {
    echo json_encode(array('result' => 'Question not found'));
    die;
}

$result .= '<ul id="listofannotaions">';
$fields = array('questionid' => $question->id, 'attemptid' => $attemptid, 'annotatedfor' => $stid, 'attemptcount' => $attemptcount);
if ($annotations = $DB->get_records('qtype_drawing_annotations', $fields, 'timemodified DESC')) {
    foreach ($annotations as $teacherannotation) {
        $user = $DB->get_record('user', array('id' => $teacherannotation->annotatedby));
        $annotatestr = preg_replace('/\v(?:[\v\h]+)/', '', $teacherannotation->annotation);
        $result .= '<li id="annotationelem_'.$user->id.'"
                    class="annotaionelems" data-block="block'.$user->id.'">
                    <a href="#" id="showannotationid_'.$teacherannotation->id.'" style="color:#fff" class="tool_showannotation"
                    data-type="0" data-annotationid="'.$teacherannotation->id.'">'.fullname($user).'</a>
                    <div id="teacherannotationdate_'.$user->id.'">'.
                    userdate($teacherannotation->timemodified).
                    ' ('.get_string('ago', 'core_message', format_time(time() + 1 - $teacherannotation->timemodified)).
                    ')</div></li>';
    }
}
$result .= '<li data-block="block0">
            <a href="#" id="showoriginalanswer" style="color:#fff"
            class="tool_showannotation" data-type="1" data-annotationid="-1">'.
            get_string('originalanswer', 'qtype_drawing').'</a></li>';
$result .= '<li data-block="block1">
            <a href="#" id="studentview" style="color:#fff" class="tool_showannotation" data-type="2" data-annotationid="-1">'.
            get_string('studentview', 'qtype_drawing').'</a></li>';

$result .= '</ul>';
echo json_encode(array('result' => $result, 'works' => 'OK'));
