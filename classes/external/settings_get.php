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
 * Get the program management plugin settings
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs\external;

defined('MOODLE_INTERNAL') || die();

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
require_once($_SERVER['DOCUMENT_ROOT'].'/lib/externallib.php');

require_login();

/**
 * Get set of codes
 */
class settings_get extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
        ]);
    }
    /**
     * Returns description of method returns
     * @return external_external_multiple_structure
     */
    public static function execute_returns() {
        return
            new external_single_structure([
                'menuadmin' => new external_value(PARAM_TEXT, 'program management menu item name'),
                'menustudent' => new external_value(PARAM_TEXT, 'student resources menu item name'),
                'logourl' => new external_value(PARAM_TEXT, 'logo url'),
                'printlogoenabled' => new external_value(PARAM_BOOL, 'print logo on reports?'),
                'version' => new external_value(PARAM_INT, 'version number'),
                'release' => new external_value(PARAM_TEXT, 'release id'),
            ]);
    }
    /**
     * Get plugin settings
     * @return settings object
     */
    public static function execute() {
        $arr = (object) [];
        $settings = new \local_gcs\settings();
        $arr->menuadmin = $settings->menuadmin;
        $arr->menustudent = $settings->menustudent;
        $arr->logourl = (string) $settings->logourl;
        $arr->printlogoenabled = (bool) $settings->printlogoenabled;
        $arr->version = $settings->version;
        $arr->release = $settings->release;
        return $arr;
    }
}
