<?php
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
 *
 * @package    mod_msociograma
 * @copyright  2009 - 2020 Marco Alarcón
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//msociograma is based in CESC sociogram, designed by Collell, J. and Escudé, C.

//This file corresponds to the lib.php. 
//Contains some of most important functions


defined('MOODLE_INTERNAL') || die();

/**
 * Example constant, you probably want to remove this :-)
 */
define('msociograma_ULTIMATE_ANSWER', 42);

/* Moodle core API */

/**
 * Returns the information on whether the module supports a feature
 *
 * See {@link plugin_supports()} for more info.
 *
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function msociograma_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return true;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_SHOW_DESCRIPTION:        return true;

        default: return null;
    }
}

/**
 * Saves a new instance of the msociograma into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $msociograma Submitted data from the form in mod_form.php
 * @param mod_msociograma_mod_form $mform The form instance itself (if needed)
 * @return int The id of the newly inserted msociograma record
 */
/**
 * Returns all other caps used in module
 * @return array
 */
function msociograma_get_extra_capabilities() {
    return array('moodle/site:accessallgroups');
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function msociograma_reset_userdata($data) {
    return array();
}

/**
 * List of view style log actions
 * @return array
 */
function msociograma_get_view_actions() {
    return array('view', 'view all');
}

/**
 * List of update style log actions
 * @return array
 */
function msociograma_get_post_actions() {
    return array('update', 'add');
}

/**
 * Add msociograma instance.
 * @param object $data
 * @param object $mform
 * @return int new msociograma instance id
 */
function msociograma_add_instance($data, $mform) {
    global $DB;

    $cmid        = $data->coursemodule;
   // $draftitemid = $data->files;

    $data->timemodified = time();
    $data->id = $DB->insert_record('msociograma', $data);

    // we need to use context now, so we need to make sure all needed info is already in db
    $DB->set_field('course_modules', 'instance', $data->id, array('id'=>$cmid));
    $context = context_module::instance($cmid);

   // if ($draftitemid) {
   //     file_save_draft_area_files($draftitemid, $context->id, 'mod_msociograma', 'content', 0, array('subdirs'=>true));
   // }

    return $data->id;
}

/**
 * Update msociograma instance.
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function msociograma_update_instance($data, $mform) {
    global $CFG, $DB;

    $cmid        = $data->coursemodule;
    
	if (isset($data->files))
	  $draftitemid = $data->files;

    $data->timemodified = time();
    $data->id           = $data->instance;
    $data->revision++;

    $DB->update_record('msociograma', $data);

    $context = context_module::instance($cmid);
    if ($draftitemid = file_get_submitted_draft_itemid('files')) {
        file_save_draft_area_files($draftitemid, $context->id, 'mod_msociograma', 'content', 0, array('subdirs'=>true));
    }

    return true;
}
/**
 * Delete msociograma instance.
 * @param int $id
 * @return bool true
 */
function msociograma_delete_instance($id) {
    global $DB;

    if (!$msociograma = $DB->get_record('msociograma', array('id'=>$id))) {
        return false;
    }

    // note: all context files are deleted automatically

    $DB->delete_records('msociograma', array('id'=>$msociograma->id));

    return true;
}
/**
 * Return use outline
 * @param object $course
 * @param object $user
 * @param object $mod
 * @param object $msociograma
 * @return object|null
 */
function msociograma_user_outline($course, $user, $mod, $msociograma) {
    global $DB;

    if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'msociograma',
                                              'action'=>'view', 'info'=>$msociograma->id), 'time ASC')) {

        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $result = new stdClass();
        $result->info = get_string('numviews', '', $numviews);
        $result->time = $lastlog->time;

        return $result;
    }
    return NULL;
}

/**
 * Return use complete
 * @param object $course
 * @param object $user
 * @param object $mod
 * @param object $msociograma
 */
function msociograma_user_complete($course, $user, $mod, $msociograma) {
    global $CFG, $DB;

    if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'msociograma',
                                              'action'=>'view', 'info'=>$msociograma->id), 'time ASC')) {
        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $strmostrecently = get_string('mostrecently');
        $strnumviews = get_string('numviews', '', $numviews);

        echo "$strnumviews - $strmostrecently ".userdate($lastlog->time);

    } else {
        print_string('neverseen', 'msociograma');
    }
}


/**
 * Lists all browsable file areas
 *
 * @package  mod_msociograma
 * @category files
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @return array
 */
function msociograma_get_file_areas($course, $cm, $context) {
    $areas = array();
    $areas['content'] = get_string('msociogramacontent', 'msociograma');

    return $areas;
}

