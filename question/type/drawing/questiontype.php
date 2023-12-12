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
 * Question type class for the drawing question type.
 *
 * @package    qtype
 * @subpackage drawing
 * @copyright  ETHZ LET <amr.hourani@id.ethz.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/question/type/drawing/question.php');
require_once(dirname(__FILE__) . '/renderer.php');

/**
 * The drawing question type.
 *
 * @copyright  ETHZ LET <amr.hourani@id.ethz.chh>
 * @license    http://opensource.org/licenses/BSD-3-Clause
 */
class qtype_drawing extends question_type {
    public function extra_question_fields() {
        return array('qtype_drawing', 'drawingmode', 'backgrounduploaded',
                     'backgroundwidth', 'backgroundheight', 'preservear', 'drawingoptions', 'alloweraser');
    }

    public function questionid_column_name() {
        return 'questionid';
    }

    public function move_files($questionid, $oldcontextid, $newcontextid) {
        parent::move_files($questionid, $oldcontextid, $newcontextid);
        $fs = get_file_storage();
        $fs->move_area_files_to_new_context($oldcontextid, $newcontextid, 'qtype_drawing', 'qtype_drawing_image_file', $questionid);
    }

    protected function delete_files($questionid, $contextid) {
        parent::delete_files($questionid, $contextid);
        $this->delete_files_in_answers($questionid, $contextid, true);
        $this->delete_files_in_hints($questionid, $contextid);
    }
    /**
     * Custom method for deleting drawing questions.
     *
     * (non-PHPdoc)
     *
     * @see question_type::delete_question()
     */
    public function delete_question($questionid, $contextid) {
        global $DB;
        $originalrecord = $DB->get_record('qtype_drawing', array('questionid' => $questionid));
        $DB->delete_records('qtype_drawing', array('questionid' => $questionid));
        $DB->delete_records('qtype_drawing_annotations', array('questionid' => $originalrecord->id));
        parent::delete_question($questionid, $contextid);
    }
    public function save_question_options($question) {
        global $DB, $USER;
        $context = $question->context;
        $drawingconfig = get_config('qtype_drawing');
        $result = new stdClass();
        // Insert all the new options.
        $options = $DB->get_record('qtype_drawing',
        array('questionid' => $question->id
        ));
        if (!$question->drawingmode) {
            $question->drawingmode = 1;
        }
        if (!$options) {
            $options = new stdClass();
            $options->questionid = $question->id;
            $options->drawingmode = 1;
            $options->allowstudentimage = 0;
            $options->backgrounduploaded = 0;
            $options->backgroundwidth = $drawingconfig->defaultcanvaswidth;
            $options->backgroundheight = $drawingconfig->defaultcanvasheight;
            $options->preservear = 1;
            $options->drawingoptions = '';
            $options->alloweraser = 0;
            $options->id = $DB->insert_record('qtype_drawing', $options);
        }
        $options->drawingmode = $question->drawingmode;
        if (isset($question->allowstudentimage)) {
            $options->allowstudentimage = $question->allowstudentimage;
        }
        $options->backgrounduploaded = $question->backgrounduploaded;
        $options->backgroundwidth = $question->backgroundwidth;
        $options->backgroundheight = $question->backgroundheight;
        if (!isset($question->preservear)) {
            $question->preservear = 0;
        }
        $options->preservear = $question->preservear;
        if(!isset($question->alloweraser)){
            $question->alloweraser = 0;
        }
        $options->alloweraser = $question->alloweraser;

        $DB->update_record('qtype_drawing', $options);
        $this->save_hints($question);

        // Save the background image.
        if (isset($question->qtype_drawing_image_file)) {
            $fs = get_file_storage();
            $usercontext = context_user::instance($USER->id);
            $draftfiles = $fs->get_area_files($usercontext->id, 'user', 'draft', $question->qtype_drawing_image_file, 'id');
            if (count($draftfiles) >= 2) {
                $fs->delete_area_files($question->context->id, 'qtype_drawing', 'qtype_drawing_image_file', $question->id);
                file_save_draft_area_files($question->qtype_drawing_image_file,
                $question->context->id, 'qtype_drawing', 'qtype_drawing_image_file',
                $question->id, array('subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1));
            } else {
                // No files have been indicated to be uploaded.
                // Check if this is an attempt to make a duplicate copy of this question.
                // And that this is not a simple EDIT, in which case we don't have to do anything to the background image file.
                if (property_exists($question, 'pre_existing_question_id') &&
                    $question->pre_existing_question_id != 0 && $question->pre_existing_question_id != $question->id) {
                    // This was an edit form which turned out to be a "Make copy".
                    // We need to copy over the background image of the old question into a new record.
                    // First fetch the old one.
                    $oldfiles   = $fs->get_area_files($question->context->id,
                                  'qtype_drawing', 'qtype_drawing_image_file', $question->pre_existing_question_id, 'id');

                    if (count($oldfiles) >= 2) {
                        // Files indeed exist.
                        foreach ($oldfiles as $oldfile) {
                            if ($oldfile->is_directory()) {
                                continue;
                            }
                            $newfile = array(
                              'contextid' => $question->context->id,
                              'component' => 'qtype_drawing',
                              'filearea' => 'qtype_drawing_image_file',
                              'itemid' => $question->id,
                              'filepath' => '/',
                              'filename' => $oldfile->get_filename());
                            $fs->create_file_from_storedfile($newfile, $oldfile);
                            continue;
                        }
                    }
                } else {
                    // Background image has been delibrately removed by teacher.
                    // Question updated but background was not touched?. If not, delete the bg.
                    if (!isset($question->pre_existing_question_id) || $question->pre_existing_question_id == 0) {
                        $fs = get_file_storage();
                        $fs->delete_area_files($question->context->id, 'qtype_drawing', 'qtype_drawing_image_file', $question->id);
                    }

                }
            }
        }

    }


    protected function initialise_question_instance(question_definition $question, $questiondata) {
        parent::initialise_question_instance($question, $questiondata);
        $this->initialise_question_answers($question, $questiondata);
    }

    public function get_random_guess_score($questiondata) {
        return 0;
    }

    public function get_possible_responses($questiondata) {
        $responses = array();
        $starfound = false;

        foreach ($questiondata->options->answers as $aid => $answer) {
            $responses[$aid] = new question_possible_response($answer->answer, $answer->fraction);

            if ($answer->answer === '*') {
                $starfound = true;
            }
        }
        if (!$starfound) {
            $responses[0] = new question_possible_response(
                    get_string('didnotmatchanyanswer', 'question'), 0);
        }

        $responses[null] = question_possible_response::no_response();

        return array($questiondata->id => $responses);
    }

    public function export_to_xml($question, qformat_xml $format, $extra=null) {
        $extraquestionfields = $this->extra_question_fields();
        if (!is_array($extraquestionfields)) {
            return false;
        }
        // Omit table name (question).
        array_shift($extraquestionfields);
        $expout = '';

        foreach ($extraquestionfields as $field) {
            $exportedvalue = $question->options->$field;
            if (!empty($exportedvalue) && htmlspecialchars($exportedvalue) != $exportedvalue) {
                $exportedvalue = "<![CDATA[" . $exportedvalue . "]]>";
            }
            $expout .= "    <$field>{$exportedvalue}</$field>\n";
        }

        $expout .= "    <bgimage>\n";
        $bgimagearray = qtype_drawing_renderer::get_image_for_question($question);
        if($bgimagearray === null || !isset($bgimagearray)){
            $bgimagearray = array(null, null, null);
        }

        $expout .= "        <filename>" . $bgimagearray[2] .  "</filename>\n";
        $expout .= "        <dataURL><![CDATA[" . $bgimagearray[1] . "]]></dataURL>\n";
        $expout .= "        <imagetype>" . $bgimagearray[0] .  "</imagetype>\n";
        $expout .= "    </bgimage>\n";

        foreach ($question->options->answers as $answer) {
            $percent = 100 * $answer->fraction;
            $expout .= "    <answer fraction=\"$percent\">\n";
            $expout .= $format->writetext($answer->answer, 3, false);
            $expout .= "      <feedback format=\"html\">\n";
            $expout .= $format->writetext($answer->feedback, 4, false);
            $expout .= "      </feedback>\n";
            $expout .= "    </answer>\n";
        }
        return $expout;
    }
    public function import_from_xml($data, $question, qformat_xml $format, $extra=null) {
        if (!isset($data['@']['type']) || $data['@']['type'] != 'drawing') {
            return false;
        }

        $question = $format->import_headers($data);
        $question->qtype = 'drawing';

        $question->shuffleanswers = array_key_exists('shuffleanswers', $format->getpath($data, array('#'), array()));

        $format->import_combined_feedback($question, $data, true);
        $format->import_hints($question, $data, true, false, $format->get_format($question->questiontextformat));

        // Save Extra Fields.
        $extraquestionfields = $this->extra_question_fields();
        if (!is_array($extraquestionfields)) {
            return false;
        }
        // Remove table name from array of extra fields (question name).
        array_shift($extraquestionfields);
        $question->options = new stdClass();
        foreach ($extraquestionfields as $field) {
            $question->$field = $format->getpath($data, array('#', $field, '0', '#'), 'does_not_exist');
            if ($question->$field === 'does_not_exist') {
                return false;
            }
        }
        // Save canvas background image file.
        $bgimagearray[0] = $format->getpath($data, array('#', 'bgimage', '0', '#', 'imagetype', '0', '#'), 'does_not_exist');
        $bgimagearray[1] = $format->getpath($data, array('#', 'bgimage', '0', '#', 'dataURL', '0', '#'), 'does_not_exist');
        $bgimagearray[2] = $format->getpath($data, array('#', 'bgimage', '0', '#', 'filename', '0', '#'), 'does_not_exist');

        if ($bgimagearray[1] === 'does_not_exist' || $bgimagearray[2] === 'does_not_exist') {
            return false;
        }
        if (trim($bgimagearray[1]) != '') {

            if ($bgimagearray[0] != 'svg') {
                  // Convert dataURL to binary.
                $imgbinarydata = base64_decode(qtype_drawing_renderer::strstr_after($bgimagearray[1], 'base64,'));
                // Make sure this is a valid image file we could read.
                if (($gdimg = imagecreatefromstring($imgbinarydata)) === false) {
                    return false;
                }
                // Clean up GD resource.
                imagedestroy($gdimg);
            } else {
                $imgbinarydata = $bgimagearray[1];
            }
               // Prepare draft file area which would later be really saved in.
               // ::save_question_options() when the question object already exists.
               global $USER;
               $fs = get_file_storage();
               $usercontext = context_user::instance($USER->id);
               $question->qtype_drawing_image_file = file_get_unused_draft_itemid();
               $record = new stdClass();
               $record->contextid = $usercontext->id;
               $record->component = 'user';
               $record->filearea  = 'draft';
               $record->itemid    = $question->qtype_drawing_image_file;
               $record->filename  = $bgimagearray[2];
               $record->filepath  = '/';
               $fs->create_file_from_string($record, $imgbinarydata);
        }
        return $question;
    }

}
