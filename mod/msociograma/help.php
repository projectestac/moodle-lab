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

//This file show the help 

$lang = current_language();

echo "<style>p.round1 {border: 2px solid grey; border-radius: 5px; padding: 10px;}</style>";

echo "<B><FONT  COLOR=grey>".get_string('about','msociograma')."</FONT></B> <BR>";
echo "<p class='round1'>".get_string('textabout','msociograma')."</p>";
echo "<BR><BR><BR>";
echo "<B><FONT  COLOR=grey>".get_string('help','msociograma')."</FONT></B> <BR>";
echo "<p class='round1'>";
echo "<A HREF ='help/$lang/".get_string('howtocreateactivityfile', 'msociograma').".pdf' TARGET='_blank'><FONT FACE=arial COLOR=blue>[ ". get_string('howtocreateactivity', 'msociograma')." ]</FONT></A>"."<BR><BR>";
echo "<A HREF ='help/$lang/".get_string('howtointroducestudentsfile', 'msociograma').".pdf' TARGET='_blank'><FONT FACE=arial COLOR=blue>[ ". get_string('howtointroducestudents', 'msociograma')." ]</FONT></A>"."<BR><BR>";
echo "<A HREF='help/studentsSample.csv' download='studentsSample.csv'><FONT FACE=arial COLOR=blue>[ ". get_string('downloadsamplefile', 'msociograma')." ]</FONT></A>"."<BR><BR>";
echo "<A HREF ='help/$lang/".get_string('howtoassigntutoringfile', 'msociograma').".pdf' TARGET='_blank'><FONT FACE=arial COLOR=blue>[ ". get_string('howtoassigntutoring', 'msociograma')." ]</FONT></A>"."<BR><BR>";
echo "<A HREF ='help/$lang/".get_string('howtoactivategroupfile', 'msociograma').".pdf' TARGET='_blank'><FONT FACE=arial COLOR=blue>[ ". get_string('howtoactivategroup', 'msociograma')." ]</FONT></A>"."<BR><BR>";
echo "<A HREF ='help/$lang/".get_string('howtoviewdatafile', 'msociograma').".pdf' TARGET='_blank'><FONT FACE=arial COLOR=blue>[ ". get_string('howtoviewdata', 'msociograma')." ]</FONT></A>"."<BR><BR>";
echo "<A HREF ='help/$lang/".get_string('howtousesociogramfile', 'msociograma').".pdf' TARGET='_blank'><FONT FACE=arial COLOR=blue>[ ". get_string('howtousesociogram', 'msociograma')." ]</FONT></A>"."<BR><BR>";
echo "<A HREF ='help/$lang/".get_string('howtoanswerquizfile', 'msociograma').".pdf' TARGET='_blank'><FONT FACE=arial COLOR=blue>[ ". get_string('howtoanswerquiz', 'msociograma')." ]</FONT></A>"."<BR><BR>";

echo "</p>";






