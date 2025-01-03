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
 * Strings for component eTask course format.
 *
 * @package   format_etask
 * @copyright 2020, Martin Drlik <martin.drlik@email.cz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['choose'] = 'Vyberte ...';
$string['currentsection'] = 'Aktuální sekce';
$string['failedlabel'] = 'Štítek nesplněno';
$string['failedlabel_help'] = 'Toto nastavení přepisuje defaultní text štítku Nesplněno.';
$string['gradeitemcompleted'] = 'Dokončeno';
$string['gradeitemfailed'] = 'Nesplněno';
$string['gradeitempassed'] = 'Splněno';
$string['gradeitemprogressbars'] = 'Plnění hodnocené položky';
$string['gradeitemprogressbars_help'] = 'Toto nastavení určuje, zda se má studentovi zobrazit plnění hodnocené položky v tabulce hodnocení.';
$string['gradeitemprogressbars_no'] = 'Skrýt studentovi plnění hodnocené položky v tabulce hodnocení';
$string['gradeitemprogressbars_yes'] = 'Zobrazit studentovi přehled plnění hodnocené položky v tabulce hodnocení';
$string['gradeitemssorting'] = 'Řazení hodnocených položek';
$string['gradeitemssorting_help'] = 'Toto nastavení určuje, zda jsou hodnocené položky v tabulce hodnocení řazeny od nejnovějších, nejstarších nebo tak, jak jsou v kurzu.';
$string['gradeitemssorting_inherit'] = 'Řadit hodnocené položky v tabulce hodnocení tak, jak jsou v kurzu';
$string['gradeitemssorting_latest'] = 'Řadit hodnocené položky v tabulce hodnocení od nejnovějších';
$string['gradeitemssorting_oldest'] = 'Řadit hodnocené položky v tabulce hodnocení od nejstarších';
$string['gradepasschanged'] = 'Potřebná známka u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> byla úspěšně změněna na <strong>{$a->gradepass}</strong>.';
$string['gradepasserrdatabase'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a}</strong> nelze změnit. Prosím, zkuste to znovu později nebo kontaktujte vývojáře pluginu.';
$string['gradepasserrgrademax'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> nelze změnit na <strong>{$a->gradepass}</strong>. Hodnota je větší než max. hodnocení.';
$string['gradepasserrgrademin'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> nelze změnit na <strong>{$a->gradepass}</strong>. Hodnota je menší než min. hodnocení.';
$string['gradepasserrnumeric'] = 'Potřebnou známku u&nbsp;hodnocené položky <strong>{$a->itemname}</strong> nelze změnit na <strong>{$a->gradepass}</strong>. Musíte zadat číslo.';
$string['gradepassremoved'] = 'Potřebná známka u&nbsp;hodnocené položky <strong>{$a}</strong> byla úšpěšně odstraněna.';
$string['helpabout'] = 'Formát eTask rozšiřuje formát Vlastní sekce a poskytuje nejkratší způsob správy aktivit a jejich komfortního hodnocení. Kromě své přehlednosti vytváří motivující a soutěživé prostředí podporující pozitivní vzdělávací zkušenost.';
$string['helpimprovebody'] = 'Pomozte nám vylepšit tento plugin! Napište zpětnou vazbu, nahlaste problém nebo vyplňte dostupné dotazníky na <a href="https://moodle.org/plugins/format_etask" target="_blank">stránce pluginu</a>.';
$string['helpimprovehead'] = 'Vylepšení pluginu';
$string['hidefromothers'] = 'Skrýt';
$string['indentation'] = 'Povolit odsazení na stránce kurzu';
$string['indentation_help'] = 'Umožňuje učitelům a dalším uživatelům s možností správy aktivit odsadit položky na stránce kurzu.';
$string['legacysectionname'] = 'Téma';
$string['max'] = 'max.';
$string['newsection'] = 'Nová sekce';
$string['nogradeitemsfound'] = 'Nebyly nalezeny žádné položky hodnocení.';
$string['nostudentsfound'] = 'Nebyli nalezeni žádní studenti k hodnocení.';
$string['page-course-view-topics'] = 'Hlavní stránka libovolného kurzu v eTask formátu';
$string['page-course-view-topics-x'] = 'Jakákoliv stránka kurzu v eTask formátu';
$string['passedlabel'] = 'Štítek splněno';
$string['passedlabel_help'] = 'Toto nastavení přepisuje defaultní text štítku Splněno.';
$string['placement'] = 'Umístění';
$string['placement_above'] = 'Umístit tabulku hodnocení nad sekcemi kurzu';
$string['placement_below'] = 'Umístit tabulku hodnocení pod sekcemi kurzu';
$string['placement_help'] = 'Toto nastavení určuje umístění tabulky hodnocení nad nebo pod sekcemi kurzu.';
$string['plugin_description'] = 'Hodnotící tabulka jako součást kurzu rozděleného do přizpůsobitelných sekcí';
$string['pluginname'] = 'eTask format';
$string['privacy:metadata'] = 'eTask format plugin neukládá žádné osobní údaje.';
$string['progresspercentage'] = '{$a} % <span class="text-black-50">všech studentů</span>';
$string['registeredduedatemodules'] = 'Registrované moduly s datem odevzdání';
$string['registeredduedatemodules_help'] = 'Určuje, v jakém databázovém poli modulu je ukládána hodnota data odevzdání.';
$string['section0name'] = 'Hlavní';
$string['section_highlight_feedback'] = 'Sekce {$a->name} zvýrazněna.';
$string['section_unhighlight_feedback'] = 'Zvýraznění odebráno ze sekce {$a->name}.';
$string['sectionname'] = 'Sekce';
$string['showfromothers'] = 'Zobrazit';
$string['showmore'] = 'Zobrazit více ...';
$string['studentprivacy'] = 'Soukromí studenta';
$string['studentprivacy_help'] = 'Toto nastavení určuje, zda může student v tabulce hodnocení vidět známky ostatních nebo ne.';
$string['studentprivacy_no'] = 'Student může v tabulce hodnocení vidět známky ostatních';
$string['studentprivacy_yes'] = 'Student může v tabulce hodnocení vidět pouze své známky';
$string['studentsperpage'] = 'Počet studentů na stránce';
$string['studentsperpage_help'] = 'Toto nastavení přepisuje defaultní hodnotu 10 studentů na stránce v tabulce hodnocení.';
$string['timemodified'] = 'Poslední úprava {$a}';
