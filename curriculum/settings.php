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
 * Plugin administration pages are defined here.
 *
 * @package     mod_curriculum
 * @category    admin
 * @copyright   2022 michaelpollak <moodle@michaelpollak.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('mod_curriculum_settings', new lang_string('pluginname', 'mod_curriculum'));

    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_configtext('mod_curriculum_clientid', get_string('clientid', 'mod_curriculum'),
          get_string('clientid_text', 'mod_curriculum'), 1, PARAM_INT));
        $settings->add(new admin_setting_configtext('mod_curriculum_clientsecret', get_string('clientsecret', 'mod_curriculum'),
          get_string('clientsecret_text', 'mod_curriculum'), '', PARAM_TEXT));
        $settings->add(new admin_setting_configcheckbox('mod_curriculum_ssl', get_string('clientssl', 'mod_curriculum'),
          get_string('clientssl_text', 'mod_curriculum'), 1));
        $settings->add(new admin_setting_configtext('mod_curriculum_serverurl', get_string('serverurl', 'mod_curriculum'),
          get_string('serverurl_text', 'mod_curriculum'), '127.0.0.1:8000', PARAM_TEXT));
        $settings->add(new admin_setting_configtext('mod_curriculum_timeout', get_string('servertimeout', 'mod_curriculum'),
          get_string('servertimeout_text', 'mod_curriculum'), '10', PARAM_INT));
        $settings->add(new admin_setting_configtext('mod_curriculum_commonname', get_string('commonname', 'mod_curriculum'),
          get_string('commonname_text', 'mod_curriculum'), '', PARAM_TEXT));
        $settings->add(new admin_setting_configcheckbox('mod_curriculum_gettoken', get_string('gettoken', 'mod_curriculum'),
          get_string('gettoken_text', 'mod_curriculum'), 1));
        $settings->add(new admin_setting_configtext('mod_curriculum_idpssourl', get_string('idpssourl', 'mod_curriculum'),
            get_string('idpssourl_text', 'mod_curriculum'), '', PARAM_TEXT));
    }
}
