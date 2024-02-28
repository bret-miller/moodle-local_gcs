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
 * Get student program requirements completed for GCS Program Management
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
 * Get list of requirements completed
 */
class program_req_completed_get extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT, 'Student ID'),
            'programcode' => new external_value(PARAM_TEXT, 'Program Code'),
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
                'programname' => new external_value(PARAM_TEXT, 'Program Name'),
                'reportseq' => new external_value(PARAM_INT, 'Report Sequence|nolist|hide'),
                'categorycode' => new external_value(PARAM_TEXT, 'Category Code|nolist'),
                'categoryname' => new external_value(PARAM_TEXT, 'Category'),
                'classcategorycode' => new external_value(PARAM_TEXT, 'Class Category|nolist|hide'),
                'coursecode' => new external_value(PARAM_TEXT, 'Course Code'),
                'shorttitle' => new external_value(PARAM_TEXT, 'Short Title|nolist'),
                'title' => new external_value(PARAM_TEXT, 'Title'),
                'transfershorttitle' => new external_value(PARAM_TEXT, 'Transfer Course Short Title|nolist'),
                'transfertitle' => new external_value(PARAM_TEXT, 'Transfer Course Title'),
                'credittypecode' => new external_value(PARAM_TEXT, 'Credit type'),
                'gradecode' => new external_value(PARAM_TEXT, 'Grade'),
                'passfail' => new external_value(PARAM_TEXT, 'Pass/Fail'),
                'coursehours' => new external_value(PARAM_INT, 'Hours'),
                'termyear' => new external_value(PARAM_INT, 'Term Year|nolist|hide'),
                'termcode' => new external_value(PARAM_TEXT, 'Term Code|nolist|hide'),
                'completiondate' => new external_value(PARAM_INT, 'Completed|date'),
                'elective' => new external_value(PARAM_BOOL, 'Elective?|bool'),
                'electiveeligible' => new external_value(PARAM_BOOL, 'Elective Eligible?|bool'),
                'termname' => new external_value(PARAM_TEXT, 'Term'),
                'coursepermitted' => new external_value(PARAM_BOOL, 'Permitted?|bool'),
                'coursesrequired' => new external_value(PARAM_INT, 'Courses Required|nolist|hide'),
            ])
        );
    }
    /**
     * Get current list of enrollments
     * @param  int  $studentid   student ID
     * @param  char $programcode program code
     * @return hash of student records
     */
    public static function execute($studentid, $programcode) {
        $recs = \local_gcs\data::get_student_course_completion($studentid, $programcode);
        return $recs;
    }
}
