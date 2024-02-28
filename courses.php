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
 * Display and edit list of courses for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Web service calls:
 *
 * methodname: local_gcs_instructors_get
 * args: {}
 *
 * methodname: local_gcs_courses_get
 * args: {}
 *
 * methodname: local_gcs_course_get
 * args: { id (int) }
 *
 * methodname: local_gcs_course_insert
 * args: { courserec (object) }
            'id' => new external_value(PARAM_INT, 'course record id'),
            'course_seq' => new external_value(PARAM_INT, 'sequence tiebreaker for reports'),
            'course_code' => new external_value(PARAM_TEXT, 'course code'),
            'short_title' => new external_value(PARAM_TEXT, 'title of course, shortened for when space is limited'),
            'title' => new external_value(PARAM_TEXT, 'title of course'),
            'description' => new external_value(PARAM_TEXT, 'course description text'),
            'course_hours' => new external_value(PARAM_INT, 'number of academic hours given for this course'),
            'lectures' => new external_value(PARAM_INT, 'number of lectures in course'),
            'required_textbooks' => new external_value(PARAM_TEXT, 'textbooks required for this course'),
            'default_instructor' => new external_value(PARAM_INT, 'user id of default instructor'),
            'comments' => new external_value(PARAM_TEXT, 'other miscellaneous comments'),
 * courserec: object {
 *   id int                    // course record id
 *   course_seq int            // sequence tie-breaker for reports
 *   course_code string        // course code
 *   short_title string        // title of course, shortened for when space is limited
 *   title string              // title of course
 *   description string        // course description text
 *   course_hours int          // hours awarded for completing this course
 *   lectures int              // number of lectures in this course
 *   required_textbooks string // textbooks required for this course
 *   default_instructor int    // userid of default instructor
 *   comments string           // other miscellaneous comments
 * }
 *
 * methodname: local_gcs_course_update
 * args: { courserec (object) }
 * courserec: object {
 *   id int                    // course record id
 *   course_seq int            // sequence tie-breaker for reports
 *   course_code string        // course code
 *   short_title string        // title of course, shortened for when space is limited
 *   title string              // title of course
 *   description string        // course description text
 *   course_hours int          // hours awarded for completing this course
 *   lectures int              // number of lectures in this course
 *   required_textbooks string // textbooks required for this course
 *   default_instructor int    // userid of default instructor
 *   comments string           // other miscellaneous comments
 * }
 *
 * methodname: local_gcs_course_delete
 * args: { id (int) }
 *
 */

require_once(__DIR__.'/../../config.php');
require_login();
require_capability('local/gcs:administrator', context_system::instance());

$angularpage = new \local_gcs\angular_page('gcs-courses');
echo $angularpage->output->render($angularpage);
