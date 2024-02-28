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

class studentrecord {
    public $recdef;
    

    /**
     * Initializes the student record field list definition
     */
    public function __construct() {
        $this->recdef = [
                'id' => new external_value(PARAM_INT, 'Identity Key|nolist|ro'),
                'legallastname' => new external_value(PARAM_TEXT, 'Legal Last Name'),
                'legalfirstname' => new external_value(PARAM_TEXT, 'Legal First Name'),
                'legalmiddlename' => new external_value(PARAM_TEXT, 'Legal Middle Name'),
                'preferredfirstname' => new external_value(PARAM_TEXT, 'Preferred First Name|nolist'),
                'programcode' => new external_value(PARAM_TEXT, 'Program Code|nolist'),
                'acceptancedate' => new external_value(PARAM_INT, 'Acceptance Date|nolist|date'),
                'statuscode' => new external_value(PARAM_TEXT, 'Status Code'),
                'exitdate' => new external_value(PARAM_INT, 'Exit Date|nolist|date'),
                'idnumber' => new external_value(PARAM_TEXT, 'External ID'),
                'userid' => new external_value(PARAM_INT, 'Moodle User ID|nolist'),
                'address' => new external_value(PARAM_TEXT, 'Address|nolist'),
                'address2' => new external_value(PARAM_TEXT, 'Address2|nolist'),
                'city' => new external_value(PARAM_TEXT, 'City|nolist'),
                'stateprovince' => new external_value(PARAM_TEXT, 'State|nolist'),
                'zip' => new external_value(PARAM_TEXT, 'Zip code|nolist'),
                'country' => new external_value(PARAM_TEXT, 'Zip code|nolist'),
                'birthplace' => new external_value(PARAM_TEXT, 'Birth Place|nolist'),
                'ssn' => new external_value(PARAM_TEXT, 'Social Security Number|nolist'),
                'citizenship' => new external_value(PARAM_TEXT, 'Citizenship|nolist'),
                'alienregnumber' => new external_value(PARAM_TEXT, 'Alien Reg Number|nolist'),
                'visatype' => new external_value(PARAM_TEXT, 'Visa Type|nolist'),
                'isveteran' => new external_value(PARAM_BOOL, 'Is Veteran?|bool|nolist'),
                'isgraduated' => new external_value(PARAM_BOOL, 'Is Graduated?|bool|nolist'),
                'ethniccode' => new external_value(PARAM_TEXT, 'Ethnic Code|nolist'),
                'donotemail' => new external_value(PARAM_BOOL, 'Do Not Email|bool|nolist'),
                'scholarshipeligible' => new external_value(PARAM_TEXT, 'Scholarship Eligible|nolist'),
                'regfoxemails' => new external_value(PARAM_TEXT, 'RegFox Emails (comma-separated)|nolist'),
            ];
    }
}
