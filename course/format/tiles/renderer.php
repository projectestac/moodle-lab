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
 * Renderer for outputting the tiles course format.
 *
 * @package format_tiles
 * @copyright 2018 David Watson {@link http://evolutioncode.uk}
 * @copyright Based partly on previous topics format renderer and general course format renderer
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.7
 */


defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/course/format/renderer.php');
require_once($CFG->dirroot . '/course/format/tiles/locallib.php');

/**
 * Basic renderer for tiles format.
 * @package format_tiles
 * @copyright 2016 David Watson {@link http://evolutioncode.uk}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends core_courseformat\output\section_renderer {

    /**
     * Generate the starting container html for a list of sections as <ul class="tiles">
     * @param boolean $issinglesec true if rendering a single section
     * so that can add this to id and then use in css
     * @return string HTML to output.
     * @throws coding_exception
     */
    protected function start_section_list($issinglesec = false) {
        $class = 'tiles';
        if (optional_param('expanded', 0, PARAM_INT) == 1) {
            $class .= ' expanded';
        }
        if ($issinglesec) {
            $id = 'single_section_tiles';
        } else {
            $id = 'multi_section_tiles';
        }
        return html_writer::start_tag('ul', array('class' => $class, 'id' => $id));
    }

    /**
     * Generate the closing container html for a list of sections
     * @return string HTML to output.
     */
    protected function end_section_list() {
        return html_writer::end_tag('ul');
    }

    /**
     * Generate the title for this section page
     * @return string the page title
     * @throws coding_exception
     */
    protected function page_title() {
        return get_string('topicoutline');
    }

    /**
     * Generate the display of the footer part of a section
     * @see section_header() for more explanation of this
     * @return string HTML to output.
     */
    protected function section_footer() {
        return html_writer::end_tag('li');
    }

    /**
     * Generate the section title, wraps it in a link to the section page if page is to be displayed on a separate page
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @return string HTML to output.
     */
    public function section_title($section, $course) {
        return $this->render(course_get_format($course)->inplace_editable_render_section_name($section));
    }

    /**
     * Get the section title but not as a link
     * @param stdClass $section the section object
     * @param stdClass $course the course object
     * @return string the section title
     */
    public function section_title_without_link($section, $course) {
        return $this->render(course_get_format($course)->inplace_editable_render_section_name($section, false));
    }

    /**
     * Generate a summary of the activites in a section
     *
     * Very similar to its parent except that it does not include
     * progress data, and is reformatted
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course the course record from DB
     * @param array $mods (argument not used)
     * @return string HTML to output.
     * @throws coding_exception
     * @throws moodle_exception
     * @see format_section_renderer_base::section_activity_summary()
     */
    public function section_activity_summary($section, $course, $mods) {
        $modinfo = get_fast_modinfo($course);
        if (empty($modinfo->sections[$section->section])) {
            return '';
        }

        // Generate array with count of activities in this section.
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course);
        foreach ($modinfo->sections[$section->section] as $cmid) {
            $thismod = $modinfo->cms[$cmid];

            if ($thismod->modname == 'label') {
                // Labels are special (not interesting for students)!
                continue;
            }

            if ($thismod->uservisible) {
                if (isset($sectionmods[$thismod->modname])) {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                    $sectionmods[$thismod->modname]['count']++;
                } else {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                    $sectionmods[$thismod->modname]['count'] = 1;
                }
                if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $total++;
                    $completiondata = $completioninfo->get_data($thismod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                        $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $complete++;
                    }
                }
            }
        }

        if (empty($sectionmods)) {
            // No sections.
            return '';
        }

        // Output section activities summary.
        $o = '';
        if (!$this->page->user_is_editing()) {
            // Added for tiles.
            $contents = '<b>' . get_string('contents', 'format_tiles') . ':</b><br>';
            $extraclass = '';
        } else {
            $contents = '';
            $extraclass = ' pull-right';
        }
        // For tiles removed mdl-right class.
        $o .= html_writer::start_tag('div', array('class' => 'section-summary-activities' . $extraclass));
        $o .= $contents;
        foreach ($sectionmods as $mod) {
            $o .= html_writer::start_tag('span', array('class' => 'activity-count'));
            $o .= $mod['name'].': '.$mod['count'];
            $o .= html_writer::end_tag('span');
        }
        $o .= html_writer::end_tag('div');

        return $o;
    }
}
