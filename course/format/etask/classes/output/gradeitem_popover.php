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
 * Class containing data for grade item popover.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_etask\output;

use grade_item;
use html_writer;
use moodle_url;
use renderable;
use renderer_base;
use single_select;
use stdClass;
use templatable;

/**
 * Class to prepare a grade item popover for display.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradeitem_popover implements renderable, templatable {

    /** @var int */
    private $completed;

    /** @var int */
    private $passed;

    /** @var string */
    private $passedlabel;

    /** @var int|null */
    private $duedate;

    /** @var string */
    private $gradepass;

    /** @var string */
    private $grademax;

    /** @var bool */
    private $showprogressbars;

    /** @var string */
    private $itemmodule;

    /** @var single_select|null */
    private $select = null;

    /** @var gradepass_input|single_select|null */
    private $gradepassform = null;

    /** @var bool */
    private $showsettings;

    /** @var string */
    private $viewurl;

    /** @var string */
    private $editurl;

    /**
     * The popover constructor.
     *
     * @param grade_item $gradeitem
     * @param int $completed
     * @param int $passed
     * @param int|null $duedate
     * @param string $gradepass
     * @param string $grademax
     */
    public function __construct(grade_item $gradeitem, int $completed, int $passed, ?int $duedate, ?string $gradepass,
                                string $grademax) {
        global $PAGE;

        // Get course module ID.
        $cmid = (int) get_fast_modinfo($PAGE->course->id)->instances[$gradeitem->itemmodule][$gradeitem->iteminstance]->id;

        $this->itemname = $gradeitem->itemname;
        $this->timemodified = $gradeitem->timemodified;
        $this->completed = $completed;
        $this->passed = $passed;
        $this->passedlabel = course_get_format($PAGE->course)->get_passed_label();
        $this->duedate = $duedate;
        $this->gradepass = $gradepass;
        $this->grademax = $grademax;
        $this->showprogressbars = course_get_format($PAGE->course)->show_grade_item_progress_bars();
        $this->itemmodule = $gradeitem->itemmodule;
        $this->showsettings = has_capability('moodle/course:manageactivities', $PAGE->context);
        $this->viewurl = new moodle_url('/mod/' . $gradeitem->itemmodule . '/view.php', [
            'id' => $cmid
        ]);

        // If the user can view the grade item settings, prepare grade to pass select and the grade item edit URL.
        if ($this->showsettings) {
            $action = new moodle_url(
                '/course/format/etask/update_settings.php',
                [
                    'course' => $PAGE->course->id,
                    'gradeitemid' => $gradeitem->id,
                    'sesskey' => sesskey(),
                ]
            );

            if (($scale = $gradeitem->load_scale()) !== null) {
                $gradepassform = new single_select(
                    $action,
                    'gradepass',
                    make_menu_from_list($scale->scale),
                    round($gradeitem->gradepass),
                    [get_string('choose', 'format_etask')]);
                $gradepassform->set_label(get_string('gradepass', 'grades'), ['class' => 'mb-0']);
                $gradepassform->attributes = ['onchange' => 'this.form.submit()'];
            } else {
                $gradepassform = new gradepass_input(
                    $action,
                    'gradepass',
                    $gradeitem->gradepass > 0 ? format_float($gradeitem->gradepass, $gradeitem->get_decimals(), true, false) : null
                );
                $gradepassform->set_label(get_string('gradepass', 'grades'), ['class' => 'mb-0']);
            }

            $this->gradepassform = $gradepassform;

            $sesskey = sesskey();
            $sectionreturn = optional_param('sr', 0, PARAM_INT);
            $this->editurl = new moodle_url('/course/mod.php', [
                'sesskey' => $sesskey,
                'sr' => $sectionreturn,
                'update' => $cmid
            ]);
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
        $data->itemname = $this->itemname;
        $data->timemodified = $this->timemodified;
        $data->completed = $this->completed;
        $data->passed = $this->passed;
        $data->passedlabel = $this->passedlabel;
        $data->duedate = $this->duedate;
        $data->gradepass = $this->gradepass;
        $data->grademax = $this->grademax;
        $data->showprogressbars = $this->showprogressbars;
        $data->itemmoduleicon = html_writer::img($output->image_url('icon', $this->itemmodule), '', [
            'class' => 'icon itemicon',
            'alt' => '',
        ]);
        $data->settingsicon = $output->pix_icon('t/edit', get_string('edit'), 'core', [
            'class' => 'icon itemicon',
            'alt' => '',
        ]);
        $data->gradepassform = $this->gradepassform ? $output->box($output->render($this->gradepassform), 'mt-n3') : null;
        $data->showsettings = $this->showsettings;
        $data->viewurl = $this->viewurl;
        $data->editurl = $this->editurl;
        $data->margintop = $this->gradepass !== null || $this->duedate !== null;

        return $data;
    }
}
