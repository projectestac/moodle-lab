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
 * Post install script for the qtype_drawing plugin.
 *
 * @package   qtype_drawing
 * @copyright 2019 ETH Zurich (amr.hourani@let.ethz.ch)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
/**
 * Post install hook implementation for the qtype_drawing plugin.
 */
function xmldb_qtype_drawing_install() {
    global $OUTPUT;
    set_config('advancedmode', 0, 'qtype_drawing');
    set_config('allowteachertochosemode', 0, 'qtype_drawing');
    set_config('defaultcanvaswidth', 580, 'qtype_drawing');
    set_config('defaultcanvasheight', 400, 'qtype_drawing');
}
