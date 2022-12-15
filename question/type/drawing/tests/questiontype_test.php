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
 * Unit tests for the drawing question type class.
 *
 * @package    qtype
 * @subpackage drawing
 * @copyright  2020 ETH Zurich
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/type/drawing/questiontype.php');
require_once($CFG->dirroot . '/question/engine/tests/helpers.php');



/**
 * Unit tests for the drawing question type class.
 *
 * @copyright  2018 ETH Zurich
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_drawing_test extends advanced_testcase {
    protected $qtype;

    protected function setUp(): void {
        $this->qtype = new qtype_drawing();
    }

    protected function tearDown(): void {
        $this->qtype = null;
    }

    protected function get_test_question_data() {
        $q = new stdClass();
        $q->options = new stdClass();
        $q->options->answers[0] = new stdClass();
        return $q;
    }

    public function test_name() {
        $this->assertEquals($this->qtype->name(), 'drawing');
    }

    public function test_can_analyse_responses() {
        $this->assertTrue($this->qtype->can_analyse_responses());
    }

    public function test_get_random_guess_score() {
        $q = $this->get_test_question_data();
        $this->assertEquals(0, $this->qtype->get_random_guess_score($q));
    }

}
