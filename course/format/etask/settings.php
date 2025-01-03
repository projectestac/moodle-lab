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
 * Settings for the eTask format plugin.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $url = new moodle_url('/admin/course/resetindentation.php', ['format' => 'etask']);
    $link = html_writer::link($url, get_string('resetindentation', 'admin'));
    $settings->add(new admin_setting_configcheckbox(
        'format_etask/indentation',
        new lang_string('indentation', 'format_etask'),
        new lang_string('indentation_help', 'format_etask').'<br />'.$link,
        1
    ));
}

$settings->add(
    new admin_setting_configtextarea(
        'format_etask/registered_due_date_modules',
        get_string('registeredduedatemodules', 'format_etask'),
        get_string('registeredduedatemodules_help', 'format_etask'),
        'assign:duedate, forum:duedate, lesson:deadline, lucson:deadline, quiz:timeclose, scorm:timeclose, workshop:submissionend'
    )
);
