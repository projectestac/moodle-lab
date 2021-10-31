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
 * Defines the editing form for the drawing question type.
 *
 * @package qtype
 * @subpackage drawing
 * @copyright ETH Zurich LET <amr.hourani@id.ethz.ch>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/renderer.php');
class qtype_drawing_edit_form extends question_edit_form {

    /**
     * (non-PHPdoc).
     *
     * @see myquestion_edit_form::qtype()
     */
    public function qtype() {
        return 'drawing';
    }

    /**
     * Build the form definition.
     * This adds all the form fields that the default question type supports.
     * If your question type does not support all these fields, then you can
     * override this method and remove the ones you don't want with $mform->removeElement().
     */
    protected function definition() {
        global $COURSE, $CFG, $DB;
        $qtype = $this->qtype();
        $langfile = "qtype_$qtype";
        $mform = $this->_form;
        // Standard fields at the start of the form.
        $mform->addElement('header', 'categoryheader', get_string('category', 'question'));
        if (!isset($this->question->id)) {
            if (!empty($this->question->formoptions->mustbeusable)) {
                $contexts = $this->contexts->having_add_and_use();
            } else {
                $contexts = $this->contexts->having_cap('moodle/question:add');
            }
            // Adding question.
            $mform->addElement('questioncategory', 'category', get_string('category', 'question'),
                            array('contexts' => $contexts));
        } else if (!($this->question->formoptions->canmove || $this->question->formoptions->cansaveasnew)) {
            // Editing question with no permission to move from category.
            $mform->addElement('questioncategory', 'category', get_string('category', 'question'),
                            array('contexts' => array($this->categorycontext)));
            $mform->addElement('hidden', 'usecurrentcat', 1);
            $mform->setType('usecurrentcat', PARAM_BOOL);
            $mform->setConstant('usecurrentcat', 1);
        } else if (isset($this->question->formoptions->movecontext)) {
            // Moving question to another context.
            $mform->addElement('questioncategory', 'categorymoveto', get_string('category', 'question'),
                            array('contexts' => $this->contexts->having_cap('moodle/question:add')));
            $mform->addElement('hidden', 'usecurrentcat', 1);
            $mform->setType('usecurrentcat', PARAM_BOOL);
            $mform->setConstant('usecurrentcat', 1);
        } else {
            // Editing question with permission to move from category or save as new q.
            $currentgrp = array();
            $currentgrp[0] = $mform->createElement('questioncategory', 'category', get_string('categorycurrent', 'question'),
                                                array('contexts' => array($this->categorycontext)));
            if ($this->question->formoptions->canedit || $this->question->formoptions->cansaveasnew) {
                // Not move only form.
                $currentgrp[1] = $mform->createElement('checkbox', 'usecurrentcat', '',
                                                    get_string('categorycurrentuse', 'question'));
                $mform->setDefault('usecurrentcat', 1);
            }
            $currentgrp[0]->freeze();
            $currentgrp[0]->setPersistantFreeze(false);
            $mform->addGroup($currentgrp, 'currentgrp', get_string('categorycurrent', 'question'), null, false);
            $mform->addElement('questioncategory', 'categorymoveto', get_string('categorymoveto', 'question'),
                            array('contexts' => array($this->categorycontext)));
            if ($this->question->formoptions->canedit || $this->question->formoptions->cansaveasnew) {
                // Not move only form.
                $mform->disabledIf('categorymoveto', 'usecurrentcat', 'checked');
            }
        }
        $mform->addElement('header', 'generalheader', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('tasktitle', 'qtype_drawing'),
                        array('size' => 50, 'maxlength' => 255));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addElement('text', 'defaultmark', get_string('maxpoints', 'qtype_drawing'), array('size' => 7));
        $mform->setType('defaultmark', PARAM_FLOAT);
        $mform->setDefault('defaultmark', 1);
        $mform->addRule('defaultmark', null, 'required', null, 'client');
        $mform->addElement('editor', 'questiontext', get_string('stem', 'qtype_drawing'),
                        array('rows' => 15), $this->editoroptions);
        $mform->setType('questiontext', PARAM_RAW);
        $mform->addRule('questiontext', null, 'required', null, 'client');
        $mform->setDefault('questiontext', array('text' => get_string('enterstemhere', 'qtype_drawing')));
        $mform->addElement('editor', 'generalfeedback', get_string('generalfeedback', 'question'),
                        array('rows' => 10), $this->editoroptions);
        $mform->setType('generalfeedback', PARAM_RAW);
        $mform->addHelpButton('generalfeedback', 'generalfeedback', 'qtype_drawing');
        // Any questiontype specific fields.
        $this->definition_inner($mform);

        if (core_tag_tag::is_enabled('core_question', 'question')) {
            $mform->addElement('header', 'tagshdr', get_string('tags', 'tag'));
            $mform->addElement('tags', 'tags', get_string('tags'),
                            array('itemtype' => 'question', 'component' => 'core_question'));
        }

        $this->add_interactive_settings(true, true);
        if (!empty($this->question->id)) {
            $mform->addElement('header', 'createdmodifiedheader', get_string('createdmodifiedheader', 'question'));
            $a = new stdClass();
            if (!empty($this->question->createdby)) {
                $a->time = userdate($this->question->timecreated);
                $a->user = fullname(
                                    $DB->get_record('user',
                                                    array('id' => $this->question->createdby)));
            } else {
                $a->time = get_string('unknown', 'question');
                $a->user = get_string('unknown', 'question');
            }
            $mform->addElement('static', 'created', get_string('created', 'question'), get_string('byandon', 'question', $a));
            if (!empty($this->question->modifiedby)) {
                $a = new stdClass();
                $a->time = userdate($this->question->timemodified);
                $a->user = fullname(
                                    $DB->get_record('user',
                                                    array('id' => $this->question->modifiedby)));
                $mform->addElement('static', 'modified', get_string('modified', 'question'), get_string('byandon', 'question', $a));
            }
        }

        global $PAGE;
        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'updatebutton', get_string('savechangesandcontinueediting', 'question'));
        if ($this->can_preview()) {
            $previewlink = $PAGE->get_renderer('core_question')->question_preview_link($this->question->id, $this->context, true);
            $buttonarray[] = $mform->createElement('static', 'previewlink', '', $previewlink);
        }
        $mform->addGroup($buttonarray, 'updatebuttonar', '', array(' '), false);
        $mform->closeHeaderBefore('updatebuttonar');
        if ((!empty($this->question->id)) &&
                        (!($this->question->formoptions->canedit || $this->question->formoptions->cansaveasnew))) {
            $mform->hardFreezeAllVisibleExcept(array('categorymoveto', 'buttonar', 'currentgrp'));
        }
        $this->add_hidden_fields();
        $mform->addElement('hidden', 'qtype');
        $mform->setType('qtype', PARAM_ALPHA);
        $mform->addElement('hidden', 'makecopy');
        $mform->setType('makecopy', PARAM_ALPHA);

