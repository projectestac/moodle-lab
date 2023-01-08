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
 * Class containing data for grading table footer.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_etask\output;

use coding_exception;
use moodle_exception;
use moodle_url;
use renderable;
use renderer_base;
use single_select;
use stdClass;
use templatable;

/**
 * Class to prepare a grading table footer for display.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradingtable_footer implements renderable, templatable {

    /** @var single_select|null */
    private $select = null;

    /** @var string */
    private $pagingbar;

    /** @var bool */
    private $showhelp;

    /**
     * Footer constructor.
     *
     * @param int $studentscountforview
     * @param array $groups
     * @param int|null $selectedgroup
     *
     * @throws moodle_exception
     * @throws coding_exception
     */
    public function __construct(int $studentscountforview, array $groups, ?int $selectedgroup) {
        global $OUTPUT, $PAGE;

        $currentpage = course_get_format($PAGE->course)->get_current_page($studentscountforview, course_get_format(
            $PAGE->course)->get_students_per_page());
        $this->pagingbar = $OUTPUT->paging_bar($studentscountforview, $currentpage, course_get_format(
            $PAGE->course)->get_students_per_page(), $PAGE->url);
        $this->showhelp = has_capability('moodle/course:update', $PAGE->context);

        // If more then one group, prepare groups select. This method contains only groups available by permissions.
        if (count($groups) > 1) {
            $action = new moodle_url(
                '/course/format/etask/update_settings.php',
                [
                    'course' => $PAGE->course->id,
                ]
            );

            $select = new single_select($action, 'group', $groups, $selectedgroup, []);
            $select->set_label(get_string('group'), ['class' => 'mb-0 d-none d-md-inline']);
            $this->select = $select;
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
        $data->select = $this->select ? $output->box($output->render($this->select), 'mt-n3') : null;
        $data->pagingbar = $this->pagingbar;
        $data->popover = $this->showhelp ? $output->render(new gradingtable_help_popover()) : null;

        return $data;
    }
}
