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

class userrecord {
    public $recdef;
    

    /**
     * Initializes the student record field list definition
     */
    public function __construct() {
        $this->recdef = [
                'id' => new external_value(PARAM_INT, 'Identity Key|nolist|ro'),
                'username' => new external_value(PARAM_TEXT, 'User Name'),
                'idnumber' => new external_value(PARAM_TEXT, 'External ID'),
                'firstname' => new external_value(PARAM_TEXT, 'First Name'),
                'middlename' => new external_value(PARAM_TEXT, 'Middle Name'),
                'lastname' => new external_value(PARAM_TEXT, 'Last Name'),
                'alternatename' => new external_value(PARAM_TEXT, 'Alternate Name'),
                'email' => new external_value(PARAM_TEXT, 'Email'),
                'suspended' => new external_value(PARAM_BOOL, 'Is Suspended?|bool|nolist'),
            ];
    }
}
