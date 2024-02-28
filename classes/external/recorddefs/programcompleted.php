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

class programcompletedrecord {
    public $recdef;
    
    /**
     * Initializes the class record field list definition
     */
    public function __construct() {
        $this->recdef = [
			'id' => new external_value(PARAM_INT, 'Identity Key|ro'),
			'studentid' => new external_value(PARAM_INT, 'Student ID'),
			'programcode' => new external_value(PARAM_TEXT, 'Program Code'),
			'description' => new external_value(PARAM_TEXT, 'Description'),
			'university' => new external_value(PARAM_TEXT, 'School/University|nolist'),
			'enrolldate' => new external_value(PARAM_INT, 'Enrolled|date'),
			'completiondate' => new external_value(PARAM_INT, 'Completed|date'),
			'notes' => new external_value(PARAM_TEXT, 'Notes|nolist'),
			'basisofadmission' => new external_value(PARAM_BOOL, 'Basis of admission|bool|nolist'),
			'source' => new external_value(PARAM_TEXT, 'Source'),
        ];
    }
}
