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
 * Process classes taken records to remind students to sign their enrollment agreement
 * and auto-sign the agreement when a student completes or leaves the course
 * if it is not already signed.
 *
 * @package    local_gcs
 * @copyright  2024 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class agreement_processor {
    /** @var bool $interactive whether this is running interactive or as a task. */
    public $interactive;
    /** @var string $logrecs log records. */
    public $logrecs;

    /**
     * Initializes the RegFox Processor class
     *
     * @param bool $interactive optional indicator if running interactively, default is false.
     */
    public function __construct($interactive = false) {
        $this->interactive = $interactive;
        $this->logrecs = '--------------------------------------------------------------------------------'.PHP_EOL;
        $this->logthis('Interactive: '.json_encode($this->interactive));
    }

    /**
     * Process the unsigned enrollment agreements
     *
     * @param none
     */
    public function process_agreements() {
        global $DB;
        // Get the minimum enrollment agreement date. 
        // We only process the classes where the registration date is equal to or greater than this.
        $sql = 'SELECT 1 as id, MIN(adddate) as adddate FROM {local_gcs_enroll_agreements}';
        $recs = $DB->get_records_sql($sql, [], $limitfrom = 0, $limitnum = 0);
        if (count($recs)) {
            $rec = array_pop($recs);
            $mindate = $rec->adddate;
        }
        
        // Get the current term.
        // We only remind students to sign agreements for the current term.
        $currtermarr = data::get_term_date_current();
        $curterm = array_pop($currtermarr);
        
        // Get the classes with unsigned agreements.
        $sql = 'SELECT * FROM {local_gcs_classes_taken}
             WHERE COALESCE(agreementsigned,0) = 0
               AND registrationdate >= :mindate';
        $recs = $DB->get_records_sql($sql, ['mindate' => $mindate], $limitfrom = 0, $limitnum = 0);
        $this->logthis("Found ".count($recs)." agreements to process.");
        foreach ($recs as $rec) {
            $needsave = false;
            $ctr = new classes_taken($rec);
            // For records without an agreement ID, get one.
            if (!$ctr->agreementid) {
                $earec = data::get_class_enrollment_agreement_rec ($ctr);
                if ($earec) {
                    $ctr->agreementid = $earec->id;    
                    $needsave = true;
                }
            }
            // For completed classes, auto-sign with the completion date.
            if (($ctr->agreementid) && ($ctr->completiondate)) {
                $this->autosign($ctr, $ctr->completiondate);
            // For classes withdrawn from, auto-sign with the cancel date.
            } else if (($ctr->agreementid) && ($ctr->canceldate)) {
                $this->autosign($ctr, $ctr->canceldate);
            // For classes in the current term, remind student to sign.
            } else {
                if (($ctr->termyear == $curterm->termyear) && ($ctr->termcode == $curterm->termcode)) {
                    $this->reminder($ctr);
                }
                // If we added the agreement id and didn't autosign, then save it.
                if ($needsave) {
                    $ctr->save();
                }
            } 
        }
        $log = $this->logrecs;
        $this->logrecs = PHP_EOL;
        return $log;
    }

    /**
     * Auto-sign enrollment agreement.
     *
     * @param classes_taken $ctr classes taken record.
     * @param int $signdate sign the agreement as of this date.
     */
    private function autosign($ctr, $signdate) {
        $ctr->agreementsigned = $signdate;
        $ctr->save();
        $stud = data::get_students($ctr->studentid);
        $term = data::get_code_by_code('term',$ctr->termcode);
        $msg = 'Autosigned agreement for ' . $stud->preferredfirstname . ' ' . $stud->legallastname;
        $msg .= ' for ' . $ctr->coursecode . ' taken in ' . $term->description . ' ' . $ctr->termyear;
        $this->logthis($msg);
    }

    /**
     * Remind student to sign enrollment agreement.
     *
     * @param classes_taken $ctr classes taken record.
     */
    private function reminder($ctr) {
        global $CFG;
        $stud = data::get_students($ctr->studentid);
        $studuser = \core_user::get_user($stud->userid);
        $crs = data::get_course_by_code($ctr->coursecode);
        $msg = '<p>Please sign your enrollment agreement for ' . $ctr->coursecode . ' - ' . $crs->title . '</p>' . PHP_EOL;;
        $msg .= '<p><a href="' . $CFG->wwwroot . '/local/gcs/enrollment_agreements_signing.php">';
        $msg .= 'Click here to sign.</a></p>' . PHP_EOL;
        $this->logthis('Would send this message to '.$studuser->email.':');
        $this->logthis($msg);
		// Only actually send emails in the live system.
        if (utils::is_live()) {
            utils::send_email($studuser->email, 'Reminder: Sign Enrollment Agreement', $msg);
        }
        $msg = 'Reminded ' . $stud->preferredfirstname . ' ' . $stud->legallastname;
        $msg .= ' to sign agreement for ' . $ctr->coursecode;
        $this->logthis($msg);
    }

    /**
     * Add a message to the log records.
     *
     * @param none
     */
    private function logthis($logrec) {
        $this->logrecs .= $logrec.PHP_EOL;
        if (!$this->interactive) {
            mtrace($logrec);
        }
    }
}
