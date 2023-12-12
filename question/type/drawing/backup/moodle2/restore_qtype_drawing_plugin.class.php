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
 *
 * @package qtype
 * @subpackage drawing
 * @copyright ETHZ LET <amr.hourani@id.ethz.ch>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
class restore_qtype_drawing_plugin extends restore_qtype_plugin {

    /**
     * Returns the paths to be handled by the plugin at question level
     */
    protected function define_question_plugin_structure() {
        $paths = array();

        // This qtype uses question_answers, add them.
        $this->add_question_question_answers($paths);

        $paths[] = new restore_path_element('drawing', $this->get_pathfor('/drawing'));
        $paths[] = new restore_path_element('drawingannotation', $this->get_pathfor('/drawingannotations/drawingannotation'));

        return $paths; // And we return the interesting paths.
    }

    /**
     * Detect if the question is created or mapped.
     *
     * @return bool
     */
    protected function is_question_created() {
        $oldquestionid = $this->get_old_parentid('question');
        $questioncreated = (bool)$this->get_mappingid('question_created', $oldquestionid);

        return $questioncreated;
    }

    /**
     * Process the qtype/drawing element.
     */
    public function process_drawing($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        // Detect if the question is created or mapped.
        $oldquestionid = $this->get_old_parentid('question');
        $newquestionid = $this->get_new_parentid('question');

        // If the question has been created by restore, we need to create its
        // qtype_drawing_options too.
        if ($this->is_question_created()) {
            // Adjust some columns.
            $data->questionid = $newquestionid;
            // Insert record.
            $newitemid = $DB->insert_record('qtype_drawing', $data);
            // Create mapping (needed for decoding links).
            $this->set_mapping('qtype_drawing', $oldid, $newitemid);
        }
    }

    /**
     * Process the qtype/drawing drawingannotation element.
     */
    public function process_drawingannotation($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        // Detect if the question is created or mapped.
        $oldquestionid = $this->get_old_parentid('question');
        $newquestionid = $this->get_new_parentid('question');
        $questioncreated = (bool)$this->get_mappingid('question_created', $oldquestionid);

        // If the question has been created by restore, we need to create its
        // qtype_drawing_annotations too.
        if ($this->is_question_created()) {
            $data->questionid = $newquestionid;

            if (isset($data->annotatedfor) && $data->annotatedfor > 0) {
                $data->annotatedfor = $this->get_mappingid('user', $data->annotatedfor);
            }
            if (isset($data->annotatedby) && $data->annotatedby > 0) {
                $data->annotatedby = $this->get_mappingid('user', $data->annotatedby);
            }
            // Serve for old backups before annotation upgrade.
            if (isset($data->attemptcount) && $data->attemptcount == 0) {
                $data->attemptcount = 1;
            }

            // Insert record.
            $newitemid = $DB->insert_record('qtype_drawing_annotations', $data);
            // Create mapping (needed for decoding links).
            $this->set_mapping('qtype_drawing_annotations', $oldid, $newitemid);
        }
    }

    public function after_execute_question() {
        global $DB;
        // Now that all the questions have been restored, let's process
        // the created question_multianswer sequences (list of question ids).
    }

    public function recode_response($questionid, $sequencenumber, array $response) {
        if (array_key_exists('_order', $response)) {
            $response['_order'] = $this->recode_choice_order($response['_order']);
        }
        return $response;
    }

    /**
     * Recode the choice order as stored in the response.
     *
     * @param string $order
     *        the original order.
     * @return string the recoded order.
     */
    protected function recode_choice_order($order) {
        $neworder = array();
        foreach (explode(',', $order) as $id) {
            if ($newid = $this->get_mappingid('question_answer', $id)) {
                $neworder[] = $newid;
            }
        }
        return implode(',', $neworder);
    }

    /**
     * Return the contents of this qtype to be processed by the links decoder
     */
    public static function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('qtype_drawing', 'drawingoptions', 'qtype_drawing');
        $fields = array('annotation', 'notes');
        $contents[] = new restore_decode_content('qtype_drawing_annotations', $fields, 'qtype_drawing_annotations');

        return $contents;
    }
}
