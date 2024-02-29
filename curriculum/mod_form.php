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
 * The main mod_curriculum configuration form.
 *
 * @package     mod_curriculum
 * @copyright   2022 michaelpollak <moodle@michaelpollak.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
$PAGE->requires->jquery();
$PAGE->requires->js( new moodle_url($CFG->wwwroot . "/mod/curriculum/module.js"));
/**
 * Module instance settings form.
 *
 * @package     mod_curriculum
 * @copyright   2022 michaelpollak <moodle@michaelpollak.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_curriculum_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $USER;

        $common_name = $USER->username;
        // if (isset($CFG->mod_curriculum_commonname)) {
        //    $common_name = $CFG->mod_curriculum_commonname;
        // }

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('curriculumname', 'mod_curriculum'), array('size' => '64'));

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'curriculumname', 'mod_curriculum');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Add kanban.
        $mform->addElement('header', 'kanban', get_string('kanban', 'mod_curriculum'));
        $mform->setExpanded('kanban');

        // Show autoselect of all curriculum elements.
        $areanames = array();
        $allkanbans = curriculum_get_kanbans($common_name);

        // If false stop here, show error and help.
        if ($allkanbans === FALSE) {
            $mform->addElement('html', '<div class="alert alert-danger" role="alert">' . get_string('serveroffline', 'mod_curriculum') . '</div>');
        }

        if (is_array($allkanbans)) {
            foreach ($allkanbans as &$kanban) {
                $areanames[$kanban->id] = $kanban->title;
            }

            $options = array(
                'multiple' => true,
                'placeholder' => get_string('kanselect', 'mod_curriculum'),
            );
            $mform->addElement('autocomplete', 'kanban_elements', get_string('kanviews', 'mod_curriculum'), $areanames, $options);
        }

        // Add logbook.
        $mform->addElement('header', 'logbook', get_string('logbook', 'mod_curriculum'));

        // Show autoselect of all curriculum elements.
        $areanames = array();
        $alllogbooks = curriculum_get_logbooks($common_name);

        // If false stop here, show error and help.
        if ($alllogbooks === FALSE) {
            $mform->addElement('html', '<div class="alert alert-danger" role="alert">' . get_string('serveroffline', 'mod_curriculum') . '</div>');
        }

        if (is_array($alllogbooks)) {
            foreach ($alllogbooks as &$logbook) {
                $areanames[$logbook->id] = $logbook->title;
            }

            $options = array(
                'multiple' => true,
                'placeholder' => get_string('logselect', 'mod_curriculum'),
            );
            $mform->addElement('autocomplete', 'logbook_elements', get_string('logviews', 'mod_curriculum'), $areanames, $options);
        }

        // Add curricula.
        $mform->addElement('header', 'curricula', get_string('curricula', 'mod_curriculum'));

        // Show autoselect of all curriculum elements.
        $areanames = array();
        $token = curriculum_get_token();
        $allcurricula = curriculum_get_all_curricula($common_name);

        // If false stop here, show error and help.
        if ($allcurricula === FALSE) {
            $mform->addElement('html', '<div class="alert alert-danger" role="alert">' . get_string('serveroffline', 'mod_curriculum') . '</div>');
        }

        if (is_array($allcurricula)) {
            foreach ($allcurricula as &$curriculum) {
                $currnames[$curriculum->id] = $curriculum->title;
            }

            $options = array(
                'multiple' => true,
                'placeholder' => get_string('currselect', 'mod_curriculum'),
            );
            $mform->addElement('autocomplete', 'curriculum_elements', get_string('currviews', 'mod_curriculum'), $currnames, $options);
        }

        // If curricula is selected and only one visible show bereiche to select.
        $mform->addElement('static', 'description', "", get_string('exactlyone', 'mod_curriculum'));

        // If we see exactly one curriculum_elements we look for areas and objectives.
        $current = $this->get_current();
        if (isset($current->curriculum_elements)) {
          $current_elements = json_decode($current->curriculum_elements);
        }

        if (isset($current_elements) AND count($current_elements) == 1) {
          $areas = curriculum_get_elements("v1/moodle/curricula/" . $current_elements['0'] . "/terminalObjectives?common_name=$common_name");
          $areanames = array();
          foreach ($areas as &$area) {
            $areanames[$area->id] = $area->title;
          }
            $options = array(
                'multiple' => true,
                'placeholder' => get_string('currselectterminal', 'mod_curriculum'),
            );
          $mform->addElement('autocomplete', 'curriculum_elements_areas', get_string('currviews_areas', 'mod_curriculum'), $areanames, $options);
          $mform->setAdvanced('curriculum_elements_areas');

          // TODO: Show only objectives that can be reached with the areas.
          $objectives = curriculum_get_elements("v1/moodle/curricula/" . $current_elements['0'] . "/enablingObjectives?common_name=$common_name");
          $objectivenames = array();
          foreach ($objectives as &$objective) {
            $objectivenames[$objective->id] = $objective->title;
          }
            $options = array(
                'multiple' => true,
                'placeholder' => get_string('currselectenabling', 'mod_curriculum'),
            );
          $mform->addElement('autocomplete', 'curriculum_elements_objectives', get_string('currviews_objectives', 'mod_curriculum'), $objectivenames, $options);
          $mform->setAdvanced('curriculum_elements_objectives');
        }

        // Show Groups selector for enrolment
        $mform->addElement('header', 'group', get_string('group_enrolments', 'mod_curriculum'));
        $mform->setExpanded('group', true);

        // Show autoselect of all groups .
        $areanames = array();
        $allgroups = curriculum_get_groups($common_name);

        // If false stop here, show error and help.
        if ($allgroups === FALSE) {
            $mform->addElement('html', '<div class="alert alert-danger" role="alert">' . get_string('serveroffline', 'mod_curriculum') . '</div>');
        }

        if (is_array($allgroups)) {
            foreach ($allgroups as &$group) {
                $areanames[$group->id] = $group->title;
            }

            $options = array(
                'multiple' => true,
                'placeholder' => get_string('groupselect', 'mod_curriculum'),
            );
            $mform->addElement('autocomplete', 'group_elements', get_string('groupviews', 'mod_curriculum'), $areanames, $options);
            $mform->addElement('static', 'description', "", get_string('group_enrolments_help', 'mod_curriculum'));

        }

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }

    /**
     * Modify data for display in mod_form.
     *
     * @param stdClass $data the form data to be modified.
     */
    function data_preprocessing(&$default_values){
        if (isset($default_values['curriculum_elements'])) {
            $default_values['curriculum_elements'] = json_decode($default_values['curriculum_elements']);
        }
        if (isset($default_values['curriculum_elements_areas'])) {
            $default_values['curriculum_elements_areas'] = json_decode($default_values['curriculum_elements_areas']);
        }
        if (isset($default_values['curriculum_elements_objectives'])) {
            $default_values['curriculum_elements_objectives'] = json_decode($default_values['curriculum_elements_objectives']);
        }

        if (isset($default_values['logbook_elements'])) {
            $default_values['logbook_elements'] = json_decode($default_values['logbook_elements']);
        }

        if (isset($default_values['kanban_elements'])) {
            $default_values['kanban_elements'] = json_decode($default_values['kanban_elements']);
        }

        if (isset($default_values['group_elements'])) {
            $default_values['group_elements'] = json_decode($default_values['group_elements']);
        }
    }

    /**
     * Modify data before storing in db.
     *
     * @param stdClass $data the form data to be modified.
     */
    public function data_postprocessing($data) {
        // send enrolled users to curriculum to check enrolment
        global $COURSE, $USER;

        parent::data_postprocessing($data);
        curriculum_enrol_user_to_group([
            'common_name' => $USER->username,
            'groups' => $data->group_elements,
            'users' => get_enrolled_users(context_course::instance($COURSE->id)),
            'curricula' => $data->curriculum_elements,
            'logbooks' => $data->logbook_elements,
            'kanbans' => $data->kanban_elements,

        ]);


        if (isset($data->curriculum_elements)) {
          $data->curriculum_elements = json_encode($data->curriculum_elements);
        }

        if (isset($data->curriculum_elements_areas)) {
          $data->curriculum_elements_areas = json_encode($data->curriculum_elements_areas);
        }

        if (isset($data->curriculum_elements_objectives)) {
          $data->curriculum_elements_objectives = json_encode($data->curriculum_elements_objectives);
        }

        if (isset($data->logbook_elements)) {
          $data->logbook_elements = json_encode($data->logbook_elements);
        }

        if (isset($data->kanban_elements)) {
          $data->kanban_elements = json_encode($data->kanban_elements);
        }

        if (isset($data->group_elements)) {
            $data->group_elements = json_encode($data->group_elements);
        }

    }
}
