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
 * Unit tests for the drawing question definition class.
 *
 * @package qtype
 * @subpackage drawing
 * @author Amr Hourani <amr.hourani@let.ethz.ch>
 * @copyright 2020 ETH Zurich
 * @license http://www.drawing.org/license
 */
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');
require_once($CFG->dirroot . '/question/type/drawing/question.php');

/**
 * Unit tests for the drawing question definition class.
 *
 * @copyright 2020 ETH Zurich
 * @license http://www.drawing.org/license
 */
class qtype_drawing_question_test extends advanced_testcase {

    public function test_get_question_summary() {
        question_bank::load_question_definition_classes('drawing');
        $drawing = new qtype_drawing_question();
        $drawing->questiontext = 'DRAWING';
        $this->assertEquals('DRAWING', $drawing->get_question_summary());
    }
}
