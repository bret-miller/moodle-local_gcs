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
 * Defines a regfox registrant record.
 *
 * @package    local_gcs
 * @copyright  2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs\external;

defined('MOODLE_INTERNAL') || die();

use external_single_structure;
use external_value;

class classtakenrecord {
    public $recdef;
    
    /**
     * Initializes the class record field list definition
     */
    public function __construct() {
        $this->recdef = [
			'id' => new external_value(PARAM_INT, 'Identity Key|ro'),
			'termyear' => new external_value(PARAM_INT, 'Year'),
			'termcode' => new external_value(PARAM_TEXT, 'Term'),
			'coursecode' => new external_value(PARAM_TEXT, 'Course code'),
			'studentid' => new external_value(PARAM_INT, 'Stud ID'),
			'idnumber' => new external_value(PARAM_TEXT, 'External ID|nolist'),
			'shorttitleoverride' => new external_value(PARAM_TEXT, 'Short title Override'),
			'titleoverride' => new external_value(PARAM_TEXT, 'Title Override|nolist'),
			'credittypecode' => new external_value(PARAM_TEXT, 'Credit'),
			'gradecode' => new external_value(PARAM_TEXT, 'Grade'),
			'coursehoursoverride' => new external_value(PARAM_INT, 'Hours|nolist'),
			'registrationdate' => new external_value(PARAM_INT, 'Registration Date|date|nolist'),
			'tuitionpaid' => new external_value(PARAM_FLOAT, 'Tuition paid|nolist'),
			'completiondate' => new external_value(PARAM_INT, 'Completed|date|nolist'),
			'canceldate' => new external_value(PARAM_INT, 'Cancelled|date|nolist'),
			'comments' => new external_value(PARAM_TEXT, 'Comments|nolist'),
			'assignedcoursecode' => new external_value(PARAM_TEXT, 'Assigned course code|nolist'),
			'elective' => new external_value(PARAM_BOOL, 'Elective|bool|nolist'),
			'scholarshippedamount' => new external_value(PARAM_FLOAT, 'Sch amt|nolist'),
			'scholarshippedadjustment' => new external_value(PARAM_FLOAT, 'Sch adj|nolist'),
			'scholarshipid' => new external_value(PARAM_INT, 'Scholarship ID|nolist'),
			'agreementsigned' => new external_value(PARAM_INT, 'Enr Agr Signed|date|nolist'),
			'agreementid' => new external_value(PARAM_INT, 'Enr Agr ID|nolist'),
			'ordernumber' => new external_value(PARAM_INT, 'Order No|nolist'),
			'linenumber' => new external_value(PARAM_INT, 'Line No|nolist'),
			'fee' => new external_value(PARAM_FLOAT, 'Fee|nolist'),
			'classtuition' => new external_value(PARAM_FLOAT, 'Class Tuition|nolist'),
			'ordertotal' => new external_value(PARAM_FLOAT, 'Order total|nolist'),
			'studentpaid' => new external_value(PARAM_FLOAT, 'Student paid|nolist'),
			'regfoxcode' => new external_value(PARAM_TEXT, 'RegFox code|nolist'),
			'manualpricing' => new external_value(PARAM_BOOL, 'Manual pricing|bool|nolist'),
		];
    }
}
