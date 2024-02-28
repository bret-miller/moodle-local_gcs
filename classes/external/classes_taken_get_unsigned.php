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
 * Get classes taken for GCS Program Management
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
require_once(__dir__.'/recorddefs/classtaken.php');

require_login();

/**
 * Get list of classes taken
 */
class classes_taken_get_unsigned extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'stuid' => new external_value(PARAM_TEXT, 'student id or empty for all students', VALUE_REQUIRED),
        ]);
    }
    /**
     * Returns description of method returns
     * @return external_external_multiple_structure
     */
    public static function execute_returns() {
		$def = new classtakenrecord();
        return new external_multiple_structure(
            new external_single_structure($def->recdef)
        );
    }
    /**
     * Get current list of classes taken
     * @param  int  $stuid student id
     * @return classes taken records
     */
    public static function execute($stuid) {
        $recs = \local_gcs\data::get_classes_taken_unsigned($stuid);
        return $recs;
    }
}