/**
 * File browsing support for msociograma module content area.
 *
 * @package  mod_msociograma
 * @category files
 * @param file_browser $browser file browser instance
 * @param array $areas file areas
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param int $itemid item ID
 * @param string $filepath file path
 * @param string $filename file name
 * @return file_info instance or null if not found
 */
function msociograma_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    global $CFG;


    if ($filearea === 'content') {
        if (!has_capability('mod/msociograma:view', $context)) {
            return NULL;
        }
        $fs = get_file_storage();

        $filepath = is_null($filepath) ? '/' : $filepath;
        $filename = is_null($filename) ? '.' : $filename;
        if (!$storedfile = $fs->get_file($context->id, 'mod_msociograma', 'content', 0, $filepath, $filename)) {
            if ($filepath === '/' and $filename === '.') {
                $storedfile = new virtual_root_file($context->id, 'mod_msociograma', 'content', 0);
            } else {
                // not found
                return null;
            }
        }

        require_once("$CFG->dirroot/mod/msociograma/locallib.php");
        $urlbase = $CFG->wwwroot.'/pluginfile.php';

        // students may read files here
        $canwrite = has_capability('mod/msociograma:managefiles', $context);
        return new msociograma_content_file_info($browser, $context, $storedfile, $urlbase, $areas[$filearea], true, true, $canwrite, false);
    }

    // note: msociograma_intro handled in file_browser automatically

    return null;
}


/**
 * Return a list of page types
 * @param string $pagetype current page type
 * @param stdClass $parentcontext Block's parent context
 * @param stdClass $currentcontext Current context of block
 */
function msociograma_page_type_list($pagetype, $parentcontext, $currentcontext) {
    $module_pagetype = array('mod-msociograma-*'=>get_string('page-mod-msociograma-x', 'msociograma'));
    return $module_pagetype;
}

/**
 * Export msociograma resource contents
 *
 * @return array of file content
 */
function msociograma_export_contents($cm, $baseurl) {
    global $CFG, $DB;
    $contents = array();
    $context = context_module::instance($cm->id);
    $msociograma = $DB->get_record('msociograma', array('id'=>$cm->instance), '*', MUST_EXIST);

    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'mod_msociograma', 'content', 0, 'sortorder DESC, id ASC', false);

    foreach ($files as $fileinfo) {
        $file = array();
        $file['type'] = 'file';
        $file['filename']     = $fileinfo->get_filename();
        $file['filepath']     = $fileinfo->get_filepath();
        $file['filesize']     = $fileinfo->get_filesize();
        $file['fileurl']      = file_encode_url("$CFG->wwwroot/" . $baseurl, '/'.$context->id.'/mod_msociograma/content/'.$msociograma->revision.$fileinfo->get_filepath().$fileinfo->get_filename(), true);
        $file['timecreated']  = $fileinfo->get_timecreated();
        $file['timemodified'] = $fileinfo->get_timemodified();
        $file['sortorder']    = $fileinfo->get_sortorder();
        $file['userid']       = $fileinfo->get_userid();
        $file['author']       = $fileinfo->get_author();
        $file['license']      = $fileinfo->get_license();
        $contents[] = $file;
    }

    return $contents;
}

/**
 * Register the ability to handle drag and drop file uploads
 * @return array containing details of the files / types the mod can handle
 */
function msociograma_dndupload_register() {
    return array('files' => array(
                     array('extension' => 'zip', 'message' => get_string('dnduploadmakemsociograma', 'mod_msociograma'))
                 ));
}

/**
 * Handle a file that has been uploaded
 * @param object $uploadinfo details of the file / content that has been uploaded
 * @return int instance id of the newly created mod
 */
function msociograma_dndupload_handle($uploadinfo) {
    global $DB, $USER;

    // Gather the required info.
    $data = new stdClass();
    $data->course = $uploadinfo->course->id;
    $data->name = $uploadinfo->displayname;
    $data->intro = '<p>'.$uploadinfo->displayname.'</p>';
    $data->introformat = FORMAT_HTML;
    $data->coursemodule = $uploadinfo->coursemodule;
    $data->files = null; // We will unzip the file and sort out the contents below.

    $data->id = msociograma_add_instance($data, null);

    // Retrieve the file from the draft file area.
    $context = context_module::instance($uploadinfo->coursemodule);
    file_save_draft_area_files($uploadinfo->draftitemid, $context->id, 'mod_msociograma', 'temp', 0, array('subdirs'=>true));
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'mod_msociograma', 'temp', 0, 'sortorder', false);
    // Only ever one file - extract the contents.
    $file = reset($files);

    $success = $file->extract_to_storage(new zip_packer(), $context->id, 'mod_msociograma', 'content', 0, '/', $USER->id);
    $fs->delete_area_files($context->id, 'mod_msociograma', 'temp', 0);

    if ($success) {
        return $data->id;
    }

    $DB->delete_records('msociograma', array('id' => $data->id));
    return false;
}

