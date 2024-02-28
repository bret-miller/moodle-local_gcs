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
 * Display and edit list of classes for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_classes_get
 * args: {}
 *
 * methodname: local_gcs_class_get
 * args: { id (int) }
 *
 * methodname: local_gcs_class_insert
 * args: { classrec (object) }
 * classrec: object {
 *   id int                    // class record id
 *   term_year int             // term year
 *   term_code string          // term code
 *   course_code string        // course code
 *   lectures int              // number of lectures in this class
 *   required_textbooks string // textbooks required for this class
 *   short_title string        // title of class, shortened for when space is limited
 *   title string              // title of class
 *   description string        // class description text
 *   course_hours int          // hours awarded for completing this class
 *   instructor int            // userid of default instructor
 *   comments string           // other miscellaneous comments
 * }
 *
 * methodname: local_gcs_class_update
 * args: { classrec (object) }
 * classrec: object {
 *   id int                    // class record id
 *   term_year int             // term year
 *   term_code string          // term code
 *   course_code string        // course code
 *   lectures int              // number of lectures in this class
 *   required_textbooks string // textbooks required for this class
 *   short_title string        // title of class, shortened for when space is limited
 *   title string              // title of class
 *   description string        // class description text
 *   course_hours int          // hours awarded for completing this class
 *   instructor int            // userid of default instructor
 *   comments string           // other miscellaneous comments
 * }
 *
 * methodname: local_gcs_class_delete
 * args: { id (int) }
 *
 */

require_once(__DIR__.'/../../config.php');
require_login();
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-classes');
echo $angularpage->output->render($angularpage);
