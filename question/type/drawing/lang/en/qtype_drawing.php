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
 * Strings for component 'qtype_drawing', language 'en'.
 *
 * @package    qtype
 * @subpackage drawing
 * @copyright  ETHZ LET <amr.hourani@id.ethz.ch>
 * @author Amr Hourani amr.hourani@id.ethz.ch, Kristina Isacson kristina.isacson@let.ethz.ch
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Freehand drawing (ETH)';
$string['pluginname_help'] = 'In response to a question the respondent draws a solution on a predefined image. There is only one right answer possible.';
$string['pluginname_link'] = 'question/type/drawing';
$string['pluginnameadding'] = 'Adding a Freehand drawing question';
$string['pluginnameediting'] = 'Editing a Freehand drawing question';
$string['pluginnamesummary'] = 'In response to a question the respondent draws an answer on an predefined image. There is only one right answer possible.';
$string['threshold_for_correct_answers'] = 'Threshold for correct answers (%)';

$string['answer'] = 'Answer: {$a}';
$string['correctansweris'] = 'The correct answer is: {$a}.';
$string['pleaseenterananswer'] = 'Please enter an answer.';
$string['drawing_background_image'] = 'Background image';
$string['drawingrawdata'] = '';
$string['backgroundfilemustbegiven'] = 'You must specify a file as a background image for the Freehand drawing.';
$string['drawingmustbegiven'] = 'You must make a drawing.';
$string['drawanswer'] = 'Draw answer';
$string['drawing'] = 'Drawing';
$string['accepted_background_image_file_types'] = 'Accepted file types';
$string['nobackgroundimageselectedyet'] = 'No background image selected yet.';
$string['are_you_sure_you_want_to_erase_the_canvas'] = 'Are you sure you want to erase your drawing?';
$string['are_you_sure_you_want_to_pick_a_new_bgimage'] = 'Do you really want to change the image? This might have an effect on the students who drew before.';
$string['are_you_sure_you_want_to_change_the_drawing_radius'] = 'If you change the drawing radius now, it will erase your solution. Are you okay with that?';
$string['set_radius'] = 'Set size of the pencil (pixel)';
$string['threshold_must_be_reasonable'] = 'You must select a reasonable threshold.';
$string['erase_canvas'] = 'Clear';
$string['no_response_summary'] = 'No response summary';
$string['no_correct_answer_summary'] = 'No correct answer summary for freehand drawing question types.';
$string['out_of_necessary'] = 'out of necessary';
$string['selected_background_image_filename'] = 'Selected background image';
$string['enterfullscreen'] = 'Full Screen';
$string['exitfullscreen'] = 'Exit Full Screen';
$string['zoomin'] = 'Zoom in';
$string['zoomout'] = 'Zoom out';
$string['redo_drawing'] = 'Redo';
$string['privacy:metadata'] = 'The freehand drawing plugin does not store any personal data.';
$string['canvasspecs'] = 'Drawing specifications';
$string['basicmode'] = 'Standard mode';
$string['advancedmode'] = 'Advanced mode';
$string['drawingmode'] = 'Drawing mode';
$string['drawingmode_help'] = 'Whether drawing is in advanced mode, or in simple mode';
$string['backgroundwidth'] = 'Drawing width';
$string['backgroundheight'] = 'Drawing height';
$string['preserveaspectratio'] = 'Preserve aspect ratio';
$string['canvassize'] = 'Drawing size';
$string['allowstudentimage'] = 'Allow image upload by students.';
$string['allowstudentimage_help'] = 'Allowing image upload by students.';
$string['cut'] = 'Cut';
$string['copy'] = 'Copy';
$string['paste'] = 'Paste';
$string['duplicate'] = 'Duplicate';
$string['delete'] = 'Delete';
$string['bringtofront'] = 'Bring to front';
$string['bringforward'] = 'Bring forward';
$string['sendbackward'] = 'Send backward';
$string['sendtoback'] = 'Send to back';
$string['groupelements'] = 'Group elements';
$string['ungroupelements'] = 'Ungroup elements';
$string['converttopath'] = 'Convert to path';
$string['reorientpath'] = 'Reorient path';
$string['view'] = 'View';
$string['viewrulers'] = 'View rulers';
$string['viewwireframe'] = 'View wireframe';
$string['snaptogrid'] = 'Snap to grid';
$string['source'] = 'Source';
$string['drawingpresets'] = 'Drawing presets';
$string['changestroke'] = 'Change stroke';
$string['strokewidth'] = 'Stroke width';
$string['dashstyle'] = 'Change stroke dash style';
$string['strokedash'] = 'Stroke dash';
$string['deleteobject'] = 'Delete object';
$string['changerotationangle'] = 'Change rotation angle';
$string['rotation'] = 'Rotation';
$string['opacity'] = 'Opacity';
$string['changeopacity'] = 'Change object opacity';
$string['changeblur'] = 'Change object blur';
$string['blur'] = 'Blur';
$string['roundness'] = 'Roundness';
$string['changecornerradius'] = 'Change corner radius';
$string['align'] = 'Align';
$string['rectangle'] = 'Rectangle';
$string['width'] = 'Width';
$string['height'] = 'Height';
$string['path'] = 'Path';
$string['image'] = 'Image';
$string['circle'] = 'Circle';
$string['centerx'] = 'Center X';
$string['centery'] = 'Center Y';
$string['ellipse'] = 'Ellipse';
$string['radiusx'] = 'Radius X';
$string['radiusy'] = 'Radius Y';
$string['line'] = 'Line';
$string['startx'] = 'Start X';
$string['starty'] = 'Start Y';
$string['endx'] = 'End X';
$string['endy'] = 'End Y';
$string['text'] = 'Text';
$string['font'] = 'Font';
$string['fontsize'] = 'Size';
$string['group'] = 'Group';
$string['editpath'] = 'Edit path';
$string['segmenttype'] = 'Segment type';
$string['straight'] = 'Straight';
$string['curve'] = 'Curve';
$string['addnode'] = 'Add node';
$string['deletenode'] = 'Delete node';
$string['openpath'] = 'Open path';
$string['multipleelements'] = 'Multiple elements';
$string['aligntoobjects'] = 'Align to objects';
$string['aligntopage'] = 'Align to page';
$string['strokejoin'] = 'Stroke join';
$string['strokecap'] = 'Stroke cap';
$string['selecttool'] = 'Select tool';
$string['drawingtool'] = 'Pencil tool';
$string['linetool'] = 'Line tool';
$string['texttool'] = 'Text tool';
$string['recttool'] = 'Rectangle tool';
$string['ellipsetool'] = 'Circle tool';
$string['pathtool'] = 'Path tool';
$string['switchstrokefill'] = 'Switch stroke and fill colors';
$string['changefill'] = 'Change fill color';
$string['changestrokecolor'] = 'Change stroke color';
$string['zoomtool'] = 'Zoom tool';
$string['changezoom'] = 'Change zoom level';
$string['copysvgsrc'] = 'Copy the contents of this box into a text editor, then save the file with a .svg extension.';
$string['done'] = 'Done';
$string['cancel'] = 'Cancel';
$string['applychanges'] = 'Apply changes';
$string['object'] = 'Object';
$string['ungroup'] = 'Ungroup';
$string['edittext'] = 'Edit text';
$string['size'] = 'Size';
$string['color'] = 'Color';
$string['file'] = 'File';
$string['edit'] = 'Edit';
$string['erasedrawing'] = 'Erase Drawing';
$string['drawingcomment'] = 'Created with ETHz Freehand drawing qtype for moodle.';
$string['newconfirmationmsg'] = 'Do you want to open a new file?\nThis will also erase your undo history';
$string['eraseconfirmationmsg'] = '<strong>Do you want to clear the drawing?</strong>\nThis will also erase your undo history';
$string['parsingerror'] = 'There were parsing errors in your SVG source.\nRevert back to original SVG source?';
$string['ignorechanges'] = 'Ignore changes made to SVG source?';
$string['defaultcanvaswidth'] = 'Default drawing area (Canvas) width';
$string['defaultcanvaswidth_help'] = 'Set a default width for the drawing area (canvas)';
$string['defaultcanvasheight'] = 'Default drawing area (Canvas) height';
$string['defaultcanvasheight_help'] = 'Set a default height for the drawing area (canvas)';
$string['allowteachertochosemode'] = 'Allow teachers to choose drawing mode?';
$string['allowteachertochosemode_help'] = 'If enabled, teachers are allowed to choose a drawing mode.';
$string['configintro'] = 'Freehand drawing site-level configuration';
$string['tasktitle'] = 'Task title';
$string['maxpoints'] = 'Max points';
$string['stem'] = 'Stem';
$string['enterstemhere'] = 'Enter the stem, a question or a part of a sentence, here.';
$string['generalfeedback'] = 'General feedback';
$string['generalfeedback_help'] = 'General feedback help';
$string['ok'] = 'OK';
$string['cancel'] = 'Cancel';
$string['eyedroppertool'] = 'Eye Dropper Tool';
$string['shapelibrary'] = 'Shape Library';
$string['drawmarkers'] = 'Drag Markers To Pick A Color';
$string['solidcolor'] = 'Solid Color';
$string['lingrad'] = 'Linear Gradient';
$string['radgrad'] = 'Radial Gradient';
$string['new'] = 'New';
$string['current'] = 'Current';
$string['viewgrid'] = 'View Grid';
$string['annotation'] = 'Annotation';
$string['originalanswer'] = 'Original drawing';
$string['by'] = 'By: ';
$string['saveannotation'] = 'Save annotation';
$string['annotationsaved'] = 'Annotation saved.';
$string['saving'] = 'Saving..';
$string['studentview'] = 'Student View';
$string['showanswer'] = 'Show answer';
$string['showannotation'] = 'Show annotation';
$string['enableeraser'] = 'Teachers are allowed to enable the Eraser tool?';
$string['enableeraser_help'] = 'If enabled, teachers are allowed to choose to enable Eraser tool';
$string['alloweraser'] = 'Allow Eraser tool';
$string['drawing_settings'] = 'Drawing settings';