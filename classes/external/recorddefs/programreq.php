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

class programreqrecord {
    public $recdef;
    
    /**
     * Initializes the class record field list definition
     */
    public function __construct() {
        $this->recdef = [
			'id' => new external_value(PARAM_INT, 'program record id'),
			'programcode' => new external_value(PARAM_TEXT, 'id of program'),
			'categorycode' => new external_value(PARAM_TEXT, 'category'),
			'description' => new external_value(PARAM_TEXT, 'program description text'),
			'coursesrequired' => new external_value(PARAM_INT, 'number of courses required in this category'),
			'reportseq' => new external_value(PARAM_INT, 'order to display on reports, lowest first'),
            ];
    }
}
