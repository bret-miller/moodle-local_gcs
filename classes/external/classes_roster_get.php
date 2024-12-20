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
 * Get roster for classes in a specified term for GCS Program Management
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
require_capability('local/gcs:administrator', \context_system::instance());

/**
 * Get classes roster
 */
class classes_roster_get extends \external_api {
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'termyear' => new external_value(PARAM_INT, 'term year'),
            'termcode' => new external_value(PARAM_TEXT, 'course code'),
        ]);
    }
    /**
     * Returns description of method returns
     * @return external_external_multiple_structure
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'rowid' => new external_value(PARAM_INT, 'row number'),
                'coursecode' => new external_value(PARAM_TEXT, 'course code'),
                'title' => new external_value(PARAM_TEXT, 'title of course'),
                'coursehours' => new external_value(PARAM_INT, 'course hours'),
                'instructor' => new external_value(PARAM_INT, 'instructor id'),
                'instructorname' => new external_value(PARAM_TEXT, 'instructor full name'),
                'studentid' => new external_value(PARAM_INT, 'student id'),
                'legallastname' => new external_value(PARAM_TEXT, 'student last name'),
                'legalfirstname' => new external_value(PARAM_TEXT, 'student first name'),
                'credittype' => new external_value(PARAM_TEXT, 'credit type'),
                'grade' => new external_value(PARAM_TEXT, 'student grade'),
                'completion' => new external_value(PARAM_TEXT, 'student status description'),
            ])
        );
    }
    /**
     * Get roster for classes in a specified term
     * @param  int term year
     * @param  string term code
     * @return hash of class roster records
     */
    public static function execute($termyear, $termcode) {
        global $DB;
        $sql = "SELECT ROW_NUMBER() OVER( ORDER BY c.coursecode, s.legallastname, s.legalfirstname ) as rowid,
                       c.coursecode, c.title, c.coursehours, c.instructor, coalesce(ud.data,'** none **') as instructorname,
                       ct.studentid, s.legallastname, s.legalfirstname, crt.description as credittype,
                       coalesce(gr.description,'') AS grade,
                       case when ct.completiondate=0 or ct.completiondate is null
                           then 'In-progress' else 'Completed' end as completion
                  FROM mdl_local_gcs_classes c
             LEFT JOIN mdl_local_gcs_classes_taken ct
                    ON ct.coursecode=c.coursecode
                   AND ct.termyear=c.termyear
                   AND ct.termcode=c.termcode
             LEFT JOIN mdl_local_gcs_student s ON s.id=ct.studentid
             LEFT JOIN mdl_local_gcs_codes crt ON crt.codeset='cr_type' AND crt.code=ct.credittypecode
             LEFT JOIN mdl_user_info_data ud on ud.userid = c.instructor
             LEFT JOIN mdl_user_info_field uf on uf.id = ud.fieldid
             LEFT JOIN mdl_local_gcs_codes gr ON gr.codeset='grade' AND gr.code=ct.gradecode
                 WHERE c.termyear=:termyear AND c.termcode=:termcode
                   AND coalesce(uf.shortname,'local_gcs_fullname')='local_gcs_fullname'
              ORDER BY c.coursecode, s.legallastname, s.legalfirstname";
        $param = ['termyear' => $termyear, 'termcode' => $termcode];
        $classlist = $DB->get_records_sql(
            $sql,
            $param,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $classlist;
    }
}
