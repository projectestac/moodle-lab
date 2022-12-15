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
 * This file contains main class for eTask topics course format.
 *
 * @package   format_etask
 * @copyright 2022, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/format/topics/lib.php');

use core\output\inplace_editable;

/**
 * Main class for the eTask topics course format.
 *
 * @package   format_etask
 * @copyright 2022, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_etask extends format_topics {

    /** @var string */
    public const STATUS_COMPLETED = 'completed';

    /** @var string */
    public const STATUS_PASSED = 'passed';

    /** @var string */
    public const STATUS_FAILED = 'failed';

    /** @var string */
    public const STATUS_NONE = 'none';

    /** @var int */
    public const STUDENTS_PER_PAGE_DEFAULT = 10;

    /** @var string */
    public const GRADEITEMS_SORTING_LATEST = 'latest';

    /** @var string */
    public const GRADEITEMS_SORTING_OLDEST = 'oldest';

    /** @var string */
    public const GRADEITEMS_SORTING_INHERIT = 'inherit';

    /** @var string */
    public const PLACEMENT_ABOVE = 'above';

    /** @var string */
    public const PLACEMENT_BELOW = 'below';

    /**
     * Definitions of the additional options that this course format uses for course.
     *
     * eTask topics format uses the following options:
     * - hiddensections,
     * - coursedisplay,
     * - studentprivacy,
     * - gradeitemprogressbars,
     * - studentsperpage,
     * - gradeitemssorting,
     * - placement,
     * - passedlabel,
     * - failedlabel.
     *
     * @param bool $foreditform
     *
     * @return array<string, mixed>
     */
    public function course_format_options($foreditform = false): array {
        static $courseformatoptions = false;

        if ($courseformatoptions === false) {
            $courseconfig = get_config('moodlecourse');
            $courseformatoptions = [
                'hiddensections' => [
                    'default' => $courseconfig->hiddensections,
                    'type' => PARAM_INT,
                ],
                'coursedisplay' => [
                    'default' => $courseconfig->coursedisplay,
                    'type' => PARAM_INT,
                ],
                'studentprivacy' => [
                    'default' => 1,
                    'type' => PARAM_INT,
                ],
                'gradeitemprogressbars' => [
                    'default' => 1,
                    'type' => PARAM_INT,
                ],
                'studentsperpage' => [
                    'default' => self::STUDENTS_PER_PAGE_DEFAULT,
                    'type' => PARAM_INT,
                ],
                'gradeitemssorting' => [
                    'default' => self::GRADEITEMS_SORTING_LATEST,
                    'type' => PARAM_ALPHA,
                ],
                'placement' => [
                    'default' => self::PLACEMENT_ABOVE,
                    'type' => PARAM_ALPHA,
                ],
                'passedlabel' => [
                    'default' => get_string('gradeitempassed', 'format_etask'),
                    'type' => PARAM_RAW,
                ],
                'failedlabel' => [
                    'default' => get_string('gradeitemfailed', 'format_etask'),
                    'type' => PARAM_RAW,
                ],
            ];
        }

        if ($foreditform && !isset($courseformatoptions['coursedisplay']['label'])) {
            $courseformatoptionsedit = [
                'hiddensections' => [
                    'label' => new lang_string('hiddensections'),
                    'help' => 'hiddensections',
                    'help_component' => 'moodle',
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            0 => new lang_string('hiddensectionscollapsed'),
                            1 => new lang_string('hiddensectionsinvisible'),
                        ],
                    ],
                ],
                'coursedisplay' => [
                    'label' => new lang_string('coursedisplay'),
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            COURSE_DISPLAY_SINGLEPAGE => new lang_string('coursedisplay_single'),
                            COURSE_DISPLAY_MULTIPAGE => new lang_string('coursedisplay_multi'),
                        ],
                    ],
                    'help' => 'coursedisplay',
                    'help_component' => 'moodle',
                ],
                'studentprivacy' => [
                    'label' => new lang_string('studentprivacy', 'format_etask'),
                    'help' => 'studentprivacy',
                    'help_component' => 'format_etask',
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            0 => new lang_string('studentprivacy_no', 'format_etask'),
                            1 => new lang_string('studentprivacy_yes', 'format_etask'),
                        ]
                    ],
                ],
                'gradeitemprogressbars' => [
                    'label' => new lang_string('gradeitemprogressbars', 'format_etask'),
                    'help' => 'gradeitemprogressbars',
                    'help_component' => 'format_etask',
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            0 => new lang_string('gradeitemprogressbars_no', 'format_etask'),
                            1 => new lang_string('gradeitemprogressbars_yes', 'format_etask'),
                        ],
                    ],
                ],
                'studentsperpage' => [
                    'label' => new lang_string('studentsperpage', 'format_etask'),
                    'help' => 'studentsperpage',
                    'help_component' => 'format_etask',
                    'element_type' => 'text',
                ],
                'gradeitemssorting' => [
                    'label' => new lang_string('gradeitemssorting', 'format_etask'),
                    'help' => 'gradeitemssorting',
                    'help_component' => 'format_etask',
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            self::GRADEITEMS_SORTING_LATEST => new lang_string(
                                'gradeitemssorting_latest', 'format_etask'
                            ),
                            self::GRADEITEMS_SORTING_OLDEST => new lang_string(
                                'gradeitemssorting_oldest', 'format_etask'
                            ),
                            self::GRADEITEMS_SORTING_INHERIT => new lang_string(
                                'gradeitemssorting_inherit', 'format_etask'
                            ),
                        ],
                    ],
                ],
                'placement' => [
                    'label' => new lang_string('placement', 'format_etask'),
                    'help' => 'placement',
                    'help_component' => 'format_etask',
                    'element_type' => 'select',
                    'element_attributes' => [
                        [
                            self::PLACEMENT_ABOVE => new lang_string(
                                'placement_above', 'format_etask'
                            ),
                            self::PLACEMENT_BELOW => new lang_string(
                                'placement_below', 'format_etask'
                            ),
                        ],
                    ],
                ],
                'passedlabel' => [
                    'label' => new lang_string('passedlabel', 'format_etask'),
                    'help' => 'passedlabel',
                    'help_component' => 'format_etask',
                    'element_type' => 'text',
                ],
                'failedlabel' => [
                    'label' => new lang_string('failedlabel', 'format_etask'),
                    'help' => 'failedlabel',
                    'help_component' => 'format_etask',
                    'element_type' => 'text',
                ],
            ];

            $courseformatoptions = array_merge_recursive($courseformatoptions, $courseformatoptionsedit);
        }

        return $courseformatoptions;
    }

    /**
     * Return the grade item completed/passed progress in percent.
     *
     * @param string[]|null $gradeitemstatuses
     * @param int $studentscountforcalculations
     *
     * @return array<float, float>
     */
    public function get_progress_values(?array $gradeitemstatuses, int $studentscountforcalculations): array {
        // If progress bars are not allowed, return zero values.
        if (!$this->show_grade_item_progress_bars() || $gradeitemstatuses === null) {
            return [0.0, 0.0];
        }

        // Merge initial values with the count of each status.
        $progressbardatacount = array_merge(['passed' => 0.0, 'completed' => 0.0, 'failed' => 0.0],
            array_count_values($gradeitemstatuses));

        // Calculate % of completed/passed progress.
        $progresscompleted = round(100 * (array_sum([$progressbardatacount['completed'], $progressbardatacount['passed'],
            $progressbardatacount['failed']]) / $studentscountforcalculations));
        $progresspassed = round(100 * ($progressbardatacount['passed'] / $studentscountforcalculations));

        return [$progresscompleted, $progresspassed];
    }

    /**
     * Return 'true' if student privacy is required.
     *
     * @return bool
     */
    public function is_student_privacy(): bool {
        global $PAGE;

        // If the user can view all the grades, return 'false', i.e. do not take care of student privacy.
        if (has_capability('moodle/grade:viewall', $PAGE->context)) {
            return false;
        }

        return (bool) $this->course->studentprivacy;
    }

    /**
     * Return 'true' if the grade item progress bars can be shown.
     *
     * @return bool
     */
    public function show_grade_item_progress_bars(): bool {
        global $PAGE;

        // If the user can view all the grades, return 'true', i.e. show the progress bars.
        if (has_capability('moodle/grade:viewall', $PAGE->context)) {
            return true;
        }

        return (bool) $this->course->gradeitemprogressbars;
    }

    /**
     * Return number of students per page.
     *
     * @return int
     */
    public function get_students_per_page(): int {
        if ($this->course->studentsperpage === 0) {
            return self::STUDENTS_PER_PAGE_DEFAULT;
        }

        return $this->course->studentsperpage;
    }

    /**
     * Return grading table student's total count for view.
     *
     * @param array $students
     *
     * @return int
     */
    public function get_students_count_for_view(array $students): int {
        global $USER;

        if ($this->is_student_privacy()) {
            return isset($students[$USER->id]) ? 1 : 0;
        }

        return count($students);
    }

    /**
     * Return the grade items sorting type.
     *
     * @return string
     */
    public function get_grade_items_sorting(): string {
        return $this->course->gradeitemssorting;
    }

    /**
     * Return the grading table placement.
     *
     * @return string
     */
    public function get_placement(): string {
        return $this->course->placement;
    }

    /**
     * Return passed label.
     *
     * @return string
     */
    public function get_passed_label(): string {
        if ($this->course->passedlabel === '') {
            return get_string('gradeitempassed', 'format_etask');
        }

        return $this->course->passedlabel;
    }

    /**
     * Return failed label.
     *
     * @return string
     */
    public function get_failed_label(): string {
        if ($this->course->failedlabel === '') {
            return get_string('gradeitemfailed', 'format_etask');
        }

        return $this->course->failedlabel;
    }

    /**
     * Return 'true' if the cell is 'collectible', i.e collect table cells by student privacy - either all or the current student
     * only.
     *
     * @param stdClass $user
     *
     * @return bool
     */
    public function is_collectible_cell(stdClass $user): bool {
        global $USER;

        return !$this->is_student_privacy() || ($this->is_student_privacy() && $user->id === $USER->id);
    }

    /**
     * Return array of groups (ID => name). If the user cannot access all groups, only groups for a specific user are returned.
     *
     * @return array<int, string>
     * @throws coding_exception
     */
    public function get_groups(): array {
        global $PAGE, $USER;

        // If the user can access all the groups set the user ID to '0'.
        $userid = has_capability('moodle/site:accessallgroups', $PAGE->context) ? 0 : $USER->id;
        // Get all groups by the user ID. If the user ID is '0', all groups are returned.
        $groups = groups_get_all_groups($PAGE->course->id, $userid, 0, 'g.id, g.name', false);

        // Transform groups to an array of group ID => group name.
        foreach ($groups as $id => $group) {
            /** @var array<int, string> $transformedgroups */
            $transformedgroups[$id] = $group->name;
        }

        return $transformedgroups ?? [];
    }

    /**
     * Return current group ID. If the user cannot access all groups, only groups for a specific user are returned.
     *
     * @return int
     * @throws coding_exception
     */
    public function get_current_group_id(): int {
        global $PAGE, $SESSION, $USER;

        // If the user can access all the groups set the user ID to '0'.
        $userid = has_capability('moodle/site:accessallgroups', $PAGE->context) ? 0 : $USER->id;
        /** @var array<int, int> $groupids */
        $groupids = array_keys(groups_get_all_groups($PAGE->course->id, $userid, 0, 'g.id', false));

        // Set current group ID to '0' due to undefined variable notice.
        $currentgroupid = 0;
        if (isset($SESSION->format_etask['currentgroup']) && in_array($SESSION->format_etask['currentgroup'], $groupids)) {
            // The group ID is in the session and this session is valid with the group IDs.
            $currentgroupid = (int) $SESSION->format_etask['currentgroup'];
        } else if (count($groupids) > 0) {
            // The group ID is not in the session or is not valid with the group IDs, i.e. use the first group ID.
            $currentgroupid = (int) $SESSION->format_etask['currentgroup'] = current($groupids);
        }

        return $currentgroupid;
    }

    /**
     * Return the current pagination page.
     *
     * @param int $studentscountforview
     * @param int $studentsperpage
     *
     * @return int
     * @throws coding_exception
     */
    public function get_current_page(int $studentscountforview, int $studentsperpage): int {
        global $SESSION;

        // Try to get a page from the URL parameter.
        $currentpage = optional_param('page', null, PARAM_INT);
        if ($currentpage !== null) {
            // If the page exists in the URL parameter, set it to the session and use it.
            return (int) $SESSION->format_etask['currentpage'] = $currentpage;
        }

        // If the current page is out of bound set it to the last page. Use '<=' comparison because the pages are numbered from
        // the zero.
        if (isset($SESSION->format_etask['currentpage']) && $studentscountforview <= $SESSION->format_etask['currentpage']
            * $studentsperpage && !$this->is_student_privacy()) {
            return (int) $SESSION->format_etask['currentpage'] = round($studentscountforview / $studentsperpage, 0) - 1;
        }

        // Return valid current page, i.e. does not allow a negative current page.
        return isset($SESSION->format_etask['currentpage']) && $SESSION->format_etask['currentpage'] > 0 ?
            $SESSION->format_etask['currentpage'] : 0;
    }

    /**
     * Return sorted grade items.
     *
     * @return array<string, grade_item>
     */
    public function get_sorted_gradeitems(): array {
        global $COURSE;

        // Fetch all the grade item instances.
        $gradeiteminstances = grade_item::fetch_all(['courseid' => $COURSE->id, 'itemtype' => 'mod', 'hidden' => false]);

        // If no grade items, return an empty array.
        if (!$gradeiteminstances) {
            return [];
        }

        /** @var array<string, grade_item> $gradeitems */
        $gradeitems = [];
        /** @var grade_item $gradeiteminstance */
        foreach ($gradeiteminstances as $gradeiteminstance) {
            // If deletion is in progress for a grade item, continue silently.
            if ((bool) get_fast_modinfo($COURSE->id)->instances[$gradeiteminstance->itemmodule][$gradeiteminstance->iteminstance]
                ->deletioninprogress) {
                continue;
            }

            // Initialize the grade item number.
            $itemnum[$gradeiteminstance->itemmodule] = $itemnum[$gradeiteminstance->itemmodule] ?? 0;

            // Store the grade item instances count due to sub-numbering. If the same instance is repeating (count is greater than
            // zero), sub-numbering is needed.
            $instancescount[$gradeiteminstance->itemmodule][$gradeiteminstance->iteminstance] = $instancescount[$gradeiteminstance
                ->itemmodule][$gradeiteminstance->iteminstance] ?? 0;

            // If the item number exists, do not increment number and include this item number after the dot. E.g. the workshop has
            // assessment and submission parts, i.e. shortcut is W1 and W1.1.
            if ($gradeiteminstance->itemnumber > 0
                && $instancescount[$gradeiteminstance->itemmodule][$gradeiteminstance->iteminstance] > 0) {
                $shortcut = sprintf('%s%d.%d', strtoupper(substr($gradeiteminstance->itemmodule, 0, 1)),
                    $itemnum[$gradeiteminstance->itemmodule], $gradeiteminstance->itemnumber);
            } else {
                $shortcut = sprintf('%s%d', strtoupper(substr($gradeiteminstance->itemmodule, 0, 1)),
                    ++$itemnum[$gradeiteminstance->itemmodule]);
            }

            // Increment the grade item instances count.
            ++$instancescount[$gradeiteminstance->itemmodule][$gradeiteminstance->iteminstance];

            // Collect grade items with numbering.
            $gradeitems[$shortcut] = $gradeiteminstance;
        }

        // Sort grade items by course setting.
        switch ($this->get_grade_items_sorting()) {
            case self::GRADEITEMS_SORTING_OLDEST:
                uasort($gradeitems, function($a, $b) {
                    return $a->id > $b->id;
                });
                break;
            case self::GRADEITEMS_SORTING_INHERIT:
                $gradeitems = $this->sort_grade_items_by_sections($gradeitems);
                break;
            default:
                uasort($gradeitems, function($a, $b) {
                    return $a->id < $b->id;
                });
                break;
        }

        // Return sorted grade items with shortcuts as a key.
        return $gradeitems;
    }

    /**
     * Return the due date of the grade item.
     *
     * @param grade_item $gradeitem
     *
     * @return int|null
     */
    public function get_due_date(grade_item $gradeitem): ?int {
        global $COURSE, $DB;

        /** @var array<string, string> $duedatefields */
        $duedatefields = $this->get_due_date_fields();
        // If due date fields exist for the item module, try to return timestamp from the database.
        if (isset($duedatefields[$gradeitem->itemmodule])) {
            $time = (int) $DB->get_field($gradeitem->itemmodule, $duedatefields[$gradeitem->itemmodule],
                ['id' => $gradeitem->iteminstance], IGNORE_MISSING);

            if ($time > 0) {
                return $time;
            }
        }

        // If the due date field does not exist for the item module, try to return the expected completion timestamp from the item
        // module instance.
        $completionexpected = (int) get_fast_modinfo($COURSE->id)->instances[$gradeitem->itemmodule][$gradeitem->iteminstance]
            ->completionexpected;

        return $completionexpected > 0 ? $completionexpected : null;
    }

    /**
     * Return the grade item status.
     *
     * @param grade_item $gradeitem
     * @param stdClass $user
     *
     * @return string
     */
    public function get_grade_item_status(grade_item $gradeitem, stdClass $user): string {
        global $PAGE;

        // Get the grade item completion state, i.e. true for the completed grade item.
        $completionstate = (bool) (new completion_info($PAGE->course))->get_data(get_fast_modinfo($PAGE->course->id, $user->id)
            ->instances[$gradeitem->itemmodule][$gradeitem->iteminstance], false, $user->id)->completionstate;
        $gradepass = (float) $gradeitem->gradepass;
        $grade = $gradeitem->get_grade($user->id)->finalgrade ?? 0.0;

        $status = self::STATUS_NONE;
        // Switch the grade item statuses by the defined criteria.
        if ($grade === 0.0 && $completionstate) {
            // Activity no have grade value and have completed status or is marked as completed.
            $status = self::STATUS_COMPLETED;
        } else if ($grade === 0.0 || $gradepass === 0.0) {
            // Activity no have grade value and is not completed or grade to pass is not set.
            $status = self::STATUS_NONE;
        } else if ($grade >= $gradepass) {
            // Activity grade value is higher then grade to pass.
            $status = self::STATUS_PASSED;
        } else if ($grade < $gradepass) {
            // Activity grade value is lower then grade to pass.
            $status = self::STATUS_FAILED;
        }

        return $status;
    }

    /**
     * Transform grade item status to the CSS attributes.
     *
     * @param string $status
     *
     * @return string
     */
    public function transform_status_to_css(string $status): string {
        switch ($status) {
            case self::STATUS_COMPLETED:
                $css = 'badge badge-warning';
                break;
            case self::STATUS_PASSED:
                $css = 'badge badge-success';
                break;
            case self::STATUS_FAILED:
                $css = 'badge badge-danger';
                break;
            default:
                $css = '';
        }

        return $css;
    }

    /**
     * Transform grade item status to the CSS attributes.
     *
     * @param string $status
     *
     * @return string
     */
    public function transform_status_to_label(string $status): string {
        switch ($status) {
            case self::STATUS_COMPLETED:
                $label = get_string('gradeitemcompleted', 'format_etask');
                break;
            case self::STATUS_PASSED:
                $label = $this->get_passed_label();
                break;
            case self::STATUS_FAILED:
                $label = $this->get_failed_label();
                break;
            default:
                $label = '';
        }

        return $label;
    }

    /**
     * Get gradable students (logged in student move to the first position in the grade table).
     *
     * @return array<int, stdClass>
     */
    public function get_gradable_students(): array {
        global $COURSE, $USER;

        $students = get_enrolled_users(context_course::instance($COURSE->id), 'moodle/grade:view',
            $this->get_current_group_id(), 'u.*', null, 0, 0, true);

        if (isset($students[$USER->id]) && !$this->is_student_privacy()) {
            $currentuser = $students[$USER->id];
            unset($students[$USER->id]);
            array_unshift($students , $currentuser);
        }

        return $students;
    }

    /**
     * Sort grade items by sections.
     *
     * @param grade_item[] $gradeitems
     *
     * @return array<string, grade_item>
     */
    private function sort_grade_items_by_sections(array $gradeitems): array {
        global $COURSE;

        // Get an array of sections with course-module IDs.
        $sections = get_fast_modinfo($COURSE)->get_sections();
        /** @var string[] $cmids */
        $cmids = [];

        // Prepare ordered cmids by sections.
        foreach ($sections as $section) {
            $cmids = array_merge($cmids, $section);
        }

        // Sort grade items by cmids.
        uasort($gradeitems, function($a, $b) use ($cmids, $COURSE) {
            $cmida = get_fast_modinfo($COURSE->id)->instances[$a->itemmodule][$a->iteminstance]->id;
            $cmidb = get_fast_modinfo($COURSE->id)->instances[$b->itemmodule][$b->iteminstance]->id;

            $cmpa = array_search($cmida, $cmids);
            $cmpb = array_search($cmidb, $cmids);

            return $cmpa > $cmpb;
        });

        return $gradeitems;
    }

    /**
     * Return registered due date modules.
     *
     * @return array<string, string>
     */
    private function get_due_date_fields(): array {
        /** @var array<int, string> $registeredduedatemodules */
        $registeredduedatemodules = explode(',', get_config('format_etask', 'registered_due_date_modules'));

        $duedatefields = [];
        // Prepare an array of due date fields, i.e. module => due date database field.
        foreach ($registeredduedatemodules as $registeredduedatemodules) {
            if (strpos($registeredduedatemodules, ':')) {
                [$module, $duedatefield] = explode(':', $registeredduedatemodules);
                $duedatefields[trim($module)] = trim($duedatefield);
            }
        }

        return $duedatefields;
    }
}

/**
 * Implements callback inplace_editable() allowing to edit values in-place.
 *
 * @param string $itemtype
 * @param int $itemid
 * @param mixed $newvalue
 *
 * @return null|inplace_editable
 */
function format_etask_inplace_editable($itemtype, $itemid, $newvalue): ?inplace_editable {
    global $DB, $CFG;
    require_once($CFG->dirroot . '/course/lib.php');
    if ($itemtype === 'sectionname' || $itemtype === 'sectionnamenl') {
        $section = $DB->get_record_sql(
            'SELECT s.* FROM {course_sections} s JOIN {course} c ON s.course = c.id WHERE s.id = ? AND c.format = ?',
            [$itemid, 'etask'], MUST_EXIST);

        return course_get_format($section->course)->inplace_editable_update_section_name($section, $itemtype, $newvalue);
    }

    return null;
}
