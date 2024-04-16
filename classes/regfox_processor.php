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
 * Process course registration data from RegFox webhooks
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class regfox_processor {
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
     * Process the raw webhook data
     *
     * @param none
     */
    public function process_webhooks() {
        $secret = '3213b1dee75d47c4acfb32e295ae2dcd'; // Hash secret for checking signatures.
        // Process raw webhooks into registrants and classes.
        $recs = data::get_regfox_webhooks_unprocessed();
        $this->logthis("Found ".count($recs)." webhooks to process.");
        foreach ($recs as $rec) {
            $hash = hash_hmac('sha256',$rec->postdata,$secret);
            if ($rec->signature != $hash) {
                $this->logthis('Skipping webhook, invalid signature.');
            } else {
                // Note that building the transaction class also builds the registration and class classes attached to it.
                $tran = new regfox_transaction($rec->id, $rec->postdata);
                // And saving the transaction also saves the registrants and classes registered.
                $tran->save();
                $rec->processedtime = time();
                data::update_regfox_webhook($rec);
                $this->logthis('Paid by: '.$tran->firstname.' '.$tran->lastname.' ('.$tran->email.')');
                $this->logthis('Total cost: '.number_format(floatval($tran->total),2). ' ('.$tran->paymethod.')');
                $this->logthis('Students:');
                foreach ($tran->registrants as $reg) {
                    $this->logthis('... '.$reg->firstname.' '.$reg->lastname.' ('.$reg->email.')');
                    $this->logthis('... Scholarship: '.$reg->scholarshipcode);
                    $this->logthis('... Total: '.number_format(floatval($reg->amount),2));
                    foreach ($reg->classes as $class) {
                        $this->logthis('....... '.$class->coursecode.' - '.$class->title);
                    }
                    $this->task->writelog();
                }
            }
        }
        $log = $this->logrecs;
        $this->logrecs = PHP_EOL;
        return $log;
    }

    /**
     * Process the unprocessed registrants and class registrations
     *
     * @param none
     */
     public function process_registrants() {
        // Process unprocessed classes. Note that records are keyed by id.
        $rfcrecs = data::get_regfox_classes_unprocessed();
        $rfrrecs = data::get_regfox_registrants_unprocessed();
        $this->logthis("Found ".count($rfcrecs)." classes to process for ".count($rfrrecs)." registrants.");
        $registrants = [];
        // Build array of unprocessed registrants.
        foreach ($rfrrecs as $rec) {
            $rfreg = new regfox_registrant($rec);
            $registrants[$rfreg->id] = $rfreg;
        }
        // Attach classes to registrants.
        foreach ($rfcrecs as $rec) {
            $rfclass = new regfox_class($rec);
            $rfreg = $registrants[$rfclass->regid];
            array_push($rfreg->classes,$rfclass);
        }
        // Process them.
        foreach ($registrants as $rfreg) {
            $this->logrecs = $this->process_registrant($rfreg)->log;
        }
        $log = $this->logrecs;
        $this->logrecs = PHP_EOL;
        return $log;
    }
    
    /**
     * Process one unprocessed registrant and class registrations for the registrant
     *
     * @param local_gcs\regfox_registrant $rfreg RegFox registrant
	 * @return array 
	 *     'processed' = whether or not we could fully process the registrant.
	 *     'registrant' = the registrant record we tried to process.
	 *                    the studentid will be nonzero if we matched a student.
	 *     'log = information about what we did.
     */
    public function process_registrant($rfreg) {
        $rftran  = new regfox_transaction($rfreg->tranid); // Get the transaction for this registrant.
        $this->logthis('Found order '.$rftran->ordernumber.', for registrant '.$rfreg->firstname.' '.$rfreg->lastname);
        if ($rfreg->studentid) {
            $stus = data::get_student_by_id($rfreg->studentid);
        } else {
            // We need the student ID.
            $stus = data::get_students_by_email($rfreg->email);
        }
        if (count($stus) == 1) {
            // Found 1 student that matches, so we continue.
            $stu = array_pop($stus);
            if (!$rfreg->studentid) {
                $rfreg->studentid = $stu->id;
                $rfreg->save();
            }
        } else {
            if (count($stus)) {
                $msg = 'Found registrant '.$rfreg->firstname.' '.$rfreg->lastname.' ('.
                    $rfreg->email.') but multiple students match this email.';
                $this->logthis($msg);
                if (!$this->interactive) {
                    utils::send_notification_email('Student Match Error', "<p>$msg</p>");
                }
            } else {
                $msg = 'Found registrant '.$rfreg->firstname.' '.$rfreg->lastname.' ('.
                    $rfreg->email.') but no students match this email.';
                $this->logthis($msg);
                if (!$this->interactive) {
                    utils::send_notification_email('Student Match Error', "<p>$msg</p>");
                }
            }
        }
        //$f = fopen(__DIR__ . "/debug.log", "w");
        //fwrite($f, print_r($rfclass, true).' regid type is '.gettype($rfclass->regid)."\n");
        //fwrite($f, print_r(debug_backtrace(), true)."\n");
        //fclose($f);
        
        if ($rfreg->studentid) { // Can only process if we have the student id.
            $stu = data::get_students($rfreg->studentid);
            $this->logthis('Found student id = '.$rfreg->studentid.' '.$stu->firstname.' '.$stu->lastname);
            $credittypes = data::get_codes('cr_type');
            if (count($rfreg->classes)==0) {
                // Get the unprocessed class registrations for this registrant.
                $rfreg->classes = data::get_regfox_classes_unprocessed($rfreg->id);
            }

            foreach ($rfreg->classes as $rfclass) {
                // First fix the credittype code to use the code not the description.
                foreach ($credittypes as $crt) {
                    if ($rfclass->credittypecode == $crt->description) {
                        $rfclass->credittypecode = $crt->code;
                        break;
                    }
                }
                // Pull the year and term from the order number and lookup or create the class.
                $ty = intval(substr($rftran->ordernumber, 3, 4));
                $tc = intval(substr($rftran->ordernumber, 7, 1));
                $cls = new classrec($rfclass->coursecode, $ty, $tc);
                $this->logthis('Found class id = '.$cls->id.', course code = '.$cls->coursecode.', term = '.$cls->termyear.$cls->termcode);
                if ($cls->id) {
                    // We found or created the class record so we can proceed with processing the registration.
                    $ctr = new classes_taken($stu->id, $ty, $tc, $cls->coursecode);
                    if (!$ctr->id) {
                        // Need to create classes taken record. to complete registration.
                        $ctr->termyear = $ty;
                        $ctr->termcode = $tc;
                        $ctr->coursecode = $cls->coursecode;
                        $ctr->studentid = $stu->id;
                        $ctr->idnumber = $stu->idnumber;
                        $ctr->registrationdate = $rftran->transactiontime;
                        $ctr->credittypecode = $rfclass->credittypecode;
                        $ctr->tuitionpaid = $rfclass->cost;
                        $ctr->studentpaid = $rfclass->paid;
						$ctr->classtuition = $rfclass->paid;
                        $ctr->regfoxcode = $rfreg->scholarshipcode;
                        $schs = data::get_sch_given_logical($rfreg->studentid, $ty);
                        $schcat = '';
                        $schtotal = $ctr->tuitionpaid;
                        if (count($schs)) {
                            $sch = array_pop($schs);
                            $ctr->scholarshippedamount = $sch->perunitamount * $cls->coursehours;
                            $ctr->scholarshipid = $sch->id;
                            $schcat = $sch->category;
                            $schtotal = ($ctr->tuitionpaid-$ctr->scholarshippedamount);
                        }
						$ctr->ordertotal = $ctr->studentpaid+$ctr->scholarshippedamount;
                        $this->logthis("Created classes taken record:\n".print_r($ctr,true));
                        $ctr->save();
						if ($stu->userid) {
							enrollment::enroll_user($stu->userid, $ctr->coursecode);
						}
                        if ( $schtotal != $ctr->studentpaid) {
                            // Student didn't pay correct amount?
                            $msg = 
                                '=================================================='.PHP_EOL.
                                '=============== CHECK TUITION PAID ==============='.PHP_EOL.
                                '=================================================='.PHP_EOL.
                                'Student: '.$stu->legalfirstname.' '.$stu->legallastname.PHP_EOL.
                                'Paid: ' . $ctr->studentpaid .PHP_EOL.
                                'Tuition: ' . $ctr->tuitionpaid .PHP_EOL;
                            if ($ctr->regfoxcode) {
                                $msg .= 'Scholarship code: ' . $ctr->regfoxcode.PHP_EOL;
                            }
                            if ($schcat != '') {
                                $msg .= 'Scholarship: ' . $sch->category . ' per unit amount ' . number_format($sch->perunitamount,2).PHP_EOL.
                                    'For this course: '.number_format($ctr->scholarshippedamount,2).PHP_EOL;
                            }
                            $msg .= 'Total should be: '.number_format($schtotal,2);
                            $this->logthis($msg);
							utils::send_notification_email('Check Tuition Paid',"<p>$msg</p>");
                        }
                    }
                    if ($ctr->id) {
                        // Then we mark the registration as processed.
                        $rfclass = new regfox_class($rfclass);
                        $now = time();
                        $rfclass->processedtime = $now;
                        $rfclass->save();
                    }
                }
            }
        }
        $rfclasses = data::get_regfox_classes_unprocessed($rfreg->id);
        if (!count($rfclasses)) {
            $allprocessed = true;
        } else {
            $allprocessed = false;
        }
        if ($allprocessed) {
            $now = time();
            $rfreg->processedtime = $now;
            $rfreg->save();
            $rftran->processedtime = $now;
            $rftran->save();
        }
        $log = $this->logrecs;
        $this->logrecs = PHP_EOL;
        return [
            'processed' => $allprocessed,
			'registrant' => $rfreg,
            'log' => $log
        ];
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
