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

class termdaterecord {
    public $recdef;
    
    /**
     * Initializes the record field list definition
     */
    public function __construct() {
        $this->recdef = [
                'id' => new external_value(PARAM_INT, 'Identity Key|nolist|ro'),
                'termyear' => new external_value(PARAM_INT, 'Term Year'),
                'termcode' => new external_value(PARAM_TEXT, 'Term Code'),
                'termname' => new external_value(PARAM_TEXT, 'Term Name'),
                'accountingcode' => new external_value(PARAM_TEXT, 'Accounting Code'),
                'accountingtitle' => new external_value(PARAM_TEXT, 'Accounting Title'),
                'registrationstart' => new external_value(PARAM_INT, 'Registration Starts'),
                'registrationend' => new external_value(PARAM_INT, 'Registration Ends'),
                'classesstart' => new external_value(PARAM_INT, 'Classes Starts'),
                'classesend' => new external_value(PARAM_INT, 'Classes Ends'),
			/*
                'address' => new external_value(PARAM_TEXT, 'Address|nolist|width=400px|newline'),
                'city' => new external_value(PARAM_TEXT, 'City|nolist|width=300px'),
                'state' => new external_value(PARAM_TEXT, 'State|nolist|width=80px'),
                'zip' => new external_value(PARAM_TEXT, 'Zip code|nolist|width=150px'),
			*/
            ];
    }
}