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

class regfoxregistrantrecord {
    /** @var external single_structure $rec regfox registrant record */
    public $rec;
    

    /**
     * Initializes the RegFox registrant record definition
     */
    public function __construct() {
        $this->rec = new external_single_structure([
            'id' => new external_value(PARAM_INT, 'Identity Key|ro'),
            'email' => new external_value(PARAM_TEXT, 'Email address'),
            'firstname' => new external_value(PARAM_TEXT, 'First name'),
            'lastname' => new external_value(PARAM_TEXT, 'Last name'),
            'scholarshipcode' => new external_value(PARAM_TEXT, 'Scholarship code'),
            'amount' => new external_value(PARAM_FLOAT, 'Amount paid'),
            'tranid' => new external_value(PARAM_INT, 'Transaction ID'),
            'processedtime' => new external_value(PARAM_INT, 'Time processed|date'),
            'studentid' => new external_value(PARAM_INT, 'Student id'),
        ]);
    }
}
