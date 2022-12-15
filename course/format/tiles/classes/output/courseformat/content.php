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
 * Contains the default content output class.
 *
 * @package   format_tiles
 * @copyright 2022 David Watson
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_tiles\output\courseformat;


use core_courseformat\output\local\content as content_base;

/**
 * Format tiles class to render course content.
 *
 * @package   format_tiles
 * @copyright 2022 David Watson
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class content extends content_base {

    /**
     * Export this data so it can be used as the context for a mustache template (core/inplace_editable).
     *
     * @param \renderer_base $output typically, the renderer that's calling this function
     * @return \stdClass data context for a mustache template
     */
    public function export_for_template(\renderer_base $output) {
        global $PAGE;
        $isediting = $PAGE->user_is_editing();

        $data = parent::export_for_template($output);
        $data->editoradvice = [];

        $courseformatoptions = $this->format->get_format_options();
        // TODO for now this class is only used if the user is editing but we check anyway as one day it will be used when not editing.
        if ($isediting && get_config('format_tiles', 'allowsubtilesview')
            && isset($courseformatoptions['courseusesubtiles']) && $courseformatoptions['courseusesubtiles']) {
            // TODO for now (Beta version) we warn editor about sub tiles only appearing in non-edit view.
            $data->editoradvice[] = [
                'text' => get_string('editoradvicesubtiles', 'format_tiles', self::get_tiles_plugin_release()),
                'icon' => 'info-circle', 'class' => 'secondary'
            ];
        }
        return $data;
    }

    /**
     * Export sections array data.
     *
     * @param \renderer_base $output typically, the renderer that's calling this function
     * @return array data context for a mustache template
     */
    protected function export_sections(\renderer_base $output): array {
        return parent::export_sections($output);
    }

    /**
     * Get the release details of this version of Tiles.
     * @return string
     */
    private static function get_tiles_plugin_release(): string {
        global $CFG;
        $plugin = new \stdClass();
        $plugin->release = '';
        require("$CFG->dirroot/course/format/tiles/version.php");
        return $plugin->release;
    }
}
