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
 * Validate current session/user for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023-2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_gcs;

/**
 * Data services for local_gcs web services
 */
class data {
    /**
     * Retrieves a set of codes
     *
     * @return hash of codeset names
     */
    public static function get_codesets () {
        global $DB;
        $sqlparam = [];

        $sql = 'select distinct codeset from {local_gcs_codes}';
        $codesets = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );

        return $codesets;
    }
    /**
     * Retrieves a set of codes
     *
     * @param  string $codeset set of codes to return or blank for all sets
     * @return hash of code records
     */
    public static function get_codes ($codeset) {
        global $DB;
        $sqlparam = [];

        if ( $codeset ) {
            $sql = 'select * from {local_gcs_codes} where codeset=?';
            $sqlparam['codeset'] = $codeset;
        } else {
            $sql = 'select * from {local_gcs_codes}';
        }
        $codes = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );

        // Special case:  for the codeset "codesets" we also add the list of codeset nmes to it.
        if ( $codeset == 'codesets' ) {
            $codesets = self::get_codesets();
            foreach ($codesets as $csrec) {
                $coderec = [];
                $coderec['id'] = 0;
                $coderec['codeset'] = $codeset;
                $coderec['code'] = 'codeset_' . $csrec->codeset;
                $coderec['description'] = '(codeset) ' . $csrec->codeset;
                $codes[] = $coderec;
            }
        }

        return $codes;
    }

    /**
     * Retrieves a single code
     *
     * @param  int    $id   id of the code record
     * @return object       code record
     */
    public static function get_code ($id) {
        global $DB;
        $code = $DB->get_record(
                'local_gcs_codes',
                [ 'id' => $id ]
            );
        return $code;
    }

    /**
     * Retrieves a single code using the codeset and code
     *
     * @param  string $codeset set the code belongs to
     * @param  string $code    the code
     * @return object code record
     */
    public static function get_code_by_code ($codeset, $code) {
        global $DB;
        $code = $DB->get_record(
                'local_gcs_codes',
                [ 'codeset' => $codeset, 'code' => $code ]
            );
        return $code;
    }

    /**
     * Update a single code
     *
     * @param object $rec code record
     */
    public static function update_code ($rec) {
        global $DB;
        $rec->code = $rec->code;
        $DB->update_record('local_gcs_codes', $rec);
        return $rec;
    }

     /**
      * inserts a code
      *
      * @param  object $rec code record (less id)
      * @return object code record
      */
    public static function insert_code ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $rec->code = $rec->code;
        $id = $DB->insert_record('local_gcs_codes', $rec);
        $coderec = self::get_code($id);
        return $coderec;
    }

    /**
     * deletes a code in the database
     *
     * @param int $id code id
     */
    public static function delete_code ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_codes', 'id=:codeid', ['codeid' => $id]);
        return;
    }

    /**
     * Retrieves the list of programs from the database
     *
     * @return  hash  of program records
     */
    public static function get_programs () {
        global $DB;

        $sql = 'select * from {local_gcs_program}';
        $programs = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($programs as $pgm) {
            (bool) $pgm->academicdegree = (bool) $pgm->academicdegree;
            (bool) $pgm->inactive = (bool) $pgm->inactive;
        }
        return $programs;
    }
    /**
     * Retrieves single program record from the database
     *
     * @param int $id program id
     * @return  object  program record
     */
    public static function get_program ($id) {
        global $DB;

        //$sql = 'select * from {local_gcs_program} where id=:id';
        $pgm = $DB->get_record('local_gcs_program', ['id' => $id]);
        (bool) $pgm->academicdegree = (bool) $pgm->academicdegree;
        (bool) $pgm->inactive = (bool) $pgm->inactive;
        return $pgm;
    }
    /**
     * Retrieves single program record from the database
     *
     * @param string $programcode
     * @return  object  program record
     */
    public static function get_program_by_programcode ($programcode) {
        global $DB;

        //$sql = 'select * from {local_gcs_program} where programcode=:programcode';
        $pgm = $DB->get_record('local_gcs_program', ['programcode' => $programcode]);
        (bool) $pgm->academicdegree = (bool) $pgm->academicdegree;
        (bool) $pgm->inactive = (bool) $pgm->inactive;
        return $pgm;
    }
    /**
     * updates single program record in the database
     *
     * @param object $rec program record
     */
    public static function update_program ($rec) {
        global $DB;
        $rec->programcode = strtoupper($rec->programcode);
        (int) $rec->academicdegree = (int) $rec->academicdegree;
        (int) $rec->inactive = (int) $rec->inactive;
        $programrec = $DB->update_record('local_gcs_program', $rec);
        return $rec;
    }
    /**
     * inserts single program record in the database
     *
     * @param object $rec program record (less id)
     */
    public static function insert_program ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $rec->programcode = strtoupper($rec->programcode);
        (int) $rec->academicdegree = (int) $rec->academicdegree;
        (int) $rec->inactive = (int) $rec->inactive;
        $id = $DB->insert_record('local_gcs_program', $rec);
        $programrec = self::get_program($id);
        return $programrec;
    }
    /**
     * deletes single program record in the database
     *
     * @param int $id program id
     */
    public static function delete_program ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_program', 'id=:programid', ['programid' => $id]);
        return;
    }

    /**
     * Retrieves the list of program requirements from the database
     *
     * @param string $programcode program code or empty for all requirements
     * @return  hash  of program records
     */
    public static function get_program_reqs ($programcode) {
        global $DB;

        $sql = 'select * from {local_gcs_program_req}';
        if ($programcode != '') {
            $sql .= ' where programcode=:code';
        }
        $programreqs = $DB->get_records_sql(
            $sql,
            ['code' => $programcode],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $programreqs;
    }
    /**
     * Retrieves single program requirement record from the database
     *
     * @param int $id program requirement record id
     * @return  object  program requirement record
     */
    public static function get_program_req ($id) {
        global $DB;

        //$sql = 'select * from {local_gcs_program_req} where id=:id';
        $rec = $DB->get_record('local_gcs_program_req', ['id' => $id]);
        return $rec;
    }
    /**
     * updates single program record in the database
     *
     * @param object $rec program requirement record
     */
    public static function update_program_req ($rec) {
        global $DB;
        $programrec = $DB->update_record('local_gcs_program_req', $rec);
        return $rec;
    }
    /**
     * inserts single program requirement record in the database
     *
     * @param object $rec program requirement record (less id)
     */
    public static function insert_program_req ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_program_req', $rec);
        return self::get_program_req($id);
    }
    /**
     * deletes single program requirement record in the database
     *
     * @param int $id program requirement record id
     */
    public static function delete_program_req ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_program_req', 'id=:recid', ['recid' => $id]);
        return;
    }

    /**
     * Retrieves the list of courses permitted from the database
     *
     * @param string $programcode program code or empty for all programs
     * @return  hash  of course permitted records
     */
    public static function get_courses_permitted ($programcode) {
        global $DB;

        $sql = 'select * from {local_gcs_courses_permitted}';
        if ($programcode != '') {
            $sql .= ' where programcode=:programcode';
        }
        $recs = $DB->get_records_sql(
            $sql,
            ['programcode' => $programcode],
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->electiveeligible = (bool) $rec->electiveeligible;
        }
        return $recs;
    }
    /**
     * Retrieves single course permitted record from the database
     *
     * @param int $id course permitted record id
     * @return  object  course permitted record
     */
    public static function get_course_permitted ($id) {
        global $DB;

        //$sql = 'select * from {local_gcs_courses_permitted} where id=:id';
        $rec = $DB->get_record('local_gcs_courses_permitted', ['id' => $id]);
        (bool) $rec->electiveeligible = (bool) $rec->electiveeligible;
        return $rec;
    }
    /**
     * updates single course permitted record in the database
     *
     * @param object $rec course permitted record
     */
    public static function update_course_permitted ($rec) {
        global $DB;
        $rec->course_code = strtoupper($rec->course_code);
        (int) $rec->electiveeligible = (int) $rec->electiveeligible;
        $programrec = $DB->update_record('local_gcs_courses_permitted', $rec);
        return $rec;
    }
    /**
     * inserts single course permitted record in the database
     *
     * @param object $rec course permitted record (less id)
     */
    public static function insert_course_permitted ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $rec->course_code = strtoupper($rec->course_code);
        (int) $rec->electiveeligible = (int) $rec->electiveeligible;
        $id = $DB->insert_record('local_gcs_courses_permitted', $rec);
        $ppgmreq = self::get_course_permitted($id);
        return $pgmreq;
    }
    /**
     * deletes single course permitted record in the database
     *
     * @param int $id course permitted record id
     */
    public static function delete_course_permitted ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_courses_permitted', 'id=:recid', ['recid' => $id]);
        return;
    }

    /**
     * Retrieves the list of accounting scholarship categories
     *
     * @return  hash  of scholarship category records
     */
    public static function get_sch_cats () {
        global $DB;

        $sql = 'select * from {local_gcs_acct_sch_cat}';
        $cats = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );

        return $cats;
    }
    /**
     * Retrieves single accounting scholarship category
     *
     * @param char $id accounting scholarship category record id
     * @return  object  scholarship category record
     */
    public static function get_sch_cat ($id) {
        global $DB;
        $catrec = $DB->get_record(
                'local_gcs_acct_sch_cat',
                [ 'id' => $id ]
            );
        return $catrec;
    }
    /**
     * updates single accounting scholarship category
     *
     * @param object $rec accounting scholarship category record
     */
    public static function update_sch_cat ($rec) {
        global $DB;
        $catrec = $DB->update_record('local_gcs_acct_sch_cat', $rec);
        return $rec;
    }
    /**
     * inserts single accounting scholarship category
     *
     * @param object $rec accounting scholarship category record (less id)
     */
    public static function insert_sch_cat ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_acct_sch_cat', $rec);
        $catrec = self::get_sch_cat($id);
        return $catrec;
    }
    /**
     * deletes single accounting scholarship category
     *
     * @param int $id category record id
     */
    public static function delete_sch_cat ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_acct_sch_cat', 'id=:id', ['id' => $id]);
        return;
    }

    /**
     * Retrieves the list of term date definition recors.
     *
     * @return  hash  of term defnition records
     */
    public static function get_term_dates_all () {
        global $DB;

        $sql = 'select * from {local_gcs_term_dates} order by termyear desc, termcode desc';
        $cats = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $cats;
    }
    /**
     * Retrieves single term definition record
     *
     * @param char $id term definition record record id
     * @return  object  term definition record
     */
    public static function get_term_dates ($id) {
        global $DB;
        $catrec = $DB->get_record(
                'local_gcs_term_dates',
                [ 'id' => $id ]
            );
        return $catrec;
    }
    /**
     * Retrieves the current term definition record
     *
     * @return  object  term definition record
     */
    public static function get_term_date_current () {
        global $DB;

        // Get the record.
        $sql = "select *
            from {local_gcs_term_dates} td
            where td.registrationstart < unix_timestamp()
            order by td.registrationstart desc
            limit 1";

        $recs = $DB->get_records_sql(
            $sql,
            [ ],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * updates single term definition record
     *
     * @param object $rec term definition record
     */
    public static function update_term_dates ($rec) {
        global $DB;
        $catrec = $DB->update_record('local_gcs_term_dates', $rec);
        return $rec;
    }
    /**
     * inserts single term definition record
     *
     * @param object $rec term definition record (less id)
     */
    public static function insert_term_dates ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_term_dates', $rec);
        $catrec = self::get_term_dates($id);
        return $catrec;
    }
    /**
     * deletes single term definition record
     *
     * @param int $id category record id
     */
    public static function delete_term_dates ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_term_dates', 'id=:id', ['id' => $id]);
        return;
    }

    /**
     * Retrieves the list of instructors from the database
     *
     * @return  hash  of instructor records
     */
    public static function get_instructors () {
        global $DB;

        $sql = 'select u.id, u.lastname, u.firstname, fn.data as fullname
                  from {user} u
                  left join {user_info_data} fn on fn.userid=u.id
                  left join {user_info_field} fnf on fnf.id=fn.fieldid
                  left join {user_info_data} ins on ins.userid=u.id
                  left join {user_info_field} insf on insf.id=ins.fieldid
                 where 1=cast(ins.data as int) and fnf.name=:fnfname and insf.name=:insfname';
        $courses = $DB->get_records_sql(
            $sql,
            ['fnfname' => 'Full Name', 'insfname' => 'Instsructor?'],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $courses;
    }

    /**
     * Retrieves the list of courses from the database
     *
     * @return  hash  of course records
     */
    public static function get_courses () {
        global $DB;

        $sql = 'select * from {local_gcs_courses}';
        $courses = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $courses;
    }
    /**
     * Retrieves single course record from the database
     *
     * @param int $id course id
     * @return  object  course record
     */
    public static function get_course ($id) {
        global $DB;

        //$sql = 'select * from {local_gcs_courses} where id=:courseid';
        $course = $DB->get_record('local_gcs_courses', ['id' => $id]);
        return $course;
    }
    /**
     * Retrieves single course record from the database
     *
     * @param int $id course id
     * @return  object  course record
     */
    public static function get_course_by_coursecode($coursecode) {
        global $DB;

        //$sql = 'select * from {local_gcs_courses} where coursecode=:coursecode';
        return $DB->get_record('local_gcs_courses', ['coursecode' => $coursecode]);
    }
    /**
     * Retrieves single course record from the database
     *
     * @param string $coursecode course code
     * @return  object  course record
     */
    public static function get_course_by_code ($coursecode) {
        global $DB;

        //$sql = 'select * from {local_gcs_courses} where coursecode=:coursecode';
        $course = $DB->get_record('local_gcs_courses', ['coursecode' => $coursecode]);
        return $course;
    }
    /**
     * updates single course record in the database
     *
     * @param object $rec course record
     */
    public static function update_course ($rec) {
        global $DB;
        $rec->coursecode = strtoupper($rec->coursecode);
        $courserec = $DB->update_record('local_gcs_courses', $rec);
        return $rec;
    }
    /**
     * inserts single course record in the database
     *
     * @param object $rec course record (less id)
     */
    public static function insert_course ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $rec->coursecode = strtoupper($rec->coursecode);
        $id = $DB->insert_record('local_gcs_courses', $rec);
        $courserec = self::get_course($id);
        return $courserec;
    }
    /**
     * deletes single course record in the database
     *
     * @param int $id course id
     */
    public static function delete_course ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_courses', 'id=:courseid', ['courseid' => $id]);
        return;
    }

    /**
     * Retrieves the list of classes from the database
     *
     * @return  hash  of class records
     */
    public static function get_classes () {
        global $DB;

        $sql = 'select * from {local_gcs_classes}';
        $classes = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $classes;
    }
    /**
     * Retrieves single class record from the database
     *
     * @param int $id class id
     * @return  object  class record
     */
    public static function get_class ($id) {
        global $DB;

        //$sql = 'select * from {local_gcs_classes} where id=:classid';
        $class = $DB->get_record('local_gcs_classes', ['id' => $id]);
        return $class;
    }
    /**
     * Retrieves single class record from the database
     *
     * @param int $termyear
     *        int $termcode
     *        string $coursecode
     * @return  object  class record
     */
    public static function get_class_by_code_and_term ($coursecode, $termyear, $termcode) {
        global $DB;

        //$sql = 'select * from {local_gcs_classes} where coursecode=:coursecode and termyear=:termyear and termcode=:termcode limit 1';
        $class = $DB->get_record('local_gcs_classes', [
            'coursecode' => $coursecode,
            'termyear' => $termyear,
            'termcode' => $termcode,
            ]);
        return $class;
	}
    /**
     * updates single class record in the database
     *
     * @param object $rec class record
     */
    public static function update_class ($rec) {
        global $DB;
        $rec->coursecode = strtoupper($rec->coursecode);
        $classrec = $DB->update_record('local_gcs_classes', $rec);
        return $rec;
    }
    /**
     * inserts single class record in the database
     *
     * @param object $rec class record (less id)
     */
    public static function insert_class ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $rec->coursecode = strtoupper($rec->coursecode);
        $id = $DB->insert_record('local_gcs_classes', $rec);
        $classrec = self::get_class($id);
        return $classrec;
    }
    /**
     * deletes single class record in the database
     *
     * @param int $id class id
     */
    public static function delete_class ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_classes', 'id=:classid', ['classid' => $id]);
        return;
    }

    /**
     * Retrieves the list of student records.
     *
     * @param   bool $onlyactive optional switch to return only "active" students with a user account
     * @return  hash  of student records
     */
    public static function get_students_all ($onlyactive=false) {
        global $DB;
        global $USER;

        $systemcontext = \context_system::instance();
        if (has_capability('local/gcs:administrator', $systemcontext)) {
            $sql  = "select * from {local_gcs_student}";
            if ($onlyactive) {
                $sql .= " where statuscode='ACT' and userid <> 0";
            }
            $sql .= ' order by legallastname, legalfirstname, legalmiddlename';
            $sqlvar = [];
        } else {
            $sql = 'select * from {local_gcs_student} where userid=:userid';
            $sqlvar = ['userid' => $USER->id];
        }
        $recs = $DB->get_records_sql(
            $sql,
            $sqlvar,
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->isveteran = (bool) $rec->isveteran;
            (bool) $rec->donotemail = (bool) $rec->donotemail;
            if (is_null($rec->regfoxemails)) {
                $rec->regfoxemails = '';
            }
        }
        return $recs;
    }

    /**
     * For admins - Retrieves the list of students with unsigned enroll agreement class records.
     * For student - Always returns his/her student record whether or not there are unsigned enroll agreement records.
     *
     * @param
     * @return  student record(s)
     */
    public static function get_students_with_unsigned_enrollment_agreements () {
        global $DB;
        global $USER;

        $systemcontext = \context_system::instance();
        if (has_capability('local/gcs:administrator', $systemcontext)) {
            $sql = "
WITH td AS (
    select max(d.registrationstart) as registrationstart
    from {local_gcs_term_dates} d
    where d.registrationstart<unix_timestamp()
)
SELECT DISTINCT st.*
FROM td
JOIN {local_gcs_term_dates} d on d.registrationstart=td.registrationstart
JOIN {local_gcs_classes_taken} ct on ct.termyear=d.termyear and ct.termcode=d.termcode
JOIN {local_gcs_student} st on st.id=ct.studentid
WHERE ct.agreementsigned is null or ct.agreementsigned = 0
ORDER BY st.legallastname, st.legalfirstname, st.legalmiddlename;
";
            $sqlvar = [];
        } else {
            $sql = 'select * from {local_gcs_student} where userid=:userid';
            $sqlvar = ['userid' => $USER->id];
        }

        $recs = $DB->get_records_sql(
            $sql,
            $sqlvar
        );
        foreach ($recs as $rec) {
            (bool) $rec->isveteran = (bool) $rec->isveteran;
            (bool) $rec->donotemail = (bool) $rec->donotemail;
            if (is_null($rec->regfoxemails)) {
                $rec->regfoxemails = '';
            }
        }
        return $recs;
    }


    /**
     * Retrieves the list of student records.
     *
     * @param
     * @return  student records
     */
    public static function get_students_with_scholarships () {
        global $DB;
        global $USER;

        $systemcontext = \context_system::instance();
        if (has_capability('local/gcs:administrator', $systemcontext)) {
            $sql = "
SELECT DISTINCT st.*
FROM {local_gcs_student} st
JOIN {local_gcs_sch_given} sg on sg.studentid = st.id
WHERE st.statuscode='ACT'
ORDER BY st.legallastname, st.legalfirstname, st.legalmiddlename;
";
            $sqlvar = [];
        } else {
            $sql = 'select * from {local_gcs_student} where userid=:userid';
            $sqlvar = ['userid' => $USER->id];
        }

        $recs = $DB->get_records_sql(
            $sql,
            $sqlvar,
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->isveteran = (bool) $rec->isveteran;
            (bool) $rec->donotemail = (bool) $rec->donotemail;
            if (is_null($rec->regfoxemails)) {
                $rec->regfoxemails = '';
            }
        }
        return $recs;
    }

    /**
     * Retrieves single student record DEPRECATED--USE get_student_by_id WHICH RETURNS LIST
     *
     * @param char $id student record record id
     * @return  object  student record
     */
    public static function get_students ($id) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_student',
                [ 'id' => $id ]
            );
        (bool) $rec->isveteran = (bool) $rec->isveteran;
        (bool) $rec->donotemail = (bool) $rec->donotemail;
        if (is_null($rec->regfoxemails)) {
            $rec->regfoxemails = '';
        }
        return $rec;
    }
    /**
     * Retrieves student record as a list.  Empty list means not found or not authorized.
     *
     * @param char $id student record record id
     * @return  list of student, 1 or 0 records
     */
    public static function get_student_by_id ($id) {
        global $DB;
        // Non-Administrators only allowed to read their record.
        $systemcontext = \context_system::instance();
        if (!has_capability('local/gcs:administrator', $systemcontext) && !self::is_student_logged_in($id)) {
            return [];
        }

        // Read single record to list.
        $sql = 'select * from {local_gcs_student} where id=:id';
        $sqlvar = ['id' => $id];
        $recs = $DB->get_records_sql(
            $sql,
            $sqlvar,
            $limitfrom = 0,
            $limitnum = 1
        );
        foreach ($recs as $rec) {
            (bool) $rec->isveteran = (bool) $rec->isveteran;
            (bool) $rec->donotemail = (bool) $rec->donotemail;
            if (is_null($rec->regfoxemails)) {
                $rec->regfoxemails = '';
            }
        }
        return $recs;
    }
    /**
     * Checks if $stuid is logged in
     *
     * @param char $stuid (student record id)
     * @return  true/false
     */
    public static function is_student_logged_in ($stuid) {
        $sturec = self::get_student_logged_in();
        return ($stuid == $sturec->id);
    }
    /**
     * Retrieves the student record for the current user
     *
     * @return  object  student record
     */
    public static function get_student_logged_in () {
        global $DB;
        global $USER;

        $id = $USER->id;
        $rec = $DB->get_record(
                'local_gcs_student',
                [ 'userid' => $USER->id ]
            );

        (bool) $rec->isveteran = (bool) $rec->isveteran;
        (bool) $rec->donotemail = (bool) $rec->donotemail;
        if (is_null($rec->regfoxemails)) {
            $rec->regfoxemails = '';
        }
        return $rec;
    }
    /**
     * Retrieves a list of students by email address.
     *
     * @param   string $email email address
     * @return  hash  of student records
     */
    public static function get_students_by_email ($email) {
        global $DB;
        $systemcontext = \context_system::instance();
        $sql = 'select s.*, u.email from {local_gcs_student} s
                  join {user} u on u.id=s.userid
                  where u.email=:email or s.regfoxemails like :rfepartial';
        $sqlvar = ['email' => $email, 'rfepartial' => "%$email%"];
        $recs = $DB->get_records_sql(
            $sql,
            $sqlvar,
            $limitfrom = 0,
            $limitnum = 0
        );
        $students = [];
        foreach ($recs as $rec) {
            (bool) $rec->isveteran = (bool) $rec->isveteran;
            (bool) $rec->donotemail = (bool) $rec->donotemail;
            if (is_null($rec->regfoxemails)) {
                $rec->regfoxemails = '';
            }
            // We only want to include those where the emails truly match.
            // We can partially match an email in regfoxemails without matching it completely with the sql query.
            // For example, searching for user@example.com would match user@example.com.au in the query, but not here.
            $rfemails = explode(',', $rec->regfoxemails);
            if (($rec->email == $email) || (in_array($email, $rfemails))) {
                unset($rec->email); // Remove this property since it is not in the original data record.
                $students[$rec->id] = $rec; // Make the array identical to what is returned from the query.
            }
        }
        return $recs;
    }
    /**
     * Retrieves a list of students by partial name.
     *
     * @param   string $firstname partial first name
     * @param   string $lastname partial last name
     * @return  hash  of student records
     */
    public static function get_students_by_name ($firstname, $lastname) {
        global $DB;
        $systemcontext = \context_system::instance();
        $fncontains = "$firstname%";
        $lncontains = "$lastname%";
        $sql = 'select s.* from {local_gcs_student} s
                  left join {user} u on u.id=s.userid
                 where (u.firstname like :fn and u.lastname like :ln)
                    or (s.legalfirstname like :lfn and s.legallastname like :lln)';
        $sqlvar = ['fn' => $fncontains, 'ln' => $lncontains, 'lfn' => $fncontains, 'lln' => $lncontains];
        $recs = $DB->get_records_sql(
            $sql,
            $sqlvar,
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->isveteran = (bool) $rec->isveteran;
            (bool) $rec->donotemail = (bool) $rec->donotemail;
            if (is_null($rec->regfoxemails)) {
                $rec->regfoxemails = '';
            }
        }
        return $recs;
    }

    /**
     * Retrieves the list of student records without a user account.
     *
     * @return  hash  of student records
     */
    public static function get_students_with_no_user () {
        global $DB;
        global $USER;

        $systemcontext = \context_system::instance();
        if (has_capability('local/gcs:administrator', $systemcontext)) {
            $sql  = "select * from {local_gcs_student} where userid=0";
            $sql .= ' order by legallastname, legalfirstname, legalmiddlename';
            $sqlvar = [];
        } else {
            $sql = 'select * from {local_gcs_student} where userid=:userid';
            $sqlvar = ['userid' => $USER->id];
        }
        $recs = $DB->get_records_sql(
            $sql,
            $sqlvar,
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->isveteran = (bool) $rec->isveteran;
            (bool) $rec->donotemail = (bool) $rec->donotemail;
            if (is_null($rec->regfoxemails)) {
                $rec->regfoxemails = '';
            }
        }
        return $recs;
    }
    /**
     * updates single student record
     *
     * @param object $rec student record
     */
    public static function update_students ($rec) {
        global $DB;
        (int) $rec->isveteran = (int) $rec->isveteran;
        (int) $rec->donotemail = (int) $rec->donotemail;
        if (is_null($rec->regfoxemails)) {
            $rec->regfoxemails = '';
        } else {
            $rfemails = explode(',', $rec->regfoxemails);
            $elist = [];
            foreach ($rfemails as $email) {
                $email = trim($email); // Make sure no leading or trailing white space.
                array_push($elist, $email);
            }
            $rec->regfoxemails = implode(',', $elist);
        }
        $DB->update_record('local_gcs_student', $rec);
        return $rec;
    }
    /**
     * inserts single student record
     *
     * @param object $rec student record (less id)
     */
    public static function insert_students ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        (int) $rec->isveteran = (int) $rec->isveteran;
        (int) $rec->donotemail = (int) $rec->donotemail;
        if (is_null($rec->regfoxemails)) {
            $rec->regfoxemails = '';
        } else {
            $rfemails = explode(',', $rec->regfoxemails);
            $elist = [];
            foreach ($rfemails as $email) {
                $email = trim($email); // Make sure no leading or trailing white space.
                array_push($elist, $email);
            }
            $rec->regfoxemails = implode(',', $elist);
        }
        $id = $DB->insert_record('local_gcs_student', $rec);
        $rec = self::get_students($id);
        return $rec;
    }
    /**
     * deletes single student record
     *
     * @param int $id category record id
     */
    public static function delete_students ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_student', 'id=:id', ['id' => $id]);
        return;
    }

    /**
     * Retrieves the list of enrollment agreement records.
     *
     * @return  hash  of enrollment agreement records
     */
    public static function get_enrollment_agreements_all () {
        global $DB;

        $sql = 'select * from {local_gcs_enroll_agreements} order by adddate desc';
        $recs = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * Retrieves single enrollment agreement record
     *
     * @param char $id enrollment agreement record record id
     * @return  object  enrollment agreement record
     */
    public static function get_enrollment_agreements ($id) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_enroll_agreements',
                [ 'id' => $id ]
            );
        return $rec;
    }
    /**
     * updates single enrollment agreement record
     *
     * @param object $rec enrollment agreement record
     */
    public static function update_enrollment_agreements ($rec) {
        global $DB;
        $DB->update_record('local_gcs_enroll_agreements', $rec);
        return $rec;
    }
    /**
     * inserts single enrollment agreement record
     *
     * @param object $rec enrollment agreement record (less id)
     */
    public static function insert_enrollment_agreements ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_enroll_agreements', $rec);
        $rec = self::get_enrollment_agreements($id);
        return $rec;
    }
    /**
     * deletes single enrollment agreement record
     *
     * @param int $id enrollment agreement record id
     */
    public static function delete_enrollment_agreements ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_enroll_agreements', 'id=:id', ['id' => $id]);
        return;
    }
    /**
     * Format enrollment agreement info for the specified class taken record id.
     *
     * @return  enrollment agreement formatted
     */
    public static function format_classes_taken_agreement_info ($ctid) {
        // Read the class taken record.
        $ctrec = self::get_classes_taken ($ctid);

        // Read class def.
        $clrec = self::get_class_by_code_and_term($ctrec->coursecode, $ctrec->termyear, $ctrec->termcode);
        $eatext = self::format_enrollment_agreement_text ($ctrec);

        return [
            'agreementid'   => $ctrec->agreementid,
            'headertext'    => $clrec->coursecode . ' - ' . $clrec->shorttitle,
            'agreementtext' => $eatext,
        ];
    }
    /**
     * get the enrollment agreement record for a class taken rec
     *
     * @param $ctrec class taken record
     */
    public static function get_class_enrollment_agreement_rec ($ctrec) {
        $earec = null;
        global $DB;

        // Lookup the assigned enrollment agreement text.
        if ($ctrec->agreementid > 0) {
            // Lookup specific signed enrollment agreement.
            $earec = self::get_enrollment_agreements ($ctrec->agreementid);
        } else {
            // Lookup the latest ea for our class type.
            // They can be defined specific to a course or more generally for the credit type.
            // E.g. for credit agreements differ from audits.
            $sql = "
select ea.*
from {local_gcs_enroll_agreements} ea
where ea.credittype=:credittypecode
and ea.adddate<=:registrationdate
order by adddate desc
limit 1;
";
            // Lookup latest ea based on specific coursecode.
            $sqlparam['credittypecode'] = $ctrec->coursecode;
            $sqlparam['registrationdate'] = $ctrec->registrationdate;

            $earecs = $DB->get_records_sql(
                $sql,
                $sqlparam,
                $limitfrom = 0,
                $limitnum = 0
            );

            // If specific coursecode ea not found, try by credittypecode.
            if (count($earecs) == 0) {
                // Lookup latest based on credit type.
                $sqlparam['credittypecode'] = $ctrec->credittypecode;

                $earecs = $DB->get_records_sql(
                    $sql,
                    $sqlparam,
                    $limitfrom = 0,
                    $limitnum = 0
                );
            }

            // Extract id and text (should find one unless no ea was defined before this course was offered).
            if (count($earecs) > 0) {
                foreach ($earecs as $ea) {
                    $earec = $ea;
                }
            }
        }

        return $earec;
    }
    /**
     * Format raw enrollment agreement info
     *
     * @return  enrollment agreement formatted
     */
    public static function format_enrollment_agreement_info ($eaid) {
        // Read the class taken record.
        $earec = self::get_enrollment_agreements ($eaid);

        return [
            'agreementid'   => $earec->id,
            'headertext'    => '',
            'agreementtext' => earec->agreement,
        ];
    }
    /**
     * format enrollment agreement text
     *
     * @param $ctrec class taken record
     * all needed settrlite info is looked up in the function.
     */
    private static function format_enrollment_agreement_text ($ctrec) {
        $eatext = '';
        global $DB;

        // Lookup the assigned enrollment agreement text.
        $earec = self::get_class_enrollment_agreement_rec ($ctrec);
        if ($earec != null) {
            $eatext = $earec->agreement;
            $ctrec->agreementid = $earec->id;

            // Lookup student record.
            $strec = self::get_students ($ctrec->studentid);

            // Lookup corresponding class record.
            $clrec = self::get_class_by_code_and_term($ctrec->coursecode, $ctrec->termyear, $ctrec->termcode);

            $eatext = str_replace("[STUDENT NAME]", trim($strec->legalfirstname . " " .
                $strec->legalmiddlename) . " " . $strec->legallastname, $eatext);
            $eatext = str_replace("[STUDENT NUMBER]", $strec->id, $eatext);
            $eatext = str_replace("[STUDENT ADDRESS]", self::format_address ($strec), $eatext);
            $eatext = str_replace("[COURSE NUMBER]", $clrec->coursecode, $eatext);
            $eatext = str_replace("[COURSE TITLE]", $clrec->title, $eatext);
            $eatext = str_replace("[REQUIRED TEXTBOOKS]", $clrec->requiredtextbooks, $eatext);
            $eatext = str_replace("[COURSE CREDITS]", $clrec->coursehours, $eatext);
            $eatext = str_replace("[COURSE LESSONS]", $clrec->lectures, $eatext);
            $eatext = str_replace("[COURSE DESCRIPTION]", $clrec->description, $eatext);
            $eatext = str_replace("[STUDENT PAID]", number_format($ctrec->studentpaid, 2), $eatext);
            // Note we use tuitionpaid (original tuition before any refund) not classtuition.
            $eatext = str_replace("[COURSE FEE @ 90%]", number_format($ctrec->tuitionpaid * .9, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 80%]", number_format($ctrec->tuitionpaid * .8, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 70%]", number_format($ctrec->tuitionpaid * .7, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 60%]", number_format($ctrec->tuitionpaid * .6, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 50%]", number_format($ctrec->tuitionpaid * .5, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 40%]", number_format($ctrec->tuitionpaid * .4, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 30%]", number_format($ctrec->tuitionpaid * .3, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 20%]", number_format($ctrec->tuitionpaid * .2, 2), $eatext);
            $eatext = str_replace("[COURSE FEE @ 10%]", number_format($ctrec->tuitionpaid * .1, 2), $eatext);
            $eatext = str_replace("[COURSE FEE]", number_format($ctrec->tuitionpaid), $eatext);

            // Condition the entire scholarship line on if there is one.
            // This looks nicer than showing empty scholarships for many students.
            if ($ctrec->scholarshippedamount > 0) {
                $eatext = str_replace("[SCHOLARSHIP LINE]", 'SCHOLARSHIP:  $' .
                    number_format($ctrec->scholarshippedamount, 2) . PHP_EOL, $eatext);
            } else {
                $eatext = str_replace("[SCHOLARSHIP LINE]", '', $eatext);
            }
        }

        return $eatext;
    }
    /**
     * Format the student address block text
     *
     * @param $strec supplied student record
     */
    private static function format_address ($strec) {
        $addrblk = '';
        if (strlen($strec->address) > 0) {
            $addrblk = $strec->address;
        }
        if (strlen($strec->address2) > 0) {
            if (strlen($addrblk) > 0) {
                $addrblk .= PHP_EOL;
            }
            $addrblk .= $strec->address2;
        }
        if (strlen($strec->city) > 0) {
            if (strlen($addrblk) > 0) {
                $addrblk .= PHP_EOL;
            }
            $addrblk .= $strec->city;
        }
        if (!empty($strec->stateprovince)) {
            $addrblk .= ' ' . $strec->stateprovince;
        }
        if (strlen($strec->zip) > 0) {
            $addrblk .= ' ' . $strec->zip;
        }
        if (strlen($strec->country) > 0) {
            if (strlen($addrblk) > 0) {
                $addrblk .= PHP_EOL;
            }
            $addrblk .= $strec->country;
        }
        return $addrblk;
    }

    /**
     * Retrieves the list of scholarships available records.
     *
     * @return  hash  of scholarships available records
     */
    public static function get_sch_available_all () {
        global $DB;

        $sql = 'select * from {local_gcs_sch_available} order by scholarshipcode';
        $recs = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * Retrieves single scholarships available record
     *
     * @param char $id scholarships available record id
     * @return  object  scholarships available record
     */
    public static function get_sch_available_by_id ($id) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_sch_available',
                [ 'id' => $id ]
            );
        return $rec;
    }
    /**
     * Retrieves single scholarships available record
     *
     * @param char $scholarshipcode scholarships available record id
     * @return  object  scholarships available record
     */
    public static function get_sch_available_by_code ($scholarshipcode) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_sch_available',
                [ 'scholarshipcode' => $scholarshipcode ]
            );
        return $rec;
    }
    /**
     * updates single scholarships available record
     *
     * @param object $rec scholarships available record
     */
    public static function update_sch_available ($rec) {
        global $DB;
        $DB->update_record('local_gcs_sch_available', $rec);
        return $rec;
    }
    /**
     * inserts single scholarships available record
     *
     * @param object $rec scholarships available record (less id)
     */
    public static function insert_sch_available ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_sch_available', $rec);
        return self::get_sch_available_by_id($id);
    }
    /**
     * deletes single scholarships available record
     *
     * @param int $id scholarships available record id
     */
    public static function delete_sch_available ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_sch_available', 'id=:id', ['id' => $id]);
        return;
    }

    /**
     * Retrieves the list of scholarships given records.
     *
     * @param   string   $stuid student id
     * @return  hash  of scholarships given records
     */
    public static function get_sch_given_all ($stuid) {
        global $DB;

        if ( empty($stuid) ) {
            $sql = "
SELECT sg.*
FROM {local_gcs_student} st
JOIN {local_gcs_sch_given} sg on sg.studentid = st.id
WHERE st.statuscode='ACT'
";
            $sqlparam = [];
        } else {
            $sql = 'select * from {local_gcs_sch_given} where studentid=:studentid';
            $sqlparam['studentid'] = $stuid;
        }

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * Retrieves single scholarships given record
     *
     * @param char $id scholarships given record id
     * @return  object  scholarships given record
     */
    public static function get_sch_given ($id) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_sch_given',
                [ 'id' => $id ]
            );
        return $rec;
    }
    /**
     * Retrieves single scholarships given record
     *
     * @param char $id scholarships given record id
     * @return  object  scholarships given record
     */
    public static function get_sch_given_logical ($stuid, $termyear) {
        global $DB;
        $systemcontext = \context_system::instance();
        // Non-Administrators only allowed to read their record.
        if (!has_capability('local/gcs:administrator', $systemcontext) && !self::is_student_logged_in($stuid)) {
            return [];
        }

        $sql = "
            select s.*
            from {local_gcs_sch_given} s
            where s.studentid=:studentid
            and s.termyear=:termyear";
        $sqlparam['studentid'] = $stuid;
        $sqlparam['termyear'] = $termyear;

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 1
        );
        return $recs;
    }
    /**
     * updates single scholarships given record
     * when record was updated with a decision, send notification to the student
     *
     * @param object $rec scholarships given record
     */
    public static function update_sch_given ($rec) {
        global $DB;
        $DB->update_record('local_gcs_sch_given', $rec);

        // Notify internally of scholarship application (when appropriate).
        if (!self::schoolnotify_scholarship_application($rec)) {
            // If not needed, notify student of review decision (when appropriate).
            if (self::studentnotify_scholarship_approval($rec)) {
                // If sent, mark as notified.
                $rec->studentnotified = time();
                $DB->update_record('local_gcs_sch_given', $rec);
            }
        }
        return $rec;
    }
    /**
     * inserts single scholarships given record
     *
     * @param object $rec scholarships given record (less id)
     */
    public static function insert_sch_given ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_sch_given', $rec);
        if ($id > 0) {
            self::schoolnotify_scholarship_application($rec); // Notify internally of scholarship application.
        }
        $rec = self::get_sch_given($id);
        return $rec;
    }
    /**
     * notify us of student scholarship application
     *
     * @param $schgivenrec of scholarships given record
     */
    public static function schoolnotify_scholarship_application ($schgivenrec) {
        if (empty($schgivenrec->decision) && empty($schgivenrec->reviewdate) &&
            empty($schgivenrec->studentnotified) && $schgivenrec->cadinfoauth == 1) {
            // Get message template.
            $msg = file_get_contents('/home/gcswww/dev.gcs.edu/local/gcs/templates/email_scholarship_application_review.html');
            if (!empty($msg) && isset($schgivenrec)) {
                // Lookup additional info.
                $sturec = array_pop(self::get_student_by_id($schgivenrec->studentid));
                $pgmrec = self::get_program_by_programcode($schgivenrec->programcode);
                $schavailrec = self::get_sch_available_by_code($schgivenrec->category);
                $userrec = \core_user::get_user($sturec->userid);
                // Variable replacement.
                $settings = new \local_gcs\settings();
                $msg = str_replace("{logourl}", (string) $settings->logourl, $msg);
                $msg = str_replace("{termyear}", $schgivenrec->termyear, $msg);
                $msg = str_replace("{fullname}", trim($sturec->legalfirstname . " " .
                    $sturec->legalmiddlename) . " " . $sturec->legallastname, $msg);
                $msg = str_replace("{studentid}", $sturec->id, $msg);
                $msg = str_replace("{stuemail}", $userrec->email, $msg);
                $msg = str_replace("{scholarshiptext}", self::decode_html($schavailrec->scholarshiptext), $msg);
                $msg = str_replace("{programdesc}", $pgmrec->description, $msg);
                $msg = str_replace("{statusconfirm}", self::decode_html($schavailrec->statusconfirm), $msg);

                // Send to internal recipients.
                utils::send_notification_email('GCS Scholarship Application Review', $msg);
                return true;
            }
        }
        return false;
    }
    /**
     * notify student of scholarship application review decision
     *
     * @param $rec of scholarships given record
     */
    public static function studentnotify_scholarship_approval ($schgivenrec) {
        // Only send when all filled out.
        if (!empty($schgivenrec->reviewdate) && empty($schgivenrec->studentnotified) &&
            (strtolower($schgivenrec->decision) == 'approved' || strtolower($schgivenrec->decision) == 'denied')) {
            // Get message template.
            $tmplt = '/home/gcswww/dev.gcs.edu/local/gcs/templates/email_scholarship_application_approval_notification.html';
            $msg = file_get_contents($tmplt);

            // Lookup student.
            $stuarr = self::get_student_by_id($schgivenrec->studentid);
            if (!empty($msg) && isset($stuarr) && count($stuarr) === 1) {
                $sturec = array_pop($stuarr);

                // Lookup student email.
                $userrec = \core_user::get_user($sturec->userid);
                if (isset($userrec) && !empty($userrec->email)) {
                    $stuemail = $userrec->email;

                    // Salutation.
                    if (!empty($sturec->preferredfirstname)) {
                        $salutation = 'Dear ' . $sturec->preferredfirstname;
                    } else if (!empty($sturec->legalfirstname)) {
                        $salutation = 'Dear ' . $sturec->legalfirstname;
                    } else {
                        $salutation = 'GCS Scholarship Applicant';
                    }

                    // Get scholarship def.
                    $schavailrec = self::get_sch_available_by_code($schgivenrec->category);
                    $scholarshipdesc = $schavailrec->description;

                    // Format approval message.
                    $decision = strtolower($schgivenrec->decision);
                    if ($decision == 'approved') {
                        $scholarshiptext = self::decode_html($schavailrec->scholarshiptext);
                    } else if ($decision == 'denied') {
                        $scholarshiptext = '';
                    }

                    // Variable replacement.
                    $settings = new \local_gcs\settings();

                    $msg = str_replace("{logourl}", (string) $settings->logourl, $msg);
                    $msg = str_replace("{salutation}", $salutation, $msg);
                    $msg = str_replace("{decision}", $decision, $msg);
                    $msg = str_replace("{scholarshipdesc}", $scholarshipdesc, $msg);
                    $msg = str_replace("{scholarshiptext}", $scholarshiptext, $msg);
                    $msg = str_replace("{comments}", self::decode_html($schgivenrec->comments), $msg);

                    // Send it to the student.
                    utils::send_email($stuemail, 'GCS Scholarship Application Review', $msg);
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * html decode kludge until real decoding is set up
     *
     * @param object $rec scholarships given record (less id)
     */
    private static function decode_html ($html) {
        $html = str_replace("[^", '<', $html);
        $html = str_replace("^]", '>', $html);
        return $html;
    }
    /**
     * deletes single scholarships given record
     *
     * @param int $id scholarships given record id
     */
    public static function delete_sch_given ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_sch_given', 'id=:id', ['id' => $id]);
        return;
    }
    /**
     * Retrieves the list of classes taken records.
     *
     * @param   int   $stuid student id
     * @return  hash  of classes taken records
     */
    public static function get_classes_taken_all ($stuid) {
        global $DB;
        $sqlparam = [];

        $systemcontext = \context_system::instance();
        if ( $stuid != 0 ) {
            // Non-Administrators only allowed to read their record.
            if (!has_capability('local/gcs:administrator', $systemcontext) && !self::is_student_logged_in($stuid)) {
                return [];
            }
            $sql = 'select * from {local_gcs_classes_taken} where studentid=:studentid';
            $sqlparam['studentid'] = $stuid;
        } else {
            // Non-Administrators only allowed to read their record.
            if (!has_capability('local/gcs:administrator', $systemcontext)) {
                return [];
            }
            $sql = 'select * from {local_gcs_classes_taken}';
        }

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * Retrieves the list of unsigned enrollment agreement records for a student (formatted).
     *
     * @return  enrollment agreement records
     */
    public static function get_classes_taken_unsigned ($stuid) {
        $ctearecs = []; // To collect the unsigned enrollment agreement classes taken.
        global $DB;

        // Get current term record.
        $termreclist = self::get_term_date_current();
        foreach ($termreclist as $termrec) {
            $ctrecs = self::get_classes_taken_all ($stuid);
            foreach ($ctrecs as $ctrec) {
                if (($ctrec->agreementsigned == null || $ctrec->agreementsigned == 0)
                    && $ctrec->termyear == $termrec->termyear && $ctrec->termcode == $termrec->termcode) {
                    $ctrec->comments = self::format_enrollment_agreement_text ($ctrec); // Tack on the formatted ea text.

                    // Read class def.
                    $clrec = self::get_class_by_code_and_term($ctrec->coursecode, $ctrec->termyear, $ctrec->termcode);
                    $ctrec->shorttitleoverride = $clrec->shorttitle;
                    $ctearecs[] = $ctrec;
                }
            }
        }

        return $ctearecs;
    }
    /**
     * Retrieves the list of classes taken records.
     *
     * @param   int   $stuid student id
     * @return  hash  of classes taken records
     */
    public static function get_classes_taken_by_stu_year ($stuid, $termyear) {
        global $DB;
        $sql = "
            select ct.*
            from {local_gcs_classes_taken} ct
            join {local_gcs_student} st on st.id=ct.studentid
            join {local_gcs_courses_permitted} cp on cp.coursecode=ct.coursecode and cp.programcode=st.programcode
            where ct.studentid=:studentid
            and ct.termyear=:termyear
            order by ct.termcode, ct.coursecode";
        $sqlparam['studentid'] = $stuid;
        $sqlparam['termyear'] = $termyear;

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * Retrieves single classes taken record
     *
     * @param char $id classes taken record id
     * @return  object  classes taken record
     */
    public static function get_classes_taken ($id) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_classes_taken',
                [ 'id' => $id ]
            );
        return $rec;
    }
    /**
     * updates single classes taken record
     *
     * @param object $rec classes taken record
     */
    public static function update_classes_taken ($rec) {
        global $DB;
        global $USER;

        $rec->changeddate = time();
        $rec->changedbyuserid = $USER->id;
        $DB->update_record('local_gcs_classes_taken', $rec);
        return $rec;
    }
    /**
     * inserts single classes taken record
     *
     * @param object $rec classes taken record (less id)
     */
    public static function insert_classes_taken ($rec) {
        global $DB;
        global $USER;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.

        // Assign the enrollment agreement.
        $earec = self::get_class_enrollment_agreement_rec ($rec);
        if ($earec != null) {
            $rec->agreementid = $earec->id;
        }

        $rec->changeddate = time();
        $rec->changedbyuserid = $USER->id;
        $id = $DB->insert_record('local_gcs_classes_taken', $rec);
        $rec = self::get_classes_taken($id);
        return $rec;
    }
    /**
     * deletes single classes taken record
     *
     * @param int $id classes taken record id
     */
    public static function delete_classes_taken ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_classes_taken', 'id=:id', ['id' => $id]);
        return;
    }

    /**
     * Retrieves the list of field def records.
     *
     * @param   (none)
     * @return  hash  of field def records
     */
    public static function get_field_def_by_tableid ($tableid) {
        global $DB;
        $sql = "
            select *
            from {local_gcs_table_field_def} d
            order by d.tableid, d.id";
        $sqlparam['tableid'] = $tableid;

        $recs = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * Retrieves the list of field def records for a given tableid.
     *
     * @param   string   $tableid
     * @return  hash  of field def records
    public static function get_field_def_by_tableid ($tableid) {
        global $DB;
        $sql = "
            select *
            from {local_gcs_table_field_def} d
            where d.tableid=:tableid
            order by d.id";
        $sqlparam['tableid'] = $tableid;

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
     */
    /**
     * Retrieves single field def record
     *
     * @param int $id
     * @return  object
     */
    public static function get_field_def ($id) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_table_field_def',
                [ 'id' => $id ]
            );
        return $rec;
    }
    /**
     * updates single field def record
     *
     * @param object $rec field def record
     */
    public static function update_field_def ($rec) {
        global $DB;
        $DB->update_record('local_gcs_table_field_def', $rec);
        return $rec;
    }
    /**
     * inserts single field def record
     *
     * @param object $rec field def record (less id)
     */
    public static function insert_field_def ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.

        $id = $DB->insert_record('local_gcs_table_field_def', $rec);
        $rec = self::get_field_def($id);
        return $rec;
    }
    /**
     * deletes single field def record
     *
     * @param int $id field def record id
     */
    public static function delete_field_def ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_table_field_def', 'id=:id', ['id' => $id]);
        return;
    }

    /**
     * Retrieves the list of program completion records.
     *
     * @return  hash  of program completion records
     */
    public static function get_program_completion_all () {
        global $DB;

        $sql = 'select * from {local_gcs_program_completion} order by completiondate desc';
        $recs = $DB->get_records_sql(
            $sql,
            [],
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->basisofadmission = (bool) $rec->basisofadmission;
        }
        return $recs;
    }
    /**
     * Retrieves program completion records for a student
     *
     * @param int $studentid student id
     * @return  hash  of program completion records
     */
    public static function get_program_completion_by_student ($studentid) {
        global $DB;
        $sql = 'select * from {local_gcs_program_completion} where studentid=:studentid order by completiondate desc';
        $recs = $DB->get_records_sql(
            $sql,
            [ 'studentid' => $studentid ],
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->basisofadmission = (bool) $rec->basisofadmission;
        }
        return $recs;
    }
    /**
     * Retrieves program completion records for a program
     *
     * @param string $programcode programcode id
     * @return  hash  of program completion records
     */
    public static function get_program_completion_by_program ($programcode) {
        global $DB;
        $sql = 'select * from {local_gcs_program_completion} where programcode=:programcode order by completiondate desc';
        $recs = $DB->get_records_sql(
            $sql,
            [ 'programcode' => $programcode ],
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->basisofadmission = (bool) $rec->basisofadmission;
        }
        return $recs;
    }
    /**
     * Retrieves single program completion record
     *
     * @param int $id program completion record id
     * @return  object  program completion record
     */
    public static function get_program_completion ($id) {
        global $DB;
        $rec = $DB->get_record(
                'local_gcs_program_completion',
                [ 'id' => $id ]
            );
        (bool) $rec->basisofadmission = (bool) $rec->basisofadmission;
        return $rec;
    }
    /**
     * updates single program completion record
     *
     * @param object $rec program completion record
     */
    public static function update_program_completion ($rec) {
        global $DB;
        (int) $rec->basisofadmission = (int) $rec->basisofadmission;
        $DB->update_record('local_gcs_program_completion', $rec);
        return $rec;
    }
    /**
     * inserts single program completion record
     *
     * @param object $rec program completion record (less id)
     */
    public static function insert_program_completion ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        (int) $rec->basisofadmission = (int) $rec->basisofadmission;
        $id = $DB->insert_record('local_gcs_program_completion', $rec);
        $rec = self::get_program_completion($id);
        return $rec;
    }
    /**
     * deletes single program completion record
     *
     * @param int $id program completion record id
     */
    public static function delete_program_completion ($id) {
        global $DB;

        $DB->delete_records_select('local_gcs_program_completion', 'id=:id', ['id' => $id]);
        return;
    }
    /**
     * Retrieves student enrollments for program completion report
     *
     * @param int $studentid student id
     * @return  hash  of program completion records
     */
    public static function get_student_enrollments ($studentid) {
        global $DB;
        $systemcontext = \context_system::instance();
        if (has_capability('local/gcs:administrator', $systemcontext)) {
            // For administrators, we get data for anyone.
            $stid = $studentid;
        } else {
            // For others, we get only the data for the logged-in user.
            $srec = self::get_student_logged_in();
            $stid = $srec->id;
        }
        $sql = "WITH stuenr AS (
                    SELECT p.programcode, p.description,
                           p.academicdegree,
                           FROM_UNIXTIME(s.acceptancedate,'%Y-%m-%d') AS enrolldate,
                           '(enrolled)' AS completiondate,
                           s.id AS studentid,
                           CAST(0 AS INTEGER) AS completed,
                           '' AS notes,
                           CAST(1 AS INTEGER) AS iscurrent
                      FROM {local_gcs_student} s
                      JOIN {local_gcs_program} p ON p.programcode=s.programcode

                     UNION

                    SELECT p.programcode, p.description,
                           p.academicdegree,
                           FROM_UNIXTIME(enrolldate,'%Y-%m-%d') AS enrolldate,
                           FROM_UNIXTIME(completiondate,'%Y-%m-%d') AS completiondate,
                           pc.studentid,
                           CAST(1 AS INTEGER) AS completed,
                           notes,
                           CAST(CASE WHEN s.programcode=p.programcode THEN 1 ELSE 0 END AS INTEGER) AS iscurrent
                      FROM {local_gcs_program_completion} pc
                      JOIN {local_gcs_program} p ON p.programcode=pc.programcode
                 LEFT JOIN {local_gcs_student} s on s.id=pc.studentid
                )
                SELECT * FROM stuenr WHERE studentid=:studentid";
        $recs = $DB->get_records_sql(
            $sql,
            [ 'studentid' => $stid ],
            $limitfrom = 0,
            $limitnum = 0
        );
        foreach ($recs as $rec) {
            (bool) $rec->academicdegree = (bool) $rec->academicdegree;
            (bool) $rec->completed = (bool) $rec->completed;
            (bool) $rec->iscurrent = (bool) $rec->iscurrent;
        }
        return $recs;
    }

    /**
     * Retrieves student courses completed for program completion report
     *
     * @param int $studentid student id
     * @param char $programcode program code
     * @return  hash  of program course completion records
     */
    public static function get_student_course_completion ($studentid, $programcode) {
        global $DB;

        $systemcontext = \context_system::instance();
        if (has_capability('local/gcs:administrator', $systemcontext)) {
            // For administrators, we get data for anyone.
            $stid = $studentid;
        } else {
            // For others, we get only the data for the logged-in user.
            $srec = self::get_student_logged_in();
            $stid = $srec->id;
        }

        // First we get the records.
        $sql = "WITH preq AS (
                    SELECT req.*
                      FROM {local_gcs_program_req} req
                     WHERE req.programcode=:programcode1
                ), cperm AS (
                    SELECT cp.* FROM {local_gcs_courses_permitted} cp
                      JOIN preq on preq.programcode=cp.programcode and preq.categorycode=cp.categorycode
                ), clstkn AS (
                    SELECT
                        ct.id as clsid,
                        CASE WHEN LENGTH(ct.assignedcoursecode)=2 THEN ct.assignedcoursecode
                             WHEN cp.categorycode IS NOT NULL THEN cp.categorycode
                             ELSE 'XX'
                              END AS classcategorycode,
                        CASE WHEN LENGTH(ct.assignedcoursecode)<3 THEN ct.coursecode ELSE ct.assignedcoursecode END AS crscode,
                        crs.shorttitle,
                        crs.title,
                        ct.shorttitleoverride AS transfershorttitle,
                        ct.titleoverride AS transfertitle,
                        ct.credittypecode,
                        ct.gradecode,
                        pf.description AS passfail,
                        ct.completiondate,
                        CASE WHEN ct.coursehoursoverride > 0 THEN ct.coursehoursoverride ELSE cls.coursehours END AS coursehours,
                        ct.termyear,
                        ct.termcode, ct.elective,
                        COALESCE(cp.electiveeligible, 0) AS electiveeligible,
                        CAST(CASE WHEN cp.coursecode IS NOT NULL THEN 1 ELSE 0 END AS integer) AS coursepermitted,
                        CASE WHEN COALESCE(ct.completiondate,0) = 0 THEN 0 ELSE 1 END as completed
                      FROM {local_gcs_classes_taken} ct
                 LEFT JOIN {local_gcs_classes} cls ON cls.coursecode=ct.coursecode
                                                    AND cls.termyear=ct.termyear
                                                    AND cls.termcode=ct.termcode
                 LEFT JOIN {local_gcs_courses} crs
                     ON crs.coursecode=CASE WHEN LENGTH(ct.assignedcoursecode)<3 THEN ct.coursecode ELSE ct.assignedcoursecode END
                 LEFT JOIN {local_gcs_codes} pf ON pf.codeset='pass_fail' AND pf.code=ct.gradecode
                 LEFT JOIN cperm cp ON cp.coursecode=ct.assignedcoursecode OR cp.coursecode=ct.coursecode
                     WHERE studentid=:studentid and coalesce(pf.description,'PAS') in('PAS','AUD','IP')
                ), classlist AS (
                    SELECT DISTINCT
                        CONCAT(COALESCE(preq.id,'XX'),'-',COALESCE(c.clsid, ' ')) AS recid,
                        p.programcode,p.description as programname,
                        COALESCE(preq.reportseq, 0) AS reportseq,
                        COALESCE(preq.categorycode, 0) AS categorycode,
                        COALESCE(cc.description, ' ') AS categoryname,
                        COALESCE(c.clsid, 0) AS id,
                        COALESCE(c.classcategorycode, ' ') AS classcategorycode,
                        COALESCE(c.crscode, '') AS coursecode,
                        COALESCE(c.shorttitle, '') AS shorttitle,
                        COALESCE(c.title, '') AS title,
                        COALESCE(c.transfershorttitle, '') AS transfershorttitle,
                        COALESCE(c.transfertitle, '') AS transfertitle,
                        COALESCE(c.credittypecode, '') AS credittypecode,
                        COALESCE(c.gradecode, '') AS gradecode,
                        COALESCE(c.passfail, '') AS passfail,
                        COALESCE(c.coursehours, 0) AS coursehours,
                        COALESCE(c.termyear, 0) AS termyear,
                        COALESCE(c.termcode, '0') AS termcode,
                        COALESCE(c.completiondate, 0) AS completiondate,
                        COALESCE(c.elective, 0) AS elective,
                        COALESCE(c.electiveeligible, 0) AS electiveeligible,
                        CONCAT(t.description, ' ', c.termyear)  AS termname,
                        COALESCE(c.coursepermitted, 0) AS coursepermitted,
                        COALESCE(preq.coursesrequired, 0) as coursesrequired, c.completed
                      FROM clstkn c
                 LEFT JOIN preq ON c.classcategorycode=preq.categorycode
                 LEFT JOIN {local_gcs_codes} t ON t.codeset='TERM' AND t.code=c.termcode
                 LEFT JOIN {local_gcs_codes} cc on cc.codeset='CATEGORY' and cc.code=preq.categorycode
                 LEFT JOIN {local_gcs_program} p ON p.programcode=:programcode2

                 UNION

                    SELECT DISTINCT
                        CONCAT(preq.id,'-',COALESCE(c.clsid, ' ')) AS recid,
                        p.programcode,p.description as programname,
                        COALESCE(preq.reportseq, 0) AS reportseq,
                        COALESCE(preq.categorycode, 0) AS categorycode,
                        COALESCE(cc.description, ' ') AS categoryname,
                        COALESCE(c.clsid, 0) AS id,
                        COALESCE(c.classcategorycode, ' ') AS classcategorycode,
                        COALESCE(c.crscode, '') AS coursecode,
                        COALESCE(c.shorttitle, '') AS shorttitle,
                        COALESCE(c.title, '') AS title,
                        COALESCE(c.transfershorttitle, '') AS transfershorttitle,
                        COALESCE(c.transfertitle, '') AS transfertitle,
                        COALESCE(c.credittypecode, '') AS credittypecode,
                        COALESCE(c.gradecode, '') AS gradecode,
                        COALESCE(c.passfail, '') AS passfail,
                        COALESCE(c.coursehours, 0) AS coursehours,
                        COALESCE(c.termyear, 0) AS termyear,
                        COALESCE(c.termcode, '0') AS termcode,
                        COALESCE(c.completiondate, 0) AS completiondate,
                        COALESCE(c.elective, 0) AS elective,
                        COALESCE(c.electiveeligible, 0) AS electiveeligible,
                        CONCAT(t.description, ' ', c.termyear)  AS termname,
                        COALESCE(c.coursepermitted, 0) AS coursepermitted,
                        COALESCE(preq.coursesrequired, 0) as coursesrequired,
                        COALESCE(c.completed, 0) as completed
                      FROM preq
                 LEFT JOIN clstkn c ON c.classcategorycode=preq.categorycode
                 LEFT JOIN {local_gcs_codes} t ON t.codeset='TERM' AND t.code=c.termcode
                 LEFT JOIN {local_gcs_codes} cc on cc.codeset='CATEGORY' and cc.code=preq.categorycode
                 LEFT JOIN {local_gcs_program} p ON p.programcode=:programcode3
                 where c.clsid is null
                )
                SELECT recid, programcode, programname, reportseq, categorycode, categoryname,
                       id, classcategorycode, coursecode, shorttitle, title, transfershorttitle,
                       transfertitle, credittypecode, gradecode, passfail, coursehours,
                       termyear, termcode, completiondate, elective, electiveeligible, termname,
                       coursepermitted, coursesrequired
                  FROM classlist cl
              ORDER BY reportseq, categorycode, electiveeligible, completed desc, termyear, termcode, coursecode";
        $dbrecs = $DB->get_records_sql(
            $sql,
            [
                'studentid' => $stid,
                'programcode1' => $programcode,
                'programcode2' => $programcode,
                'programcode3' => $programcode,
            ],
            $limitfrom = 0,
            $limitnum = 0
        );
        $recs = [];
        foreach ($dbrecs as $dbrec) {
            $recs[] = $dbrec;
        }
        // Then we place the records into a grid of slots for how many courses of each category are needed.
        $xrecs = []; // Extra courses.
        $precs = []; // Courses for this program.
        $arecs = []; // Audited courses.
        $cat = '';   // Current Category.
        $nreq = 0;   // Number of courses required for the current category.
        $ntkn = 0;   // Number of courses taken in the current category.
        $lastrec = [];
        foreach ($recs as $key => $rec) {
            // Reset variables for the new category.
            if ($cat != $rec->categorycode) {
                $nreq = $rec->coursesrequired;
                $ntkn = 0;
                $cat = $rec->categorycode;
            }
            // Handle booleans.
            (bool) $rec->elective = (bool) $rec->elective;
            (bool) $rec->electiveeligible = (bool) $rec->electiveeligible;
            (bool) $rec->coursepermitted = (bool) $rec->coursepermitted;

            // Add courses to correct slot.
            if (($rec->coursecode != '') && ($rec->passfail != 'PAS') && ($rec->passfail != 'IP')) {
                $rec->categorycode = 'AA';
                $rec->categoryname = 'Non-Credit Courses';
                $arecs[] = $rec;
                continue;
            }

            // Add courses to correct slot.
            if (($rec->coursepermitted) && ($ntkn < $nreq)) {
                // Add course completed to records to return until we have the number required.
                $precs[] = $rec;
                $ntkn++;
            } else {
                // Above that, add them to extra records.
                if ($rec->coursecode != '') {
                    $xrecs[] = $rec;
                }
            }

            // End of category.
            if ( $key >= (count($recs) - 1) ) {
                $endofcat = true;
            } else if ($recs[$key + 1]->categorycode != $cat) {
                $endofcat = true;
            } else {
                $endofcat = false;
            }
            if ($endofcat) {
                if ($cat == 'EL') {
                    // For electives, we pull in any extra courses that qualify for these slots.
                    if ($ntkn < $nreq) {
                        foreach ($xrecs as $xk => $xrec) {
                            if (($xrec->coursepermitted) && ($xrec->electiveeligible)) {
                                $xrec->categorycode = 'EL';
                                $xrec->categoryname = 'Elective';
                                $precs[] = $xrec;
                                $ntkn++;
                                unset($xrecs[$xk]);
                                if ($ntkn >= $nreq) {
                                    break;
                                }
                            }
                        }
                    }
                }
                // Fill remaining required slots with blank records.
                while ($ntkn < $nreq) {
                    $precs[] = [
                        'programcode'       => $rec->programcode,
                        'programname'       => $rec->programname,
                        'reportseq'         => $rec->reportseq,
                        'categorycode'      => $rec->categorycode,
                        'categoryname'      => $rec->categoryname,
                        'classcategorycode' => $rec->classcategorycode,
                        'coursecode'        => '',
                        'shorttitle'        => '',
                        'title'             => '',
                        'transfershorttitle' => '',
                        'transfertitle'     => '',
                        'credittypecode'    => '',
                        'gradecode'         => '',
                        'passfail'          => '',
                        'coursehours'       => 0,
                        'termyear'          => 0,
                        'termcode'          => '',
                        'completiondate'    => 0,
                        'elective'          => false,
                        'electiveeligible'  => false,
                        'termname'          => '',
                        'coursepermitted'   => false,
                        'coursesrequired'   => $rec->coursesrequired,
                    ];
                    $ntkn++;
                }
            }
        }
        // Any remaining extra records, we tack on the end for reporting.
        foreach ($xrecs as $xrec) {
            $xrec->categorycode = 'XX';
            $xrec->categoryname = 'Additional Courses taken towards subsequent GCS programs';
            $precs[] = $xrec;
        }

        // Any audited courses, we tack on the end for reporting.
        foreach ($arecs as $xrec) {
            $precs[] = $xrec;
        }
        return $precs;
    }
    /**
     * inserts a regfox webhook post record
     *
     * @param object $rec regfox webhook post record
     */
    public static function insert_regfox_webhook ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_regfox_webhook', $rec);
        return $id;
    }
    /**
     * updates a regfox webhook post record
     *
     * @param object $rec regfox webhook post record
     */
    public static function update_regfox_webhook ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->update_record('local_gcs_regfox_webhook', $rec);
        return $id;
    }
    /**
     * Retrieves the list of unprocess RegFox webhooks.
     *
     * @return  hash  of webhook records
     */
    public static function get_regfox_webhooks_unprocessed () {
        global $DB;
        $sqlparam = [];
        $sql = 'select * from {local_gcs_regfox_webhook} where processedtime=0';

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * inserts a regfox webhook transaction record
     *
     * @param object $rec regfox webhook transaction record
     */
    public static function insert_regfox_transaction ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_regfox_transaction', $rec);
        return $id;
    }
    /**
     * updates a regfox webhook transaction record
     *
     * @param object $rec regfox webhook transaction record
     */
    public static function update_regfox_transaction ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->update_record('local_gcs_regfox_transaction', $rec);
        return $id;
    }
    /**
     * Retrieves the list of unprocess RegFox registrants.
     *
     * @return  hash  of webhook registrant records
     */
    public static function get_regfox_registrants_unprocessed () {
        global $DB;
        $sqlparam = [];
        $sql = 'select * from {local_gcs_regfox_registrant} where processedtime=0 or processedtime is null';

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * Retrieves a RegFox registrant record.
     *
     * @param  int $id regfox registrant id
     * @return hash  of RegFox registrant records
     */
    public static function get_regfox_registrants ($id) {
        global $DB;
        $sqlparam = ['id' => $id];
        $sql = 'select * from {local_gcs_regfox_registrant} where id=:id';

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * inserts a regfox webhook registrant record
     *
     * @param object $rec regfox webhook registrant record
     */
    public static function insert_regfox_registrant ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_regfox_registrant', $rec);
        return $id;
    }
    /**
     * updates a regfox webhook registrant record
     *
     * @param object $rec regfox webhook registrant record
     */
    public static function update_regfox_registrant ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->update_record('local_gcs_regfox_registrant', $rec);
        return $id;
    }
    /**
     * Retrieves the list of unprocess RegFox classes.
     *
     * @param int $regid optional Regfox registrant id, default is get unprocessed classes for all registrants.
     * @return  hash  of RegFox class records
     */
    public static function get_regfox_classes_unprocessed ($regid=0) {
        global $DB;
        $sqlparam = [];
        $sql = 'select * from {local_gcs_regfox_class} where processedtime=0';
        if ($regid) {
            $sqlparam['regid'] = $regid;
            $sql .= ' and regid=:regid';
        }

        $recs = $DB->get_records_sql(
            $sql,
            $sqlparam,
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    /**
     * inserts a regfox webhook class record
     *
     * @param object $rec regfox webhook class record
     */
    public static function insert_regfox_class ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->insert_record('local_gcs_regfox_class', $rec);
        return $id;
    }
    /**
     * updates a regfox webhook class record
     *
     * @param object $rec regfox webhook class record
     */
    public static function update_regfox_class ($rec) {
        global $DB;

        $rec = (object) $rec; // For some reason, we get an array in this function instead of an object.
        $id = $DB->update_record('local_gcs_regfox_class', $rec);
        return $id;
    }

    /**
     * Retrieves list of depedent record ids and table identifier
     *
     * @param  string  $tablecode table description
     * @param  string  $keycsv key values for lookup
     * @return hash of returned records
     */
    const DEFS = [
        ['tablecode' => 'class', 'keyfldnames' => 'termyear,termcode,coursecode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'course', 'keyfldnames' => 'coursecode', 'tblsfxlist' => 'classes_taken,courses_permitted,classes'],
        ['tablecode' => 'course', 'keyfldnames' => 'assignedcoursecode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'enrollagreement', 'keyfldnames' => 'agreementid', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'program', 'keyfldnames' => 'programcode', 'tblsfxlist' => 'courses_permitted,program_req,program_completion,sch_given,student'],
        // THIS WOULD HAVE TO BE A CUSTOM QUERY CHECKING THE programcode OF THE student AND THE courses_permitted categorycode ['tablecode' => 'programreq', 'keyfldnames' => 'programcode,categorycode', 'tblsfxlist' => 'courses_permitted'],
        ['tablecode' => 'schavailable', 'keyfldnames' => 'scholarshipeligible', 'tblsfxlist' => 'student'],
        ['tablecode' => 'schavailable', 'keyfldnames' => 'category', 'tblsfxlist' => 'sch_given'],
        ['tablecode' => 'scholarshipgiven', 'keyfldnames' => 'scholarshipid', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'student', 'keyfldnames' => 'studentid', 'tblsfxlist' => 'classes_taken,sch_given,program_completion'],
        ['tablecode' => 'termdates', 'keyfldnames' => 'termyear,termcode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'codeset.category', 'keyfldnames' => 'categorycode', 'tblsfxlist' => 'courses_permitted,program_req'],
        ['tablecode' => 'codeset.category', 'keyfldnames' => 'category', 'tblsfxlist' => 'sch_given'],
        ['tablecode' => 'codeset.citizenship', 'keyfldnames' => 'citizenship', 'tblsfxlist' => 'student'],
        ['tablecode' => 'codeset.cr_type', 'keyfldnames' => 'credittypecode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'codeset.cr_type', 'keyfldnames' => 'credittype', 'tblsfxlist' => 'enroll_agreements'],
        ['tablecode' => 'codeset.datatype', 'keyfldnames' => 'datatype', 'tblsfxlist' => 'table_field_def'],
        ['tablecode' => 'codeset.dbdatatype', 'keyfldnames' => 'dbdatatype', 'tblsfxlist' => 'table_field_def'],
        ['tablecode' => 'codeset.ethnic', 'keyfldnames' => 'ethniccode', 'tblsfxlist' => 'student'],
        ['tablecode' => 'codeset.grade', 'keyfldnames' => 'gradecode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'codeset.pass_fail', 'keyfldnames' => 'gradecode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'codeset.popupid', 'keyfldnames' => 'addpopupid', 'tblsfxlist' => 'table_field_def'],
        ['tablecode' => 'codeset.popupid', 'keyfldnames' => 'updpopupid', 'tblsfxlist' => 'table_field_def'],
        ['tablecode' => 'codeset.pgm_completed_src', 'keyfldnames' => 'source', 'tblsfxlist' => 'program_completion'],
        ['tablecode' => 'codeset.programcode', 'keyfldnames' => 'programcode', 'tblsfxlist' => 'program_completion'],
        ['tablecode' => 'codeset.scholarship_category', 'keyfldnames' => 'category', 'tblsfxlist' => 'sch_given'],
        ['tablecode' => 'codeset.scholarship_accounting', 'keyfldnames' => 'regfoxcode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'codeset.scholarship_approve', 'keyfldnames' => 'decision', 'tblsfxlist' => 'sch_given'],
        ['tablecode' => 'codeset.status', 'keyfldnames' => 'statuscode', 'tblsfxlist' => 'student'],
        ['tablecode' => 'codeset.term', 'keyfldnames' => 'termcode', 'tblsfxlist' => 'classes_taken'],
        ['tablecode' => 'codeset.visibility', 'keyfldnames' => 'addshow', 'tblsfxlist' => 'table_field_def'],
        ['tablecode' => 'codeset.visibility', 'keyfldnames' => 'updshow', 'tblsfxlist' => 'table_field_def'],
    ];

    public static function get_table_record_dependencies ($tablecode, $keycsv) {
        $recs = []; // Output list.
        $keyvals = explode(",", $keycsv);

        foreach (self::DEFS as $def) {
            if ($def['tablecode'] == $tablecode) {
                $keyfldnames = explode(",", $def['keyfldnames']); // Expand key field names.
                // Must match the passed-in key value count.
                if (count($keyfldnames) == count($keyvals)) {
                    // Build the key array.
                    $keylist = [];
                    for ($i = 0; $i < count($keyfldnames); $i++) {
                        $keylist[$keyfldnames[$i]] = $keyvals[$i];
                    }

                    // Check each table.
                    foreach (explode(",", $def['tblsfxlist']) as $tblsfx) {
                        self::getdependents($recs, $tblsfx, $keylist);
						// early breakout to save db resources (we don't need to know all dependencies at this point, just that it has at least one)
						if (count($recs) > 0) {
							return $recs;
						}
                   }
                }
            }
        }
        return $recs;
    }

/*     public static function has_table_record_dependencies ($tablecode, $keycsvlist) {
        $hasdeps = false;
		$recs = [];
        $keylist = explode("|", $keycsvlist);
		$resultlist = []; // Output list.
        
        foreach (self::DEFS as $def) {
			// first find the tablecode in the array
            if ($def['tablecode'] == $tablecode) {
				// process each key set, checking for its dependency
				foreach (explode("|", $keycsvlist) as $keycsvset) {
					$hasdep = 0;
					$keyvals = explode(",", $keycsvset);
					$keyfldnames = explode(",", $def['keyfldnames']); // Expand key field names.
					// Must match the passed-in key value count.
					if (count($keyfldnames) == count($keyvals)) {
						// Build the key array.
						$keylist = [];
						for ($i = 0; $i < count($keyfldnames); $i++) {
							$keylist[$keyfldnames[$i]] = $keyvals[$i];
						}

						// Check each table.
						foreach (explode(",", $def['tblsfxlist']) as $tblsfx) {
							self::getdependents($recs, $tblsfx, $keylist);
							if (count($recs) > 0) {
								$hasdep = 1;
							}
						}
					}
					$resultlist[] = $hasdep;
				}
            }
        }
        return join(",", $resultlist);
    }
 */
    private static function getdependents(&$recs, $tablecode, $keylist) {
        global $DB;

        $sqlcondition = '';
        foreach ($keylist as $key => $val) {
            if ($sqlcondition != '') {
                $sqlcondition .= ' and ';
            }
            $sqlcondition .= $key . '=:' . $key;
        }
        $sql = 'select id from {local_gcs_' . $tablecode . '} where ' . $sqlcondition . ' limit 1';// for now, to save resources, only get first one
        $dbrecs = $DB->get_records_sql(
            $sql,
            $keylist,
            $limitfrom = 0,
            $limitnum = 0
        );

        foreach ($dbrecs as $dbrec) {
            $recs[] = ['tablecode' => $tablecode, 'id' => $dbrec->id];
        }
        return $recs;
    }
}
