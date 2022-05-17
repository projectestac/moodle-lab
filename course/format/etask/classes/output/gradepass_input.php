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
 * Class containing data for gradepass input form content.
 *
 * @package   format_etask
 * @copyright 2021, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace format_etask\output;


use html_writer;
use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;

/**
 * Class to prepare a gradepass input form for display.
 *
 * @package   format_etask
 * @copyright 2021, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gradepass_input implements renderable, templatable {

    /** @var moodle_url */
    private $url;

    /** @var string */
    private $name;

    /** @var string */
    private $value;

    /** @var array */
    public $attributes = [];

    /** @var string */
    private $label = '';

    /** @var array  */
    private $labelattributes = [];

    /** @var string */
    private $method = 'get';

    /** @var string */
    private $formid = null;

    /**
     * Single input constructor.
     *
     * @param moodle_url $url
     * @param string $name
     * @param string $value
     * @param string $formid
     */
    public function __construct(moodle_url $url, string $name, ?string $value, $formid = null) {
        $this->url = $url;
        $this->name = $name;
        $this->value = $value;
        $this->formid = $formid;
    }

    /**
     * Sets input's label.
     *
     * @param string $label
     * @param array $attributes
     */
    public function set_label(string $label, array $attributes = []) {
        $this->label = $label;
        $this->labelattributes = $attributes;

    }

    /**
     * Export for template.
     *
     * @param renderer_base $output
     *
     * @return stdClass
     */
    public function export_for_template(renderer_base $output): stdClass {
        $attributes = $this->attributes;

        $data = new stdClass();
        $data->name = $this->name;
        $data->value = $this->value;
        $data->method = $this->method;
        $data->action = $this->method === 'get' ? $this->url->out_omit_querystring(true) : $this->url->out_omit_querystring();
        $data->label = $this->label;
        $data->formid = !empty($this->formid) ? $this->formid : html_writer::random_id('single_input_f');
        $data->id = $attributes['id'] ?? html_writer::random_id('single_input');

        // Unset attributes that are already predefined in the template.
        unset($attributes['id']);
        unset($attributes['class']);
        unset($attributes['name']);

        // Map the attributes.
        $data->attributes = array_map(function($key) use ($attributes) {
            return ['name' => $key, 'value' => $attributes[$key]];
        }, array_keys($attributes));

        // Form parameters.
        $params = $this->url->params();
        if ($this->method === 'post') {
            $params['sesskey'] = sesskey();
        }
        $data->params = array_map(function($key) use ($params) {
            return ['name' => $key, 'value' => $params[$key]];
        }, array_keys($params));

        // Label attributes.
        $data->labelattributes = [];
        // Unset label attributes that are already in the template.
        unset($this->labelattributes['for']);
        // Map the label attributes.
        foreach ($this->labelattributes as $key => $value) {
            $data->labelattributes[] = ['name' => $key, 'value' => $value];
        }

        return $data;
    }
}
