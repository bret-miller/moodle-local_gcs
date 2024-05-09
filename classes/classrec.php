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

class classrec {
    /** @var int record id */
    public $id;
    /** @var int term year */
    public $termyear;
    /** @var string term code */
    public $termcode;
    /** @var string course code */
    public $coursecode;
    /** @var int number of lectures */
    public $lectures;
    /** @var string required textbooks */
    public $requiredtextbooks;
    /** @var string short title */
    public $shorttitle;
    /** @var string title */
    public $title;
    /** @var string description */
    public $description;
    /** @var int course hourse */
    public $coursehours;
    /** @var int instructor */
    public $instructor;
    /** @var string comments */
    public $comments;

    /**
     * Initializes a record
     * 
     * @param none for blank record
     *     or id to read from database
     *     or object to build from object properties
     */
    public function __construct() {
        $args = func_get_args();
        $argc = func_num_args();
        if ($argc == 0) {
            // Initialize blank class record.
            $this->blankrec();
        } else if (gettype($args[0]) == 'int') {
            // Read from database.
            $rec = data::get_class($args[0]);
            if ($rec) {
                $this->fill($rec);
            } else {
                $this->blankrec();
            }
        } else if ($argc == 3) {
            // Read from database with course code and term.
            $rec = data::get_class_by_code_and_term($args[0],$args[1],$args[2]);
            if ($rec) {
                // Found the class record.
                $this->fill($rec);
            } else {
                // No class record, add from course record.
                $this->blankrec();
                $crs = data::get_course_by_code($args[0]);
                if ($crs) {
                    $this->termyear = $args[1];
                    $this->termcode = $args[2];
                    $this->coursecode = $crs->coursecode;
                    $this->lectures = $crs->lectures;
                    $this->requiredtextbooks = $crs->requiredtextbooks;
                    $this->shorttitle = $crs->shorttitle;
                    $this->title = $crs->title;
                    $this->description = $crs->description;
                    $this->coursehours = $crs->coursehours;
                    $this->instructor = $crs->defaultinstructor;
                    $this->comments = $crs->comments;
                    $this->save();
                } else {
                    $msg =  'Could not create class record for course ' . $args[0] . '.' . PHP_EOL;
					$msg .= 'Course ' . $args[0] . ' does not exist.' . PHP_EOL;
					utils::send_notification_email('Missing Course ' . $args[0], $msg);
                }
            }
        } else {
            // Build from object properties.
            $this->fill($args[0]);
        }
    }

    /**
     * Initializes a blank class record
     */
    private function blankrec() {
        $this->id = 0;
        $this->termyear = intval(date("Y"));
        $this->termcode='';
        $this->coursecode = '';
        $this->lectures = 0;
        $this->requiredtextbooks = '';
        $this->shorttitle = '';
        $this->title = '';
        $this->description = '';
        $this->coursehours = 0;
        $this->instructor = 0;
        $this->comments = '';
    }

    /**
     * Initializes a class record from object properties
     *
     * @param object $rec object with all class properties set
     */
    private function fill($rec) {
        $this->id = $rec->id;
        $this->termyear = $rec->termyear;
        $this->termcode = $rec->termcode;
        $this->coursecode = $rec->coursecode;
        $this->lectures = $rec->lectures;
        $this->requiredtextbooks = $rec->requiredtextbooks;
        $this->shorttitle = $rec->shorttitle;
        $this->title = $rec->title;
        $this->description = $rec->description;
        $this->coursehours = $rec->coursehours;
        $this->instructor = $rec->instructor;
        $this->comments = $rec->comments;
    }
    /**
     * Saves a class record to the database
     */
    public function save() {
        if ($this->id == 0) {
            $this->id = data::insert_class($this);
        } else {
            data::update_class($this);
        }
    }
}
