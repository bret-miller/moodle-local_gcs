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
 * Display and edit list of accounting scholarship categories
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_sch_cats_get
 * args: { }
 *
 * methodname: local_gcs_sch_cat_get
 * args: { id (int) }       // record id
 *
 * methodname: local_gcs_sch_cat_insert
 * args: { sch_cat_rec (object) }
 * coderec: object {
 *   id int                 // record id
 *   code string(20)        // code
 *   scholarship string(10) // scholarship code
 *   result string(20)      // result
 * }
 *
 * methodname: local_gcs_sch_cat_update
 * args: { coderec (object) }
 * coderec: object {
 *   id int                 // record id
 *   code string(20)        // code
 *   scholarship string(10) // scholarship code
 *   result string(20)      // result
 * }
 *
 * methodname: local_gcs_sch_cat_delete
 * args: { id (int) }       // record id
 *
 */

require_once(__DIR__.'/../../config.php');
require_login();
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-scholarship-accounting');
echo $angularpage->output->render($angularpage);
