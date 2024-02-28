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
 * Display and edit list of codes for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_codesets_get
 * args: { }
 *
 * methodname: local_gcs_codes_get
 * args: { codeset (string) }  or empty string for all codes in all sets
 *
 * methodname: local_gcs_code_get
 * args: { id (int) }
 *
 * methodname: local_gcs_code_get_by_cod
 * args: { codeset (string), code (string) }
 *
 * methodname: local_gcs_code_insert
 * args: { coderec (object) }
 * coderec: object {
 *   id int   //code record id - set to 0
 *   codeset string   //set the code belongs to
 *   code string   //the code
 *   description string   //code description
 * }
 *
 * methodname: local_gcs_code_update
 * args: { coderec (object) }
 * coderec: object {
 *   id int   //code record id
 *   codeset string   //set the code belongs to
 *   code string   //the code
 *   description string   //code description
 * }
 *
 * methodname: local_gcs_code_delete
 * args: { id (int) }
 *
 */

require_once(__DIR__.'/../../config.php');
require_login();
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-code-management');
echo $angularpage->output->render($angularpage);
