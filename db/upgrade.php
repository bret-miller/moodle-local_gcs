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
 * Database upgrades for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade local_gcs database tables.
 *
 * @param int $oldversion
 * @return bool always true
 */
function xmldb_local_gcs_upgrade($oldversion = 0) {
    global $DB;
    $dbman = $DB->get_manager();

    // Index on program_code so that program codes are unique.
    if ($oldversion < 2023092100) {
        // Define table local_gcs_program to be created.
        $table = new xmldb_table('local_gcs_program');

        // Adding fields to table local_gcs_program.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('program_code', XMLDB_TYPE_CHAR, '5', null, XMLDB_NOTNULL, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_CHAR, '60', null, XMLDB_NOTNULL, null, null);
        $table->add_field('academic_degree', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('inactive', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_gcs_program.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_program.
        $table->add_index('ix_program_code', XMLDB_INDEX_UNIQUE, ['program_code']);

        // Conditionally launch create table for local_gcs_program.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023092100, 'local', 'gcs');
    }

    // Add new local_gcs_codes table as a place to store code descriptions.
    if ($oldversion < 2023092700) {

        // Define table local_gcs_codes to be created.
        $table = new xmldb_table('local_gcs_codes');

        // Adding fields to table local_gcs_codes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('codeset', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
        $table->add_field('code', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_CHAR, '128', null, null, null, null);

        // Adding keys to table local_gcs_codes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_codes.
        $table->add_index('ix_codeset_code', XMLDB_INDEX_UNIQUE, ['codeset', 'code']);

        // Conditionally launch create table for local_gcs_codes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023092700, 'local', 'gcs');
    }

    // Add new local_gcs_term_dates table to define academic calendar terms (periods of study).
    if ($oldversion < 2023100301) {

        // Define table local_gcs_term_dates to be created.
        $table = new xmldb_table('local_gcs_term_dates');

        // Conditionally launch drop table for local_gcs_term_dates if it already exists.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Adding fields to table local_gcs_term_dates.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('term_year', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('term_code', XMLDB_TYPE_CHAR, '1', null, null, null, null);
        $table->add_field('term_name', XMLDB_TYPE_CHAR, '32', null, null, null, null);
        $table->add_field('accounting_code', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('accounting_title', XMLDB_TYPE_CHAR, '64', null, null, null, null);
        $table->add_field('registration_start', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('registration_end', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('classes_start', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('classes_end', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('address', XMLDB_TYPE_CHAR, '64', null, null, null, null);
        $table->add_field('city', XMLDB_TYPE_CHAR, '30', null, null, null, null);
        $table->add_field('state', XMLDB_TYPE_CHAR, '2', null, null, null, null);
        $table->add_field('zip', XMLDB_TYPE_CHAR, '10', null, null, null, null);

        // Adding keys to table local_gcs_term_dates.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_term_dates.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023100301, 'local', 'gcs');
    }

    // Add accounting scholarship category table.
    if ($oldversion < 2023100302) {

        // Define table local_gcs_acct_sch_cat to be created.
        $table = new xmldb_table('local_gcs_acct_sch_cat');

        // Adding fields to table local_gcs_acct_sch_cat.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('code', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('scholarship', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('result', XMLDB_TYPE_CHAR, '20', null, null, null, null);

        // Adding keys to table local_gcs_acct_sch_cat.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_acct_sch_cat.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023100302, 'local', 'gcs');
    }

    // Added the courses table.
    if ($oldversion < 2023101201) {

        // Define table local_gcs_courses to be created.
        $table = new xmldb_table('local_gcs_courses');

        // Adding fields to table local_gcs_courses.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course_seq', XMLDB_TYPE_INTEGER, '3', null, null, null, null);
        $table->add_field('course_code', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('short_title', XMLDB_TYPE_CHAR, '40', null, null, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '60', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('course_hours', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('lectures', XMLDB_TYPE_INTEGER, '2', null, null, null, null);
        $table->add_field('required_textbooks', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('default_instructor', XMLDB_TYPE_INTEGER, '5', null, null, null, null);
        $table->add_field('comments', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table local_gcs_courses.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_courses.
        $table->add_index('ix_course_code', XMLDB_INDEX_UNIQUE, ['course_code']);

        // Conditionally launch create table for local_gcs_courses.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023101201, 'local', 'gcs');
    }

    // Create our Angular module cache & custom fields.
    if ($oldversion < 2023101302) {
        // See if our custom profile field category exists.
        global $DB;
        $cat = $DB->get_record(
                'user_info_category',
                [ 'name' => 'GCS Program Management' ]
            );
        // If not, create it.
        if (!$cat) {
            $catid = $DB->insert_record(
                'user_info_category',
                [ 'id' => 0, 'name' => 'GCS Program Management', 'sortorder' => 0 ]
            );
            $cat = $DB->get_record(
                'user_info_category',
                [ 'id' => $catid ]
            );
        }

        // See if our custom instructor field is present.
        $fld = $DB->get_record(
                'user_info_field',
                [ 'shortname' => 'local_gcs_instructor' ]
            );
        // If not, create it.
        if (!$fld) {
            $catid = $DB->insert_record(
                'user_info_field', [
                    'id' => 0,
                    'shortname' => 'local_gcs_instructor',
                    'name' => 'Instructor?',
                    'datatype' => 'checkbox',
                    'description' => 'Indicates this person is an instructor for GCS Program Management',
                    'descriptionformat' => 0,
                    'categoryid' => $cat->id,
                    'sortorder' => 1,
                    'required' => 0,
                    'locked' => 1,
                    'visible' => 2,
                    'forceunique' => 0,
                    'signup' => 0,
                    'defaultdata' => 0,
                    'defaultdataformat' => 0,
                    'param1' => null,
                    'param2' => null,
                    'param3' => null,
                    'param4' => null,
                    'param5' => null,
                    ]
            );
        }

        // See if our custom fullname field is present.
        $fld = $DB->get_record(
                'user_info_field',
                [ 'shortname' => 'local_gcs_fullname' ]
            );
        // If not, create it.
        if (!$fld) {
            $catid = $DB->insert_record(
                'user_info_field', [
                    'id' => 0,
                    'shortname' => 'local_gcs_fullname',
                    'name' => 'Full Name',
                    'datatype' => 'text',
                    'description' => 'Full name used in display, selections, and reports',
                    'descriptionformat' => 0,
                    'categoryid' => $cat->id,
                    'sortorder' => 2,
                    'required' => 0,
                    'locked' => 1,
                    'visible' => 2,
                    'forceunique' => 0,
                    'signup' => 0,
                    'defaultdata' => '',
                    'defaultdataformat' => 0,
                    'param1' => null,
                    'param2' => null,
                    'param3' => null,
                    'param4' => null,
                    'param5' => null,
                    ]
            );
        }

        upgrade_plugin_savepoint(true, 2023101302, 'local', 'gcs');
    }

    // Add program requirements.
    if ($oldversion < 2023101800) {

        // Define table local_gcs_program_req to be created.
        $table = new xmldb_table('local_gcs_program_req');

        // Adding fields to table local_gcs_program_req.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('program_code', XMLDB_TYPE_CHAR, '5', null, null, null, null);
        $table->add_field('category_code', XMLDB_TYPE_CHAR, '2', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_CHAR, '30', null, null, null, null);
        $table->add_field('courses_required', XMLDB_TYPE_INTEGER, '2', null, null, null, null);
        $table->add_field('report_seq', XMLDB_TYPE_INTEGER, '2', null, null, null, null);

        // Adding keys to table local_gcs_program_req.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_program_req.
        $table->add_index('ix_program_category', XMLDB_INDEX_UNIQUE, ['program_code', 'category_code']);

        // Conditionally launch create table for local_gcs_program_req.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023101800, 'local', 'gcs');
    }

    // Add courses permitted.
    if ($oldversion < 2023101901) {

        // Define table local_gcs_courses_permitted to be created.
        $table = new xmldb_table('local_gcs_courses_permitted');

        // Adding fields to table local_gcs_courses_permitted.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('program_code', XMLDB_TYPE_CHAR, '5', null, null, null, null);
        $table->add_field('course_category', XMLDB_TYPE_CHAR, '2', null, null, null, null);
        $table->add_field('course_code', XMLDB_TYPE_CHAR, '10', null, null, null, null);

        // Adding keys to table local_gcs_courses_permitted.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_courses_permitted.
        $table->add_index('ix_all', XMLDB_INDEX_UNIQUE, ['program_code', 'course_category', 'course_code']);

        // Conditionally launch create table for local_gcs_courses_permitted.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023101901, 'local', 'gcs');
    }

    // Add classes table.
    if ($oldversion < 2023103100) {

        // Define table local_gcs_classes to be created.
        $table = new xmldb_table('local_gcs_classes');

        // Adding fields to table local_gcs_classes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('term_year', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('term_code', XMLDB_TYPE_CHAR, '1', null, null, null, null);
        $table->add_field('course_code', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('lectures', XMLDB_TYPE_INTEGER, '2', null, null, null, null);
        $table->add_field('required_textbooks', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('short_title', XMLDB_TYPE_CHAR, '40', null, null, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '60', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('course_hours', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('instructor', XMLDB_TYPE_INTEGER, '5', null, null, null, null);
        $table->add_field('comments', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table local_gcs_classes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_classes.
        $table->add_index('ix_term_course', XMLDB_INDEX_UNIQUE, ['term_year', 'term_code', 'course_code']);

        // Conditionally launch create table for local_gcs_classes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023103100, 'local', 'gcs');
    }

    // Add classes to menu.
    if ($oldversion < 2023103102) {
        upgrade_plugin_savepoint(true, 2023103102, 'local', 'gcs');
    }

    // Add student table.
    if ($oldversion < 2023110800) {

        // Define table local_gcs_student to be created.
        $table = new xmldb_table('local_gcs_student');

        // Adding fields to table local_gcs_student.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('legal_lastname', XMLDB_TYPE_CHAR, '30', null, null, null, null);
        $table->add_field('legal_firstname', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('legal_middlename', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('preferred_firstname', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('program_code', XMLDB_TYPE_CHAR, '5', null, null, null, null);
        $table->add_field('acceptance_date', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('status_code', XMLDB_TYPE_CHAR, '5', null, null, null, null);
        $table->add_field('exit_date', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('idnumber', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('birth_place', XMLDB_TYPE_CHAR, '40', null, null, null, null);
        $table->add_field('ssn', XMLDB_TYPE_CHAR, '11', null, null, null, null);
        $table->add_field('citizenship', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('alien_reg_number', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('visa_type', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('is_veteran', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('ethic_code', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('donotemail', XMLDB_TYPE_INTEGER, '1', null, null, null, null);
        $table->add_field('scholarship_eligible', XMLDB_TYPE_CHAR, '10', null, null, null, null);

        // Adding keys to table local_gcs_student.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_student.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023110800, 'local', 'gcs');
    }

    // Add Moodle userid field to student table.
    if ($oldversion < 2023110801) {

        // Define fields to be added to local_gcs_student.
        $table = new xmldb_table('local_gcs_student');

        // Conditionally launch add field userid.
        $field = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'idnumber');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add field address.
        $field = new xmldb_field('address', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null, 'ssn');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add field address2.
        $field = new xmldb_field('address2', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null, 'address');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add field city.
        $field = new xmldb_field('city', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null, 'address2');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add field state_province.
        $field = new xmldb_field('state_province', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'city');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add field zip.
        $field = new xmldb_field('zip', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null, 'state_province');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add field country.
        $field = new xmldb_field('country', XMLDB_TYPE_CHAR, '25', null, XMLDB_NOTNULL, null, null, 'zip');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023110801, 'local', 'gcs');
    }

    // Add student web service functions.
    // Fix date datatype, ethnic_code field name.
    if ($oldversion < 2023110900) {
        $table = new xmldb_table('local_gcs_student');

        // Changing type of field acceptance_date on table local_gcs_student to int.
        $field = new xmldb_field('acceptance_date', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'program_code');
        $dbman->change_field_type($table, $field);

        // Changing type of field exit_date on table local_gcs_student to int.
        $field = new xmldb_field('exit_date', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'status_code');
        $dbman->change_field_type($table, $field);

        // Rename field ethic_code on table local_gcs_student to ethnic_code.
        $field = new xmldb_field('ethic_code', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'is_veteran');
        $dbman->rename_field($table, $field, 'ethnic_code');

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023110900, 'local', 'gcs');
    }

    // Add enrollment agreements table.
    if ($oldversion < 2023111000) {

        // Define table local_gcs_enroll_agreements to be created.
        $table = new xmldb_table('local_gcs_enroll_agreements');

        // Adding fields to table local_gcs_enroll_agreements.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('seqn', XMLDB_TYPE_INTEGER, '4', null, null, null, null);
        $table->add_field('credittype', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('agreement', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('adddate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_gcs_enroll_agreements.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_enroll_agreements.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111000, 'local', 'gcs');
    }

    // Remove course_seq, field not necessary.
    // Add scholarships available table.
    if ($oldversion < 2023111301) {

        // Define field course_seq to be dropped from local_gcs_courses.
        $table = new xmldb_table('local_gcs_courses');
        $field = new xmldb_field('course_seq');

        // Conditionally launch drop field course_seq.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define table local_gcs_sch_available to be created.
        $table = new xmldb_table('local_gcs_sch_available');

        // Adding fields to table local_gcs_sch_available.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('scholarshipcode', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('scholarshiptext', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('statusconfirm', XMLDB_TYPE_CHAR, '200', null, XMLDB_NOTNULL, null, null);
        $table->add_field('perunitamount', XMLDB_TYPE_NUMBER, '10, 3', null, XMLDB_NOTNULL, null, null);
        $table->add_field('coursemax', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('eligibleyears', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('applyfrom', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('applythru', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_gcs_sch_available.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_sch_available.
        $table->add_index('ix_sch_code', XMLDB_INDEX_UNIQUE, ['scholarshipcode']);

        // Conditionally launch create table for local_gcs_sch_available.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111301, 'local', 'gcs');
    }

    // Fix precision on perunitamount.
    if ($oldversion < 2023111400) {

        // Changing precision of field perunitamount on table local_gcs_sch_available to (10, 2).
        $table = new xmldb_table('local_gcs_sch_available');
        $field = new xmldb_field('perunitamount', XMLDB_TYPE_NUMBER, '10, 2', null, XMLDB_NOTNULL, null, null, 'statusconfirm');

        // Launch change of precision for field perunitamount.
        $dbman->change_field_precision($table, $field);

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111400, 'local', 'gcs');
    }

    // Add scholarships given.
    if ($oldversion < 2023111401) {

        // Define table local_gcs_sch_given to be created.
        $table = new xmldb_table('local_gcs_sch_given');

        // Adding fields to table local_gcs_sch_given.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('studentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('termyear', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('requestdate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('programcode', XMLDB_TYPE_CHAR, '5', null, XMLDB_NOTNULL, null, null);
        $table->add_field('occupation', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('employer', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('cadinfoauth', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('category', XMLDB_TYPE_CHAR, '3', null, XMLDB_NOTNULL, null, null);
        $table->add_field('perunitamount', XMLDB_TYPE_NUMBER, '11, 2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('coursemax', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('eligiblefrom', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('eligiblethru', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('decision', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('reviewdate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('comments', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('studentnotified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table local_gcs_sch_given.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_sch_given.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111401, 'local', 'gcs');
    }

    // Add classes taken.
    if ($oldversion < 2023111500) {

        // Define table local_gcs_classes_taken to be created.
        $table = new xmldb_table('local_gcs_classes_taken');

        // Adding fields to table local_gcs_classes_taken.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('termyear', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('termcode', XMLDB_TYPE_CHAR, '1', null, null, null, null);
        $table->add_field('coursecode', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('studentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('idnumber', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('shorttitleoverride', XMLDB_TYPE_CHAR, '40', null, null, null, null);
        $table->add_field('titleoverride', XMLDB_TYPE_CHAR, '60', null, null, null, null);
        $table->add_field('credittypecode', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('gradecode', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('coursehoursoverride', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('registrationdate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('tuitionpaid', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);
        $table->add_field('completiondate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('canceldate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('comments', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('assignedcoursecode', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('elective', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('scholarshippedamount', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);
        $table->add_field('scholarshippedadjustment', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);
        $table->add_field('scholarshipid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('agreementsigned', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('agreementid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('ordernumber', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('linenumber', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fee', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);
        $table->add_field('classtuition', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);
        $table->add_field('ordertotal', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);
        $table->add_field('studentpaid', XMLDB_TYPE_NUMBER, '10, 2', null, null, null, null);
        $table->add_field('regfoxcode', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('manualpricing', XMLDB_TYPE_INTEGER, '1', null, null, null, null);

        // Adding keys to table local_gcs_classes_taken.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table local_gcs_classes_taken.
        $table->add_index('ix_studtermcourse', XMLDB_INDEX_UNIQUE, ['studentid', 'termyear', 'termcode', 'coursecode']);

        // Conditionally launch create table for local_gcs_classes_taken.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111500, 'local', 'gcs');
    }

    // Add seqn field to classes taken and scholarships given.
    if ($oldversion < 2023111501) {

        // Define field seqn to be added to local_gcs_sch_given.
        $table = new xmldb_table('local_gcs_sch_given');
        $field = new xmldb_field('seqn', XMLDB_TYPE_INTEGER, '5', null, null, null, null, 'studentnotified');

        // Conditionally launch add field seqn.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field seqn to be added to local_gcs_classes_taken.
        $table = new xmldb_table('local_gcs_classes_taken');
        $field = new xmldb_field('seqn', XMLDB_TYPE_INTEGER, '5', null, null, null, null, 'manualpricing');

        // Conditionally launch add field seqn.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111501, 'local', 'gcs');
    }

    if ($oldversion < 2023111502) {

        // Changing nullability of field assignedcoursecode on table local_gcs_classes_taken to null.
        $table = new xmldb_table('local_gcs_classes_taken');
        $field = new xmldb_field('assignedcoursecode', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'comments');

        // Launch change of nullability for field assignedcoursecode.
        $dbman->change_field_notnull($table, $field);

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111502, 'local', 'gcs');
    }

    // Program/education completion table.
    if ($oldversion < 2023111700) {

        // Define table local_gcs_program_completion to be created.
        $table = new xmldb_table('local_gcs_program_completion');

        // Adding fields to table local_gcs_program_completion.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('studentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('programcode', XMLDB_TYPE_CHAR, '15', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null);
        $table->add_field('university', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('enrolldate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('completetiondate', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('notes', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('basisofadmission', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table local_gcs_program_completion.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_program_completion.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111700, 'local', 'gcs');
    }
    if ($oldversion < 2023111701) {

        // Rename field completiondate on table local_gcs_program_completion to completiondate.
        $table = new xmldb_table('local_gcs_program_completion');
        $field = new xmldb_field('completetiondate', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'enrolldate');

        // Launch rename field completiondate.
        $dbman->rename_field($table, $field, 'completiondate');

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023111701, 'local', 'gcs');
    }

    // We need to know the source of the program completed so we know whether to display it in official reports.
    if ($oldversion < 2023112800) {

        // Define field source to be added to local_gcs_program_completion.
        $table = new xmldb_table('local_gcs_program_completion');
        $field = new xmldb_field('source', XMLDB_TYPE_CHAR, '15', null, XMLDB_NOTNULL, null, null, 'basisofadmission');

        // Conditionally launch add field source.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023112800, 'local', 'gcs');
    }

    // Rename course_category to category_code in permitted courses to be consistent.
    if ($oldversion < 2023120100) {

        // Define index ix_all (unique) to be dropped from local_gcs_courses_permitted.
        $table = new xmldb_table('local_gcs_courses_permitted');
        $index = new xmldb_index('ix_all', XMLDB_INDEX_UNIQUE, ['program_code', 'course_category', 'course_code']);

        // Conditionally launch drop index ix_all.
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }

        // Define field course_category to be renamed.
        $field = new xmldb_field('course_category', XMLDB_TYPE_CHAR, '2', null, null, null, null, 'program_code');

        // Launch rename field course_category.
        $dbman->rename_field($table, $field, 'category_code');

        // Define index ix_all (unique) to be added to local_gcs_courses_permitted.
        $index = new xmldb_index('ix_all', XMLDB_INDEX_UNIQUE, ['program_code', 'category_code', 'course_code']);

        // Conditionally launch add index ix_all.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023120100, 'local', 'gcs');
    }

    // Remove underscores in field names for consistency between tables.
    if ($oldversion < 2023120700) {

        // Rename fields on table local_gcs_program.
        $table = new xmldb_table('local_gcs_program');

        // Conditionally launch drop index ix_program_code.
        $index = new xmldb_index('ix_program_code', XMLDB_INDEX_UNIQUE, ['program_code']);
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }
        // Launch rename field program_code.
        $field = new xmldb_field('program_code', XMLDB_TYPE_CHAR, '5', null, XMLDB_NOTNULL, null, null, 'id');
        $dbman->rename_field($table, $field, 'programcode');
        // Launch rename field academic_degree.
        $field = new xmldb_field('academic_degree', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'description');
        $dbman->rename_field($table, $field, 'academicdegree');
        // Conditionally launch add index ix_program_code.
        $index = new xmldb_index('ix_programcode', XMLDB_INDEX_UNIQUE, ['programcode']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Rename fields table local_gcs_term_dates.
        $table = new xmldb_table('local_gcs_term_dates');

        // Launch rename field term_year.
        $field = new xmldb_field('term_year', XMLDB_TYPE_INTEGER, '4', null, null, null, null, 'id');
        $dbman->rename_field($table, $field, 'termyear');
        // Rename fields table local_gcs_term_dates.
        $table = new xmldb_table('local_gcs_term_dates');
        // Launch rename field term_code.
        $field = new xmldb_field('term_code', XMLDB_TYPE_CHAR, '1', null, null, null, null, 'termyear');
        $dbman->rename_field($table, $field, 'termcode');
        // Launch rename field term_name.
        $field = new xmldb_field('term_name', XMLDB_TYPE_CHAR, '32', null, null, null, null, 'termcode');
        $dbman->rename_field($table, $field, 'termname');
        // Launch rename field accounting_code.
        $field = new xmldb_field('accounting_code', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'termname');
        $dbman->rename_field($table, $field, 'accountingcode');
        // Launch rename field accounting_title.
        $field = new xmldb_field('accounting_title', XMLDB_TYPE_CHAR, '64', null, null, null, null, 'accountingcode');
        $dbman->rename_field($table, $field, 'accountingtitle');
        // Launch rename field registration_start.
        $field = new xmldb_field('registration_start', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'accountingtitle');
        $dbman->rename_field($table, $field, 'registrationstart');
        // Launch rename field registration_end.
        $field = new xmldb_field('registration_end', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'registrationstart');
        $dbman->rename_field($table, $field, 'registrationend');
        // Launch rename field classes_start.
        $field = new xmldb_field('classes_start', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'registrationend');
        $dbman->rename_field($table, $field, 'classesstart');
        // Launch rename field classes_end.
        $field = new xmldb_field('classes_end', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'classesstart');
        $dbman->rename_field($table, $field, 'classesend');

        // Rename fields on table local_gcs_courses.
        $table = new xmldb_table('local_gcs_courses');

        // Conditionally launch drop index ix_course_code.
        $index = new xmldb_index('ix_course_code', XMLDB_INDEX_UNIQUE, ['course_code']);
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }
        // Launch rename field course_code.
        $field = new xmldb_field('course_code', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'id');
        $dbman->rename_field($table, $field, 'coursecode');
        // Launch rename field course_hours.
        $field = new xmldb_field('course_hours', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'description');
        $dbman->rename_field($table, $field, 'coursehours');
        // Launch rename field required_textbooks.
        $field = new xmldb_field('required_textbooks', XMLDB_TYPE_TEXT, null, null, null, null, null, 'lectures');
        $dbman->rename_field($table, $field, 'requiredtextbooks');
        // Launch rename field default_instructor.
        $field = new xmldb_field('default_instructor', XMLDB_TYPE_INTEGER, '5', null, null, null, null, 'requiredtextbooks');
        $dbman->rename_field($table, $field, 'defaultinstructor');
        // Conditionally launch add index ix_coursecode.
        $index = new xmldb_index('ix_coursecode', XMLDB_INDEX_UNIQUE, ['coursecode']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Rename fields on table local_gcs_program_req.
        $table = new xmldb_table('local_gcs_program_req');

        // Conditionally launch drop index ix_program_category.
        $index = new xmldb_index('ix_program_category', XMLDB_INDEX_UNIQUE, ['program_code', 'category_code']);
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }
        // Launch rename field program_code.
        $field = new xmldb_field('program_code', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'id');
        $dbman->rename_field($table, $field, 'programcode');
        // Launch rename field category_code.
        $field = new xmldb_field('category_code', XMLDB_TYPE_CHAR, '2', null, null, null, null, 'programcode');
        $dbman->rename_field($table, $field, 'categorycode');
        // Launch rename field courses_required.
        $field = new xmldb_field('courses_required', XMLDB_TYPE_INTEGER, '2', null, null, null, null, 'description');
        $dbman->rename_field($table, $field, 'coursesrequired');
        // Launch rename field report_seq.
        $field = new xmldb_field('report_seq', XMLDB_TYPE_INTEGER, '2', null, null, null, null, 'coursesrequired');
        $dbman->rename_field($table, $field, 'reportseq');
        // Conditionally launch add index ix_programcategory.
        $index = new xmldb_index('ix_programcategory', XMLDB_INDEX_UNIQUE, ['programcode', 'categorycode']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Rename fields on table local_gcs_courses_permitted.
        $table = new xmldb_table('local_gcs_courses_permitted');

        // Conditionally launch drop index ix_all.
        $index = new xmldb_index('ix_all', XMLDB_INDEX_UNIQUE, ['program_code', 'course_category', 'course_code']);
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }
        // Launch rename field program_code.
        $field = new xmldb_field('program_code', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'id');
        $dbman->rename_field($table, $field, 'programcode');
        // Launch rename field course_category.
        $field = new xmldb_field('category_code', XMLDB_TYPE_CHAR, '2', null, null, null, null, 'programcode');
        $dbman->rename_field($table, $field, 'categorycode');
        // Launch rename field course_code.
        $field = new xmldb_field('course_code', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'categorycode');
        $dbman->rename_field($table, $field, 'coursecode');
        // Conditionally launch add index ix_all.
        $index = new xmldb_index('ix_all', XMLDB_INDEX_UNIQUE, ['programcode', 'categorycode', 'coursecode']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Rename fields on table local_gcs_classes.
        $table = new xmldb_table('local_gcs_classes');
        $field = new xmldb_field('term_year', XMLDB_TYPE_INTEGER, '4', null, null, null, null, 'id');

        // Conditionally launch drop index ix_term_course.
        $index = new xmldb_index('ix_term_course', XMLDB_INDEX_UNIQUE, ['term_year', 'term_code', 'course_code']);
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }
        // Launch rename field term_year.
        $field = new xmldb_field('term_year', XMLDB_TYPE_INTEGER, '4', null, null, null, null, 'id');
        $dbman->rename_field($table, $field, 'termyear');
        // Launch rename field term_code.
        $field = new xmldb_field('term_code', XMLDB_TYPE_CHAR, '1', null, null, null, null, 'termyear');
        $dbman->rename_field($table, $field, 'termcode');
        // Launch rename field course_code.
        $field = new xmldb_field('course_code', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'termcode');
        $dbman->rename_field($table, $field, 'coursecode');
        // Launch rename field required_textbooks.
        $field = new xmldb_field('required_textbooks', XMLDB_TYPE_TEXT, null, null, null, null, null, 'lectures');
        $dbman->rename_field($table, $field, 'requiredtextbooks');
        // Launch rename field short_title.
        $field = new xmldb_field('short_title', XMLDB_TYPE_CHAR, '40', null, null, null, null, 'requiredtextbooks');
        $dbman->rename_field($table, $field, 'shorttitle');
        // Launch rename field course_hours.
        $field = new xmldb_field('course_hours', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'description');
        $dbman->rename_field($table, $field, 'coursehours');
        // Conditionally launch add index ix_termcourse.
        $index = new xmldb_index('ix_termcourse', XMLDB_INDEX_UNIQUE, ['termyear', 'termcode', 'coursecode']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Rename fields on table local_gcs_student.
        $table = new xmldb_table('local_gcs_student');

        // Launch rename field legal_lastname.
        $field = new xmldb_field('legal_lastname', XMLDB_TYPE_CHAR, '30', null, null, null, null, 'id');
        $dbman->rename_field($table, $field, 'legallastname');
        // Launch rename field legal_firstname.
        $field = new xmldb_field('legal_firstname', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'legallastname');
        $dbman->rename_field($table, $field, 'legalfirstname');
        // Launch rename field legal_middlename.
        $field = new xmldb_field('legal_middlename', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'legalfirstname');
        $dbman->rename_field($table, $field, 'legalmiddlename');
        // Launch rename field preferred_firstname.
        $field = new xmldb_field('preferred_firstname', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'legalmiddlename');
        $dbman->rename_field($table, $field, 'preferredfirstname');
        // Launch rename field program_code.
        $field = new xmldb_field('program_code', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'preferredfirstname');
        $dbman->rename_field($table, $field, 'programcode');
        // Launch rename field acceptance_date.
        $field = new xmldb_field('acceptance_date', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'programcode');
        $dbman->rename_field($table, $field, 'acceptancedate');
        // Launch rename field status_code.
        $field = new xmldb_field('status_code', XMLDB_TYPE_CHAR, '5', null, null, null, null, 'acceptancedate');
        $dbman->rename_field($table, $field, 'statuscode');
        // Launch rename field exit_date.
        $field = new xmldb_field('exit_date', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'statuscode');
        $dbman->rename_field($table, $field, 'exitdate');
        // Launch rename field birth_place.
        $field = new xmldb_field('birth_place', XMLDB_TYPE_CHAR, '40', null, null, null, null, 'userid');
        $dbman->rename_field($table, $field, 'birthplace');
        // Launch rename field state_province.
        $field = new xmldb_field('state_province', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'city');
        $dbman->rename_field($table, $field, 'stateprovince');
        // Launch rename field alienregnumber.
        $field = new xmldb_field('alien_reg_number', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'citizenship');
        $dbman->rename_field($table, $field, 'alienregnumber');
        // Launch rename field visa_type.
        $field = new xmldb_field('visa_type', XMLDB_TYPE_CHAR, '20', null, null, null, null, 'alienregnumber');
        $dbman->rename_field($table, $field, 'visatype');
        // Launch rename field is_veteran.
        $field = new xmldb_field('is_veteran', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'visatype');
        $dbman->rename_field($table, $field, 'isveteran');
        // Launch rename field ethnic_code.
        $field = new xmldb_field('ethnic_code', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'isveteran');
        $dbman->rename_field($table, $field, 'ethniccode');
        // Launch rename field scholarship_eligible.
        $field = new xmldb_field('scholarship_eligible', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'donotemail');
        $dbman->rename_field($table, $field, 'scholarshipeligible');

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023120700, 'local', 'gcs');
    }

    // Remove underscores in field names for consistency between tables.
    if ($oldversion < 2023120701) {

        // Rename fields on table local_gcs_courses.
        $table = new xmldb_table('local_gcs_courses');
        // Launch rename field short_title.
        $field = new xmldb_field('short_title', XMLDB_TYPE_CHAR, '40', null, null, null, null, 'coursecode');
        $dbman->rename_field($table, $field, 'shorttitle');

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023120701, 'local', 'gcs');
    }

    // Add electiveeligible field to courses permitted.
    if ($oldversion < 2023121100) {

        // Define field electiveeligible to be added to local_gcs_courses_permitted.
        $table = new xmldb_table('local_gcs_courses_permitted');
        $field = new xmldb_field('electiveeligible', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'coursecode');

        // Conditionally launch add field electiveeligible.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023121100, 'local', 'gcs');
    }

    // New plugin settings.
    if ($oldversion < 2023122001) {

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023122001, 'local', 'gcs');
    }

    // New RegFox webhook table.
    if ($oldversion < 2023122101) {

        // Define table local_gcs_regfox_webhook to be created.
        $table = new xmldb_table('local_gcs_regfox_webhook');

        // Adding fields to table local_gcs_regfox_webhook.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('receivedtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('signature', XMLDB_TYPE_CHAR, '256', null, null, null, null);
        $table->add_field('event', XMLDB_TYPE_CHAR, '32', null, null, null, null);
        $table->add_field('postdata', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('processedtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_gcs_regfox_webhook.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_regfox_webhook.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2023122101, 'local', 'gcs');
    }

    // Add new external function local_gcs_student_get_logged_in.
    if ($oldversion < 2024010403) {
        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024010403, 'local', 'gcs');
    }

    // Add tables to store RegFox transactions,registrants and classes.
    if ($oldversion < 2024010500) {

        // Define table local_gcs_regfox_transaction to be created.
        $table = new xmldb_table('local_gcs_regfox_transaction');

        // Adding fields to table local_gcs_regfox_transaction.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('transactionid', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('ordernumber', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('orderstatus', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('email', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('firstname', XMLDB_TYPE_CHAR, '30', null, null, null, null);
        $table->add_field('lastname', XMLDB_TYPE_CHAR, '30', null, null, null, null);
        $table->add_field('paymethod', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('formname', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('total', XMLDB_TYPE_NUMBER, '7, 2', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('transactiontime', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timestamp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('webhookid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table local_gcs_regfox_transaction.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_regfox_transaction.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_gcs_regfox_registrant to be created.
        $table = new xmldb_table('local_gcs_regfox_registrant');

        // Adding fields to table local_gcs_regfox_registrant.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('email', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('firstname', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, null);
        $table->add_field('lastname', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, null);
        $table->add_field('scholarshipcode', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('amount', XMLDB_TYPE_NUMBER, '7, 2', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('tranid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('processedtime', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table local_gcs_regfox_registrant.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_regfox_registrant.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_gcs_regfox_class to be created.
        $table = new xmldb_table('local_gcs_regfox_class');

        // Adding fields to table local_gcs_regfox_class.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('regid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('coursecode', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '60', null, XMLDB_NOTNULL, null, null);
        $table->add_field('credittypecode', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('cost', XMLDB_TYPE_NUMBER, '5, 2', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('paid', XMLDB_TYPE_NUMBER, '5, 2', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('discount', XMLDB_TYPE_NUMBER, '5, 2', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('processedtime', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table local_gcs_regfox_class.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_gcs_regfox_class.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024010500, 'local', 'gcs');
    }

    // Add new record classes for RegFox tables, email address settings.
    if ($oldversion < 2024011901) {
        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024011901, 'local', 'gcs');
    }

    // Add student field for Regfox emails.
    if ($oldversion < 2024012200) {

        // Define field regfoxemails to be added to local_gcs_student.
        $table = new xmldb_table('local_gcs_student');
        $field = new xmldb_field('regfoxemails', XMLDB_TYPE_TEXT, null, null, null, null, null, 'scholarshipeligible');

        // Conditionally launch add field regfoxemails.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024012200, 'local', 'gcs');
    }

    // Add studentid field to regfox registrant table and ctrid to regfox class registration.
    if ($oldversion < 2024012300) {

        // Define field studentid to be added to local_gcs_regfox_registrant.
        $table = new xmldb_table('local_gcs_regfox_registrant');
        $field = new xmldb_field('studentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'processedtime');

        // Conditionally launch add field studentid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field id to be added to local_gcs_regfox_class.
        $table = new xmldb_table('local_gcs_regfox_class');
        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);

        // Conditionally launch add field id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024012300, 'local', 'gcs');
    }

    // Add isgraduated field to student table.
    if ($oldversion < 2024020602) {

        // Define field isdeceased to be added to local_gcs_student.
        $table = new xmldb_table('local_gcs_student');
        $field = new xmldb_field('isgraduated', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'regfoxemails');

        // Conditionally launch add field isgraduated.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024020602, 'local', 'gcs');
    }

    // Add new page for class list with roster & transcript.
	// Add new reports menu folder & student listing.
	// Add new service function class_get_by_code_and_term.
	// Add new service function students_get_with_unsigned_enrollment_agreements.
	// Add new swervice users_get_instructors.
	// Add Program Completion menu item.
	// Add Unsigned Enrollment agreements.
	// Add formatted enrollment agreement services
	// Add students with scholarships service
    if ($oldversion < 2024040402) {
        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024040402, 'local', 'gcs');
    }
	
	// Add student birthdate
    if ($oldversion < 2024041601) {

        // Define field birthdate to be added to local_gcs_student.
        $table = new xmldb_table('local_gcs_student');
        $field = new xmldb_field('birthdate', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'userid');

        // Conditionally launch add field birthdate.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024041601, 'local', 'gcs');
    }

    if ($oldversion < 2024041800) {
        // Gcs savepoint reached.
        upgrade_plugin_savepoint(true, 2024041800, 'local', 'gcs');
    }
}
