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
 * Match students with their user accounts
 *
 * @package    local_gcs
 * @copyright  2023 Grace Communion Seminary
 * @author     Bret Miller
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_gcs;

class student_user_match {
    /** @var bool $interactive whether this is running interactive or as a task. */
    public $interactive;
    /** @var string $logrecs log records. */
    public $logrecs;

    /**
     * Initializes the student user match class
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
    public function match() {
        global $DB;
        $students = data::get_students_with_no_user();
        $this->logthis('Found '.count($students).' students without user account.');
        foreach ($students as $student) {
            $users = $DB->get_records_list('user', 'idnumber', [$student->idnumber]);
            if (count($users) == 1) {
                $user = array_pop($users);
                $student->userid = $user->id;
                data::update_students($student);
                $this->logthis('Matched student '.$student->legalfirstname.' id '.$student->id.' to user id '.$user->id);
            } else {
                $this->logthis('Unable to match student '.$student->legalfirstname.' id '.$student->id.' to user.');
            }
        }
        $log = $this->logrecs;
        $this->logrecs = PHP_EOL;
        return $log;
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
