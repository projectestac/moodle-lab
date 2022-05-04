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
 * Test helpers for the drawing question type.
 *
 * @package qtype_drawing
 * @copyright 2019 ETH Zurich
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');

/**
 * Test helper class for the drawing question type.
 *
 * @copyright 2020 ETHz
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_drawing_test_helper extends question_test_helper {

    public function get_test_questions() {
        return array('plain');
    }

    /**
     * Helper method to reduce duplication.
     *
     * @return qtype_drawing_question
     */
    public function initialise_drawing_question() {
        question_bank::load_question_definition_classes('drawing');
        $q = new qtype_drawing_question();
        test_question_maker::initialise_a_question($q);
        $q->name = 'Please draw a cell.';
        $q->questiontext = 'Please draw a cell.';
        $q->generalfeedback = 'I hope your code had a beginning, a middle and an end.';
        $q->questiontextformat = FORMAT_HTML;
        $q->generalfeedback = "Generalfeedback: Drawing a cell isn't to hard";
        $q->generalfeedbackformat = FORMAT_HTML;
        $q->defaultmark = 1;
        $q->contextid = 1;
        $q->penalty = 0.3333333;
        $q->qtype = 'drawing';
        $q->length = '1';
        $q->hidden = '0';
        $q->createdby = '2';
        $q->modifiedby = '2';
        $q->options = new stdClass();
        $q->options->questionid = 1;
        $q->options->drawingmode = 1;
        $q->options->allowstudentimage = 0;
        $q->options->backgrounduploaded = 0;
        $q->options->backgroundwidth = 500;
        $q->options->backgroundheight = 400;
        $q->options->preservear = 1;
        $q->options->drawingoptions = '';

        $q->qtype = question_bank::get_qtype('drawing');

        return $q;
    }

    /**
     * Makes an drawing question using plain text input.
     *
     * @return qtype_drawing_question
     */
    public function make_drawing_question_plain() {
        $q = $this->initialise_drawing_question();
        return $q;
    }

    /**
     * Make the data what would be received from the editing form for an drawing
     * question.
     *
     * @return stdClass the data that would be returned by $form->get_gata();
     */
    public function get_drawing_question_form_data_plain() {
        $q = new stdClass();
        $q->name = 'Please draw a cell.';
        $q->questiontext = 'Please draw a cell.';
        $q->generalfeedback = 'I hope your drawing had a beginning, a middle and an end.';
        $q->questiontextformat = FORMAT_HTML;
        $q->generalfeedback = "Generalfeedback: Writing a code isn't to hard";
        $q->generalfeedbackformat = FORMAT_HTML;
        $q->defaultmark = 1;
        $q->contextid = 1;
        $q->penalty = 0.3333333;
        $q->qtype = 'drawing';
        $q->length = '1';
        $q->hidden = '0';
        $q->createdby = '2';
        $q->modifiedby = '2';
        $q->options = new stdClass();
        $q->options->questionid = 1;
        $q->options->drawingmode = 1;
        $q->options->allowstudentimage = 0;
        $q->options->backgrounduploaded = 0;
        $q->options->backgroundwidth = 500;
        $q->options->backgroundheight = 400;
        $q->options->preservear = 1;
        $q->options->drawingoptions = '';

        return $q;
    }
}