        $mform->addElement('hidden', 'backgrounduploaded');
        $mform->setType('backgrounduploaded', PARAM_INT);
        $mform->setDefault('backgrounduploaded', 0);

        $this->add_action_buttons();
    }

    protected function definition_inner($mform) {
        global $PAGE, $CFG, $USER, $COURSE;

        $usercontext = context_user::instance($USER->id);
        $bgimagearray = null;
        $canvastextareapreexistingpnswer = '';
        $nobackgroundimageselectedyetstyle = '';
        $eraserhtmlparams = '';
        $this->editoroptions['changeformat'] = 1;

        $mform->addElement('header', 'drawsetting', get_string('drawing_settings', 'qtype_drawing'));
        $mform->setExpanded('drawsetting');

        $drawingconfig = get_config('qtype_drawing');

        $canvassizearray = array();
        $canvassizearray[] = & $mform->createElement('text', 'backgroundwidth', get_string('backgroundwidth', 'qtype_drawing'),
                        array('size' => 4, 'maxlength' => 5, 'id' => 'qtype_drawing_backgroundwidth'));
        $canvassizearray[] = & $mform->createElement('static', '', '', 'px  &nbsp;  &nbsp;', 'px &nbsp; &nbsp;');
        $canvassizearray[] = & $mform->createElement('text', 'backgroundheight', get_string('backgroundheight', 'qtype_drawing'),
                        array('size' => 4, 'maxlength' => 5, 'id' => 'qtype_drawing_backgroundheight'));
        $canvassizearray[] = & $mform->createElement('static', '', '', 'px  &nbsp;  &nbsp;', 'px &nbsp; &nbsp;');
        $canvassizearray[] = & $mform->createElement('checkbox', 'preservear', get_string('preserveaspectratio', 'qtype_drawing'));


        $mform->addGroup($canvassizearray, 'buttonarx', get_string('canvassize', 'qtype_drawing'), array(' '), false);
        $mform->closeHeaderBefore('drawsetting');
        $mform->setType('backgroundwidth', PARAM_INT);
        $mform->setDefault('backgroundwidth', $drawingconfig->defaultcanvaswidth);
        $mform->setType('backgroundheight', PARAM_INT);
        $mform->setDefault('backgroundheight', $drawingconfig->defaultcanvasheight);
        $mform->setType('preservear', PARAM_INT);
        $mform->setDefault('preservear', 1);

        if (isset($drawingconfig->allowteachertochosemode) && $drawingconfig->allowteachertochosemode == 1) {
            $options = array(1 => get_string('basicmode', 'qtype_drawing'), 2 => get_string('advancedmode', 'qtype_drawing'));
            $mform->addElement('select', 'drawingmode', get_string('drawingmode', 'qtype_drawing'), $options, array('onchange' => 'document.getElementById("id_alloweraser").checked = false;'));
            $mform->addHelpButton('drawingmode', 'drawingmode', 'qtype_drawing');
        } else {
            $mform->addElement('hidden', 'drawingmode', 1);
        }
        if (isset($drawingconfig->enableeraser) && $drawingconfig->enableeraser == 1) {
            $mform->addElement('checkbox', 'alloweraser', get_string('alloweraser', 'qtype_drawing'));
            $mform->disabledIf('alloweraser', 'drawingmode', 'eq', 2);
        } else {
            $mform->addElement('hidden', 'alloweraser', 0);
        }

        $mform->setType('alloweraser', PARAM_INT);
        $mform->setDefault('alloweraser', 0);
        $mform->setType('drawingmode', PARAM_INT);
        $mform->setDefault('drawingmode', 1);

        $mform->addElement('html', '<div style="display:none">'); // Hide until version 2.
        $mform->addElement('checkbox', 'allowstudentimage', get_string('allowstudentimage', 'qtype_drawing'), '&nbsp;');
        $mform->addHelpButton('allowstudentimage', 'allowstudentimage', 'qtype_drawing');
        $mform->setType('allowstudentimage', PARAM_INT);
        $mform->setDefault('allowstudentimage', 0);
        $mform->addElement('html', '</div>'); // Hide until version 2.

        $bgimagearray = qtype_drawing_renderer::get_image_for_files($usercontext->id, 'user', 'draft',
                                                                    file_get_submitted_draft_itemid('qtype_drawing_image_file'));
        if ($bgimagearray !== null) {
            $nobackgroundimageselectedyetstyle = 'style="display: none;"';
            $mform->addElement('hidden', 'pre_existing_background_data', $bgimagearray[1],
                            array('id' => 'pre_existing_background_data'));
            $mform->setType('pre_existing_background_data', PARAM_RAW);
        } else {
            if (property_exists($this->question, 'id') === true) {
                $question = $this->question;
                if (property_exists($question, 'contextid') === false || property_exists($question, 'answers') === false) {
                    $question = question_bank::load_question($question->id, false);
                }
                // Question already exists! We are in edit mode.
                // --------------------------------------------------------
                // This is in case duplicates are requested to be made.
                // So that the saving code in question.php would know there was a pre-existing question.
                $mform->addElement('hidden', 'pre_existing_question_id', $question->id);
                $mform->setType('pre_existing_question_id', PARAM_INT);
                // --------------------------------------------------------

                $bgimagearray = qtype_drawing_renderer::get_image_for_question($question);
                if($bgimagearray === null || !isset($bgimagearray)){
                   $bgimagearray = array(null, null, null);
                }
                // This is the structure of the array:
                // 0 image dataURL string.
                // 1 width.
                // 2 height.
                // 3 filename string.
                $mform->addElement('hidden', 'pre_existing_background_data', $bgimagearray[1],
                                array('id' => 'pre_existing_background_data'));
                $mform->setType('pre_existing_background_data', PARAM_RAW);

                if ($bgimagearray[0] == 'svg') {
                    $finalbackground = 'data:image/svg+xml,' . rawurlencode($bgimagearray[1]);
                } else {
                    $finalbackground = $bgimagearray[1];
                }
                $nobackgroundimageselectedyetstyle = 'style="display: none;"';

                // This will be a UI aid to make sure the user knows a file has been chosen rather than just displaying the empty
                // file picker widget
                // which doesn't indicate that there is already a background image file associated with the question.
                $mform->addElement('header', 'qtype_drawing_drawing_background_image_selected',
                                get_string('drawing_background_image', 'qtype_drawing'));
                $mform->addElement('html',
                                "<div class=\"fitem\"><div class=\"fitemtitle\">" .
                                 get_string("selected_background_image_filename", "qtype_drawing") .
                                 "</div><div class=\"felement\">
                                  <input type=\"button\" class=\"fp-btn-choose\" value=\"Choose a different file...\"
                                   name=\"qtype_drawing_image_filechoose_another\">
                                  <br /><br /><img src='$finalbackground' class=\"img-thumbnail\"></div></div>");
            }
        }

        // File picker.
        $mform->addElement('header', 'qtype_drawing_drawing_background_image',
                        get_string('drawing_background_image', 'qtype_drawing'));

        $mform->addElement('filepicker', 'qtype_drawing_image_file', get_string('file'), null,
                        array('maxbytes' => $COURSE->maxbytes, 'maxfiles' => 1,
                            'accepted_types' => array('.png', '.jpg', '.jpeg', '.gif', '.svg')));

        $mform->setExpanded('qtype_drawing_drawing_background_image');


    }

    public function js_call() {
        $drawingconfig = get_config('qtype_drawing');
        global $PAGE;
        $PAGE->requires->yui_module('moodle-qtype_drawing-form', 'Y.Moodle.qtype_drawing.form.qtype_drawing_size_listener',
                                    array($drawingconfig->defaultcanvaswidth, $drawingconfig->defaultcanvasheight));
        if (isset($this->question->id)) {
            $qid = $this->question->id;
        } else {
            $qid = 0;
        }
        qtype_drawing_renderer::translate_to_js($PAGE);
        $PAGE->requires->jquery();
        if ($qid == 0) {
            $PAGE->requires->yui_module('moodle-qtype_drawing-form', 'Y.Moodle.qtype_drawing.form.newquestion', array());
        } else {
            $PAGE->requires->yui_module('moodle-qtype_drawing-form', 'Y.Moodle.qtype_drawing.form.editquestion',
                                        array($qid, $this->question->options->backgroundheight,
                                            $this->question->options->backgroundwidth));
        }
    }

    protected function data_preprocessing($question) {
        global $PAGE;
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_answers($question);
        $question = $this->data_preprocessing_hints($question);
        $this->js_call();
        return $question;
    }

    public function validation($data, $files) {
        global $USER;
        $errors = parent::validation($data, $files);
        return $errors;
    }
}
