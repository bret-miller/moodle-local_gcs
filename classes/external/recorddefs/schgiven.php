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

class schgivenrecord {
    public $recdef;
    
    /**
     * Initializes the class record field list definition
     */
    public function __construct() {
        $this->recdef = [
			'id' => new external_value(PARAM_INT, 'Identity Key|ro|nolist'),
			'studentid' => new external_value(PARAM_INT, 'Student id'),
			'termyear' => new external_value(PARAM_INT, 'Term year'),
			'requestdate' => new external_value(PARAM_INT, 'Request date|date'),
			'programcode' => new external_value(PARAM_TEXT, 'Program code'),
			'occupation' => new external_value(PARAM_TEXT, 'Occupation|nolist'),
			'employer' => new external_value(PARAM_TEXT, 'Employer|nolist'),
			'cadinfoauth' => new external_value(PARAM_BOOL, 'CAD info auth|nolist'),
			'perunitamount' => new external_value(PARAM_FLOAT, 'Per unit amount|nolist'),
			'coursemax' => new external_value(PARAM_INT, 'Maximum courses|nolist'),
			'eligiblefrom' => new external_value(PARAM_INT, 'Eligible from|date|nolist'),
			'eligiblethru' => new external_value(PARAM_INT, 'Eligible thru|nolist|date|nolist'),
			'decision' => new external_value(PARAM_TEXT, 'Decision|nolist'),
			'reviewdate' => new external_value(PARAM_INT, 'Review date|date'),
			'comments' => new external_value(PARAM_TEXT, 'Comments|nolist'),
			'studentnotified' => new external_value(PARAM_INT, 'Student notified|date|nolist'),
			'category' => new external_value(PARAM_TEXT, 'Scholarship Code|nolist'),
        ];
    }
}
