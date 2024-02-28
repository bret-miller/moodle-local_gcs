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
 * Get students for GCS Program Management
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
require_once(__dir__.'/../data.php');
require_once(__dir__.'/recorddefs/user.php');

require_login();

/**
 * Get list of students
 */
class users_get_instructors extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([]);
    }
    /**
     * Returns description of method returns
     * @return external_external_multiple_structure
     */
    public static function execute_returns() {
		$def = new userrecord();
        return new external_multiple_structure(
            new external_single_structure($def->recdef)
        );
    }
    /**
     * Get current list of students
     * @param  
     * @return list
     */
    public static function execute() {
        global $DB;
        $sql = "
select u.id, u.username, u.idnumber, u.firstname, u.middlename, u.lastname, u.alternatename, u.email, u.suspended
from mdl_user u
join mdl_user_info_data i on i.userid=u.id
join mdl_user_info_field f on f.id=i.fieldid and f.shortname='local_gcs_instructor'
where i.data=1
order by u.suspended, u.lastname, u.firstname, u.middlename
";
        $list = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $list;
    }
}
