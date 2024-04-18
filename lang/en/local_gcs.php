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
 * Version information for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['cachedef_angularmodules'] = 'Cache of angular module definitions so we dont need to parse index.html on every load.';
$string['gcs:administrator'] = 'Administer GCS Program Management System';
$string['menuacct_sch_cat'] = 'Scholarship Accounting';
$string['menuclasses'] = 'Classes';
$string['menuclasses_roster'] = 'Class List with Roster';
$string['menuclasses_taken'] = 'Classes Taken';
$string['menucodes'] = 'Code Management';
$string['menucourses'] = 'Courses';
$string['menuenrollment_agreements'] = 'Enrollment Agreements';
$string['menuenrollment_agreements_signing'] = 'Sign Enrollment Agreements';
$string['menuenrollment_agreements_unsigned'] = 'Unsigned Enrollment Agreements';
$string['menufolderadmin'] = 'Program Management';
$string['menufolderreports'] = 'Program Management Reports';
$string['menufolderstud'] = 'Student Reports';
$string['menumenu'] = '{pluginname} Menu';
$string['menupermitted_courses'] = 'Permitted Courses';
$string['menuprogram_completion'] = 'Program Completion';
$string['menuprogram_requirements'] = 'Program Requirements';
$string['menuprogram_req_completed'] = 'Program Requirements Completed';
$string['menuprograms'] = 'Programs of Study';
$string['menuregfox_webhook'] = 'RegFox Processing';
$string['menusch_application'] = 'Scholarship Application';
$string['menusch_available'] = 'Scholarships Available';
$string['menusch_given'] = 'Scholarships Given';
$string['menustudent'] = 'Student Resources';
$string['menustudents'] = 'Students';
$string['menustudent_list'] = 'Student Listing';
$string['menuterm_dates'] = 'Term Dates';
$string['menutranscript'] = 'Transcript';
$string['pluginname'] = 'GCS Program Management';
$string['setting_pluginname'] = 'Program management plugin name:';
$string['setting_pluginname_desc'] = 'The name of the plugin as displayed in the administator interface.';
$string['setting_menufolderstud'] = 'Student reports menu folder:';
$string['setting_menufolderstud_desc'] = 'The name of the folder in the menu for student report pages.';
$string['setting_menufolderadmin'] = 'Program managment menu folder:';
$string['setting_menufolderadmin_desc'] = 'The name of the folder in the menu for student and program management pages for school personnel.';
$string['setting_menufolderreports'] = 'Program managment reports menu folder:';
$string['setting_menufolderreports_desc'] = 'The name of the folder in the menu for program management reports for school personnel.';
$string['setting_printlogoenabled'] = 'Print logo on reports?';
$string['setting_printlogoenabled_desc'] = 'When enabled, prints a logo at the top of reports like Program Requirements Complete and Transcript.';
$string['setting_printlogo'] = 'Logo for reports';
$string['setting_printlogo_desc'] = 'The logo to print on reports.';
$string['setting_fromemail'] = 'From email address';
$string['setting_fromemail_desc'] = 'Plugin emails are sent from this email address.';
$string['setting_notificationemails'] = 'Email addresses to receive plugin notifications.';
$string['setting_notificationemails_desc'] = 'Comma-separated list of emails to notify of things that need attention.';
$string['setting_devsites'] = 'Host names that are development (Dev) instances.';
$string['setting_devsites_desc'] = 'Comma-separated list of host names that will be labeled "Dev".';
$string['setting_testsites'] = 'Host names that are test (Test) instances.';
$string['setting_testsites_desc'] = 'Comma-separated list of host names that will be labeled "Test".';
$string['setting_livesites'] = 'Host names that are live/production (Live) instances.';
$string['setting_livesites_desc'] = 'Comma-separated list of host names that will be labeled "Live".';
$string['setting_notificationsenabled'] = 'Enable routine notifications on non-live instances?';
$string['setting_notificationsenabled_desc'] = 'When enabled, notification emails are sent Otherwise they are suppressed.';
$string['taskdailyautomation'] = 'Daily Automation';
$string['taskprocessagreements'] = 'Process enrollment agreements';
$string['taskprocessregfox'] = 'Process RegFox registration webhooks';
$string['taskstudentusermatch'] = 'Match students with their user account';
