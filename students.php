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
 * Display and edit list of students for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_students_get_all
 * args: {}
 *
 * methodname: local_gcs_students_get
 * args: { id (int) }
 *
 * methodname: local_gcs_students_insert
 * args: { programrec (object) }
 * programrec: object {
 *   id                  int    // Identity Key - zero for insert
 *   legal_lastname      string // Legal Last Name
 *   legal_firstname     string // Legal First Name
 *   legal_middlename    string // Legal Middle Name
 *   preferred_firstname string // Preferred First Name
 *   program_code        string // Program Code
 *   acceptance_date     string // Acceptance Date
 *   status_code         string // Status Code
 *   exit_date           int    // Exit Date`
 *   idnumber            string // External ID
 *   userid              int    // Moodle User ID
 *   address             string // Address
 *   address2            string // Address line 2
 *   city                string // City
 *   state_province      string // State
 *   zip                 string // Zip code
 *   country             string // Country
 *   birth_place         string // Birth Place
 *   ssn                 string // Social Security Number
 *   citizenship         string // Citizenship
 *   alien_reg_number    string // Alien Reg Number
 *   visa_type           string // Visa Type
 *   is_veteran          bool   // Is Veteran?
 *   ethic_code          string // Ethnic Code
 *   donotemail          bool   // Do Not Email
 *   scholarship_eligible string // Scholarship Eligible
 * }
 *
 * methodname: local_gcs_students_update
 * args: { programrec (object) }
 * rec: object {
 *   id                  int    // Identity Key - zero for insert
 *   legal_lastname      string // Legal Last Name
 *   legal_firstname     string // Legal First Name
 *   legal_middlename    string // Legal Middle Name
 *   preferred_firstname string // Preferred First Name
 *   program_code        string // Program Code
 *   acceptance_date     string // Acceptance Date
 *   status_code         string // Status Code
 *   exit_date           int    // Exit Date`
 *   idnumber            string // External ID
 *   userid              int    // Moodle User ID
 *   address             string // Address
 *   address2            string // Address line 2
 *   city                string // City
 *   state_province      string // State
 *   zip                 string // Zip code
 *   country             string // Country
 *   birth_place         string // Birth Place
 *   ssn                 string // Social Security Number
 *   citizenship         string // Citizenship
 *   alien_reg_number    string // Alien Reg Number
 *   visa_type           string // Visa Type
 *   is_veteran          bool   // Is Veteran?
 *   ethic_code          string // Ethnic Code
 *   donotemail          bool   // Do Not Email
 *   scholarship_eligible string // Scholarship Eligible
 * }
 *
 * methodname: local_gcs_students_delete
 * args: { id (int) }
 *
 */

require_once(__DIR__.'/../../config.php');
require_login();
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-student');
echo $angularpage->output->render($angularpage);
