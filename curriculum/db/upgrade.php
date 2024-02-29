<?php
// This file keeps track of upgrades to
// the data module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installation to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the methods of database_manager class
//
// Please do not forget to use upgrade_set_timeout()
// before any action that may take longer time to finish.

defined('MOODLE_INTERNAL') || die();

function xmldb_curriculum_upgrade($oldversion) {
    global $CFG, $DB;

      $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.
      if ($oldversion < 2022080804) {

        // Define field curriculum_elements to be added to curriculum.
        $table = new xmldb_table('curriculum');
        $field = new xmldb_field('curriculum_elements', XMLDB_TYPE_TEXT, null, null, null, null, null, 'introformat');

        // Conditionally launch add field curriculum_elements.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Curriculum savepoint reached.
        upgrade_mod_savepoint(true, 2022080804, 'curriculum');
    }


    if ($oldversion < 2022111101) {

      // Define fields curriculum_logbooks and kanbans to be added to curriculum.
      $table = new xmldb_table('curriculum');
      $field = new xmldb_field('logbook_elements', XMLDB_TYPE_TEXT, null, null, null, null, null, 'curriculum_elements');
      // Conditionally launch add field curriculum_elements.
      if (!$dbman->field_exists($table, $field)) {
          $dbman->add_field($table, $field);
      }

      $field = new xmldb_field('kanban_elements', XMLDB_TYPE_TEXT, null, null, null, null, null, 'logbook_elements');
      // Conditionally launch add field curriculum_elements.
      if (!$dbman->field_exists($table, $field)) {
          $dbman->add_field($table, $field);
      }

      // Curriculum savepoint reached.
      upgrade_mod_savepoint(true, 2022111101, 'curriculum');
    }

    if ($oldversion < 2022111501) {

      // Define fields curriculum_elements_areas and curriculum_elements_objectives to be added to curriculum.
      $table = new xmldb_table('curriculum');
      $field = new xmldb_field('curriculum_elements_areas', XMLDB_TYPE_TEXT, null, null, null, null, null, 'curriculum_elements');
      if (!$dbman->field_exists($table, $field)) {
          $dbman->add_field($table, $field);
      }

      $table = new xmldb_table('curriculum');
      $field = new xmldb_field('curriculum_elements_objectives', XMLDB_TYPE_TEXT, null, null, null, null, null, 'curriculum_elements_areas');
      if (!$dbman->field_exists($table, $field)) {
          $dbman->add_field($table, $field);
      }

      // Curriculum savepoint reached.
      upgrade_mod_savepoint(true, 2022111501, 'curriculum');
    }

    if ($oldversion < 2024010502) {

        // Define fields curriculum_logbooks and kanbans to be added to curriculum.
        $table = new xmldb_table('curriculum');
        $field = new xmldb_field('group_elements', XMLDB_TYPE_TEXT, null, null, null, null, null, 'curriculum_elements_areas');
        // Conditionally launch add field curriculum_elements.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Curriculum savepoint reached.
        upgrade_mod_savepoint(true, 2024010502, 'curriculum');
    }

    return true;
}
