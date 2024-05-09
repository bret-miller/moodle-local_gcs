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
    /** @var string $ntfmsg notification message. */
    public $ntfmsg;
    /** @var \local\gcs\settings settings class. */
    public $settings;

    /**
     * Initializes the RegFox Processor class
     *
     * @param bool $interactive optional indicator if running interactively, default is false.
     */
    public function __construct($interactive = false) {
        $this->interactive = $interactive;
        $this->logrecs = '--------------------------------------------------------------------------------'.PHP_EOL;
        $this->logthis('Interactive: '.json_encode($this->interactive));
        $this->logrecs = '';
        $this->ntfmsg = '';
        $this->settings = new settings();
        if (!utils::is_live()){
            $this->ntfmsg .= '--> ** Not running in live instance **'.PHP_EOL;
        }
        $this->ntfmsg .= '--> Running in "' . utils::get_instance_label() . '" instance'.PHP_EOL;
		$this->logthis($this->ntfmsg);
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
        if (count($recs)) {
            $this->ntfmsg .= "Found " . count($recs) . " unsigned enrollment agreements to process." . PHP_EOL;
        }
        foreach ($recs as $rec) {
            $needsave = false;
            $ctr = new classes_taken($rec);
            // For records without an agreement ID, get one.
            if (!$ctr->agreementid) {
                $earec = data::get_class_enrollment_agreement_rec ($ctr);
                if ($earec) {
                    $ctr->agreementid = $earec->id;    
                    $needsave = true;
                } else {
					$stud = data::get_students($ctr->studentid);
					$term = data::get_code_by_code('term',$ctr->termcode);
					$msg = 'No agreement found for ' . $stud->preferredfirstname . ' ' . $stud->legallastname;
					$msg .= ' for ' . $ctr->coursecode . ' taken in ' . $term->description . ' ' . $ctr->termyear . '. Skipping.' . PHP_EOL;
					$this->ntfmsg .= $msg;
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
        if ($this->ntfmsg) {
            $msg = '<pre>'.$this->ntfmsg.'</pre>';
            $sub = 'Enrollment Agreement Review';
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
        $this->ntfmsg .= $msg.PHP_EOL;
        $this->logthis($msg);
    }

    /**
     * Remind student to sign enrollment agreement.
     *
     * @param classes_taken $ctr classes taken record.
	 * @return string log message
     */
    public function reminder($ctr) {
        global $CFG;
        $stud = data::get_students($ctr->studentid);
        $studuser = \core_user::get_user($stud->userid);
        $crs = data::get_course_by_code($ctr->coursecode);
        $msg = '<p>Please sign your enrollment agreement for ' . $ctr->coursecode . ' - ' . $crs->title . '</p>' . PHP_EOL;;
        $msg .= '<p><a href="' . $CFG->wwwroot . '/local/gcs/enrollment_agreements_signing.php">';
        $msg .= 'Click here to sign.</a></p>' . PHP_EOL;
		$msg .= '<p>If that does not work, here is how to find it manually:</p>' . PHP_EOL;
		$msg .= '<ol><li>Sign in at <a href="' . $CFG->wwwroot . '">' . $CFG->wwwroot . '</a></li>' . PHP_EOL;
		$msg .= '<li>Click Student Reports on the low left column.</li>' . PHP_EOL;
		$msg .= '<li>Click Sign Enrollment Agreements.</li></ol>' . PHP_EOL;
        // Only actually send emails in the live system.
        if (utils::is_live()) {
            utils::send_email($studuser->email, 'Reminder: Sign Enrollment Agreement', $msg);
            $msg = 'Reminded ' . $stud->preferredfirstname . ' ' . $stud->legallastname;
            $msg .= ' to sign agreement for ' . $ctr->coursecode . ' - ' . $crs->title;
            $this->ntfmsg .= $msg.PHP_EOL;
        } else {
            $msg = 'In Live, would have sent this message to ' . $studuser->email . ':' . PHP_EOL;
            $this->logthis($msg);
            $this->ntfmsg .= $msg.PHP_EOL;
        }
		return $msg;
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
