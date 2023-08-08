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
     * Makes an drawing question using plain text input.
     * @return qtype_drawing_question
     */
    public function make_drawing_question_plain() {
        $q = new qtype_drawing_question();
        test_question_maker::initialise_a_question($q);

        $q->qtype = question_bank::get_qtype('drawing');
        $q->responseformat = 'plain';
        return $q;
    }

    /**
     * Make the data what would be received from the editing form for an drawing
     * question.
     *
     * @return stdClass the data that would be returned by $form->get_gata();
     */
    public static function get_drawing_question_form_data_plain() {

        $q = new stdClass();
        $q->name = 'drawing-001';
        $q->questiontext = 'Please draw a cell.';
        $q->questiontext = array(
            "text" => 'Questiontext for drawing-001',
            'format' => FORMAT_HTML
        );
        $q->generalfeedback = array(
            "text" => 'I hope your drawing had a beginning, a middle and an end.',
            'format' => FORMAT_HTML
        );
        $q->defaultgrade = 0;
        $q->contextid = 1;
        $q->penalty = 0;
        $q->status = \core_question\local\bank\question_version_status::QUESTION_STATUS_READY;
        $q->qtype = 'drawing';
        $q->length = '1';
        $q->hidden = '0';
        $q->createdby = '2';
        $q->modifiedby = '2';
        $q->drawingmode = 1;
        $q->files= '';
        $q->backgrounduploaded = 0;
        $q->backgroundwidth = 800;
        $q->backgroundheight = 600;
        $q->options = new stdClass();
        $q->options->questionid = 1;
        $q->options->allowstudentimage = 0;
        $q->options->preservear = 1;
        $q->options->drawingoptions = '';
        $q->alloweraser = 0;

        return $q;
    }
}
