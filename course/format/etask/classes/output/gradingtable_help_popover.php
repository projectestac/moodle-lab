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
 * Class containing data for grading table help popover.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_etask\output;

use core_plugin_manager;
use renderable;
use renderer_base;
use stdClass;
use templatable;
use html_writer;

/**
 * Class to prepare a grading table help popover for display.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradingtable_help_popover implements renderable, templatable {

    /** @var int */
    private $version;

    /** @var string */
    private $release;

    /** @var string */
    private $displayname;

    /**
     * The popover constructor.
     */
    public function __construct() {
        $plugininfo = core_plugin_manager::instance()->get_plugin_info('format_etask');
        $this->version = $plugininfo->versiondb;
        $this->release = $plugininfo->release;
        $this->displayname = $plugininfo->displayname;
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
        $data->version = $this->version;
        $data->release = $this->release;
        $data->displayname = $this->displayname;
        $data->pluginicon = html_writer::img($output->image_url('plugin', 'format_etask'), '', [
            'class' => 'icon itemicon',
            'alt' => '',
        ]);
        $data->improveicon = $output->pix_icon('t/messages', get_string('edit'), 'core', [
            'class' => 'icon itemicon',
            'alt' => '',
        ]);

        return $data;
    }
}
