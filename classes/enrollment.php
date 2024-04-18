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
 * Enrollment functions for GCS Program Management
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class enrollment {
    /** @var string $logrecs log records. */
    public $logrecs;
    /** @var string $ntfmsg notification message. */
    public $ntfmsg;
    /** @var \local\gcs\settings settings class. */
    public $settings;
    /** @var array $termcodes array of term codes */
    public $termcodes;

    /**
     * Initializes the enrollment class.
     */
    public function __construct() {
        $this->logrecs = '';
        $this->ntfmsg = '';
        $this->settings = new settings();
    }
    
    /**
     * Verify enrollments - Make sure students registered for classes in the current
     * term and enrolled in the courses they registered for. Make sure students
     * registered for classes last term are no longer enrolled in those courses if
     * they completed or withdrew from the classes.
     *
     * @param  string $coursecode the course code (short name) to enroll the student in
     * @return array an array of user objects enrolled in the course
     */
    public function verify_enrollments() {
        global $DB;
        $lastterm = false;
        self::get_term_codes();
        // Get the current term dates record.
        $recs = data::get_term_date_current();
        $thisterm = array_pop($recs);
        // Get the prior term dates record.
        if ($thisterm) {
            $sql = "
                SELECT * 
                  FROM {local_gcs_term_dates} td
                 WHERE td.registrationstart < :curstart
              ORDER BY td.registrationstart desc
                 LIMIT 1";

            $recs = $DB->get_records_sql(
                $sql,
                ['curstart' => $thisterm->registrationstart],
                $limitfrom = 0,
                $limitnum = 0
            );

            $lastterm = array_pop($recs);
        }
        if ($lastterm) {
            // Get the prior term's class registrations.
            $ctrecs = self::get_classes_taken_for_term($lastterm->termyear, $lastterm->termcode);
            foreach ($ctrecs as $ctr) {
                // If a student has not completed or withdrawn, ignore it.
                if (!$ctr->completiondate && !$ctr->canceldate) {
                    continue;
                }
                // We allow 30 days after completion so we get the snapshot site made before unenrolling.
                // But for cancels and drops, we unenroll immediately.
                $unenroll = false;
                $checkdate = new DateTime();
                $checkdate.sub(new DateInterval('P30D'));
                $checkdate = $checkdate.getTimestamp();
                if ($ctr->canceldate) {
                    $unenroll = true;
                } else if ($ctr->gradecode == 'DRP') {
                    $unenroll = true;
                } else if (($ctr->completiondate) && ($ctr>completiondate < $checkdate)) {
                    $unenroll = true;
                }
                
                // Get the student record.
                $stus = data::get_student_by_id($ctr->studentid);
                $stu = array_pop($stus);
                if ($stu) {
                    $userid = $stu->userid;
                    if ($userid && $unenroll) {
                        if (self::unenroll_user($userid, $ctr->coursecode)) {
                            $crs = data::get_course_by_code($ctr->coursecode);
                            $msg = 'Unenrolled ' . $stu->preferredfirstname . " " . $stu->legallastname 
                                . " from " . $crs->coursecode . ' - ' . $crs->title 
                                . ' in ' . $this->termcodes[$ctr->termcode] . ' ' . $ctr->termyear;
                            $this->logthis($msg);
                        }
                    }
                }
            }
        }
        if ($thisterm) {
            // Get the current term's class registrations.
            $ctrecs = self::get_classes_taken_for_term($thisterm->termyear, $thisterm->termcode);
            foreach ($ctrecs as $ctr) {
                // Get the student record.
                $stus = data::get_student_by_id($ctr->studentid);
                $stu = array_pop($stus);
                if ($stu) {
                    $userid = $stu->userid;
                } else {
                    // If no student record, we can't do anything.
                    continue;
                }
                if (!$userid) {
                    // If no userid, we can't do anything.
                    continue;
                }
                $crs = data::get_course_by_code($ctr->coursecode);
                if (!$ctr->completiondate && !$ctr->canceldate) {
                    // If a student has not completed or withdrawn, enroll the student in the course.
                    if (self::enroll_user($userid, $ctr->coursecode)) {
                        $msg = 'Enrolled ' . $stu->preferredfirstname . " " . $stu->legallastname 
                            . " in " . $crs->coursecode . ' - ' . $crs->title 
                            . ' in ' . $this->termcodes[$ctr->termcode] . ' ' . $ctr->termyear;
                        $this->logthis($msg);
                    }
                } else {
                    if (($ctr->gradecode == 'DRP') || ($ctr->canceldate)) {
                        // Otherwise, we unenroll a student who withdrew or cancelled to prevent further access.
                        if (self::unenroll_user($userid, $ctr->coursecode)) {
                            $msg = 'Unenrolled ' . $stu->preferredfirstname . " " . $stu->legallastname 
                                . " from " . $crs->coursecode . ' - ' . $crs->title 
                                . ' in ' . $this->termcodes[$ctr->termcode] . ' ' . $ctr->termyear;;
                            $this->logthis($msg);
                        }
                    }
                }
            }
        }
        if ($this->logrecs) {
            $msg = '<pre>'.$this->logrecs.'</pre>';
            $sub = 'Automatic enrollments';
            if (!utils::is_live()) {
                if ($this->settings->notificationenabled) { // Only send notification in non-live if enabled.
                    utils::send_notification_email($sub, $msg);
                }
            } else {
                utils::send_notification_email($sub, $msg);
            }
        }
        $log = $this->logrecs;
        $this->logrecs = PHP_EOL;
        return $log;
    }
    
    /**
     * Get classes taken for a term
     *
     * @param  int    $termyear term year
     * @param  string $termcode term code 
     * @return array an array of classes taken records
     */
    private static function get_classes_taken_for_term($termyear, $termcode) {
        global $DB;
        $sql = "
            SELECT *
              FROM {local_gcs_classes_taken}
             WHERE termyear=:termyear AND termcode=:termcode
          ORDER BY coursecode";
        $recs = $DB->get_records_sql(
            $sql,
            ['termyear' => $termyear, 'termcode' => $termcode],
            $limitfrom = 0,
            $limitnum = 0
        );
        return $recs;
    }
    
    /**
     * Get enrollments for a course.
     *
     * @param  string $coursecode the course code (short name) to enroll the student in
     * @return array an array of user objects enrolled in the course
     */
    public static function get_enrollments($coursecode) {
        global $DB;
        // Get the course and role records from the database so we have the ids.
        $crs  = $DB->get_record('course', ['shortname' => $coursecode]);
        if ($crs) {
            $context = \context_course::instance($crs->id);
            $users = get_enrolled_users($context, null, null, null, null, null, null, true);
        } else {
            $users = [];
        }
        return $users;
    }
    
    /**
     * Enroll a user in a course.
     *
     * @param int    $userid     the id of the user to enroll
     * @param string $coursecode the course code (short name) to enroll the student in
     * @return bool true if user was enrolled, false if not.
     */
    public static function enroll_user($userid,$coursecode) {
        global $DB;
        // Get the course and role records from the database so we have the ids.
        $crs  = $DB->get_record('course', ['shortname' => $coursecode]);
        $role = $DB->get_record('role', ['shortname' => 'student']);
        $rv = false;
        if ($crs && $role) {
            $context = \context_course::instance($crs->id);
            $thisuser = \core_user::get_user($userid);
            // Make sure the user isn't already enrolled.
            if (!is_enrolled($context,$thisuser, null, true)) {
                // Get the enrollment plugin for manual enrollments.
                $epr = $DB->get_record('enrol', ['courseid' => $crs->id, 'enrol' => 'manual']);
                $ep  = enrol_get_plugin($epr->enrol);
                $ep->enrol_user($epr, $userid, $role->id, time(), 0, ENROL_USER_ACTIVE);
                $rv = true;
            }
        }
        return $rv;
    }
    
    /**
     * Unenroll a user from a course.
     *
     * @param int    $userid     the id of the user to enroll
     * @param string $coursecode the course code (short name) to enroll the student in
     * @return bool true if student was unenrolled, false if not.
     */
    public static function unenroll_user($userid,$coursecode) {
        global $DB;
        // Get the course and role records from the database so we have the ids.
        $crs  = $DB->get_record('course', ['shortname' => $coursecode]);
        $role = $DB->get_record('role', ['shortname' => 'student']);
        $rv = false;
        if ($crs && $role) {
            $context = \context_course::instance($crs->id);
            $thisuser = \core_user::get_user($userid);
            // Make sure the user isn't already enrolled.
            if (is_enrolled($context,$thisuser, null, true)) {
                // Get the enrollment plugin for manual enrollments.
                $epr = $DB->get_record('enrol', ['courseid' => $crs->id, 'enrol' => 'manual']);
                $ep  = enrol_get_plugin($epr->enrol);
                $ep->unenrol_user($epr, $userid);
                $rv = true;
            }
        }
        return $rv;
    }

    /**
     * Get term codes.
     *
     * @param none
     */
    private function get_term_codes() {
        $this->termcodes = [];
        $recs = data::get_codes('term');
        foreach ($recs as $rec) {
            $this->termcodes[$rec['code']] = $rec['description'];
        }
    }

    /**
     * Add a message to the log records.
     *
     * @param string $logrec message to add to log
     */
    private function logthis($logrec) {
        $this->logrecs .= $logrec.PHP_EOL;
        mtrace($logrec);
    }

}
