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
 * Defines a RegFox class, part of a RegFox transaction
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class classes_taken {
    /** @var int string id */
    public $id;
    /** @var int term year */
    public $termyear;
    /** @var string term code */
    public $termcode;
    /** @var string course code */
    public $coursecode;
    /** @var int student id */
    public $studentid;
    /** @var int student alternate id */
    public $idnumber;
    /** @var string short title override */
    public $shorttitleoverride;
    /** @var string title override */
    public $titleoverride;
    /** @var string credit type */
    public $credittypecode;
    /** @var string grade */
    public $gradecode;
    /** @var int course hours override */
    public $coursehoursoverride;
    /** @var int registration date */
    public $registrationdate;
    /** @var float tuition paid */
    public $tuitionpaid;
    /** @var int completion date */
    public $completiondate;
    /** @var int candel date */
    public $canceldate;
    /** @var string comments */
    public $comments;
    /** @var string assigned course code */
    public $assignedcoursecode;
    /** @var int is elective? */
    public $elective;
    /** @var float scholarshipped amount */
    public $scholarshippedamount;
    /** @var float scholarshipped adjustment */
    public $scholarshippedadjustment;
    /** @var int scholarship id */
    public $scholarshipid;
    /** @var int date agreement was signed */
    public $agreementsigned;
    /** @var int agreement id */
    public $agreementid    ;
    /** @var int order number */
    public $ordernumber;
    /** @var int line number */
    public $linenumber;
    /** @var float fee amount*/
    public $fee;
    /** @var float amount paid by student */
    public $studentpaid;
    /** @var string RegFox code */
    public $regfoxcode;
    /** @var int manual pricing? */
    public $manualpricing;

    /**
     * Initializes a classes taken record
     * 
     * @param none for blank record
     *     or int $id to read from database
     *     or object $rec to build from object properties
     */
    public function __construct() {
        $args = func_get_args();
        $argc = func_num_args();
        if ($argc == 0) {
            // Initialize blank regfox class record.
			$this->blankrec();
        } else if (($argc == 1) && ((gettype(args[0]) == 'integer') || (gettype(args[0]=='string')))) {
            // Read from database by record id.
            $rec = data::get_classes_taken($args[0]);
			if ($rec) {
				$this->fill($rec);
			} else {
				$this->blankrec();
			}
        } else if (($argc == 1) && (gettype(args[0]) == 'object')){
            // Build from object properties.
            $rec = $args[0];
			$this->fill($rec);
		} else if ($argc == 4) {
			// Read from database
			global $DB;
			$rec = $DB->get_record('local_gcs_classes_taken', [
				'studentid' => $args[0],
				'termyear' => $args[1],
				'termcode' => $args[2],
				'coursecode' =>$args[3],
				]);
			if ($rec) {
				$this->fill($rec);
			} else {
				$this->blankrec();
			}
        }
    }

    /**
     * Initializes a blank classes taken record
     */
    private function blankrec() {
		$this->id = 0;
		$this->termyear = 0;
		$this->termcode = '';
		$this->coursecode = '';
		$this->studentid = 0;
		$this->idnumber = '';
		$this->shorttitleoverride = '';
		$this->titleoverride = '';
		$this->credittypecode = '';
		$this->gradecode='';
		$this->coursehoursoverride = 0;
		$this->registrationdate = 0;
		$this->tuitionpaid = 0;
		$this->completiondate = null;
		$this->canceldate = null;
		$this->comments = '';
		$this->assignedcoursecode = '';
		$this->elective = 0;
		$this->scholarshippedamount = 0;
		$this->scholarshippedadjustment = 0;
		$this->scholarshipid = 0;
		$this->agreementsigned = 0;
		$this->agreementid = 0;
		$this->ordernumber = 0;
		$this->linenumber = 0;
		$this->fee = 0;
		$this->studentpaid = 0;
		$this->regfoxcode = '';
		$this->manualpricing = 0;
	}

    /**
     * Initializes a classes taken record from object properties
     */
    private function fill($rec) {
		$this->id = $rec->id;
		$this->termyear = $rec->termyear;
		$this->termcode = $rec->termcode;
		$this->coursecode = $rec->coursecode;
		$this->studentid = $rec->studentid;
		$this->idnumber = $rec->idnumber;
		$this->shorttitleoverride = $rec->shorttitleoverride;
		$this->titleoverride = $rec->titleoverride;
		$this->credittypecode = $rec->credittypecode;
		$this->gradecode=$rec->gradecode;
		$this->coursehoursoverride = $rec->coursehoursoverride;
		$this->registrationdate = $rec->registrationdate;
		$this->tuitionpaid = $rec->tuitionpaid;
		$this->completiondate = $rec->completiondate;
		$this->canceldate = $rec->canceldate;
		$this->comments = $rec->comments;
		$this->assignedcoursecode = $rec->assignedcoursecode;
		$this->elective = $rec->elective;
		$this->scholarshippedamount = $rec->scholarshippedamount;
		$this->scholarshippedadjustment = $rec->scholarshippedadjustment;
		$this->scholarshipid = $rec->scholarshipid;
		$this->agreementsigned = $rec->agreementsigned;
		$this->agreementid = $rec->agreementid;
		$this->ordernumber = $rec->ordernumber;
		$this->linenumber = $rec->linenumber;
		$this->fee = $rec->fee;
		$this->studentpaid = $rec->studentpaid;
		$this->regfoxcode = $rec->regfoxcode;
		$this->manualpricing = $rec->manualpricing;
	}
	

    /**
     * Saves a regfox class record to the database
     */
    public function save() {
        if ($this->id == 0) {
            $this->id = data::insert_classes_taken($this);
        } else {
            data::update_classes_taken($this);
        }
    }
}
