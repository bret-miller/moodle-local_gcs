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
 * Get a course record for GCS Program Management
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
require_once(__dir__.'/recorddefs/course.php');

require_login();
require_capability('local/gcs:administrator', \context_system::instance());

/**
 * Get single course record
 */
class course_get_by_coursecode extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'coursecode' => new external_value(PARAM_TEXT, VALUE_REQUIRED),
        ]);
    }
    /**
     * Returns description of method returns
     * @return external_external_single_structure
     */
    public static function execute_returns() {
		$def = new courserecord();
        new external_single_structure($def->recdef);
    }
    /**
     * Get a course record
     * @param  int  $coursecode  coursecode of course to get
     * @return object course record
     */
    public static function execute($coursecode) {
        return \local_gcs\data::get_course_by_coursecode($coursecode);
    }
}