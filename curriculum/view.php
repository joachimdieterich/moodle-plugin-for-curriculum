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
 * Prints an instance of mod_curriculum.
 *
 * @package     mod_curriculum
 * @copyright   2022 michaelpollak <moodle@michaelpollak.org>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
$PAGE->requires->jquery();
$PAGE->requires->js( new moodle_url($CFG->wwwroot . "/mod/curriculum/view.js"));

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$c = optional_param('c', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('curriculum', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('curriculum', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('curriculum', array('id' => $c), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('curriculum', $moduleinstance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

$event = \mod_curriculum\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('curriculum', $moduleinstance);
$event->trigger();

$PAGE->set_url('/mod/curriculum/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

// Set the cookie before any output occured.
$url = curriculum_get_serverurl();
$sso_url = curriculum_get_sso_url();
$token = curriculum_get_token();

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($moduleinstance->name), 2, null);

//Show intro first.
echo $OUTPUT->box(format_module_intro('curriculum', $moduleinstance, $cm->id), 'generalbox', 'intro');

$common_name = $USER->username;
// if (isset($CFG->mod_curriculum_commonname)) {
//    $common_name = $CFG->mod_curriculum_commonname;
// }

// We do assume that the server is online.
$serveronline = TRUE;

// We do assume curricula are used normally.
$curricula = json_decode($moduleinstance->curriculum_elements);
$selectedareas = json_decode($moduleinstance->curriculum_elements_areas);
$selectedobjectives = json_decode($moduleinstance->curriculum_elements_objectives);

if (is_array($curricula)) {
  foreach ($curricula as &$curriculum) {

    $curr = curriculum_get_curricula($curriculum);

    // Stop if server is offline or curriculum wasn't found.
    if ($curr === FALSE OR $curr == null) {
        echo $OUTPUT->notification(get_string('serveroffline', 'mod_curriculum'), 'info');
        $serveronline = FALSE;
        break;
    }

    echo $OUTPUT->heading(format_string($curr->title), 3, null);
    //echo $curr->description;

    $areas = curriculum_get_elements("v1/moodle/curricula/$curriculum/terminalObjectives?common_name=$common_name");

    if ($areas == FALSE) {
        break;
    }

    $selectedareas = json_decode($moduleinstance->curriculum_elements_areas);

    // Add Terminal Objectives as cards.
    foreach ($areas as &$area) {
        if (in_array($area->id, $selectedareas)){
            echo "<div class='card-deck' style='padding: .5rem;'>";
            echo "<a style='max-width: 18rem; background-color:$area->color; padding: 1rem;' class='card text-white' href='{$sso_url}{$url}terminalObjectives/{$area->id}'>";
            echo $area->title;
            echo "</a>";
            foreach ($area->enabling_objectives as &$objective) {
                if (in_array($objective->id, $selectedobjectives) ) {
                    echo "<a style='max-width: 18rem; padding: 1rem;' class='card text-dark' href='{$sso_url}{$url}enablingObjectives/{$objective->id}'>$objective->title</a>";
                }
            }
            echo "</div>";
        }
    }

      // Add Enabling objectives as cards.
      foreach ($areas as &$area) {
          foreach ($area->enabling_objectives as &$objective) {
              if (in_array($objective->id, $selectedobjectives) AND ($objective->terminal_objective_id === $area->id)) {
                  echo "<div class='card-deck' style='padding: .5rem;'>";
                  echo "<a style='max-width: 18rem; background-color:$area->color; padding: 1rem;' class='card text-white' href='{$sso_url}{$url}terminalObjectives/{$area->id}'>";
                  echo $area->title;
                  echo "</a>";
                  echo "<a style='max-width: 18rem; padding: 1rem;' class='card text-dark' href='{$sso_url}{$url}enablingObjectives/{$objective->id}'>$objective->title</a>";
                  echo "</div>";
              }
          }
      }

    echo "<a href='{$sso_url}{$url}curricula/{$curriculum}' target='_blank'>" . get_string('linkcurriculum', 'mod_curriculum', $curr->title) . "</a>";
    echo "<hr>";

  }
}

if ($serveronline === TRUE AND isset($moduleinstance->logbook_elements)) {
  $selectedlogbooks = json_decode($moduleinstance->logbook_elements);
  $alllogbooks = curriculum_get_logbooks($common_name);
  foreach ((array) $alllogbooks as &$singlelogbook) {
    if (isset($singlelogbook->id) AND in_array($singlelogbook->id, $selectedlogbooks)) {
      echo '<i class="icon fa fa-book"></i>';
      echo "<a href='{$sso_url}{$url}logbooks/{$singlelogbook->id}' target='_blank'>" . get_string('linklogbook', 'mod_curriculum', $singlelogbook->title) . "</a><br>";


        if ($moduleinstance->kanban_elements == "[]" AND count($curricula) == 0 AND $moduleinstance->logbook_elements == '["1"]') {
            echo '<div id="curriculum_forward" style="display:none">'.$sso_url.$url.'logbooks/'.$singlelogbook->id.'</div>';
        }
    }
  }
}

if ($serveronline === TRUE AND isset($moduleinstance->kanban_elements)) {
  $selectedkanbans = json_decode($moduleinstance->kanban_elements);
  $allkanbans = curriculum_get_kanbans($common_name);
  foreach ((array) $allkanbans as &$singlekanban) {
    if (isset($singlekanban->id) AND in_array($singlekanban->id, $selectedkanbans)) {
      echo '<i class="icon fa fa-columns"></i>';
      echo "<a href='{$sso_url}{$url}kanbans/{$singlekanban->id}' target='_blank'>" . get_string('linkkanban', 'mod_curriculum', $singlekanban->title) . "</a><br>";

      if ($moduleinstance->logbook_elements == "[]" AND count($curricula) == 0 AND $moduleinstance->kanban_elements == '["1"]') {
          echo '<div id="curriculum_forward" style="display:none">'.$sso_url.$url.'kanbans/'.$singlekanban->id.'</div>';
      }
    }
  }
}

echo $OUTPUT->footer();
