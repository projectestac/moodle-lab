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
 * Class containing data for grade item body cell content.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_etask\output;

use coding_exception;
use format_etask;
use grade_item;
use html_writer;
use moodle_exception;
use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Class to prepare a grade item body cell content for display.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradeitem_body implements renderable, templatable {

    /** @var string */
    private $value;

    /** @var moodle_url|null  */
    private $url = null;

    /** @var string */
    private $title;

    /** @var string|null */
    private $css = null;

    /**
     * Grade item body constructor.
     *
     * @param grade_item $gradeitem
     * @param stdClass $user
     * @param string $status
     *
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function __construct(grade_item $gradeitem, stdClass $user, string $status) {
        global $COURSE, $PAGE;

        $usergrade = $gradeitem->get_grade($user->id);

        // If the grade item is completed, value is replaced by the completed icon as an <i> tag. Otherwise, it is formatted grade
        // value.
        $this->value = $status === format_etask::STATUS_COMPLETED
            ? html_writer::tag('i', '', ['class' => 'fa fa-check', 'area-hidden' => 'true'])
            : grade_format_gradevalue($usergrade->finalgrade, $gradeitem, true, null, null);

        // If the table cell has some status except 'none', text color is white.
        if ($status !== format_etask::STATUS_NONE) {
            $this->css = course_get_format($COURSE)->transform_status_to_css($status);
        }

        // If the user can edit a grade, value is a link to the grade edit.
        if (has_capability('moodle/grade:edit', $PAGE->context)) {
            $this->url = new moodle_url('/grade/edit/tree/grade.php', [
                'courseid' => $PAGE->course->id,
                'id' => $usergrade->id,
                'gpr_type' => 'report',
                'gpr_plugin' => 'grader',
                'gpr_courseid' => $PAGE->course->id
            ]);

            $this->title = course_get_format($PAGE->course)->transform_status_to_label($status);
        }
    }

    /**
     * Export for template.
     *
     * @param renderer_base $output
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): stdClass {
        $data = new stdClass();
        $data->url = $this->url;
        $data->title = $this->title;
        $data->value = $this->value;
        $data->css = $this->css;

        return $data;
    }
}
