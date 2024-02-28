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
 * Display and edit list of program requirements for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_programreqs_get
 * args: { programcode string or empty for requirements for all programs}
 *
 * methodname: local_gcs_programreq_get
 * args: { id (int) }
 *
 * methodname: local_gcs_programreq_insert
 * args: { programrec (object) }
 * programrec: object {
 *   id int                // record id (zero for insert)
 *   program_code string   //id of program
 *   category_code string  //id of program
 *   description string    //program description text
 *   courses_required int  //number of courses required from the category
 *   report_seq int        //display order for reports
 * }
 *
 * methodname: local_gcs_programreq_update
 * args: { programrec (object) }
 * programrec: object {
 *   id int                // record id (zero for insert)
 *   program_code string   //id of program
 *   category_code string  //id of program
 *   description string    //program description text
 *   courses_required int  //number of courses required from the category
 *   report_seq int        //display order for reports
 * }
 *
 * methodname: local_gcs_programreq_delete
 * args: { id (int) }
 *
 */

require_once(__DIR__.'/../../config.php');
require_login();
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-program-req');
echo $angularpage->output->render($angularpage);