//this function build a combobox and the icon in every cell 
function showcombox($num)
{
  global $CFG, $USER,$COURSE,$DB;
  
  $currentgroup = get_current_group($COURSE->id);

   
  $sql="SELECT u.* FROM {$CFG->prefix}user u INNER JOIN {$CFG->prefix}groups_members m 
		ON u.id=m.userid 
			WHERE m.groupid=".$currentgroup."
				ORDER BY u.lastname ASC";
			
  
  if (!$log = $DB->get_records_sql($sql, array('%'))){
 
	print_error('nogroupsdefined', 'msociograma');
  } else {
    $id   = optional_param('id', 0, PARAM_INT); // Course Module ID
    $curs=$COURSE->id;
    $act=$id;

    //query for put the answer in the combobox if exist.
   $sql2="SELECT u.lastname AS llinatge,u.firstname AS nom, m.id_stu AS origen ,m.stu_ans AS desti 
		FROM({$CFG->prefix}msociograma_answers m )LEFT JOIN ({$CFG->prefix}user u)
			ON m.stu_ans=u.id
				WHERE question=".$num." 
					AND m.id_stu=".$USER->id."
						 AND m.grup=".$currentgroup." 
							AND course=".$curs." 
								AND activity=".$act."  
							 		 ORDER BY m.id DESC, u.username LIMIT 1;";


  if (!$log2 = $DB->get_records_sql($sql2, array('%'))){
    echo'<IMG SRC="./images/no_record.gif"  ALIGN=LEFT WIDTH=20 HEIGHT=20 BORDER=0>';
  }else{
    echo'<IMG SRC="./images/ok.gif"  ALIGN=LEFT WIDTH=20 HEIGHT=20 BORDER=0>';
  }

    if (isset($_GET['hide']))
	if ($_GET['hide']=='off'){
	  echo '<FORM NAME = "formulario'.$num.'" ACTION="view.php?id='.$id.'&page=dades&hide=off" METHOD="post">';
	} else{
        echo '<FORM NAME = "formulario'.$num.'" ACTION="view.php?id='.$id.'&page=dades&hide=on" METHOD="post">';
	}

    //parameters submit when is selected a student
	echo '<INPUT TYPE = HIDDEN NAME = id VALUE = "'.required_param('id', PARAM_INT).'">';
	echo '<INPUT TYPE = HIDDEN NAME = question VALUE = "'.$num.'">';
    echo '<INPUT TYPE = HIDDEN NAME = id_stu VALUE = "'.$USER->id.'">';
	echo '<INPUT TYPE = HIDDEN NAME = course VALUE = "'.$COURSE->id.'">';
	echo '<INPUT TYPE = HIDDEN NAME = grup VALUE = "'.$currentgroup.'">';
    
    //combobox paramenters
    echo '<SELECT NAME = "answer'.$num.'" onchange = "this.form.submit()">';
	echo ' <OPTION VALUE="1">';
	
	if (!$log2 = $DB->get_records_sql($sql2, array('%'))){
	  echo get_string('selectstudent', 'msociograma'); //no answer
	} else {
        foreach($log2 as $entrada_log2){
          $llinatge = $entrada_log2->llinatge;
          $nom = $entrada_log2->nom;
        }//foreach
        if($entrada_log2->desti !=10000){ 
		
		if (isset($_GET['hide']))
	    if (($_GET['hide']!='on')&&($_GET['hide']!='')){
             echo $llinatge.', '.$nom; //this is the answer
          } else {
	      echo '*****************'; // hide answers		
          }//if
        } else {
	    echo get_string('nobody', 'msociograma'); //nobody selected
	  }//if
       }//if
	echo '</OPTION>';

	//list within
	foreach($log as $entrada_log){
	  echo '<OPTION VALUE="'.$entrada_log->id.'">';
	  echo $entrada_log->lastname.', '.$entrada_log->firstname;
	  echo '</OPTION>';
	}	
	  echo '<OPTION VALUE="10000">'.get_string('nobody', 'msociograma') .'</OPTION>'; //last item in the list for nobody
	echo'</SELECT>'; //end of combobox paramenters
  echo '</FORM>';		
  }//if
}//function showcombo($num)

function deleteRecordsetMsociograma($delID, $table){
  global $DB;
  $DB->delete_records($table, array('id' => $delID));
}//function

