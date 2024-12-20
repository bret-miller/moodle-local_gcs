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

class tablefielddefrecord {
    public $recdef;

    /**
     * Initializes the field defs record field list definition
     */
    public function __construct() {
        $this->recdef = [
            'id' => new external_value(PARAM_INT, 'Identity Key'),
            'tableid' => new external_value(PARAM_TEXT, 'Table identifier'),
            'fieldname' => new external_value(PARAM_TEXT, 'Field name'),
            'dbdatatype' => new external_value(PARAM_TEXT, 'Moodle Data type'),
            'datatype' => new external_value(PARAM_TEXT, 'App Data type'),
            'colhdr' => new external_value(PARAM_TEXT, 'Column header'),
            'widthval' => new external_value(PARAM_TEXT, 'External ID|nolist'),
            'ishtml' => new external_value(PARAM_BOOL, 'HTML encode?'),
            'tooltip' => new external_value(PARAM_TEXT, 'Field tooltip'),
            'islist' => new external_value(PARAM_BOOL, 'Include in table record list?'),
            'issort' => new external_value(PARAM_BOOL, 'Sort column?'),
            'addshow' => new external_value(PARAM_TEXT, 'Add - show/hide/readonly'),
            'addisnewline' => new external_value(PARAM_BOOL, 'Add - dialog break to newline?'),
            'addpopupid' => new external_value(PARAM_TEXT, 'Add - Popup id'),
            'addsellistid' => new external_value(PARAM_TEXT, 'Add - Select list id'),
            'addisrequired' => new external_value(PARAM_BOOL, 'Add - Validation - value is required'),
            'updshow' => new external_value(PARAM_TEXT, 'Update - show setting'),
            'updisnewline' => new external_value(PARAM_BOOL, 'Update - dialog break to newline?'),
            'updpopupid' => new external_value(PARAM_TEXT, 'Update - Popup id (shared component)'),
            'updsellistid' => new external_value(PARAM_TEXT, 'Update - Select list id'),
            'updisrequired' => new external_value(PARAM_BOOL, 'Update - Validation - value is required'),
        ];
    }
}
