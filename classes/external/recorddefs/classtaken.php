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

use external_single_structure;
use external_value;

class classtakenrecord {
    public $recdef;

    /**
     * Initializes the class record field list definition
     */
    public function __construct() {
        $this->recdef = [
            'id' => new external_value(PARAM_INT, 'Identity Key'),
            'termyear' => new external_value(PARAM_INT, 'Year'),
            'termcode' => new external_value(PARAM_TEXT, 'Term'),
            'coursecode' => new external_value(PARAM_TEXT, 'Course code'),
            'studentid' => new external_value(PARAM_INT, 'Stud ID'),
            'idnumber' => new external_value(PARAM_TEXT, 'External ID'),
            'shorttitleoverride' => new external_value(PARAM_TEXT, 'Short title Override'),
            'titleoverride' => new external_value(PARAM_TEXT, 'Title Override'),
            'credittypecode' => new external_value(PARAM_TEXT, 'Credit'),
            'gradecode' => new external_value(PARAM_TEXT, 'Grade'),
            'coursehoursoverride' => new external_value(PARAM_INT, 'Hours'),
            'registrationdate' => new external_value(PARAM_INT, 'Registration Date'),
            'tuitionpaid' => new external_value(PARAM_FLOAT, 'Tuition paid'),
            'completiondate' => new external_value(PARAM_INT, 'Completed'),
            'canceldate' => new external_value(PARAM_INT, 'Cancelled'),
            'comments' => new external_value(PARAM_TEXT, 'Comments'),
            'assignedcoursecode' => new external_value(PARAM_TEXT, 'Assigned course code'),
            'elective' => new external_value(PARAM_BOOL, 'Elective'),
            'scholarshippedamount' => new external_value(PARAM_FLOAT, 'Sch amt'),
            'scholarshippedadjustment' => new external_value(PARAM_FLOAT, 'Sch adj'),
            'scholarshipid' => new external_value(PARAM_INT, 'Scholarship ID'),
            'agreementsigned' => new external_value(PARAM_INT, 'Enr Agr Signed'),
            'agreementid' => new external_value(PARAM_INT, 'Enr Agr ID'),
            'ordernumber' => new external_value(PARAM_INT, 'Order No'),
            'linenumber' => new external_value(PARAM_INT, 'Line No'),
            'fee' => new external_value(PARAM_FLOAT, 'Fee'),
            'classtuition' => new external_value(PARAM_FLOAT, 'Class Tuition'),
            'ordertotal' => new external_value(PARAM_FLOAT, 'Order total'),
            'studentpaid' => new external_value(PARAM_FLOAT, 'Student paid'),
            'regfoxcode' => new external_value(PARAM_TEXT, 'RegFox code'),
            'manualpricing' => new external_value(PARAM_BOOL, 'Manual pricing'),
            'changeddate' => new external_value(PARAM_INT, 'Changed Date'),
            'changedbyuserid' => new external_value(PARAM_INT, 'Changed By User ID'),
        ];
    }
}
