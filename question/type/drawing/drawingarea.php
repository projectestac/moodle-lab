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
 * drawing question renderer class.
 *
 * @package qtype
 * @subpackage drawing
 * @copyright ETHZ LET <amr.hourani@id.ethz.ch>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Generates the output for drawing questions.
 *
 * @copyright ETHZ LET <amr.hourani@id.ethz.chh>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */
require_once('../../../config.php');
require_login();
$reducedmode = 0;
$id = required_param('id', PARAM_INT);
$readonly = optional_param('readonly', 0, PARAM_INT);
$stid = optional_param('stid', 0, PARAM_INT);
$attemptid = required_param('attemptid', PARAM_RAW_TRIMMED);
$uniquefieldnameattemptid = required_param('uniquefieldnameattemptid', PARAM_RAW_TRIMMED);
$sesskey = required_param('sesskey', PARAM_RAW);
$attemptcount = optional_param('attemptcount', 1, PARAM_INT);

if (!confirm_sesskey($sesskey)) {
    die("Session lost");
}
if (!$fhd = $DB->get_record('qtype_drawing', array('questionid' => $id))) {
    die("No such question.");
}
$reducedmode = 0;
$displaystyle = '';
$displaystylefull = ' style="display: inline-block;"';
if ($fhd->drawingmode == 1) {
     $reducedmode = 1;
     $displaystyle = ' display: none!important;';
     $displaystylefull = ' style="display: none!important;"';
}
require_once('../../../question/type/questiontypebase.php');
$question = question_bank::load_question_data($id);
$useupdateannotationjs = 0;
if (has_capability('mod/quiz:grade', context::instance_by_id($question->contextid)) && $readonly == 1) {
    $useupdateannotationjs = 1;
}
?>
<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="chrome=1"/>
      <link rel="stylesheet"
            href="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jgraduate/css/jPicker.css"
            type="text/css"/>
      <link rel="stylesheet"
            href="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jgraduate/css/jgraduate.css"
            type="text/css"/>
      <link rel="stylesheet"
            href="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>css/method-draw.css"
            type="text/css"/>
      <link rel="stylesheet"
            href="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>css/fonts.css"
            type="text/css"/>
      <meta name="apple-mobile-web-app-capable" content="yes"/>
      <meta name="viewport"
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
      <script>
         var qtype_drawing_str_comment = "<?php print_string("drawingcomment", "qtype_drawing");?>";
         var qtype_drawing_str_newconfirmationmsg = "<?php print_string("newconfirmationmsg", "qtype_drawing");?>";
         var qtype_drawing_str_eraseconfirmationmsg = "<?php print_string("eraseconfirmationmsg", "qtype_drawing");?>";
         var qtype_drawing_str_parsingerror = "<?php print_string("parsingerror", "qtype_drawing");?>";
         var qtype_drawing_str_ignorechanges = "<?php print_string("ignorechanges", "qtype_drawing");?>";
         var qtype_drawing_str_ok = "<?php print_string("ok", "qtype_drawing");?>";
         var qtype_drawing_str_cancel = "<?php print_string("cancel", "qtype_drawing");?>";
         var qtype_drawing_str_eyedroppertool = "<?php print_string("eyedroppertool", "qtype_drawing");?>";
         var qtype_drawing_str_shapelibrary = "<?php print_string("shapelibrary", "qtype_drawing");?>";
         var qtype_drawing_str_drag_markers = "<?php print_string("drawmarkers", "qtype_drawing");?>";
         var qtype_drawing_str_solidcolor = "<?php print_string("solidcolor", "qtype_drawing");?>";
         var qtype_drawing_str_lingrad = "<?php print_string("lingrad", "qtype_drawing");?>";
         var qtype_drawing_str_radgrad = "<?php print_string("radgrad", "qtype_drawing");?>";
         var qtype_drawing_str_new = "<?php print_string("new", "qtype_drawing");?>";
         var qtype_drawing_str_current = "<?php print_string("current", "qtype_drawing");?>";
         var qtype_drawing_str_viewgrid = "<?php print_string("viewgrid", "qtype_drawing");?>";
         var fhd_display_mode = "<?php echo $reducedmode;?>";
         var qtype_drawing_str_annotationsaved = "<?php print_string("annotationsaved", "qtype_drawing");?>";
         var qtype_drawing_str_saving = "<?php print_string("saving", "qtype_drawing");?>";
         var qtype_drawing_str_saveannotation = "<?php print_string("saveannotation", "qtype_drawing");?>";
         var questionid = <?php echo $id;?>;
         var sesskey = '<?php echo strip_tags($sesskey);?>';
         var stid = <?php echo $stid;?>;
         var attemptid = '<?php echo strip_tags($attemptid);?>';
         var attemptcount = '<?php echo $attemptcount;?>';
         var uniquefieldnameattemptid = '<?php echo strip_tags($uniquefieldnameattemptid);?>';
      </script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jquery.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/pathseg.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/touch.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/js-hotkeys/jquery.hotkeys.min.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>icons/jquery.svgicons.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jgraduate/jquery.jgraduate.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/contextmenu/jquery.contextMenu.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jquery-ui/jquery-ui-1.8.17.custom.min.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/browser.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/svgtransformlist.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/math.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/units.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/svgutils.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/sanitize.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/history.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/select.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/draw.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/path_polyfill.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/path.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/dialog.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/svgcanvas.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/method-draw.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jquery-draginput.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/contextmenu.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jgraduate/jpicker.min.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/mousewheel.js"></script>
        <?php
        if ($reducedmode == 0) {
        ?>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>extensions/ext-eyedropper.js"></script>
        <?php
        }
        ?>
      <script src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/d3.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>extensions/mtouch-events.js"></script>
        <?php
        if ($reducedmode == 1 && $fhd->alloweraser == 1) {
        ?>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>extensions/erase.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>extensions/ext-eraser.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/flatten.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/flat.js"></script>
        <?php
        }
        ?>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>src/simplify.js"></script>
        <?php
        if ($reducedmode == 0) {
        ?>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>extensions/ext-shapes.js"></script>
        <?php
        }
        ?>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>extensions/ext-grid.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/requestanimationframe.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/taphold.js"></script>
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/filesaver.js"></script>
      <link rel="stylesheet"
            href="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jquery-ui/jquery-ui.css">
      <script type="text/javascript"
              src="<?php echo $CFG->wwwroot.'/question/type/drawing/';?>lib/jquery-ui/jquery-ui.js"></script>
      <style>
         .qtype_drawing_selectedcolor{
            outline: 2px #4F80FF solid!important;
         }
            <?php
            if ($reducedmode == 1) {
            ?>
         #ruler_x {
             top:0px!important;
             height:45px;
         }
         #tools_top{
             top:0px!important;
         }
         #tools_left{
             top:0px!important;
             padding-top: 20px;
         }
         #rulers #ruler_corner {
             top:0px!important;
             height:45px!important;
         }
         #tools_top{
         overflow: auto;
         }
            <?php
            }
            ?>
         .qtype_drawing_color_divsquare{
             width:21px; height:21px;
             margin:4px; border:0px solid black; float: left;
             cursor: pointer; cursor: hand;
         }
         .qtype_drawing_color_pallette{
             width:21px; height:21px;
             margin:4px; border:0px solid black; float: left;
             cursor: pointer; cursor: hand;
         }
         .divSquarebrush{
             width:35%; height:20px; margin:4px; border:0px solid black;
             float: left;cursor: pointer; cursor: hand;
         }
         .qtype_drawing_size_pen{
             position: relative;
             width:25px; height:20px; margin:4px;
             border:0px solid white; float: left;cursor: pointer; cursor: hand;
         }
         .circleBase {
             border-radius: 50%;
             behavior: url(PIE.htc);
             margin: 5px;
         }
         .type1 {
             width: 3px;
             height: 3px;
             background: #ddd;
             position: absolute;
             top: 50%;
             left: 50%;
             transform: translate(-50%, -50%);
         }
         .type2 {
             width: 5px;
             height: 5px;
             background: #ddd;
             position: absolute;
             top: 50%;
             left: 50%;
             transform: translate(-50%, -50%);
         }
         .type3 {
             width: 10px;
             height: 10px;
             background: #ddd;
             position: absolute;
             top: 50%;
             left: 50%;
             transform: translate(-50%, -50%);
         }
         .type4 {
             width: 15px;
             height: 15px;
             background: #ddd;
             position: absolute;
             top: 80%;
             left: 50%;
             transform: translate(-50%, -50%);
         }
         .type5 {
             width: 20px;
             height: 20px;
             background: #ddd;
             position: absolute;
             top: 80%;
             left: 50%;
             transform: translate(-50%, -50%);
         }
         .type6 {
             width: 30px;
             height: 30px;
             background: #ddd;
             position: absolute;
             top: 80%;
             left: 80%;
             transform: translate(-50%, -50%);
         }
         .qtype_drawing_active_selection {
            background-color: #0cf;
         }
         .qtype_drawing_active_selection_border {
            border: 2px solid #0cf;
         }
         #svg_editor .jPicker td.Radio input{
            margin: 0 2px 0 0;
         }
         html {
             height  : 100%;
             overflow: hidden;
         }
         body {
             height  : 100%;
             overflow: auto;
         }
         @media screen and (-webkit-min-device-pixel-ratio:0) {
             select,
             textarea,
             input {
                font-size: 16px;
             }
         }
      </style>
      <title>moodle-qtype_drawing - ETHz</title>
   </head>
   <body>
      <div id="svg_editor">
         <div id="rulers">
            <div id="ruler_corner"></div>
            <div id="ruler_x" >
               <div id="ruler_x_cursor"></div>
               <div>
                  <canvas height="15"></canvas>
               </div>
            </div>
            <div id="ruler_y">
               <div id="ruler_y_cursor"></div>
               <div>
                  <canvas width="15"></canvas>
               </div>
            </div>
         </div>
         <div id="workarea" style="touch-action:none;">
            <div id="svgcanvas" style="position:relative">
            </div>
         </div>
            <?php
            if ($reducedmode == 0) {
            ?>
         <div id="menu_bar">
            <a class="menu">
               <div class="menu_list">
                  <div id="tool_about" class="menu_item"></div>
                  <div class="separator"></div>
                  <div id="tool_about" class="menu_item">Keyboard Shortcuts...</div>
               </div>
            </a>
            <div class="menu">
               <div class="menu_title"><?php print_string('file', 'qtype_drawing');?></div>
               <div class="menu_list" id="file_menu">
                  <div id="tool_clear" class="menu_item"><?php print_string('erasedrawing', 'qtype_drawing');?></div>
               </div>
            </div>
            <div class="menu">
               <div class="menu_title"><?php print_string('edit', 'qtype_drawing');?></div>
               <div class="menu_list" id="edit_menu">
                  <div class="separator"></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_cut"><?php print_string('cut', 'qtype_drawing');?> <span class="shortcut">⌘X</span></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_copy"><?php print_string('copy', 'qtype_drawing');?> <span class="shortcut">⌘C</span></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_paste"><?php print_string('paste', 'qtype_drawing');?> <span class="shortcut">⌘V</span></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_clone"><?php print_string('duplicate', 'qtype_drawing');?> <span class="shortcut">⌘D</span></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_delete"><?php print_string('delete', 'qtype_drawing');?> <span>⌫</span></div>
               </div>
            </div>
            <div class="menu">
               <div class="menu_title"><?php print_string('object', 'qtype_drawing');?></div>
               <div class="menu_list"  id="object_menu">
                  <div class="menu_item action_selected disabled"
                       id="tool_move_top"><?php print_string('bringtofront', 'qtype_drawing');?>
                       <span class="shortcut">⌘⇧↑</span></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_move_up"><?php print_string('bringforward', 'qtype_drawing');?>
                       <span class="shortcut">⌘↑</span></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_move_down"><?php print_string('sendbackward', 'qtype_drawing');?>
                       <span class="shortcut">⌘↓</span></div>
                  <div class="menu_item action_selected disabled"
                       id="tool_move_bottom"><?php print_string('sendtoback', 'qtype_drawing');?>
                       <span class="shortcut">⌘⇧↓</span></div>
                  <div class="separator"></div>
                  <div class="menu_item action_multi_selected disabled"
                       id="tool_group"><?php print_string('groupelements', 'qtype_drawing');?>
                       <span class="shortcut">⌘G</span></div>
                  <div class="menu_item action_group_selected disabled"
                       id="tool_ungroup"><?php print_string('ungroupelements', 'qtype_drawing');?>
                       <span class="shortcut">⌘⇧G</span></div>
                  <div class="menu_item action_path_convert_selected disabled"
                       id="tool_topath" style="display:none;"><?php print_string('converttopath', 'qtype_drawing');?></div>
                  <div class="menu_item action_path_selected disabled"
                       id="tool_reorient" style="display:none;"><?php print_string('reorientpath', 'qtype_drawing');?></div>
               </div>
            </div>
            <div class="menu">
               <div class="menu_title"><?php print_string('view', 'qtype_drawing');?></div>
               <div class="menu_list" id="view_menu">
                  <div class="menu_item push_button_pressed"
                       id="tool_rulers"><?php print_string('viewrulers', 'qtype_drawing');?></div>
                  <div class="menu_item"
                       id="tool_wireframe" style="display:none"><?php print_string('viewwireframe', 'qtype_drawing');?></div>
                  <div class="menu_item"
                       id="tool_snap"><?php print_string('snaptogrid', 'qtype_drawing');?></div>
                  <div class="separator"></div>
                  <div class="menu_item"
                       id="tool_source"><?php print_string('source', 'qtype_drawing');?> <span class="shortcut">⌘U</span></div>
               </div>
            </div>
         </div>
            <?php
            }
            ?>
         <div id="tools_top" class="tools_panel">
            <?php if ($useupdateannotationjs == 1) {?>
            <div id="annotation_panel" class="context_panelv">
               <h4><?php print_string('annotation', 'qtype_drawing');?></h4>
               <div class="annotation_tool draginput" style="width:145px;padding-bottom:2px; height:100%" id="annotation_tool">
                  <span><input type="button" name="saveannotation" id="tool_saveannotation"
                               value="<?php print_string('saveannotation', 'qtype_drawing');?>"
                               style="background: #4F80FF;
                                      color: #fff;
                                      border-radius: 3px;
                                      padding: 1px 14px;
                                      border: none;
                                      line-height: 140%;
                                      font-size: 14px;
                                      font-weight: bold;
                                      font-family: sans-serif;
                                      white-space: normal;
                                      height:initial;">
                  </span>
                  <script>
                     var answertxtarea = $('#qtype_drawing_original_stdanswer_id_'+attemptid+uniquefieldnameattemptid,
                                           window.parent.document).val();
                     if (answertxtarea.length == 0) {
                         $("#tool_saveannotation").attr('disabled','disabled');
                         $("#tool_saveannotation").css('background','#ddd');
                     }
                  </script>
                  <div style="padding:20px"></div>
                  <div id="teacherannotations"
                       style="margin-top:10px;
                              color: #4F80FF;
                              font-size:12px;
                              margin-left:-20px;
                              margin-right:1px;
                              text-align:left">
                        <?php
                        echo '<ul id="listofannotaions">';
                        $fields = array('questionid' => $question->id, 'attemptid' => $attemptid, 'annotatedfor' => $stid, 'attemptcount' => $attemptcount);
                        if ($annotations = $DB->get_records('qtype_drawing_annotations', $fields, 'timemodified DESC')) {
                            foreach ($annotations as $teacherannotation) {
                                $user = $DB->get_record('user', array('id' => $teacherannotation->annotatedby));
                                echo '<li id="annotationelem_'.$user->id.'"
                                          class="annotaionelems"
                                          data-block="block'.$user->id.'">
                                      <a href="#" id="showannotationid_'.$teacherannotation->id.'"
                                         style="color:#fff" class="tool_showannotation" data-type="0"
                                         data-annotationid="'.$teacherannotation->id.'">'.fullname($user).'</a>
                                         <div id="teacherannotationdate_'.$user->id.'">'.
                                         userdate($teacherannotation->timemodified).
                                         ' ('.get_string('ago', 'core_message',
                                                        format_time(time() - $teacherannotation->timemodified)).
                                         ')</div></li>';
                            }
                        }
                        echo '<li data-block="block0">
                              <a href="#" id="showoriginalanswer" style="color:#fff"
                                 class="tool_showannotation" data-type="1" data-annotationid="-1">'.
                                 get_string('originalanswer', 'qtype_drawing').
                                 '</a></li>';
                        echo '<li data-block="block1" onclick="methodDraw.updateAnnotationDetails()"><a href="#"
                                  id="studentview" style="color:#fff" class="tool_showannotation"
                                  data-type="2" data-annotationid="-1">'.
                                  get_string('studentview', 'qtype_drawing').
                                  '</a></li>';

                        echo '</ul>';
                        ?>
                  </div>
               </div>
            </div>
            <?php }
            ?>
               <div id="canvas_panel" class="context_panel">

               <div class="clearfix"></div>
               <h4><?php print_string('drawingpresets', 'qtype_drawing');?></h4>
               <div class="stroke_tool draginput" style="width:145px;padding-bottom:2px; height:100%" id="fastcolorpicks">

               <span><?php print_string('color', 'qtype_drawing');?></span>
               <div style="margin-top:15px; text-align:center;">
                  <div id="qtype_drawing_tool_color1"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#ffffff" > </div>
                  <div id="qtype_drawing_tool_color2"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#999999"> </div>
                  <div id="qtype_drawing_tool_color3"
                       class="qtype_drawing_color_divsquare qtype_drawing_selectedcolor"
                       data-strokeorfill= "stroke"  style="background:#000000"> </div>
                  <div id="qtype_drawing_tool_color4"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#333399"> </div>
                  <div id="qtype_drawing_tool_color5"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#ffff00"> </div>
                  <div id="qtype_drawing_tool_color6"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#00ff00"> </div>
                  <div id="qtype_drawing_tool_color7"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#336633"> </div>
                  <div id="qtype_drawing_tool_color8"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#33cccc"> </div>
                  <div id="qtype_drawing_tool_color9"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#66FF99"> </div>
                  <div id="qtype_drawing_tool_color10"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#ff9933"> </div>
                  <div id="qtype_drawing_tool_color11"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#ff3333"> </div>
                  <div id="qtype_drawing_tool_color12"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#ff33cc"> </div>
                  <div id="qtype_drawing_tool_color13"
                       class="qtype_drawing_color_divsquare" data-strokeorfill= "stroke"  style="background:#9900ff"> </div>
                  <div id="qtype_drawing_tool_color14"
                       class="qtype_drawing_color_pallette" data-strokeorfill= "stroke">
                       <img src="images/colors.png" width="22" height="22" alt="C"></div>
                  <div id="qtype_drawing_tool_color15"
                       class="qtype_drawing_color_pallette" data-strokeorfill= "fill">
                       <img src="images/paintbucket.png" width="22" height="22" alt="B"></div>
               </div>
            </div>
            <div class="stroke_tool draginput" id="preset_sizes_panel_id" style="width:125px;padding:10px; height:70px">
               <span><?php print_string('size', 'qtype_drawing');?></span>
               <div >
                  <div id="qtype_drawing_tool_pensize1"
                       class="qtype_drawing_size_pen" data-size="1.5"  data-penortext="pen" title="1px">
                     <div class="circleBase type1"></div>
                  </div>
                  <div id="qtype_drawing_tool_pensize2"
                       class="qtype_drawing_size_pen" data-size="2.5"  data-penortext="pen"  title="2px">
                     <div class="circleBase type2"></div>
                  </div>
                  <div id="qtype_drawing_tool_pensize3"
                       class="qtype_drawing_size_pen" data-size="3.5"  data-penortext="pen"  title="3px">
                     <div class="circleBase type3 qtype_drawing_active_selection"></div>
                  </div>
                  <div style='clear:both'></div>
                  <div id="qtype_drawing_tool_pensize4"
                       class="qtype_drawing_size_pen" data-size="5"  data-penortext="pen"  title="5px">
                     <div class="circleBase type4"></div>
                  </div>
                  <div id="qtype_drawing_tool_pensize5"
                       class="qtype_drawing_size_pen" data-size="10"  data-penortext="pen"  title="10px">
                     <div class="circleBase type5"></div>
                  </div>
                  <div id="qtype_drawing_tool_pensize6"
                       class="qtype_drawing_size_pen" data-size="20"  data-penortext="pen"  title="20px">
                     <div class="circleBase type6"></div>
                  </div>
               </div>
            </div>
            <div id="stroke_panel" class=" clearfix">
               <div class="clearfix"></div>
               <div class="toolset" data-title="<?php print_string('changestroke', 'qtype_drawing');?>" id="strokewidth_div">
                  <label>
                  <input id="stroke_width"  type="number" size="2" value="5" data-attr="stroke-width" min="1" max="99" step="1"/>
                  <span class="icon_label"><?php print_string('strokewidth', 'qtype_drawing');?></span>
                  </label>
               </div>
               <div id="where_iwant_tool_opacity"></div>
               <?php
                if ($reducedmode == 1) {
                ?><div id="force_remove_stroke_style" style="display:none">
                <?php
                }
                ?>
               <div class="stroke_tool draginput" id="strokestyle_div">
                  <span><?php print_string('strokedash', 'qtype_drawing');?></span>
                  <select id="stroke_style" data-title="<?php print_string('dashstyle', 'qtype_drawing');?>">
                     <option selected="selected" value="none">—</option>
                     <option value="2,2">···</option>
                     <option value="5,5">- -</option>
                     <option value="5,2,2,2">-·-</option>
                     <option value="5,2,2,2,2,2">-··-</option>
                  </select>
                  <div class="caret"></div>
                  <label id="stroke_style_label">—</label>
               </div>
                <?php
                if ($reducedmode == 1) {
                ?></div>
                <?php
                }
                ?>
               <span style="display:none">
                  <h4 class="clearfix">&nbsp;</h4>
                  <label data-title="Change canvas width">
                  <input size="3" id="canvas_width" type="text" pattern="[0-9]*"/>
                  <span class="icon_label">Width</span>
                  </label>
                  <label data-title="Change canvas height">
                  <input id="canvas_height" size="3" type="text" pattern="[0-9]*"/>
                  <span class="icon_label">Height</span>
                  </label>
                  <label data-title="Change canvas color" class="draginput">
                     <span>Color</span>
                     <div id="color_canvas_tools">
                        <div class="color_tool active" id="tool_canvas">
                           <div class="color_block">
                              <div id="canvas_bg"></div>
                              <div id="canvas_color"></div>
                           </div>
                        </div>
                     </div>
                  </label>
                  <div class="draginput">
                     <span>Sizes</span>
                     <select id="resolution">
                        <option id="selectedPredefined" selected="selected">Custom</option>
                        <option>640x480</option>
                        <option>800x600</option>
                        <option>1024x768</option>
                        <option>1280x960</option>
                        <option>1600x1200</option>
                        <option id="fitToContent" value="content">Fit to Content</option>
                     </select>
                     <div class="caret"></div>
                     <label id="resolution_label">Custom</label>
                  </div>
               </span>
            </div>
         </div>
         <!-- Buttons when a single element is selected -->
         <div id="selected_panel" class="context_panel">
            <?php if ($reducedmode == 1) { ?>
            <div class="clearfix"><br></div>
            <?php }?>
            <label id="tool_angle" data-title="<?php print_string('changerotationangle', 'qtype_drawing');?>"
                   class="draginput" <?php echo $displaystylefull;?>>
               <input id="angle" class="attr_changer" size="2" value="0" data-attr="transform"
                      data-min="-180" data-max="180" type="text"/>
               <span class="icon_label"><?php print_string('rotation', 'qtype_drawing');?></span>
               <div id="tool_angle_indicator">
                  <div id="tool_angle_indicator_cursor"></div>
               </div>
            </label>
            <div id="dat_tool_opacity_pain"  <?php echo $displaystylefull;?>>
               <div class="clearfix"></div>
               <label class="toolset" id="tool_opacity" data-title="<?php print_string('changeopacity', 'qtype_drawing');?>"  >
               <input id="group_opacity" class="attr_changer" data-attr="opacity"
                      data-multiplier="0.01" size="3" value="100" step="5" min="0" max="100" />
               <span id="group_opacityLabel" class="icon_label"><?php print_string('opacity', 'qtype_drawing');?></span>
               </label>
            </div>
            <div class="toolset" id="tool_blur"
                 data-title="<?php print_string('changeblur', 'qtype_drawing');?>"  <?php echo $displaystylefull;?>>
               <label>
               <input id="blur" size="2" value="0" step=".1"  min="0" max="10" />
               <span class="icon_label"><?php print_string('blur', 'qtype_drawing');?></span>
               </label>
               <div class="clearfix"></div>
            </div>
            <label id="cornerRadiusLabel"
                   data-title="<?php print_string('changecornerradius', 'qtype_drawing');?>"  <?php echo $displaystylefull;?>>
            <input id="rect_rx" size="3" value="0" data-attr="rx" class="attr_changer" type="text" pattern="[0-9]*" />
            <span class="icon_label"><?php print_string('roundness', 'qtype_drawing');?></span>
            </label>
            <div id="align_tools"  <?php echo $displaystylefull;?>>
               <div class="clearfix"></div>
               <h4><?php print_string('align', 'qtype_drawing');?></h4>
               <div class="toolset align_buttons" id="tool_position">
                  <label>
                     <div class="col last clear" id="position_opts">
                        <div class="draginput_cell" id="tool_posleft" title="Align Left"></div>
                        <div class="draginput_cell" id="tool_poscenter" title="Align Center"></div>
                        <div class="draginput_cell" id="tool_posright" title="Align Right"></div>
                        <div class="draginput_cell" id="tool_postop" title="Align Top"></div>
                        <div class="draginput_cell" id="tool_posmiddle" title="Align Middle"></div>
                        <div class="draginput_cell" id="tool_posbottom" title="Align Bottom"></div>
                     </div>
                  </label>
               </div>
            </div>
         </div>
         <div id="rect_panel" class="context_panel">
            <h4 class="clearfix"><?php print_string('rectangle', 'qtype_drawing');?></h4>
            <label>
            <input id="rect_x" class="attr_changer" data-title="Change X coordinate" size="3" data-attr="x" pattern="[0-9]*" />
            <span>X</span>
            </label>
            <label>
            <input id="rect_y" class="attr_changer" data-title="Change Y coordinate" size="3" data-attr="y" pattern="[0-9]*" />
            <span>Y</span>
            </label>
            <label id="rect_width_tool attr_changer" data-title="Change rectangle width">
            <input id="rect_width" class="attr_changer" size="3" data-attr="width" type="text" pattern="[0-9]*" />
            <span class="icon_label"><?php print_string('width', 'qtype_drawing');?></span>
            </label>
            <label id="rect_height_tool" data-title="Change rectangle height">
            <input id="rect_height" class="attr_changer" size="3" data-attr="height" type="text" pattern="[0-9]*" />
            <span class="icon_label"><?php print_string('height', 'qtype_drawing');?></span>
            </label>
         </div>
         <div id="path_panel" class="context_panel clearfix"   <?php echo $displaystylefull;?>>
            <h4 class="clearfix"><?php print_string('path', 'qtype_drawing');?></h4>
            <label>
            <input id="path_x" class="attr_changer"
                   data-title="Change ellipse's cx coordinate" size="3" data-attr="x" pattern="[0-9]*" />
            <span>X</span>
            </label>
            <label>
            <input id="path_y" class="attr_changer"
                   data-title="Change ellipse's cy coordinate" size="3" data-attr="y" pattern="[0-9]*" />
            <span>Y</span>
            </label>
         </div>
         <div id="image_panel" class="context_panel clearfix">
            <h4><?php print_string('image', 'qtype_drawing');?></h4>
            <label>
            <input id="image_x" class="attr_changer" data-title="Change X coordinate" size="3" data-attr="x"  pattern="[0-9]*"/>
            <span>X</span>
            </label>
            <label>
            <input id="image_y" class="attr_changer" data-title="Change Y coordinate" size="3" data-attr="y"  pattern="[0-9]*"/>
            <span>Y</span>
            </label>
            <label>
            <input id="image_width" class="attr_changer"
                   data-title="Change image width" size="3" data-attr="width" pattern="[0-9]*" />
            <span class="icon_label">Width</span>
            </label>
            <label>
            <input id="image_height" class="attr_changer"
                   data-title="Change image height" size="3" data-attr="height" pattern="[0-9]*" />
            <span class="icon_label">Height</span>
            </label>
         </div>
         <div id="circle_panel" class="context_panel">
            <h4><?php print_string('circle', 'qtype_drawing');?></h4>
            <label id="tool_circle_cx">
            <span><?php print_string('centerx', 'qtype_drawing');?></span>
            <input id="circle_cx" class="attr_changer" title="Change circle's cx coordinate" size="3" data-attr="cx"/>
            </label>
            <label id="tool_circle_cy">
            <span><?php print_string('centery', 'qtype_drawing');?></span>
            <input id="circle_cy" class="attr_changer" title="Change circle's cy coordinate" size="3" data-attr="cy"/>
            </label>
            <label id="tool_circle_r">
            <span>Radius</span>
            <input id="circle_r" class="attr_changer" title="Change circle's radius" size="3" data-attr="r"/>
            </label>
         </div>
         <div id="ellipse_panel" class="context_panel clearfix">
            <h4><?php print_string('ellipse', 'qtype_drawing');?></h4>
            <label id="tool_ellipse_cx">
            <input id="ellipse_cx" class="attr_changer"
                   data-title="Change ellipse's cx coordinate" size="3" data-attr="cx" pattern="[0-9]*" />
            <span>X</span>
            </label>
            <label id="tool_ellipse_cy">
            <input id="ellipse_cy" class="attr_changer"
                   data-title="Change ellipse's cy coordinate" size="3" data-attr="cy" pattern="[0-9]*" />
            <span>Y</span>
            </label>
            <label id="tool_ellipse_rx">
            <input id="ellipse_rx" class="attr_changer"
                   data-title="Change ellipse's x radius" size="3" data-attr="rx" pattern="[0-9]*" />
            <span><?php print_string('radiusx', 'qtype_drawing');?></span>
            </label>
            <label id="tool_ellipse_ry">
            <input id="ellipse_ry" class="attr_changer"
                   data-title="Change ellipse's y radius" size="3" data-attr="ry" pattern="[0-9]*" />
            <span><?php print_string('radiusy', 'qtype_drawing');?></span>
            </label>
         </div>
         <div id="line_panel" class="context_panel clearfix">
            <h4><?php print_string('line', 'qtype_drawing');?></h4>
            <label id="tool_line_x1">
            <input id="line_x1" class="attr_changer"
                   data-title="Change line's starting x coordinate" size="3" data-attr="x1" pattern="[0-9]*" />
            <span><?php print_string('startx', 'qtype_drawing');?></span>
            </label>
            <label id="tool_line_y1">
            <input id="line_y1" class="attr_changer"
                   data-title="Change line's starting y coordinate" size="3" data-attr="y1" pattern="[0-9]*" />
            <span><?php print_string('starty', 'qtype_drawing');?></span>
            </label>
            <label id="tool_line_x2">
            <input id="line_x2" class="attr_changer"
                   data-title="Change line's ending x coordinate" size="3" data-attr="x2"   pattern="[0-9]*" />
            <span><?php print_string('endx', 'qtype_drawing');?></span>
            </label>
            <label id="tool_line_y2">
            <input id="line_y2" class="attr_changer"
                   data-title="Change line's ending y coordinate" size="3" data-attr="y2"   pattern="[0-9]*" />
            <span><?php print_string('endy', 'qtype_drawing');?></span>
            </label>
         </div>
         <div id="text_panel" class="context_panel">
            <h4><?php print_string('text', 'qtype_drawing');?></h4>
            <div id="advanced_drawtext_div" >
               <label style="display:none">
               <input id="text_x" class="attr_changer"
                      data-title="Change text x coordinate" size="3" data-attr="x" pattern="[0-9]*" />
               <span>X</span>
               </label>
               <label style="display:none">
               <input id="text_y" class="attr_changer"
                      data-title="Change text y coordinate" size="3" data-attr="y" pattern="[0-9]*" />
               <span>Y</span>
               </label>
               <div class="toolset draginput select twocol" id="tool_font_family">
                  <!-- Font family -->
                  <span><?php print_string('font', 'qtype_drawing');?></span>
                  <div id="preview_font" style="font-family: Helvetica, Arial, sans-serif;">Helvetica</div>
                  <div class="caret"></div>
                  <input id="font_family" data-title="Change Font Family" size="12" type="hidden" />
                  <select id="font_family_dropdown">
                     <option value="'Courier New', Courier, monospace">Courier</option>
                     <option value="Helvetica, Arial, sans-serif" selected="selected">Helvetica</option>
                     <option value="'Times New Roman', Times, serif">Times</option>
                  </select>
                  <div class="tool_button" id="tool_bold" data-title="Bold Text [B]">B</div>
                  <div class="tool_button" id="tool_italic" data-title="Italic Text [I]">i</div>
               </div>
            </div>
            <label id="tool_font_size" data-title="Change Font Size">
            <input id="font_size" size="3" value="0" />
            <span id="font_sizeLabel" class="icon_label"><?php print_string('fontsize', 'qtype_drawing');?></span>
            </label>
         </div>
         <!-- formerly gsvg_panel -->
         <div id="container_panel" class="context_panel clearfix"></div>
         <div id="use_panel" class="context_panel clearfix">
            <div class="tool_button clearfix"
                 id="tool_unlink_use" data-title="Break link to reference element (make unique)">Break link reference</div>
         </div>
         <div id="g_panel" class="context_panel clearfix">
            <h4><?php print_string('group', 'qtype_drawing');?></h4>
            <label>
            <input id="g_x" class="attr_changer"
                   data-title="Change groups's x coordinate" size="3" data-attr="x" pattern="[0-9]*" />
            <span>X</span>
            </label>
            <label>
            <input id="g_y" class="attr_changer"
                   data-title="Change groups's y coordinate" size="3" data-attr="y" pattern="[0-9]*" />
            <span>Y</span>
            </label>
         </div>
         <div id="path_node_panel" class="context_panel clearfix">
            <h4><?php print_string('editpath', 'qtype_drawing');?></h4>
            <label id="tool_node_x">
            <input id="path_node_x" class="attr_changer" data-title="Change node's x coordinate" size="3" data-attr="x" />
            <span>X</span>
            </label>
            <label id="tool_node_y">
            <input id="path_node_y" class="attr_changer" data-title="Change node's y coordinate" size="3" data-attr="y" />
            <span>Y</span>
            </label>
            <div id="segment_type" class="draginput label">
               <span><?php print_string('segmenttype', 'qtype_drawing');?></span>
               <select id="seg_type" data-title="Change Segment type">
                  <option id="straight_segments" selected="selected" value="4">
                    <?php print_string('straight', 'qtype_drawing');?>
                  </option>
                  <option id="curve_segments" value="6"><?php print_string('curve', 'qtype_drawing');?></option>
               </select>
               <div class="caret"></div>
               <label id="seg_type_label"><?php print_string('straight', 'qtype_drawing');?></label>
            </div>
            <div class="clearfix"></div>
            <div class="tool_button" id="tool_node_clone"
                 title="Adds a node"><?php print_string('addnode', 'qtype_drawing');?></div>
            <div class="tool_button" id="tool_node_delete"
                 title="Delete Node"><?php print_string('deletenode', 'qtype_drawing');?></div>
            <div class="tool_button" id="tool_openclose_path"
                 title="Open/close sub-path"><?php print_string('openpath', 'qtype_drawing');?></div>
         </div>
         <!-- Buttons when multiple elements are selected -->
         <div id="multiselected_panel" class="context_panel clearfix">
            <h4 class="hidable"><?php print_string('multipleelements', 'qtype_drawing');?></h4>
            <div class="toolset align_buttons" style="position: relative">
               <label id="tool_align_relative" style="margin-top: 10px;">
                  <select id="align_relative_to" title="Align relative to ...">
                     <option id="selected_objects"
                             value="selected"><?php print_string('aligntoobjects', 'qtype_drawing');?></option>
                     <option id="page" value="page"><?php print_string('aligntopage', 'qtype_drawing');?></option>
                  </select>
               </label>
               <h4>.</h4>
               <div class="col last clear">
                  <div class="draginput_cell" id="tool_alignleft" title="Align Left"></div>
                  <div class="draginput_cell" id="tool_aligncenter" title="Align Center"></div>
                  <div class="draginput_cell" id="tool_alignright" title="Align Right"></div>
                  <div class="draginput_cell" id="tool_aligntop" title="Align Top"></div>
                  <div class="draginput_cell" id="tool_alignmiddle" title="Align Middle"></div>
                  <div class="draginput_cell" id="tool_alignbottom" title="Align Bottom"></div>
               </div>
            </div>
            <div class="clearfix"></div>
         </div>
         <div id="delete_panel" class="context_panel clearfix">
            <?php if ($reducedmode != 1) { ?>
            <br />
            <?php }?>
            <label id="tool_delete" data-title="<?php print_string('deleteobject', 'qtype_drawing');?>" class="draginput">
            <span class="icon_label"><?php print_string('delete', 'qtype_drawing');?></span>
            <label><img src="images/deleteforever.svg" width="32" height="32" style="cursor:pointer;display: block;
               margin: 0 auto;" alt="X" title="<?php print_string('deleteobject', 'qtype_drawing');?>"></img></label>
            </label>
         </div>
         <label style="display: none;">
         <span class="icon_label"><?php print_string('strokejoin', 'qtype_drawing');?></span>
         </label>
         <label  style="display: none;">
         <span class="icon_label"><?php print_string('strokecap', 'qtype_drawing');?></span>
         </label>
         <div class="clearfix">&nbsp;</div>
      </div>
      <!-- tools_top -->
      <div id="cur_context_panel">
      </div>
      <div id="tools_left" class="tools_panel">
         <div class="tool_button" id="tool_select" title="<?php print_string('selecttool', 'qtype_drawing');?>"></div>
         <div class="tool_button" id="tool_fhpath" title="<?php print_string('drawingtool', 'qtype_drawing');?>"></div>
         <div class="tool_button" id="tool_line" title="<?php print_string('linetool', 'qtype_drawing');?>"></div>
         <div class="tool_button" id="tool_text" title="<?php print_string('texttool', 'qtype_drawing');?>"></div>
            <?php if ($reducedmode == 0) { ?>
         <div class="separator"></div>
         <div class="tool_button" id="tool_rect" title="<?php print_string('recttool', 'qtype_drawing');?>"></div>
         <div class="tool_button" id="tool_ellipse" title="<?php print_string('ellipsetool', 'qtype_drawing');?>"></div>
         <div class="tool_button" id="tool_path" title="<?php print_string('pathtool', 'qtype_drawing');?>"></div>
            <?php } ?>
         <div id="color_tools"
                <?php
                if ($reducedmode == 1) {
                ?>
              style="display:none;"
                <?php
                }
                ?>
             >
            <div id="tool_switch" title="<?php print_string('switchstrokefill', 'qtype_drawing');?>"></div>
            <div class="color_tool active" id="tool_fill">
               <label class="icon_label" title="<?php print_string('changefill', 'qtype_drawing');?>"></label>
               <div class="color_block">
                  <div id="fill_bg"></div>
                  <div id="fill_color" class="color_block"></div>
               </div>
            </div>
            <div class="color_tool" id="tool_stroke">
               <label class="icon_label" title="<?php print_string('changestrokecolor', 'qtype_drawing');?>"></label>
               <div class="color_block">
                  <div id="stroke_bg"></div>
                  <div id="stroke_color" class="color_block"
                       title="<?php print_string('changestrokecolor', 'qtype_drawing');?>"></div>
               </div>
            </div>
         </div>
         <div class="tool_button" id="tool_zoom"
              title="<?php print_string('zoomtool', 'qtype_drawing');?>" style="display:none;"></div>
         <div class="tool_button" id="tool_undo"
              style="background:#2f2f2c"><img src="images/undo.svg" width="30" height="30"></div>
         <div class="tool_button" id="tool_redo"
              style="background:#2f2f2c"><img src="images/redo.svg" width="30" height="30"></div>
      </div>
      <!-- tools_left -->
      <div id="tools_bottom" class="tools_panel">
         <!-- Zoom buttons -->
         <div id="zoom_panel" class="toolset" title="<?php print_string('changezoom', 'qtype_drawing');?>">
            <div class="draginput select" id="zoom_label">
               <span  id="zoomLabel" class="zoom_tool icon_label"></span>
               <select id="zoom_select">
                  <option value="6">6%</option>
                  <option value="12">12%</option>
                  <option value="16">16%</option>
                  <option value="25">25%</option>
                  <option value="50">50%</option>
                  <option value="75">75%</option>
                  <option value="100"  selected="selected">100%</option>
                  <option value="150">150%</option>
                  <option value="200">200%</option>
                  <option value="300">300%</option>
                  <option value="400">400%</option>
                  <option value="500">500%</option>
               </select>
               <div class="caret"></div>
               <input id="zoom" size="3" value="100%" type="text" readonly="readonly" />
            </div>
         </div>

      </div>
      <!-- hidden divs -->
      <div id="color_picker"></div>
      </div> <!-- svg_editor -->
      <div id="svg_source_editor">
         <div id="svg_source_overlay"></div>
         <div id="svg_source_container">
            <div id="save_output_btns">
               <p id="copy_save_note"><?php print_string('copysvgsrc', 'qtype_drawing');?></p>
               <button id="copy_save_done"><?php print_string('done', 'qtype_drawing');?></button>
            </div>
            <form>
               <textarea id="svg_source_textarea" spellcheck="false"></textarea>
            </form>
            <div id="tool_source_back" class="toolbar_button">
               <button id="tool_source_cancel" class="cancel"><?php print_string('cancel', 'qtype_drawing');?></button>
               <button id="tool_source_save" class="ok"><?php print_string('applychanges', 'qtype_drawing');?></button>
            </div>
         </div>
      </div>
      <div id="svg_source_annotation">
         <div id="svg_source_annotation"></div>
         <div id="svg_source_annoitation_container">
            <div id="tool_source_back" class="toolbar_button">
               <button id="tool_source_cancel" class="cancel"><?php print_string('cancel', 'qtype_drawing');?></button>
               <button id="tool_source_save" class="ok"><?php print_string('applychanges', 'qtype_drawing');?></button>
            </div>
         </div>
      </div>
      <div id="base_unit_container">
         <select id="base_unit">
            <option value="px">Pixels</option>
            <option value="cm">Centimeters</option>
            <option value="mm">Millimeters</option>
            <option value="in">Inches</option>
            <option value="pt">Points</option>
            <option value="pc">Picas</option>
            <option value="em">Ems</option>
            <option value="ex">Exs</option>
         </select>
      </div>
      <div id="svg_prefs"></div>
      <div id="dialog_box">
         <div id="dialog_box_overlay"></div>
         <div id="dialog_container">
            <div id="dialog_content"></div>
            <div id="dialog_buttons"></div>
         </div>
      </div>
        <?php if ($reducedmode == 0) { ?>
      <ul id="cmenu_canvas" class="contextMenu">
         <li><a href="#cut"><?php print_string('cut', 'qtype_drawing');?> <span class="shortcut">⌘X;</span></a></li>
         <li><a href="#copy"><?php print_string('copy', 'qtype_drawing');?><span class="shortcut">⌘C</span></a></li>
         <li><a href="#paste"><?php print_string('paste', 'qtype_drawing');?><span class="shortcut">⌘V</span></a></li>
         <li class="separator">
           <a href="#delete"><?php print_string('delete', 'qtype_drawing');?><span class="shortcut">⌫</span></a></li>
         <li class="separator">
           <a href="#group"><?php print_string('group', 'qtype_drawing');?><span class="shortcut">⌘G</span></a></li>
         <li><a href="#ungroup"><?php print_string('ungroup', 'qtype_drawing');?><span class="shortcut">⌘⇧G</span></a></li>
         <li class="separator">
           <a href="#move_front"><?php print_string('bringtofront', 'qtype_drawing');?><span class="shortcut">⌘⇧↑</span></a>
         </li>
         <li><a href="#move_up"><?php print_string('bringforward', 'qtype_drawing');?><span class="shortcut">⌘↑</span></a>
         </li>
         <li><a href="#move_down"><?php print_string('sendbackward', 'qtype_drawing');?><span class="shortcut">⌘↓</span></a>
         </li>
         <li><a href="#move_back"><?php print_string('sendtoback', 'qtype_drawing');?><span class="shortcut">⌘⇧↓</span></a>
         </li>
      </ul>
        <?php }?>
      <input type="hidden" id="fhd_question_id" value="<?php echo $id;?>">
      <input type="hidden" id="fhd_width" value="<?php echo $fhd->backgroundwidth;?>">
      <input type="hidden" id="fhd_height" value="<?php echo $fhd->backgroundheight;?>">
      <div id="textedit_dialog" title="<?php print_string('edittext', 'qtype_drawing');?>">
         <textarea id="text" name="text" cols="40" rows="8" style="width:95%; overflow: auto;"></textarea>
      </div>
   </body>
</html>
<script>
   methodDraw.ready(function() {
     var svg = d3.select("#svgcontent");
     var g_erase = svg.append('g').attr('id', 'erase');
       // Get current student answer - if any!.
     var qid = $('#fhd_question_id').val();
     if (methodDraw.lastanswer && 0 !== methodDraw.lastanswer.length) {
         methodDraw.loadFromString(methodDraw.lastanswer);
         console.log("loading answer from lastansswer");
     }
     if ($("#qtype_drawing_loading_image_"+attemptid+uniquefieldnameattemptid, window.parent.document).length) {
         $("#qtype_drawing_loading_image_"+attemptid+uniquefieldnameattemptid, window.parent.document).hide();
     }
        <?php
        if ($useupdateannotationjs == 1) {
        ?>
     window.setInterval(methodDraw.updateAnnotationDetails, 30000);
        <?php
        }
        ?>
   });
</script>
