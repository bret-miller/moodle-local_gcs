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
 * Get student enrollments for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs\external;

defined('MOODLE_INTERNAL') || die();

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/externallib.php');
require_once(__dir__.'/../data.php');

require_login();

/**
 * Get list of enrollments
 */
class enrollments_get extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Student ID'),
        ]);
    }
    /**
     * Returns description of method returns
     * @return external_external_multiple_structure
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'programcode' => new external_value(PARAM_TEXT, 'Program Code|nolist'),
                'description' => new external_value(PARAM_TEXT, 'Program Code'),
                'academicdegree' => new external_value(PARAM_BOOL, 'Academic Degree?|bool'),
                'enrolldate' => new external_value(PARAM_TEXT, 'Date Enrolled'),
                'completiondate' => new external_value(PARAM_TEXT, 'Date Completed'),
                'studentid' => new external_value(PARAM_INT, 'Student ID'),
                'completed' => new external_value(PARAM_BOOL, 'Completed?|bool'),
                'notes' => new external_value(PARAM_TEXT, 'Notes'),
                'iscurrent' => new external_value(PARAM_BOOL, 'Is Current?|bool'),
            ])
        );
    }
    /**
     * Get current list of enrollments
     * @param  int  $studentid  student ID
     * @return hash of student records
     */
    public static function execute($studentid) {
        $recs = \local_gcs\data::get_student_enrollments($studentid);
        return $recs;
    }
}
