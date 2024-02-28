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
 * Display and edit list of programs of study for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_programs_get
 * args: {}
 *
 * methodname: local_gcs_program_get
 * args: { id (int) }
 *
 * methodname: local_gcs_program_insert
 * args: { programrec (object) }
 * programrec: object {
 *   program_code string   //id of program
 *   title string   //title of program
 *   description string   //program description text
 *   academic_degree int   //is this an academic degree? 1=yes, 0=no
 *   inactive int   //is this an inactive program? 1=yes, 0=no
 * }
 *
 * methodname: local_gcs_program_update
 * args: { programrec (object) }
 * programrec: object {
 *   id int   //program record id
 *   program_code string   //id of program
 *   title string   //title of program
 *   description string   //program description text
 *   academic_degree int   //is this an academic degree? 1=yes, 0=no
 *   inactive int   //is this an inactive program? 1=yes, 0=no
 * }
 *
 * methodname: local_gcs_program_delete
 * args: { id (int) }
 *
 */

require_once(__DIR__.'/../../config.php');
require_login();
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-program-list');
echo $angularpage->output->render($angularpage);
