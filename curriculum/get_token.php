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
 * Pass along privatetoken for curriculum single sign on.
 *
 * @package     mod_curriculum
 * @copyright   2022 michaelpollak <moodle@michaelpollak.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');

global $CFG, $DB, $USER;

// Testen ob wir angemeldet sind.
if (!isloggedin()) {
	throw new moodle_exception(get_string('pleaselogin', 'mod_curriculum'));
}

// Testen ob der Admin das erlaubt.
if (isset($CFG->mod_curriculum_gettoken) AND $CFG->mod_curriculum_gettoken != 1) {
	throw new moodle_exception(get_string('askadmin', 'mod_curriculum'));
}

// Prüfen ob Privatetoken existiert.
if (!$valid = $DB->get_record('external_tokens', array('userid' => $USER->id))) {

	// Prüfen ob der webservice aktiv ist.
	$service = $DB->get_record('external_services', array('shortname' => "moodle_mobile_app", 'enabled' => 1));
	if (empty($service)) {
		throw new moodle_exception('servicenotavailable', 'webservice');
	}

	// Neuen Token anlegen.
	require_once($CFG->libdir . '/externallib.php');
	$valid = external_generate_token_for_current_user($service);

	// Wenn kein Token erstellt wurde werfen wir eine Meldung.
	if (empty($valid->privatetoken)) {
		throw new moodle_exception(get_string('notoken', 'mod_curriculum'));
	}

}

$api["privatetoken"] = $valid->privatetoken;
echo json_encode($api);
?>
