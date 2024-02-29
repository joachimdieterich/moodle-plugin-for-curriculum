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
 * Library of interface functions and constants.
 *
 * @package     mod_curriculum
 * @copyright   2022 michaelpollak <moodle@michaelpollak.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function curriculum_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_curriculum into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_curriculum_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function curriculum_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();

    $id = $DB->insert_record('curriculum', $moduleinstance);

    return $id;
}

/**
 * Updates an instance of the mod_curriculum in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_curriculum_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function curriculum_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('curriculum', $moduleinstance);
}

/**
 * Removes an instance of the mod_curriculum from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function curriculum_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('curriculum', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('curriculum', array('id' => $id));

    return true;
}

/**
 * Call API to get any api element with postfix.
 *
 * @param string $postfix any postfix after the api url.
 * @return array of applicable elements.
 */
function curriculum_get_elements($postfix) {
    return curriculum_get_data(curriculum_get_token(), $postfix);
}

/**
 * Call API to get one curricula.
 *
 * @param string $id of the element, if not set show all curricula.
 * @return array of applicable elements.
 */
function curriculum_get_curricula($id) {
    return curriculum_get_data(curriculum_get_token(), "v1/curricula/$id");
}

/**
 * Call API to get all curriculas.
 *
 * @param string $id of the element, if not set show all curricula.
 * @return array of applicable elements.
 */
function curriculum_get_all_curricula($common_name) {
    return curriculum_get_data(curriculum_get_token(), "v1/moodle/curricula?common_name=$common_name");
}


/**
 * Call API to get all logbooks.
 *
 * @param string $name of the element.
 * @return array of applicable elements.
 */
function curriculum_get_logbooks($common_name) {
    return curriculum_get_data(curriculum_get_token(), "v1/moodle/logbooks?common_name=$common_name");
}

/**
 * Call API to get all kanbans.
 *
 * @param string $name of the element.
 * @return array of applicable elements.
 */
function curriculum_get_kanbans($common_name) {
    return curriculum_get_data(curriculum_get_token(), "v1/moodle/kanbans?common_name=$common_name");
}

/**
 * Call API to get all groups.
 *
 * @param string $name of the element.
 * @return array of applicable elements.
 */

function curriculum_get_groups($common_name) {
    return curriculum_get_data(curriculum_get_token(), "v1/moodle/groups?common_name=$common_name");
}

function curriculum_enrol_user_to_group($data) {
    return curriculum_post_data(curriculum_get_token(), "v1/moodle/groups/enrol", $data);
}

/**
 * Trigger SSO, get Access token.
 *
 * @return string access token or false if unsuccessful.
 */
function curriculum_get_token() {
    global $CFG;

    // Check if token is already stored.
    if (isset($_COOKIE["currapitoken"])) {
      return $_COOKIE["currapitoken"];
    }

    // Try to sign in now.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, curriculum_get_serverurl() . 'oauth/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $CFG->mod_curriculum_timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array (
      'client_id' => $CFG->mod_curriculum_clientid,
      'client_secret' => $CFG->mod_curriculum_clientsecret,
      'grant_type' => "client_credentials"
    ));

    // Debugging curl.
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $result = curl_exec($ch);

    if ($result === FALSE) {
        printf("Folgender cUrl error ist aufgetreten:<br>#%d %s<br>\n",
               curl_errno($ch),
               htmlspecialchars(curl_error($ch)));
        return FALSE;
    }

    curl_close($ch);

    $result = json_decode($result);

    if (!headers_sent()) {
      setcookie("currapitoken", $result->access_token, time()+(3600*24), "/");  // Expires after 24 hours.
    }
    return $result->access_token;
}

/**
 * Setup the server url.
 *
 * @return string Complete server url.
 */
function curriculum_get_serverurl() {
    global $CFG;

    $http = "http";
    if ($CFG->mod_curriculum_ssl == 1) {
        $http = "https";
    }
    return $http . "://" . $CFG->mod_curriculum_serverurl . "/";
}

/**
 * Setup the server url.
 *
 * @return string Complete server url.
 */
function curriculum_get_sso_url() {
    global $CFG;

    return  $CFG->mod_curriculum_idpssourl ;
}



/**
 * Send GET.
 *
 * @param string token.
 * @return TODO.
 */
function curriculum_get_data($token, $postfix) {

    if ($token === FALSE) {
        return FALSE;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, curriculum_get_serverurl() . 'api/' . $postfix);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array (
       'Content-Type: application/json',
       'Authorization: Bearer ' . $token
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
}

/**
 * Send POST.
 *
 * @param string token.
 * @return TODO.
 */
function curriculum_post_data($token, $postfix, $params) {

    if ($token === FALSE) {
        return FALSE;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, curriculum_get_serverurl() . 'api/' . $postfix);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array (
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result);
}
