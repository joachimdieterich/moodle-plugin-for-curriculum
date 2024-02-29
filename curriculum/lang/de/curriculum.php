<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     mod_curriculum
 * @category    string
 * @copyright   2022 michaelpollak <moodle@michaelpollak.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Curriculum';
$string['modulename'] = 'Curriculum';
$string['modulenameplural'] = 'Curricula';
$string['curriculumname'] = 'Name';
$string['curriculumname_help'] = 'Bezeichnung der Curricula in Moodle.';
$string['currviews'] = 'Ansichten'; // Ansichten.
$string['currviews_areas'] = 'Bereiche'; // Bereiche.
$string['currviews_objectives'] = 'Bausteine'; //  Bausteine.
$string['logviews'] = 'Logbücher';
$string['kanviews'] = 'Pinnwände';
$string['groupviews'] = 'Gruppen';
$string['group_enrolments'] = 'Ausgewählte Aktivität(en) für Gruppe(n) freigeben';
$string['group_enrolments_help'] = 'Auswahl für Kurs-Nutzer:innen in curriculum freigeben';
$string['currselect'] = 'Lehrplan auswählen';
$string['currselectterminal'] = 'Bereich auswählen';
$string['currselectenabling'] = 'Baustein auswählen';
$string['logselect'] = 'Logbuch auswählen';
$string['kanselect'] = 'Pinnwand auswählen';
$string['groupselect'] = 'Gruppe auswählen';

$string['pluginadministration'] = 'Administration';
$string['serveroffline'] = 'Der Server scheint offline zu sein oder die Konfiguration ist fehlerhaft.';

// Admin settings.
$string['clientid'] = 'client_id';
$string['clientid_text'] = 'Die SSO client ID hier.';
$string['clientsecret'] = 'client_secret';
$string['clientsecret_text'] = 'Das SSO client secret hier.';
$string['clientssl'] = 'Nutze SSL';
$string['clientssl_text'] = 'Nutze SSL Verschlüsselung zum Curriculum Server.';
$string['serverurl'] = 'Serverurl';
$string['serverurl_text'] = 'Die Server URL des Curriculum Servers.';
$string['servertimeout'] = 'Servertimeout';
$string['servertimeout_text'] = 'Timeout für langsame Server anpassen.';
$string['commonname'] = 'Common Name';
$string['commonname_text'] = 'Leer lassen, zum Test kann hier jeder common name eingetragen werden.';
$string['gettoken'] = 'Aktiviere get_token';
$string['gettoken_text'] = 'Nutze das get_token Feature für SSO.';
$string['idpssourl'] = 'URL für IDP SSO';
$string['idpssourl_text'] = 'Stellt Curriculum-SSO sicher';


// Modform.
$string['logbook'] = 'Logbuch';
$string['kanban'] = 'Pinnwand';
$string['curricula'] = 'Curricula';
$string['group'] = 'Gruppen';

$string['linkcurriculum'] = 'Öffne Curriculum {$a} in einem neuem Tab.';
$string['linklogbook'] = 'Öffne {$a} in einem neuen Tab.';
$string['linkkanban'] = 'Öffne {$a} in einem neuen Tab.';

$string['curriculum:addinstance'] = 'Neue Instanz hinzufügen';
$string['exactlyone'] = 'Wählen Sie ein Curriculum um Bereiche und Bausteine zu selektieren.';

// Exceptions.
$string['pleaselogin'] = 'Sie müssen in Moodle angemeldet sein.';
$string['askadmin'] = 'Dieses Feature wurde vom Admin deaktiviert.';
$string['notoken'] = 'Es gibt noch keinen Token für diese*n Nutzer*in.';
