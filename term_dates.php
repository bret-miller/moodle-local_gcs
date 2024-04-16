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
 * Display and edit list of term definitions and dates
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_term_dates_get
 * args: { }
 *
 * methodname: local_gcs_term_date_get
 * args: { id (int) }       // record id
 *
 * methodname: local_gcs_term_date_insert
 * args: { sch_cat_rec (object) }
 * coderec: object {
 *   id int                       // record id
 *   term_year int                // year
 *   term_code string(1)          // term code
 *   term_name string(32)         // term name, like "Spring 2024"
 *   accounting_code string(10)   // accounting code
 *   accounting_title string(64)  // accounting title like "2024 GCS Spring Registration"
 *   registration_start int       // unix timestamp date registration starts
 *   registration_end int         // unix timestamp date registration ends
 *   classes_start int            // unix timestamp date classes start
 *   classes_end int              // unix timestamp date classes end
 *   address string(64)           // address
 *   city string(30)              // city
 *   state string(2)              // state
 *   zip string(10)               // zipcode
 * }
 *
 * methodname: local_gcs_term_date_update
 * args: { coderec (object) }
 * coderec: object {
 *   id int                       // record id
 *   term_year int                // year
 *   term_code string(1)          // term code
 *   term_name string(32)         // term name, like "Spring 2024"
 *   accounting_code string(10)   // accounting code
 *   accounting_title string(64)  // accounting title like "2024 GCS Spring Registration"
 *   registration_start int       // unix timestamp date registration starts
 *   registration_end int         // unix timestamp date registration ends
 *   classes_start int            // unix timestamp date classes start
 *   classes_end int              // unix timestamp date classes end
 *   address string(64)           // address
 *   city string(30)              // city
 *   state string(2)              // state
 *   zip string(10)               // zipcode
 * }
 *
 * methodname: local_gcs_term_date_delete
 * args: { id (int) }       // record id
 *
 */

require_once(__DIR__.'/../../config.php');
require_login(null, false);
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-term-dates');
echo $angularpage->output->render($angularpage);
