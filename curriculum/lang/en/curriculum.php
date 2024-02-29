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
$string['curriculumname_help'] = 'Name of the curriculum or curricula.';
$string['currviews'] = 'Views'; // Ansichten.
$string['currviews_areas'] = 'Areas'; // Bereiche.
$string['currviews_objectives'] = 'Objectives'; //  Bausteine.
$string['logviews'] = 'Logbooks';
$string['kanviews'] = 'Kanbans';
$string['groupviews'] = 'Groups';
$string['group_enrolments'] = 'Enrol user(s) from group(s)';
$string['group_enrolments_help'] = 'Enrol course user(s) to selected group(s) in curriculum';
$string['currselect'] = 'Select curriculum';
$string['currselectterminal'] = 'Select Terminal Objective';
$string['currselectenabling'] = 'Select Enabling Objective';
$string['logselect'] = 'Select logbook';
$string['kanselect'] = 'Select kanban';
$string['groupselect'] = 'Select group';

$string['pluginadministration'] = 'Administration';
$string['serveroffline'] = 'The server seems to be offline or configured incorrectly.';

// Admin settings.
$string['clientid'] = 'client_id';
$string['clientid_text'] = 'Add the SSO client ID here.';
$string['clientsecret'] = 'client_secret';
$string['clientsecret_text'] = 'Add the SSO client secret here.';
$string['clientssl'] = 'Use SSL';
$string['clientssl_text'] = 'Use SSL encryption to the curriculum server.';
$string['serverurl'] = 'Serverurl';
$string['serverurl_text'] = 'Add the url of the Curriculum server.';
$string['servertimeout'] = 'Servertimeout';
$string['servertimeout_text'] = 'Change the timeout for slower servers.';
$string['commonname'] = 'Common Name';
$string['commonname_text'] = 'Leave blank in production, for testing any common name can be added.';
$string['gettoken'] = 'Enable get_token';
$string['gettoken_text'] = 'Leave blank if you don\' t use the get_token feature.';
$string['idpssourl'] = 'URL for IDP SSO';
$string['idpssourl_text'] = 'Trigger Curriculum-SSO on links';

// Modform.
$string['logbook'] = 'Logbooks';
$string['kanban'] = 'Kanbans';
$string['curricula'] = 'Curricula';
$string['group'] = 'Groups';

$string['linkcurriculum'] = 'Open the curriculum {$a} in a new tab.';
$string['linklogbook'] = 'Open {$a} in a new tab';
$string['linkkanban'] = 'Open {$a} in a new tab';

$string['curriculum:addinstance'] = 'Add a new instance';
$string['exactlyone'] = 'To select specific terminal and enabling objectives, choose exactly one curriculum. Then save and edit again to load objectives.';

// Exceptions.
$string['pleaselogin'] = 'You have to be logged in to Moodle.';
$string['askadmin'] = 'This feature was disabled by the admin.';
$string['notoken'] = 'There is no token set up for this user.';
