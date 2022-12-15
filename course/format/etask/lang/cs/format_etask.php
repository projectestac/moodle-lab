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
 * Strings for component eTask topics course format.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Topics format strings.
$string['addsections'] = 'Přidat témata';
$string['currentsection'] = 'Aktuální téma';
$string['editsection'] = 'Upravit téma';
$string['editsectionname'] = 'Upravit název tématu';
$string['deletesection'] = 'Odstranit téma';
$string['newsectionname'] = 'Nový název tématu {$a}';
$string['sectionname'] = 'Téma';
$string['pluginname'] = 'eTask tematické uspořádání';
$string['section0name'] = 'Úvod';
$string['page-course-view-topics'] = 'Hlavní stránka libovolného kurzu v eTask tématickém formátu';
$string['page-course-view-topics-x'] = 'Jakákoliv stránka kurzu s eTask tematickým uspořádáním';
$string['hidefromothers'] = 'Skrýt téma';
$string['showfromothers'] = 'Zobrazit téma';
$string['privacy:metadata'] = 'Modul Formát eTask tématické uspořádání neukládá žádné osobní údaje.';
// Course format settings strings.
$string['studentprivacy'] = 'Soukromí studenta';
$string['studentprivacy_help'] = 'Toto nastavení určuje, zda může student v tabulce hodnocení vidět známky ostatních nebo ne.';
$string['studentprivacy_no'] = 'Student může v tabulce hodnocení vidět známky ostatních';
$string['studentprivacy_yes'] = 'Student může v tabulce hodnocení vidět pouze své známky';
$string['gradeitemprogressbars'] = 'Plnění hodnocené položky';
$string['gradeitemprogressbars_help'] = 'Toto nastavení určuje, zda se má studentovi zobrazit plnění hodnocené položky v tabulce hodnocení.';
$string['gradeitemprogressbars_no'] = 'Skrýt studentovi plnění hodnocené položky v tabulce hodnocení';
$string['gradeitemprogressbars_yes'] = 'Zobrazit studentovi přehled plnění hodnocené položky v tabulce hodnocení';
$string['studentsperpage'] = 'Počet studentů na stránce';
$string['studentsperpage_help'] = 'Toto nastavení přepisuje defaultní hodnotu 10 studentů na stránce v tabulce hodnocení.';
$string['gradeitemssorting'] = 'Řazení hodnocených položek';
$string['gradeitemssorting_help'] = 'Toto nastavení určuje, zda jsou hodnocené položky v tabulce hodnocení řazeny od nejnovějších, nejstarších nebo tak, jak jsou v kurzu.';
$string['gradeitemssorting_latest'] = 'Řadit hodnocené položky v tabulce hodnocení od nejnovějších';
$string['gradeitemssorting_oldest'] = 'Řadit hodnocené položky v tabulce hodnocení od nejstarších';
$string['gradeitemssorting_inherit'] = 'Řadit hodnocené položky v tabulce hodnocení tak, jak jsou v kurzu';
$string['placement'] = 'Umístění';
$string['placement_help'] = 'Toto nastavení určuje umístění tabulky hodnocení nad nebo pod tématy kurzu.';
$string['placement_above'] = 'Umístit tabulku hodnocení nad tématy kurzu';
$string['placement_below'] = 'Umístit tabulku hodnocení pod tématy kurzu';
$string['passedlabel'] = 'Štítek splněno';
$string['passedlabel_help'] = 'Toto nastavení přepisuje defaultní text štítku Splněno.';
$string['failedlabel'] = 'Štítek nesplněno';
$string['failedlabel_help'] = 'Toto nastavení přepisuje defaultní text štítku Nesplněno.';
// Plugin settings strings.
$string['registeredduedatemodules'] = 'Registrované moduly s datem odevzdání';
$string['registeredduedatemodules_help'] = 'Určuje, v jakém databázovém poli modulu je ukládána hodnota data odevzdání.';
// Grading table strings.
$string['nostudentsfound'] = 'Nebyli nalezeni žádní studenti k hodnocení.';
$string['nogradeitemsfound'] = 'Nebyly nalezeny žádné položky hodnocení.';
// Grading table footer strings.
$string['gradeitemcompleted'] = 'Dokončeno';
$string['gradeitempassed'] = 'Splněno';
$string['gradeitemfailed'] = 'Nesplněno';
// Grade item popover strings.
$string['timemodified'] = 'Poslední úprava {$a}';
$string['max'] = 'max.';
$string['progresspercentage'] = '{$a} % <span class="text-black-50">všech studentů</span>';
$string['choose'] = 'Vyberte ...';
$string['showmore'] = 'Zobrazit více ...';
// Grading table help strings.
$string['helpabout'] = 'Tématické uspořádání eTask rozšiřuje Tématické uspořádání a poskytuje nejkratší způsob správy aktivit a jejich komfortního hodnocení. Kromě své přehlednosti vytváří motivující a soutěživé prostředí podporující pozitivní vzdělávací zkušenost.';
$string['helpimprovehead'] = 'Vylepšení pluginu';
$string['helpimprovebody'] = 'Pomozte nám vylepšit tento plugin! Napište zpětnou vazbu, nahlaste problém nebo vyplňte dostupné dotazníky na <a href="https://moodle.org/plugins/format_etask" target="_blank">stránce pluginu</a>.';
// Flash messages.
$string['gradepasschanged'] = 'Potřebná známka u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> byla úspěšně změněna na <strong>{$a->gradepass}</strong>.';
$string['gradepassremoved'] = 'Potřebná známka u&nbsp;hodnocené položky <strong>{$a}</strong> byla úšpěšně odstraněna.';
$string['gradepasserrdatabase'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a}</strong> nelze změnit. Prosím, zkuste to znovu později nebo kontaktujte vývojáře pluginu.';
$string['gradepasserrnumeric'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> nelze změnit na <strong>{$a->gradepass}</strong>. Musíte zadat číslo.';
$string['gradepasserrgrademax'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> nelze změnit na <strong>{$a->gradepass}</strong>. Hodnota je větší než max. hodnocení.';
$string['gradepasserrgrademin'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> nelze změnit na <strong>{$a->gradepass}</strong>. Hodnota je menší než min. hodnocení.';
