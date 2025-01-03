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
 * This file contains tests that walks drawing questions through some attempts.
 *
 * @package qtype_drawing
 * @copyright 2020 ETH Zurich
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');

/**
 * Unit tests for the drawing question type.
 *
 * @copyright 2013 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_drawing_walkthrough_testcase extends qbehaviour_walkthrough_test_base {

    public function test_deferred_feedback_plain_text() {

        $q = test_question_maker::make_question('drawing', 'plain');
        $this->start_attempt_at_question($q, 'deferredfeedback', 1);
        // Check the initial state.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_current_output($this->get_contains_marked_out_of_summary(), $this->get_does_not_contain_feedback_expectation(),
                                    $this->get_does_not_contain_validation_error_expectation(),
                                    $this->get_does_not_contain_try_again_button_expectation(),
                                    $this->get_no_hint_visible_expectation());
        // Submit blank.
        $this->process_submission(array('answer' => ''));
        // Verify.
        $this->check_current_state(question_state::$todo);
        $this->check_current_mark(null);
        $this->check_current_output($this->get_contains_marked_out_of_summary(), $this->get_does_not_contain_feedback_expectation(),
                                    $this->get_does_not_contain_validation_error_expectation(),
                                    $this->get_does_not_contain_try_again_button_expectation(),
                                    $this->get_no_hint_visible_expectation());
        // Submit something.
        $this->process_submission(
                                array(
                                'answer' => 'DRAWING'));
        // Verify.
        $this->check_current_state(question_state::$complete);
        $this->check_current_mark(null);
        $this->check_current_output($this->get_contains_marked_out_of_summary(), $this->get_does_not_contain_feedback_expectation(),
                                    $this->get_does_not_contain_try_again_button_expectation(),
                                    $this->get_no_hint_visible_expectation());

        // Submit all and finish.
        $this->finish();
    }
}
