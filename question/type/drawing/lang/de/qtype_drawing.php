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
 * Strings for component 'qtype_drawing', language 'de'.
 *
 * @package    qtype
 * @subpackage drawing
 * @author Amr Hourani amr.hourani@id.ethz.ch, Kristina Isacson kristina.isacson@let.ethz.ch
 * @copyright  ETHZ LET <amr.hourani@id.ethz.ch>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Freihandzeichnen (ETH)';
$string['pluginname_help'] = 'Als Antwort auf eine Frage zeichnet der Befragte eine Antwort auf ein vordefiniertes Bild. Es gibt nur eine richtige Antwort.';
$string['pluginname_link'] = 'Frage/Typ/Zeichnen';
$string['pluginnameadding'] = 'Hinzufügen einer Freihandzeichnen Frage';
$string['pluginnameediting'] = 'Bearbeiten einer Freihandzeichnen Frage';
$string['pluginnamesummary'] = 'Als Antwort auf eine Frage zeichnet der Befragte eine Antwort auf ein vordefiniertes Bild. Es gibt nur eine richtige Antwort.';
$string['threshold_for_correct_answers'] = 'Grenze für richtige Antworten (%)';

$string['answer'] = 'Antwort: {$a}';
$string['correctansweris'] = 'Die richtige Antwort ist: {$a}.';
$string['pleaseenterananswer'] = 'Bitte geben Sie eine Antwort.';
$string['drawing_background_image'] = 'Hintergrundbild';
$string['drawingrawdata'] = '';
$string['backgroundfilemustbegiven'] = 'Sie müssen eine Datei als Hintergrundbild angeben.';
$string['drawingmustbegiven'] = 'Sie müssen eine Zeichnung einfügen.';
$string['drawanswer'] = 'Zeichnen Sie eine Lösung';
$string['drawing'] = 'Zeichnung';
$string['accepted_background_image_file_types'] = 'Akzeptierte Dateitypen';
$string['nobackgroundimageselectedyet'] = 'Kein Hintergrundbild ausgewählt.';
$string['are_you_sure_you_want_to_erase_the_canvas'] = 'Sind Sie sicher, dass Sie ihre Lösung löschen wollen?';
$string['are_you_sure_you_want_to_pick_a_new_bgimage'] = 'Möchten Sie das Hintergrundbild wirklich auswechseln? Das könnte einen Einfluss auf bereits gezeichnete Lösungen haben.';
$string['are_you_sure_you_want_to_change_the_drawing_radius'] = 'Falls Sie Ihren Zeichnungsbereich jetzt ändern, wird es Ihre Lösung löschen. Wollen Sie das?';
$string['set_radius'] = 'Stiftgrösse festlegen (pixel)';
$string['threshold_must_be_reasonable'] = 'Sie müssen eine vernünftige Grenze wählen';
$string['erase_canvas'] = 'Leeren';
$string['no_response_summary'] = 'Keine Antwortzusammenfassung';
$string['no_correct_answer_summary'] = 'Keine richtige Antwortzusammenfassung für Freihandzeichnen Fragetypen.';
$string['out_of_necessary'] = 'notwendigerweise';
$string['selected_background_image_filename'] = 'Gewähltes Hintergrundbild';
$string['enterfullscreen'] = 'Vollbild';
$string['exitfullscreen'] = 'Vollbild verlassen';
$string['zoomin'] = 'Vergrössern';
$string['zoomout'] = 'Verkleinern';
$string['redo_drawing'] = 'Wiederherstellen';
$string['privacy:metadata'] = 'Das Freihandzeichnen Plugin speichert keine persönlichen Daten.';
$string['canvasspecs'] = 'Zeichnungsspezifikationen';
$string['basicmode'] = 'Standard Modus';
$string['advancedmode'] = 'Erweiterter Modus';
$string['drawingmode'] = 'Zeichnungsmodus';
$string['drawingmode_help'] = 'Mit dieser Einstellung, kann definiert werden, ob die Zeichnung mit dem Standard Modus oder mit dem Erweiterten Modus gemacht werden soll.';
$string['backgroundwidth'] = 'Breite der Zeichnung';
$string['backgroundheight'] = 'Höhe der Zeichnung';
$string['preserveaspectratio'] = 'Das Bildseitenverhältnis beibehalten';
$string['canvassize'] = 'Grösse der Bildfläche';
$string['allowstudentimage'] = 'Hochladen eines Hintergrundbildes für Studierende erlauben.';
$string['allowstudentimage_help'] = 'Erlaubt den Studierenden das Hochladen eines Hintergrundbilds.';
$string['cut'] = 'Ausschneiden';
$string['copy'] = 'Kopieren';
$string['paste'] = 'Einfügen';
$string['duplicate'] = 'Duplizieren';
$string['delete'] = 'Löschen';
$string['bringtofront'] = 'In den Vordergrund bringen';
$string['bringforward'] = 'Nach vorne bringen';
$string['sendbackward'] = 'Nach hinten schicken';
$string['sendtoback'] = 'In den Hintergrund schicken';
$string['groupelements'] = 'Elemente gruppieren';
$string['ungroupelements'] = 'Gruppe aufheben';
$string['converttopath'] = 'In einen Pfad umwandeln';
$string['reorientpath'] = 'In einen Pfad umorientieren';
$string['view'] = 'Ansicht';
$string['viewrulers'] = 'Lineal anzeigen';
$string['viewwireframe'] = 'Wireframe anzeigen';
$string['snaptogrid'] = 'Am Raster ausrichten';
$string['source'] = 'Quelle';
$string['drawingpresets'] = 'Einstellungen';
$string['changestroke'] = 'Strich ändern';
$string['strokewidth'] = 'Strichbreite';
$string['dashstyle'] = 'Strichstil ändern';
$string['strokedash'] = 'Strichart';
$string['deleteobject'] = 'Objekt löschen';
$string['changerotationangle'] = 'Rotationswinkel ändern';
$string['rotation'] = 'Rotation';
$string['opacity'] = 'Deckkraft';
$string['changeopacity'] = 'Objektdeckkraft ändern';
$string['changeblur'] = 'Objekt Schärfe ändern';
$string['blur'] = 'Schärfe';
$string['roundness'] = 'Rundung';
$string['changecornerradius'] = 'Eckenradius ändern';
$string['align'] = 'Ausrichten';
$string['rectangle'] = 'Rechteck';
$string['width'] = 'Breite';
$string['height'] = 'Höhe';
$string['path'] = 'Pfad';
$string['image'] = 'Bild';
$string['circle'] = 'Kreis';
$string['centerx'] = 'Achse X';
$string['centery'] = 'Achse Y';
$string['ellipse'] = 'Ellipse';
$string['radiusx'] = 'Radius X';
$string['radiusy'] = 'Radius Y';
$string['line'] = 'Linie';
$string['startx'] = 'Start X';
$string['starty'] = 'Start Y';
$string['endx'] = 'End X';
$string['endy'] = 'End Y';
$string['text'] = 'Text';
$string['font'] = 'Font';
$string['fontsize'] = 'Grösse';
$string['group'] = 'Gruppieren';
$string['editpath'] = 'Pfad ändern';
$string['segmenttype'] = 'Segmenttyp';
$string['straight'] = 'Gerade';
$string['curve'] = 'Kurve';
$string['addnode'] = 'Verbindung hinzufügen';
$string['deletenode'] = 'Verbindung löschen';
$string['openpath'] = 'Pfad öffnen';
$string['multipleelements'] = 'Mehrere Elemente';
$string['aligntoobjects'] = 'An Objekten ausrichten';
$string['aligntopage'] = 'An Seite ausrichten';
$string['strokejoin'] = 'Strich verbinden';
$string['strokecap'] = 'Strich Obergrenze';
$string['selecttool'] = 'Tool auswählen';
$string['drawingtool'] = 'Stift Tool';
$string['linetool'] = 'Lineal Tool';
$string['texttool'] = 'Text Tool';
$string['recttool'] = 'Rechteck Tool';
$string['ellipsetool'] = 'Kreis Tool';
$string['pathtool'] = 'Pfad Tool';
$string['switchstrokefill'] = 'Strich und Füllfarbe ändern';
$string['changefill'] = 'Füllfarbe ändern';
$string['changestrokecolor'] = 'Strichfarbe ändern';
$string['zoomtool'] = 'Vergrösserungstool';
$string['changezoom'] = 'Zoomfaktor ändern';
$string['copysvgsrc'] = 'Inhalt dieser Box in ein Texteditor kopieren und die Datei mit einer .svg Endung speichern.';
$string['done'] = 'Erledigt';
$string['cancel'] = 'Abbrechen';
$string['applychanges'] = 'Änderungen anbringen';
$string['object'] = 'Objekt';
$string['ungroup'] = 'Gruppierung aufheben';
$string['edittext'] = 'Text bearbeiten';
$string['size'] = 'Grösse';
$string['color'] = 'Farbe';
$string['file'] = 'Datei';
$string['edit'] = 'Bearbeiten';
$string['erasedrawing'] = 'Zeichnung löschen';
$string['drawingcomment'] = 'Mit ETHz Freihandzeichnen Fragetyp für Moodle erstellt.';
$string['newconfirmationmsg'] = 'Möchten Sie eine neue Datei öffnen?\nDies wird Ihren Zeichnungsverlauf löschen.';
$string['eraseconfirmationmsg'] = '<strong>Möchten Sie die Zeichnung löschen?</strong>\nDies wird Ihren Zeichnungsverlauf löschen';
$string['parsingerror'] = 'Es gab Parsing-Fehler in Ihrer SVG Quelle.\nMöchten Sie die ursprüngliche SVG Quelle wieder herstellen?';
$string['ignorechanges'] = 'Änderungen in der SVG Quelle ignorieren?';
$string['defaultcanvaswidth'] = 'Standard Zeichenfläche (Canvas) Breite';
$string['defaultcanvaswidth_help'] = 'Hier stellen Sie die Standard-Breite der Zeichenfläche ein';
$string['defaultcanvasheight'] = 'Standard Zeichenfläche (Canvas) Höhe';
$string['defaultcanvasheight_help'] = 'Hier stellen Sie die Standard-Höhe der Zeichenfläche ein';
$string['allowteachertochosemode'] = 'Den Teachers erlauben, den Zeichnungsmodus zu wählen?';
$string['allowteachertochosemode_help'] = 'Wenn diese Option aktiviert ist, dürfen Teachers den Zeichnungsmodus wählen';
$string['configintro'] = 'Freihandzeichnen site-level Konfiguration';
$string['tasktitle'] = 'Aufgabentitel';
$string['maxpoints'] = 'Max. Punkte';
$string['stem'] = 'Stamm';
$string['enterstemhere'] = 'Stamm in Form einer Frage oder einer Aussage eingeben.';
$string['generalfeedback'] = 'Allgemeines Feedback';
$string['generalfeedback_help'] = 'Allgemeines Feedback wird unabhängig von der gegebenen Antwort angezeigt.<br/>Allgemeines Feedback kann eingesetzt werden, um korrekte Antworten zu erläutern oder Links zu weiteren Informationen zu geben.';
$string['ok'] = 'OK';
$string['cancel'] = 'Abbrechen';
$string['eyedroppertool'] = 'Pipettentool';
$string['shapelibrary'] = 'Formenbibliothek';
$string['drawmarkers'] = 'Ziehen Sie die Markierungen, um eine Farbe auszuwählen';
$string['solidcolor'] = 'Einfarbig';
$string['lingrad'] = 'Gleichförmiger Verlauf';
$string['radgrad'] = 'Radialer Verlauf';
$string['new'] = 'Neu';
$string['current'] = 'Aktuell';
$string['viewgrid'] = 'Raster anzeigen';
$string['annotation'] = 'Annotation';
$string['originalanswer'] = 'Originalzeichnung';
$string['by'] = 'Von: ';
$string['saveannotation'] = 'Annotation speichern';
$string['annotationsaved'] = 'Annotation gespeichert.';
$string['saving'] = 'Am speichern..';
$string['studentview'] = 'Studierendenansicht';
$string['showanswer'] = 'Antwort anzeigen';
$string['showannotation'] = 'Annotation anzeigen';
$string['enableeraser'] = 'Den Teachers erlauben, den Radiergummi zu wählen?';
$string['enableeraser_help'] = 'Wenn diese Option aktiviert ist, dürfen Teachers den Radiergummi wählen';
$string['alloweraser'] = 'Radiergummi erlauben';
$string['drawing_settings'] = 'Zeichnungs-Einstellungen';