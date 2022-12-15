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
 * drawing question type definition class.
 *
 * @package    qtype
 * @subpackage drawing
 * @copyright  ETHZ LET <amr.hourani@id.ethz.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/renderer.php');


/**
 * Represents a drawing question.
 *
 * @copyright  ETHZ LET <amr.hourani@let.ethz.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_drawing_question extends question_graded_by_strategy implements question_response_answer_comparer {

    /** @var array of question_answer. */
    public $answers = array();

    public function __construct() {
        parent::__construct(new question_first_matching_answer_grading_strategy($this));
    }

    public function get_expected_data() {
        return array('answer' => PARAM_RAW_TRIMMED, 'uniqueuattemptid' => PARAM_RAW_TRIMMED);
    }

    public function summarise_response(array $response) {
        return get_string('no_response_summary', 'qtype_drawing');
    }
    // Initially added for LMDL-294. Remove at later stage.
    public function make_behaviour(question_attempt $qa, $preferredbehaviour) {
        return question_engine::make_behaviour('manualgraded', $qa, $preferredbehaviour);
    }
    public function is_complete_response(array $response) {
        if (array_key_exists('answer', $response)) {
            if ($response['answer'] != '') {
                return true;
            }
        }
        return false;
    }
    public function is_gradable_response(array $response) {
        return self::is_complete_response($response);
    }
    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseenterananswer', 'qtype_drawing');
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        return question_utils::arrays_same_at_key_missing_is_blank(
                $prevresponse, $newresponse, 'answer');
    }

    public function get_answers() {
        return $this->answers;
    }

    public function get_correct_response() {
        return null;
    }
    public function get_right_answer_summary() {
        return get_string('no_correct_answer_summary', 'qtype_drawing');
    }
    public function compare_response_with_answer(array $response, question_answer $answer) {

        if ($answer->answer === '' || array_key_exists('answer', $response) === false) {
            return false;
        }

        $matchpercentage = qtype_drawing_renderer::compare_drawings($answer->answer, $response['answer']);
        $answer->fraction = 0;
        return false;

    }

    public function check_file_access($qa, $options, $component, $filearea,
                    $args, $forcedownload) {
        if ($component == 'qtype_drawing' && $filearea == 'qtype_drawing_image_file') {
            $question = $qa->get_question();
            $itemid = reset($args);
            return ($itemid == $question->id);
        } else {
            return parent::check_file_access($qa, $options, $component,
                            $filearea, $args, $forcedownload);
        }
    }
}
